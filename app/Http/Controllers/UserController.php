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

        return view('users.profile', compact('user', 'tab', 'articles', 'questions', 'answers', 'collections', 'followers', 'following'));
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

