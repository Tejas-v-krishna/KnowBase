<?php
namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::orderBy('name')->get();
        return view('topics.index', compact('topics'));
    }

    public function show($slug, Request $request)
    {
        $topic = Topic::where('slug', $slug)->firstOrFail();
        $tab = $request->get('tab', 'articles');

        $articles = [];
        $questions = [];
        $threads = [];

        if ($tab === 'articles') {
            $articles = $topic->articles()->where('status', 'published')->with('user')->latest()->paginate(10);
        } elseif ($tab === 'questions') {
            $questions = $topic->questions()->with('user')->latest()->paginate(10);
        } elseif ($tab === 'threads') {
            $threads = $topic->threads()->with('user')->latest()->paginate(10);
        }

        return view('topics.show', compact('topic', 'tab', 'articles', 'questions', 'threads'));
    }

    public function follow($id)
    {
        $topic = Topic::findOrFail($id);
        $user = auth()->user();

        if ($user->topics()->where('topic_id', $id)->exists()) {
            $user->topics()->detach($id);
            $topic->decrement('followers_count');
            $message = 'Unfollowed topic.';
        } else {
            $user->topics()->attach($id);
            $topic->increment('followers_count');
            $message = 'Following topic!';
        }

        return back()->with('success', $message);
    }
}
