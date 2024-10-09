<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends BaseController
{
    public function getAllUsers(Request $request)
    {
        try {
            $current_user_id = $request->user()->id;
            $users = User::where("user_type", "0")
                ->where("users.id", "<>", $current_user_id)
                ->leftJoin('conversations', function ($join) use ($current_user_id) {
                    $join->on('users.id', '=', 'conversations.first_user_id')
                        ->orOn('users.id', '=', 'conversations.second_user_id')
                        ->where(function ($query) use ($current_user_id) {
                            $query->where('conversations.first_user_id', $current_user_id)
                                ->orWhere('conversations.second_user_id', $current_user_id);
                        });
                })
                ->selectRaw('users.*, IF(conversations.id IS NOT NULL, 1, 0) as already_have_conversation, IF(conversations.id IS NOT NULL, conversations.id, 0) as conversation_id')
                ->get();
            Log::info($users);
            return $this->sendResponse($users);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
