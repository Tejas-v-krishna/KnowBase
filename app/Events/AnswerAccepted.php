<?php
namespace App\Events;

use App\Models\Answer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnswerAccepted
{
    use Dispatchable, SerializesModels;

    public $answer;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }
}
