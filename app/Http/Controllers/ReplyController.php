<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Models\Thread;
use App\Models\Reply;
use App\Events\ThreadReplied;

class ReplyController extends Controller
{
    public function store(StoreReplyRequest $request, $threadId)
    {
        $thread = Thread::findOrFail($threadId);

        $reply = Reply::create([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'body' => $request->body,
        ]);

        $thread->increment('replies_count');

        event(new ThreadReplied($reply));

        return redirect()->route('threads.show', $thread->slug)
            ->with('success', 'Reply posted successfully.');
    }

    public function destroy($id)
    {
        $reply = Reply::with('thread')->findOrFail($id);
        if (auth()->id() !== $reply->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $thread = $reply->thread;
        $reply->delete();
        $thread->decrement('replies_count');

        return back()->with('success', 'Reply deleted successfully.');
    }
}

