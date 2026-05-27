<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Question;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');
        $topicId = $request->get('topic_id');
        $tagId = $request->get('tag_id');

        $articles = collect();
        $questions = collect();
        $threads = collect();

        if (!empty($query)) {
            if ($type === 'all' || $type === 'articles') {
                $qArticles = Article::where('status', 'published')
                    ->where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('body', 'like', "%{$query}%");
                    })
                    ->with(['user', 'topic', 'tags']);
                    
                if ($topicId) $qArticles->where('topic_id', $topicId);
                if ($tagId) {
                    $qArticles->whereHas('tags', function($q) use ($tagId) {
                        $q->where('tags.id', $tagId);
                    });
                }
                $articles = $type === 'articles' ? $qArticles->paginate(15) : $qArticles->take(10)->get();
            }

            if ($type === 'all' || $type === 'questions') {
                $qQuestions = Question::where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('body', 'like', "%{$query}%");
                    })
                    ->with(['user', 'topic']);
                
                if ($topicId) $qQuestions->where('topic_id', $topicId);
                // Questions don't have tags in this schema, so skip tag filter
                $questions = $type === 'questions' ? $qQuestions->paginate(15) : $qQuestions->take(10)->get();
            }

            if ($type === 'all' || $type === 'threads') {
                $qThreads = Thread::where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('body', 'like', "%{$query}%");
                    })
                    ->with(['user', 'topic']);
                
                if ($topicId) $qThreads->where('topic_id', $topicId);
                // Threads don't have tags
                $threads = $type === 'threads' ? $qThreads->paginate(15) : $qThreads->take(10)->get();
            }
        }

        $topics = Topic::all();
        $tags = Tag::all();

        return view('search.results', compact('query', 'type', 'topicId', 'tagId', 'articles', 'questions', 'threads', 'topics', 'tags'));
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json(['questions' => [], 'articles' => [], 'threads' => []]);
        }

        $questions = Question::where('title', 'like', "%{$query}%")
            ->select('id', 'title', 'slug')
            ->take(4)
            ->get();

        $articles = Article::where('status', 'published')
            ->where('title', 'like', "%{$query}%")
            ->select('id', 'title', 'slug')
            ->take(3)
            ->get();

        $threads = Thread::where('title', 'like', "%{$query}%")
            ->select('id', 'title', 'slug')
            ->take(3)
            ->get();

        return response()->json([
            'questions' => $questions,
            'articles' => $articles,
            'threads' => $threads
        ]);
    }
}
