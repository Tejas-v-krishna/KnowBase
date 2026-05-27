<?php
namespace App\Notifications;

use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArticleCommentedNotification extends Notification
{
    use Queueable;

    protected $article;
    protected $commenter;
    protected $comment;

    public function __construct(Article $article, User $commenter, Comment $comment)
    {
        $this->article = $article;
        $this->commenter = $commenter;
        $this->comment = $comment;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'comment',
            'actor_name' => $this->commenter->name,
            'content_title' => $this->article->title,
            'url' => route('articles.show', $this->article->slug),
        ];
    }
}
