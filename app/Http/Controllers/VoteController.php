<?php
namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Vote;
use App\Services\ReputationService;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function vote($type, $id, Request $request)
    {
        $request->validate(['value' => 'required|in:1,-1']);
        $value = (int)$request->value;
        $userId = auth()->id();

        $votableClass = null;
        if ($type === 'question') {
            $votableClass = Question::class;
        } elseif ($type === 'answer') {
            $votableClass = Answer::class;
        }

        if (!$votableClass) {
            abort(404);
        }

        $votable = $votableClass::findOrFail($id);

        if ($votable->user_id === $userId) {
            return back()->with('error', 'You cannot vote on your own post.');
        }

        $existingVote = Vote::where('user_id', $userId)
            ->where('votable_type', $votableClass)
            ->where('votable_id', $id)
            ->first();

        $oldValue = $existingVote ? $existingVote->value : 0;
        $newValue = 0;

        if ($existingVote) {
            if ($existingVote->value === $value) {
                // Neutralize vote
                $existingVote->delete();
                $newValue = 0;
            } else {
                // Change vote
                $existingVote->update(['value' => $value]);
                $newValue = $value;
            }
        } else {
            // New vote
            Vote::create([
                'user_id' => $userId,
                'votable_type' => $votableClass,
                'votable_id' => $id,
                'value' => $value,
            ]);
            $newValue = $value;
        }

        // Update vote count on parent
        $newVoteCount = Vote::where('votable_type', $votableClass)->where('votable_id', $id)->sum('value');
        $votable->update(['votes_count' => $newVoteCount]);

        // Adjust Reputation (Answers only based on spec: upvote = +10, downvote = -2)
        if ($type === 'answer') {
            $author = $votable->user;

            // Map old/new values to reputation points
            $oldPoints = 0;
            if ($oldValue === 1) {
                $oldPoints = 10;
            } elseif ($oldValue === -1) {
                $oldPoints = -2;
            }

            $newPoints = 0;
            if ($newValue === 1) {
                $newPoints = 10;
            } elseif ($newValue === -1) {
                $newPoints = -2;
            }

            $pointsDifference = $newPoints - $oldPoints;

            if ($pointsDifference > 0) {
                ReputationService::addPoints($author, $pointsDifference);
            } elseif ($pointsDifference < 0) {
                ReputationService::subtractPoints($author, abs($pointsDifference));
            }
        }

        return back()->with('success', 'Vote recorded.');
    }
}
