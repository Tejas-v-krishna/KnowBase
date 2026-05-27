<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Services\MentionService;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, $type, $id)
    {
        $commentableClass = null;
        if ($type === 'article') {
            $commentableClass = Article::class;
        } elseif ($type === 'answer') {
            $commentableClass = \App\Models\Answer::class;
        }

        if (!$commentableClass) {
            abort(404, 'Invalid commentable type.');
        }

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'commentable_type' => $commentableClass,
            'commentable_id' => $id,
            'body' => $request->body,
        ]);

        // Fire notification / event if needed (we'll notify in listener)
        if ($type === 'article') {
            $article = Article::find($id);
            if ($article && $article->user_id !== auth()->id()) {
                $article->user->notify(new \App\Notifications\ArticleCommentedNotification($article, auth()->user(), $comment));
            }
        }

        return back()->with('success', 'Comment posted successfully.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if (auth()->id() !== $comment->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}

