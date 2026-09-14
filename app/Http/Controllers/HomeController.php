<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;
use App\Models\Poll;
use App\Models\PollVote;
use App\Models\Activity;
use App\Models\Challenge;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $tab  = $request->query('tab', 'all');
        $user = auth()->user();

        // Gamification Study Streak Logic
        if ($user) {
            $now = Carbon::now();
            if ($user->last_active_at) {
                $lastActive = Carbon::parse($user->last_active_at);
                $diffInHours = $lastActive->diffInHours($now);
                
                if ($diffInHours >= 24 && $diffInHours <= 48) {
                    $user->increment('streak');
                } elseif ($diffInHours > 48) {
                    $user->update(['streak' => 1]);
                }
            } else {
                $user->update(['streak' => 1]);
            }
            $user->update(['last_active_at' => $now]);
        }

        // Base queries
        $articlesQuery  = Article::where('status', 'published')->with(['user', 'topic'])->latest('published_at');
        $questionsQuery = Question::with(['user', 'topic'])->latest();
        $threadsQuery   = Thread::with(['user', 'topic'])->orderBy('is_pinned', 'desc')->latest();

        // "Following" tab — filter by users and topics that the auth user follows
        if ($tab === 'following' && $user) {
            $followingIds = $user->following()->pluck('users.id');
            $followedTopicIds = $user->topics()->pluck('topics.id');

            if ($followingIds->isNotEmpty() || $followedTopicIds->isNotEmpty()) {
                $articlesQuery->where(function($q) use ($followingIds, $followedTopicIds) {
                    if ($followingIds->isNotEmpty()) {
                        $q->whereIn('user_id', $followingIds);
                    }
                    if ($followedTopicIds->isNotEmpty()) {
                        $q->orWhereIn('topic_id', $followedTopicIds);
                    }
                });

                $questionsQuery->where(function($q) use ($followingIds, $followedTopicIds) {
                    if ($followingIds->isNotEmpty()) {
                        $q->whereIn('user_id', $followingIds);
                    }
                    if ($followedTopicIds->isNotEmpty()) {
                        $q->orWhereIn('topic_id', $followedTopicIds);
                    }
                });

                $threadsQuery->where(function($q) use ($followingIds, $followedTopicIds) {
                    if ($followingIds->isNotEmpty()) {
                        $q->whereIn('user_id', $followingIds);
                    }
                    if ($followedTopicIds->isNotEmpty()) {
                        $q->orWhereIn('topic_id', $followedTopicIds);
                    }
                });
            } else {
                // No one and no topics followed yet — return empty collections
                $articlesQuery->whereRaw('1 = 0');
                $questionsQuery->whereRaw('1 = 0');
                $threadsQuery->whereRaw('1 = 0');
            }
        }

        $articles  = $articlesQuery->take(5)->get();
        $questions = $questionsQuery->take(5)->get();
        $threads   = $threadsQuery->take(5)->get();

        // Sidebar data
        $trendingArticles = Article::where('status', 'published')
            ->with(['user', 'topic'])
            ->orderByRaw('(views_count * 1 + likes_count * 3) DESC')
            ->take(5)
            ->get();

        $popularTopics = Topic::orderBy('followers_count', 'desc')->take(8)->get();

        $popularTags = Tag::withCount(['articles', 'questions', 'threads'])
            ->orderByRaw('(articles_count + questions_count + threads_count) DESC')
            ->take(12)
            ->get();

        // Top contributors by reputation
        $topContributors = User::where('reputation', '>', 0)
            ->orderBy('reputation', 'desc')
            ->take(5)
            ->get();

        // Unanswered questions count
        $unansweredCount = Question::where('answers_count', 0)->where('status', 'open')->count();

        // Platform stats
        $stats = [
            'articles'  => Article::where('status', 'published')->count(),
            'questions' => Question::count(),
            'threads'   => Thread::count(),
            'members'   => User::count(),
        ];

        // Active Poll Logic
        $activePoll = Poll::latest()->first();
        if (!$activePoll) {
            $activePoll = Poll::create([
                'question' => 'What is your favorite study technique to prevent exam burnout?',
                'options' => ['Pomodoro Method', 'Active Recall (Flashcards)', 'Feynman Technique', 'Spaced Repetition (Anki)']
            ]);
            
            // Seed initial mock votes ONLY if users exist in the database
            $firstUser = User::first();
            if ($firstUser) {
                PollVote::create(['user_id' => $firstUser->id, 'poll_id' => $activePoll->id, 'option_index' => 1]);
            }
        }

        $userVote = null;
        if ($user) {
            $voteRecord = PollVote::where('poll_id', $activePoll->id)->where('user_id', $user->id)->first();
            if ($voteRecord) {
                $userVote = $voteRecord->option_index;
            }
        }

        $pollVotesCount = $activePoll->votes()->count();
        $optionVotes = $activePoll->votes()
            ->groupBy('option_index')
            ->selectRaw('option_index, count(*) as count')
            ->pluck('count', 'option_index')
            ->toArray();

        // Expert Spotlights Logic
        $expertGuides = Article::where('status', 'published')
            ->where(function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('is_expert', true);
                })->orWhereHas('user', function($q) {
                    $q->where('reputation', '>=', 100);
                });
            })
            ->with(['user', 'topic'])
            ->latest('published_at')
            ->take(3)
            ->get();

        // Bounty Board: Open questions with active bounties
        $bountyQuestions = Question::withBounty()
            ->with(['user', 'topic'])
            ->orderBy('bounty_amount', 'desc')
            ->take(5)
            ->get();

        // Latest activities for live feed
        $latestActivities = Activity::with('user')
            ->latest()
            ->take(8)
            ->get();

        // Active challenges
        $activeChallenges = Challenge::active()->get();
        $userChallengeProgress = [];
        if ($user) {
            $userChallengeProgress = $user->challenges()
                ->wherePivotNull('completed_at')
                ->get()
                ->keyBy('id')
                ->map(fn($c) => $c->pivot->progress)
                ->toArray();
        }

        return view('home.index', compact(
            'articles',
            'questions',
            'threads',
            'trendingArticles',
            'popularTopics',
            'popularTags',
            'topContributors',
            'unansweredCount',
            'tab',
            'stats',
            'activePoll',
            'userVote',
            'pollVotesCount',
            'optionVotes',
            'expertGuides',
            'bountyQuestions',
            'latestActivities',
            'activeChallenges',
            'userChallengeProgress'
        ));
    }

    public function vote(Request $request, $id)
    {
        $request->validate([
            'option_index' => 'required|integer'
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $poll = Poll::findOrFail($id);

        PollVote::updateOrCreate(
            ['user_id' => $user->id, 'poll_id' => $poll->id],
            ['option_index' => $request->option_index]
        );

        return back()->with('success', 'Thank you for voting!');
    }
}
