<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityFeedController extends Controller
{
    /**
     * Return the latest 10 activities as JSON.
     */
    public function index(Request $request): JsonResponse
    {
        $activities = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $feed = $activities->map(function (Activity $activity) {
            $user = $activity->user;

            return [
                'id' => $activity->id,
                'user' => [
                    'name' => $user?->name,
                    'username' => $user?->username,
                    'avatar_url' => $user?->avatar
                        ?? 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user?->email ?? ''))) . '?d=mp&s=80',
                ],
                'description' => $activity->description ?? $activity->type,
                'type' => $activity->type,
                'created_at' => $activity->created_at?->toIso8601String(),
                'time_ago' => $activity->created_at?->diffForHumans(),
            ];
        });

        return response()->json($feed);
    }
}
