<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFollowController extends Controller
{
    public function toggle(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if ($currentUser->isFollowing($user)) {
            $currentUser->following()->detach($user->id);
            $message = "You unfollowed {$user->username}.";
        } else {
            $currentUser->following()->attach($user->id);
            $user->notify(new \App\Notifications\UserFollowedNotification($currentUser));
            $message = "You are now following {$user->username}.";
        }

        return back()->with('success', $message);
    }
}
