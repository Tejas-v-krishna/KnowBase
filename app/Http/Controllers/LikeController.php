<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Like;
use App\Services\ReputationService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle($articleId)
    {
        $article = Article::findOrFail($articleId);
        $userId = auth()->id();

        $existingLike = Like::where('user_id', $userId)
            ->where('likeable_type', Article::class)
            ->where('likeable_id', $articleId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $article->decrement('likes_count');
            ReputationService::subtractPoints($article->user, 2);
            $message = 'Article unliked.';
        } else {
            Like::create([
                'user_id' => $userId,
                'likeable_type' => Article::class,
                'likeable_id' => $articleId,
            ]);
            $article->increment('likes_count');
            ReputationService::addPoints($article->user, 2);
            
            // Notify author
            if ($article->user_id !== $userId) {
                $article->user->notify(new \App\Notifications\ArticleLikedNotification($article, auth()->user()));
            }
            $message = 'Article liked!';
        }

        return back()->with('success', $message);
    }
}
