<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserMentioned extends Notification implements ShouldQueue
{
    use Queueable;

    public $mentioner;
    public $content;
    public $url;

    public function __construct($mentioner, $content, $url)
    {
        $this->mentioner = $mentioner;
        $this->content = $content;
        $this->url = $url;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Can add 'mail' later
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'mention',
            'message' => $this->mentioner->name . ' mentioned you in ' . class_basename($this->content),
            'url' => $this->url,
            'icon' => 'at-symbol'
        ];
    }
}
