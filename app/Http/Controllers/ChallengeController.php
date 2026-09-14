<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    /**
     * Return active challenges with the authenticated user's progress.
     */
    public function index(): JsonResponse
    {
        $challenges = Challenge::active()->get();

        $user = auth()->user();
        $userChallenges = collect();

        if ($user) {
            $userChallenges = $user->challenges()
                ->wherePivotIn('challenge_id', $challenges->pluck('id'))
                ->get()
                ->keyBy('id');
        }

        $data = $challenges->map(function (Challenge $challenge) use ($userChallenges) {
            $pivot = $userChallenges->get($challenge->id)?->pivot;

            return [
                'id' => $challenge->id,
                'title' => $challenge->title,
                'description' => $challenge->description,
                'icon' => $challenge->icon,
                'type' => $challenge->type,
                'target_count' => $challenge->target_count,
                'reward_xp' => $challenge->reward_xp,
                'user_progress' => $pivot?->progress ?? 0,
                'is_completed' => $pivot?->completed_at !== null,
            ];
        });

        return response()->json($data);
    }
}
