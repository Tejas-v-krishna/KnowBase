<?php
namespace App\Listeners;

use App\Events\ThreadReplied;
use App\Models\Reply;
use App\Notifications\ThreadRepliedNotification;

class NotifyThreadAuthorOnReply
{
    public function handle(ThreadReplied $event): void
    {
        $reply = $event->reply;
        $thread = $reply->thread;

        // Notify thread author
        if ($thread->user_id !== $reply->user_id) {
            $thread->user->notify(new ThreadRepliedNotification($reply));
        }

        // Notify parent reply author if nested
        if ($reply->parent_id) {
            $parentReply = Reply::find($reply->parent_id);
            if ($parentReply && $parentReply->user_id !== $reply->user_id && $parentReply->user_id !== $thread->user_id) {
                $parentReply->user->notify(new ThreadRepliedNotification($reply));
            }
        }
    }
}
