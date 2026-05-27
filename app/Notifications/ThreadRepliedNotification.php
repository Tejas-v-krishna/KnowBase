<?php
namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThreadRepliedNotification extends Notification
{
    use Queueable;

    protected $reply;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $thread = $this->reply->thread;
        return [
            'type' => 'reply',
            'actor_name' => $this->reply->user->name,
            'content_title' => $thread->title,
            'url' => route('threads.show', $thread->slug),
        ];
    }
}
