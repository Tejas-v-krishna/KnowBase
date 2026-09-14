<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreAnswerRequest;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Activity;
use App\Services\ReputationService;
use App\Services\MentionService;
use App\Events\QuestionAnswered;
use App\Events\AnswerAccepted;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnswerController extends Controller
{
    public function store(StoreAnswerRequest $request, $questionId)
    {
        $question = Question::findOrFail($questionId);
        
        $answer = Answer::create([
            'question_id' => $question->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
            'is_verified' => auth()->user()->is_expert,
        ]);

        // Process mentions
        $answer->body = MentionService::processMentions($answer->body, auth()->user(), $answer, route('questions.show', $question->slug));
        $answer->save();

        $question->increment('answers_count');

        ReputationService::addPoints(auth()->user(), 5);

        // Log activity
        Activity::create([
            'user_id' => auth()->id(),
            'description' => 'answered: ' . Str::limit($question->title, 60),
            'type' => 'answer_posted',
            'subject_id' => $answer->id,
            'subject_type' => get_class($answer),
        ]);

        event(new QuestionAnswered($answer));

        return redirect()->route('questions.show', $question->slug)
            ->with('success', 'Answer posted successfully! +5 XP earned.');
    }

    public function markAsBrainliest($id)
    {
        $answer = Answer::with('question')->findOrFail($id);
        $question = $answer->question;

        if (auth()->id() !== $question->user_id) {
            abort(403, 'Only the author of the question can select the Brainliest answer.');
        }

        // Toggle / Set brainliest status
        if ($answer->is_brainliest) {
            // Already brainliest, revert
            $answer->update(['is_brainliest' => false]);
            $question->update(['accepted_answer_id' => null, 'status' => 'open']);
            ReputationService::subtractPoints($answer->user, 50);
        } else {
            // Unmark any previous brainliest answer
            Answer::where('question_id', $question->id)->where('is_brainliest', true)->update(['is_brainliest' => false]);

            $answer->update(['is_brainliest' => true]);
            $question->update(['accepted_answer_id' => $answer->id, 'status' => 'resolved']);

            // Award base XP + bounty XP if applicable
            $totalXP = 50;
            $bountyMsg = '';
            if ($question->bounty_amount > 0) {
                $totalXP += $question->bounty_amount;
                $bountyMsg = " + {$question->bounty_amount} XP bounty";

                // Log bounty win activity
                Activity::create([
                    'user_id' => $answer->user_id,
                    'description' => 'won a ' . $question->bounty_amount . ' XP bounty on: ' . Str::limit($question->title, 50),
                    'type' => 'bounty_won',
                    'subject_id' => $question->id,
                    'subject_type' => get_class($question),
                ]);
            }

            ReputationService::addPoints($answer->user, $totalXP);

            event(new AnswerAccepted($answer));

            return back()->with('success', "Brainliest answer selected! Answerer earned +{$totalXP} XP{$bountyMsg}.");
        }

        return back()->with('success', 'Brainliest answer updated successfully.');
    }
}



