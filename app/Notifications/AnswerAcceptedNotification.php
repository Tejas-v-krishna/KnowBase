<?php
namespace App\Notifications;

use App\Models\Answer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnswerAcceptedNotification extends Notification
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
            'type' => 'accept',
            'actor_name' => $question->user->name,
            'content_title' => $question->title,
            'url' => route('questions.show', $question->slug),
        ];
    }
}
