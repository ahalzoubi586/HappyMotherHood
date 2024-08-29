<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;
    private $title;
    private $description;

    public function __construct($title, $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
