<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreThreadRequest;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\Tag;
use App\Services\ReputationService;
use App\Services\MentionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThreadController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $filter = $request->get('filter', 'all');

        $query = Thread::with(['user', 'topic', 'tags']);

        // Filters
        if ($filter === 'pinned') {
            $query->where('is_pinned', true);
        } elseif ($filter === 'resolved') {
            $query->where('is_resolved', true);
        } elseif ($filter === 'my') {
            $query->where('user_id', auth()->id());
        }

        // Sorting
        if ($sort === 'replies') {
            $query->orderBy('replies_count', 'desc');
        } elseif ($sort === 'views') {
            $query->orderBy('views_count', 'desc');
        } else {
            $query->latest();
        }

        $threads = $query->paginate(15);
        return view('threads.index', compact('threads', 'sort', 'filter'));
    }

    public function create()
    {
        $topics = Topic::all();
        return view('threads.create', compact('topics'));
    }

    public function store(StoreThreadRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $thread = Thread::create($data);

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
            $thread->tags()->sync($tagIds);
        }

        ReputationService::addPoints(auth()->user(), 3);

        return redirect()->route('threads.show', $thread->slug)
            ->with('success', 'Discussion thread started successfully!');
    }

    public function show($slug)
    {
        $thread = Thread::where('slug', $slug)->with(['user', 'topic', 'tags', 'replies.user', 'replies.replies.user'])->firstOrFail();
        $thread->increment('views_count');

        return view('threads.show', compact('thread'));
    }

    public function destroy($slug)
    {
        $thread = Thread::where('slug', $slug)->firstOrFail();
        $this->authorize('delete', $thread);

        $thread->delete();
        return redirect()->route('threads.index')->with('success', 'Discussion thread deleted successfully.');
    }
}

