<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->get()
            ->sortByDesc(function ($conv) {
                return $conv->latestMessage ? $conv->latestMessage->created_at : $conv->created_at;
            });

        $activeConversation = null;
        if ($request->has('c')) {
            $activeConversation = Conversation::with('messages.sender')->findOrFail($request->query('c'));
            // Mark messages as read
            $activeConversation->messages()->where('sender_id', '!=', $user->id)->whereNull('read_at')->update(['read_at' => now()]);
        }

        return view('messages.index', compact('conversations', 'activeConversation'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        $request->validate(['body' => 'required|string']);

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'body' => $request->body,
        ]);

        // Trigger Notification
        $otherUser = $conversation->getOtherUser(auth()->user());
        $otherUser->notify(new \App\Notifications\NewMessage($message));

        return back();
    }

    public function start(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot message yourself.');
        }

        $conversation = Conversation::where(function ($q) use ($currentUser, $user) {
            $q->where('user_one_id', $currentUser->id)->where('user_two_id', $user->id);
        })->orWhere(function ($q) use ($currentUser, $user) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $currentUser->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $currentUser->id,
                'user_two_id' => $user->id,
            ]);
        }

        return redirect()->route('messages.index', ['c' => $conversation->id]);
    }
}
