<?php
namespace App\Notifications;

use App\Models\Badge;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BadgeAwardedNotification extends Notification
{
    use Queueable;

    protected $badge;

    public function __construct(Badge $badge)
    {
        $this->badge = $badge;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'badge',
            'actor_name' => 'System',
            'content_title' => $this->badge->name,
            'url' => route('users.show', $notifiable->username) . '?tab=collections',
        ];
    }
}
