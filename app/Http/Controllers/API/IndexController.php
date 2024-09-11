<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Duration;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class IndexController extends BaseController
{
    public function updateDuration(Request $request)
    {
        try {
            Duration::create([
                'user_id' => $request->user()->id,
                'duration' => $request->duration,
            ]);
            $avgDuration = Duration::where('user_id', $request->user()->id)
                ->avg('duration');
            $request->user()->update(['duration' => $avgDuration]);
            return $this->sendResponse($avgDuration);
        } catch (Exception $e) {
            return $this->sendError("error");
        }
    }
    public function SendVerificationCodeToEmail(Request $request)
    {
        try {
            $email = $request->emailAddress;

            return $this->sendResponse($email);
        } catch (Exception $e) {
            return $this->sendError("error");
        }
    }
    public function fetchUnreadNotificationsAndMessagesCount(Request $request)
    {
        try {

            $userId = $request->user()->id;
            Log::info("ssss" . $userId);
            $data['notificationsCount'] = $request->user()->unreadNotifications()->count();

            $unreadMessagesCount = DB::table('messages')
                ->join('conversations', function ($join) use ($userId) {
                    $join->on('messages.conversation_id', '=', 'conversations.id')
                        ->where(function ($query) use ($userId) {
                            $query->where('conversations.first_user_id', '=', $userId)
                                ->orWhere('conversations.second_user_id', '=', $userId);
                        });
                })
                ->whereNull('messages.read_at') // Only count unread messages
                ->where('messages.sender_id', '!=', $userId) // Exclude messages sent by the auth user
                ->count();
            $data['messagesCount'] = $unreadMessagesCount;
            return $this->sendResponse($data);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'], // Check current password
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'password_confirmation' => ['required'],
        ], [
            'current_password.required' => 'حقل كلمة المرور الحالية مطلوب',
            'current_password.current_password' => 'حقل كلمة المرور الحالية غير مطابق',
            'password.required' => 'حقل كلمة المرور مطلوب',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'password.min' => 'الحد الأدنى لطول كلمة المرور هو 8 أحرف',
            'password.letters' => 'يجب أن تحتوي كلمة المرور على حرف واحد على الأقل',
            'password.numbers' => 'يجب أن تحتوي كلمة المرور على رقم واحد على الأقل',
            'password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب',
        ]);
        if ($validator->fails()) {
            Log::info($this->sendError($validator->errors()->first()));
            return $this->sendError($validator->errors()->first());
        } else {
            try {
                $user = $request->user();
                $user->password = Hash::make($request->password);
                $user->save();
                return $this->sendResponse("success");
            } catch (Exception $e) {
                return $this->sendError($e->getMessage());
            }
        }
    }
}
