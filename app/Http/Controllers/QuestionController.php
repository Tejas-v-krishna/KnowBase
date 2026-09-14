<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Models\Question;
use App\Models\Topic;
use App\Models\Tag;
use App\Models\Activity;
use App\Services\ReputationService;
use App\Services\MentionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $filter = $request->get('filter', 'all');

        $query = Question::with(['user', 'topic', 'tags']);

        // Filters
        if ($filter === 'unanswered') {
            $query->where('answers_count', 0);
        } elseif ($filter === 'resolved') {
            $query->where('status', 'resolved');
        } elseif ($filter === 'my') {
            $query->where('user_id', auth()->id());
        } elseif ($filter === 'bounty') {
            $query->where('bounty_amount', '>', 0);
        }

        // Sorting
        if ($sort === 'votes') {
            $query->orderBy('votes_count', 'desc');
        } elseif ($sort === 'views') {
            $query->orderBy('views_count', 'desc');
        } elseif ($sort === 'bounty') {
            $query->orderBy('bounty_amount', 'desc');
        } else {
            $query->latest();
        }

        $questions = $query->paginate(15);
        return view('questions.index', compact('questions', 'sort', 'filter'));
    }

    public function create(Request $request)
    {
        $topics = Topic::all();
        $prefill = $request->query('prefill');
        return view('questions.create', compact('topics', 'prefill'));
    }

    public function store(StoreQuestionRequest $request)
    {
        if (auth()->user()->reputation < 10) {
            return back()->with('error', 'You need at least 10 XP to ask a question.');
        }

        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Handle bounty
        $bountyAmount = (int) ($request->input('bounty_amount', 0));
        if ($bountyAmount > 0) {
            if (!in_array($bountyAmount, [25, 50, 100, 200])) {
                return back()->with('error', 'Invalid bounty amount.');
            }
            $totalCost = 10 + $bountyAmount;
            if (auth()->user()->reputation < $totalCost) {
                return back()->with('error', "You need at least {$totalCost} XP (10 to ask + {$bountyAmount} bounty).");
            }
            $data['bounty_amount'] = $bountyAmount;
        }

        $question = Question::create($data);

        // Process mentions
        $question->body = MentionService::processMentions($question->body, auth()->user(), $question, route('questions.show', $question->slug));
        $question->save();

        if (!empty($data['tags'])) {
            $tagsArray = array_map('trim', explode(',', $data['tags']));
            $tagIds = [];
            foreach ($tagsArray as $tagName) {
                if (empty($tagName)) continue;
                $tag = Tag::firstOrCreate([
                    'name' => $tagName,
                    'slug' => Str::slug($tagName)
                ]);
                $tagIds[] = $tag->id;
            }
            $question->tags()->sync($tagIds);
        }

        // Deduct XP: 10 for asking + bounty amount
        $totalDeduction = 10 + $bountyAmount;
        ReputationService::subtractPoints(auth()->user(), $totalDeduction);

        // Log activity
        Activity::create([
            'user_id' => auth()->id(),
            'description' => $bountyAmount > 0
                ? 'asked a ' . $bountyAmount . ' XP bounty question: ' . Str::limit($question->title, 60)
                : 'asked: ' . Str::limit($question->title, 60),
            'type' => 'question_asked',
            'subject_id' => $question->id,
            'subject_type' => get_class($question),
        ]);

        $successMsg = 'Question posted successfully! 10 XP deducted.';
        if ($bountyAmount > 0) {
            $successMsg = "Question posted with {$bountyAmount} XP bounty! " . ($totalDeduction) . " XP total deducted.";
        }

        return redirect()->route('questions.show', $question->slug)
            ->with('success', $successMsg);
    }

    public function show($slug)
    {
        $question = Question::where('slug', $slug)->with(['user', 'topic', 'tags', 'answers.user', 'answers.votes', 'answers.thanks'])->firstOrFail();
        $question->increment('views_count');

        return view('questions.show', compact('question'));
    }

    public function destroy($slug)
    {
        $question = Question::where('slug', $slug)->firstOrFail();
        $this->authorize('delete', $question);

        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Question deleted successfully.');
    }
}



