<?php
namespace App\Listeners;

use App\Events\QuestionAnswered;
use App\Notifications\QuestionAnsweredNotification;

class NotifyQuestionAuthorOnAnswer
{
    public function handle(QuestionAnswered $event): void
    {
        $answer = $event->answer;
        $question = $answer->question;

        if ($question->user_id !== $answer->user_id) {
            $question->user->notify(new QuestionAnsweredNotification($answer));
        }
    }
}
