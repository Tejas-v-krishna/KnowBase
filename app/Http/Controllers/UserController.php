<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($username, Request $request)
    {
        $user = User::where('username', $username)->with('badges')->firstOrFail();
        $tab = $request->get('tab', 'articles');

        // XP Progress Ring calculation
        $rep = $user->reputation;
        if ($rep >= 2000) {
            $currentRank = 'Brainly Master';
            $nextRank = 'Ultimate Sage';
            $xpStart = 2000;
            $xpTarget = 5000;
        } elseif ($rep >= 1000) {
            $currentRank = 'Genius';
            $nextRank = 'Brainly Master';
            $xpStart = 1000;
            $xpTarget = 2000;
        } elseif ($rep >= 500) {
            $currentRank = 'Ambitious';
            $nextRank = 'Genius';
            $xpStart = 500;
            $xpTarget = 1000;
        } elseif ($rep >= 100) {
            $currentRank = 'Helping Hand';
            $nextRank = 'Ambitious';
            $xpStart = 100;
            $xpTarget = 500;
        } else {
            $currentRank = 'Beginner';
            $nextRank = 'Helping Hand';
            $xpStart = 0;
            $xpTarget = 100;
        }

        $xpProgress = $rep - $xpStart;
        $xpLevelTarget = $xpTarget - $xpStart;
        $progressPercentage = $xpLevelTarget > 0 ? min(100, max(0, round(($xpProgress / $xpLevelTarget) * 100))) : 100;

        // Personal Stats Dashboard
        $stats = [
            'questions_count' => $user->questions()->count(),
            'answers_count' => $user->answers()->count(),
            'brainliest_count' => $user->answers()->where('is_brainliest', true)->count(),
            'thanks_count' => \App\Models\Thank::whereHas('answer', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'thanks_xp_received' => \App\Models\Thank::whereHas('answer', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->sum('xp_amount'),
        ];
        
        $stats['acceptance_rate'] = $stats['answers_count'] > 0 
            ? round(($stats['brainliest_count'] / $stats['answers_count']) * 100) 
            : 0;

        // Contribution Heatmap
        $activities = \App\Models\Activity::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subYear())
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $heatmapData = [];
        $startDate = now()->subDays(364);
        $startDate->subDays($startDate->dayOfWeek); // Align to Sunday
        
        $endDate = now()->endOfDay();
        $currentDate = clone $startDate;
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $heatmapData[] = [
                'date' => $dateString,
                'count' => $activities[$dateString] ?? 0,
            ];
            $currentDate->addDay();
        }

        $articles = [];
        $questions = [];
        $answers = [];
        $collections = [];
        $followers = [];
        $following = [];

        if ($tab === 'articles') {
            $articles = $user->articles()->where('status', 'published')->latest()->paginate(10);
        } elseif ($tab === 'questions') {
            $questions = $user->questions()->latest()->paginate(10);
        } elseif ($tab === 'answers') {
            $answers = $user->answers()->with('question')->latest()->paginate(10);
        } elseif ($tab === 'collections') {
            $collections = $user->collections()
                ->when(auth()->id() !== $user->id, function($q) {
                    $q->where('is_public', true);
                })
                ->latest()
                ->paginate(10);
        } elseif ($tab === 'followers') {
            $followers = $user->followers()->paginate(20);
        } elseif ($tab === 'following') {
            $following = $user->following()->paginate(20);
        }

        return view('users.profile', compact(
            'user', 'tab', 'articles', 'questions', 'answers', 'collections', 'followers', 'following',
            'currentRank', 'nextRank', 'xpStart', 'xpTarget', 'progressPercentage', 'stats', 'heatmapData'
        ));
    }

    public function collections($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $collections = $user->collections()
            ->when(auth()->id() !== $user->id, function($q) {
                $q->where('is_public', true);
            })
            ->latest()
            ->paginate(15);

        return view('users.collections', compact('user', 'collections'));
    }

    public function showCollection($username, $id)
    {
        $user = User::where('username', $username)->firstOrFail();
        $collection = Collection::where('user_id', $user->id)->findOrFail($id);

        if (!$collection->is_public && auth()->id() !== $user->id) {
            abort(403);
        }

        // Get full models of collectable items
        $items = $collection->items()->get()->map(function($item) {
            $modelClass = $item->collectable_type;
            $model = $modelClass::find($item->collectable_id);
            if ($model) {
                $model->collectable_order = $item->order;
                $model->collectable_item_id = $item->id;
            }
            return $model;
        })->filter();

        return view('users.collection_show', compact('user', 'collection', 'items'));
    }
}

