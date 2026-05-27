<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Models\Topic;
use App\Models\Tag;
use App\Services\ReputationService;
use App\Services\MentionService;
use App\Events\ArticlePublished;
use App\Jobs\UpdateReadingTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'latest');
        $query = Article::where('status', 'published')->with(['user', 'topic', 'tags']);

        if ($sort === 'liked') {
            $query->orderBy('likes_count', 'desc');
        } elseif ($sort === 'viewed') {
            $query->orderBy('views_count', 'desc');
        } else {
            $query->latest();
        }

        $articles = $query->paginate(15);
        return view('articles.index', compact('articles', 'sort'));
    }

    public function create()
    {
        $topics = Topic::all();
        return view('articles.create', compact('topics'));
    }

    public function store(StoreArticleRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('uploads/articles', 'public');
            $data['cover_image'] = $path;
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $article = Article::create($data);

        // Tags processing
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
            $article->tags()->sync($tagIds);
        }

        // Calculate reading time
        UpdateReadingTime::dispatchSync($article);

        if ($article->status === 'published') {
            ReputationService::addPoints(auth()->user(), 10);
            event(new ArticlePublished($article));
        }

        return redirect()->route('articles.show', $article->slug)
            ->with('success', 'Article created successfully!');
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->with(['user', 'topic', 'tags', 'comments.user'])->firstOrFail();
        $article->increment('views_count');

        $relatedArticles = Article::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->where(function($q) use ($article) {
                $q->where('topic_id', $article->topic_id)
                  ->orWhereHas('tags', function($t) use ($article) {
                      $t->whereIn('tags.id', $article->tags->pluck('id'));
                  });
            })
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }

    public function edit($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $this->authorize('update', $article);

        $topics = Topic::all();
        $tagsString = $article->tags->pluck('name')->implode(', ');

        return view('articles.edit', compact('article', 'topics', 'tagsString'));
    }

    public function update(StoreArticleRequest $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $this->authorize('update', $article);

        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('uploads/articles', 'public');
            $data['cover_image'] = $path;
        }

        $wasDraft = $article->status === 'draft';
        if ($wasDraft && $data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $article->update($data);

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
            $article->tags()->sync($tagIds);
        } else {
            $article->tags()->detach();
        }

        UpdateReadingTime::dispatchSync($article);

        if ($wasDraft && $article->status === 'published') {
            ReputationService::addPoints($article->user, 10);
            event(new ArticlePublished($article));
        }

        return redirect()->route('articles.show', $article->slug)
            ->with('success', 'Article updated successfully!');
    }

    public function destroy($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $this->authorize('delete', $article);

        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Article deleted successfully.');
    }
}

