<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends BaseController
{
    public function getUserNotifications(Request $request)
    {
        try {
            $user = $request->user();
            $notifications = $user->notifications()->where('type', 'App\Notifications\GeneralNotification')->get();
            if (count($user->unreadNotifications) > 0) {
                $user->unreadNotifications->markAsRead();
            }
            return $this->sendResponse($notifications);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
