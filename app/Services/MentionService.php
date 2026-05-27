<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\UserMentioned;
use Illuminate\Support\Str;

class MentionService
{
    /**
     * Parses text for @usernames, links them, and notifies the users.
     */
    public static function processMentions($text, $mentioner, $model, $url)
    {
        if (empty($text)) return $text;

        // Find all @usernames
        preg_match_all('/@([a-zA-Z0-9_]+)/', $text, $matches);
        $usernames = array_unique($matches[1]);

        if (empty($usernames)) return $text;

        $users = User::whereIn('username', $usernames)->get();

        foreach ($users as $user) {
            // Replace @username with a link
            $link = "<a href='/u/{$user->username}' class='text-indigo-600 dark:text-indigo-400 font-bold hover:underline'>@{$user->username}</a>";
            
            // Regex to replace exact username not followed by word characters
            $text = preg_replace('/@' . preg_quote($user->username, '/') . '(?!\w)/', $link, $text);

            // Notify user if it's not the mentioner themselves
            if ($user->id !== $mentioner->id) {
                $user->notify(new UserMentioned($mentioner, $model, $url));
            }
        }

        return $text;
    }
}
