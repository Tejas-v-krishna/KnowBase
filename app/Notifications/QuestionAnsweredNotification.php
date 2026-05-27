<?php
namespace App\Notifications;

use App\Models\Answer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class QuestionAnsweredNotification extends Notification
{
    use Queueable;

    protected $answer;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $question = $this->answer->question;
        return [
            'type' => 'answer',
            'actor_name' => $this->answer->user->name,
            'content_title' => $question->title,
            'url' => route('questions.show', $question->slug),
        ];
    }
}
