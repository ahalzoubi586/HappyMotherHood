<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notification;

class ChatMessageNotification extends Notification
{
    use Queueable, HasUuids;  // Add HasUuids to ensure UUID handling
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message['message'],
            'sender_id' => $this->message['sender_id'],
            'conversation_id' => $this->message['conversation_id'],
        ];
    }
}
