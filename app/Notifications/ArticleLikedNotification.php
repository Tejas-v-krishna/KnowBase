<?php
namespace App\Notifications;

use App\Models\Article;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArticleLikedNotification extends Notification
{
    use Queueable;

    protected $article;
    protected $liker;

    public function __construct(Article $article, User $liker)
    {
        $this->article = $article;
        $this->liker = $liker;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'like',
            'actor_name' => $this->liker->name,
            'content_title' => $this->article->title,
            'url' => route('articles.show', $this->article->slug),
        ];
    }
}
