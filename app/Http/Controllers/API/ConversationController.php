<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helpers;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\ChatMessageNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use GuzzleHttp\Client;

class ConversationController extends BaseController
{
    // Fetch all conversations for a user
    public function getConversations(Request $request)
    {
        try {
            $userId = $request->user()->id;

            $conversations = Conversation::where('first_user_id', $userId)
                ->orWhere('second_user_id', $userId)
                ->with(['lastMessage' => function ($query) {
                    $query->select('conversation_id', 'message', 'read_at', 'created_at');
                }])
                ->withCount(['messages as unread_messages_count' => function ($query) use ($userId) {
                    $query->whereNull('read_at')
                        ->where('sender_id', '!=', $userId);
                }])
                ->get();

            $conversations->transform(function ($conversation) use ($userId) {
                return [
                    'id' => $conversation->id,
                    'first_user_id' => $conversation->first_user_id,
                    'second_user' => $conversation->first_user_id == $userId ? $conversation->user_2->name . "(" . $conversation->user_2->email . ")" : $conversation->user_1->name . "(" . $conversation->user_1->email . ")",
                    'second_user_id' => $conversation->second_user_id,
                    'last_message' => $conversation->lastMessage->message ?? '',
                    'last_message_time' => Carbon::parse($conversation->lastMessage->created_at)->format('Y-m-d H:i:s') ?? null,
                    'last_message_read' => $conversation->lastMessage->read_at !== null,
                    'unread_messages_count' => $conversation->unread_messages_count,
                ];
            });
            return $this->sendResponse($conversations);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }


    // Create a new conversation
    public function sendMessage(Request $request)
    {
        Log::info("send");
        try {
            $filePath = base_path('firebase_cred.json');

            if (!file_exists($filePath)) {
                return $this->sendError("Credintial Not Valid");
            }

            if (!is_readable($filePath)) {
                return $this->sendError("Credintial Unreadable");
            }
            $firebase = (new Factory)->withServiceAccount($filePath)
                ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
            $realtimeDatabase = $firebase->createDatabase();
            $conversations_key = $realtimeDatabase->getReference("conversations");
            $userId = $request->user()->id;
            $conversationId = $request->conversationId;
            $request->validate([
                'message' => 'required|string',
            ]);
            $conversation = Conversation::find($conversationId);
            if (!$conversation) {
                $secondUserId = $request->secondUserId;
                $conv = Conversation::where(function ($query) use ($userId, $secondUserId) {
                    $query->where('first_user_id', $userId)
                        ->where('second_user_id', $secondUserId);
                })->orWhere(function ($query) use ($userId, $secondUserId) {
                    $query->where('first_user_id', $secondUserId)
                        ->where('second_user_id', $userId);
                })->first();
                if (!$conv) {
                    $conversation =  Conversation::create([
                        'first_user_id' => $userId,
                        'second_user_id' => $secondUserId,
                        'last_message' => "",
                        'last_message_time' => Carbon::now(),
                        'last_message_read' => false
                    ]);
                    $conversationId = $conversation->id;
                } else {
                    $conversation = $conv;
                    $conversationId = $conv->id;
                }
            }

            $message = new Message();
            $message->conversation_id = $conversationId;
            $message->sender_id = $userId;
            $message->message = $request->message;
            if ($message->save()) {
                $other_user_id = 0;
                if ($conversation->first_user_id == $userId) {
                    $other_user_id = $conversation->second_user_id;
                } else {
                    $other_user_id = $conversation->first_user_id;
                } 
                $conversations_key->getChild($conversationId)->getChild($other_user_id)->set(1);
                $conversations_key->getChild($conversationId)->getChild("conv$other_user_id")->set(1);
            }

            // Update last message in the conversation
            $conversation->last_message = $request->message;
            $conversation->last_message_time = now();
            $conversation->last_message_read = false;
            $conversation->save();
            $recuser = User::find($conversation->second_user_id);
            /*if ($recuser) {
                try {
                    $recuser->notify(new ChatMessageNotification($message));
                } catch (Exception $e) {
                }
            }*/

            // Retrieve the user's FCM token from the database
            $userToken = $recuser->firebase_token; // Replace this with the actual token
            $noti_message = array(
                'title' => 'رسالة جديدة',
                'body'  => $request->message,
                'conversation_id' => $conversation->id,
                'username' => $recuser->name . "(" . $recuser->email . ")",
            );

            try {
                Helpers::send_to_user($userToken, $noti_message);
            } catch (Exception $e) {
                Log::info($e->getMessage());
            }
            $data['message_id'] = $message->id;
            $data['conversation_id'] = $conversationId;
            $data['first_user_id'] = $conversation->first_user_id;
            $data['second_user_id'] = $conversation->second_user_id;
            return $this->sendResponse($data);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function getConversationMessages(Request $request)
    {
        try {

            $userId = $request->user()->id;
            $conversationId = $request->conversationId;
            if ($conversationId == 0) {
                $secondUserId = $request->other_user_id;
                $conv = Conversation::where(function ($query) use ($userId, $secondUserId) {
                    $query->where('first_user_id', $userId)
                        ->where('second_user_id', $secondUserId);
                })->orWhere(function ($query) use ($userId, $secondUserId) {
                    $query->where('first_user_id', $secondUserId)
                        ->where('second_user_id', $userId);
                })->first();
                if ($conv) {
                    $conversationId = $conv->id;
                }
            }
            $messages = [];
            $conversation = Conversation::find($conversationId);
            if ($conversation) {
                $last_message = Message::where('conversation_id', $conversationId)->latest("created_at")->first();
                if ($last_message && $last_message->sender_id != $userId) {
                    $conversation->update([
                        'last_message_read' => true,
                    ]);
                }
                $messages = Message::where('conversation_id', $conversationId)
                    ->orderBy('created_at', 'asc') // Order messages by creation time
                    ->get();
                Message::whereIn('id', $messages->pluck('id'))->where("sender_id", "!=", $userId)
                    ->update(['read_at' => now()]);
                $messages->transform(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'message' => $message->message,
                        'created_at' => $message->created_at->diffForHumans()
                    ];
                });
            }
            $data['messages'] = $messages;
            return $this->sendResponse($data);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function GetNewMessages(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $conversationId = $request->conversationId;
            $messages = [];

            $conversation = Conversation::find($conversationId);
            if ($conversation) {
                // Retrieve all unread messages for the conversation, excluding those sent by the current user
                $messages = Message::where('conversation_id', $conversationId)
                    ->whereNull('read_at')
                    ->where('sender_id', '<>', $userId)
                    ->orderBy('created_at', 'asc') // Order messages by creation time
                    ->get();

                // Mark all retrieved messages as read
                Message::whereIn('id', $messages->pluck('id'))
                    ->update(['read_at' => now()]);

                // Transform messages for response
                $messages->transform(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'message' => $message->message,
                        'created_at' => $message->created_at->diffForHumans(),
                    ];
                });
            }
            $data['messages'] = $messages;
            return $this->sendResponse($data);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
