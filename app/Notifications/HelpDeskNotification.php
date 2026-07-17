<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HelpDeskNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $url;

    public function __construct($message, $url)
    {
        $this->message = $message;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        // Yeh batata hai ke notification database mein save karni hai
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Yeh data database ke 'data' column mein JSON format mein save hoga
        return [
            'message' => $this->message,
            'url' => $this->url,
        ];
    }
}