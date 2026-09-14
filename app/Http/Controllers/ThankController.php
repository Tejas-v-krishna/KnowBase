<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Thank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThankController extends Controller
{
    /**
     * Send a thank-you to an answer author, with optional XP tip.
     */
    public function store(Request $request, $answerId)
    {
        $request->validate([
            'message' => 'nullable|string|max:500',
            'xp_amount' => 'nullable|integer|in:0,5,10,25',
        ]);

        $answer = Answer::findOrFail($answerId);
        $user = auth()->user();
        $xpAmount = (int) ($request->input('xp_amount', 0));

        // Cannot thank your own answer
        if ($answer->user_id === $user->id) {
            return back()->with('error', 'You cannot thank your own answer.');
        }

        // Cannot thank twice
        $alreadyThanked = Thank::where('user_id', $user->id)
            ->where('answer_id', $answer->id)
            ->exists();

        if ($alreadyThanked) {
            return back()->with('error', 'You have already thanked this answer.');
        }

        // Check if user has enough reputation for the XP tip
        if ($xpAmount > 0 && $user->reputation < $xpAmount) {
            return back()->with('error', 'Not enough XP');
        }

        DB::transaction(function () use ($user, $answer, $request, $xpAmount) {
            // Create the thank record
            Thank::create([
                'user_id' => $user->id,
                'answer_id' => $answer->id,
                'message' => $request->input('message'),
                'xp_amount' => $xpAmount,
            ]);

            // Transfer XP if tipping
            if ($xpAmount > 0) {
                $user->decrement('reputation', $xpAmount);
                $answer->user()->increment('reputation', $xpAmount);
            }
        });

        return back()->with('success', 'Thank you sent!');
    }
}
