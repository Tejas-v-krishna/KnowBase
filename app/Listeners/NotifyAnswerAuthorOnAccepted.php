<?php
namespace App\Listeners;

use App\Events\AnswerAccepted;
use App\Notifications\AnswerAcceptedNotification;

class NotifyAnswerAuthorOnAccepted
{
    public function handle(AnswerAccepted $event): void
    {
        $answer = $event->answer;
        $question = $answer->question;

        if ($answer->user_id !== $question->user_id) {
            $answer->user->notify(new AnswerAcceptedNotification($answer));
        }
    }
}
