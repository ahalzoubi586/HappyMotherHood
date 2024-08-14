<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;

class ConversationController extends BaseController
{
    // Fetch all conversations for a user
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $conversations = Conversation::with(['user_1', 'user_2'])
                            ->where('first_user_id', $userId)
                            ->orWhere('second_user_id', $userId)
                            ->orderBy('last_message_time', 'desc')
                            ->get();

        return response()->json($conversations);
    }

    // Create a new conversation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'second_user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $user1_id = $request->user()->id;
        $user2_id = $validated['second_user_id'];
        $message = $validated['message'];

        // Create conversation if not exists
        $conversation = Conversation::firstOrCreate([
            'first_user_id' => $user1_id,
            'second_user_id' => $user2_id,
        ], [
            'last_message' => $message,
            'last_message_read' => false,
        ]);

        // Update the last message
        $conversation->update([
            'last_message' => $message,
            'last_message_time' => now(),
            'last_message_read' => false,
        ]);

        // Notify via Pusher
        //event(new NewMessage($conversation, $message));

        return response()->json($conversation, 201);
    }

    // Mark the last message as read
    public function markAsRead(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);
        $conversation->update([
            'last_message_read' => true,
        ]);

        return response()->json($conversation);
    }
}
