<?php
namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Events\ReputationChanged;
use Carbon\Carbon;

class ReputationService
{
    public static function addPoints(User $user, int $points)
    {
        $user->increment('reputation', $points);
        
        event(new ReputationChanged($user));
    }

    public static function subtractPoints(User $user, int $points)
    {
        // Don't let reputation go below 0
        $newReputation = max(0, $user->reputation - $points);
        $user->update(['reputation' => $newReputation]);
        
        event(new ReputationChanged($user));
    }

    public static function checkAndAwardBadges(User $user)
    {
        $reputation = $user->reputation;
        
        // 1. Reputation-based badges
        $repBadges = [
            'Rising Star' => 100,
            'Contributor' => 500,
            'Expert' => 1000
        ];
        
        foreach ($repBadges as $badgeName => $threshold) {
            if ($reputation >= $threshold) {
                self::awardBadgeByName($user, $badgeName);
            }
        }

        // 2. Action-based badges (checked on demand or here)
        $articlesCount = $user->articles()->where('status', 'published')->count();
        if ($articlesCount >= 1) {
            self::awardBadgeByName($user, 'First Post');
        }
        if ($articlesCount >= 10) {
            self::awardBadgeByName($user, 'Prolific');
        }

        $questionsCount = $user->questions()->count();
        if ($questionsCount >= 1) {
            self::awardBadgeByName($user, 'Curious');
        }

        $answersCount = $user->answers()->count();
        if ($answersCount >= 50) {
            self::awardBadgeByName($user, 'Answer Machine');
        }

        $acceptedAnswersCount = $user->answers()->where('is_accepted', true)->count();
        if ($acceptedAnswersCount >= 1) {
            self::awardBadgeByName($user, 'Helpful');
        }
    }

    protected static function awardBadgeByName(User $user, string $badgeName)
    {
        $badge = Badge::where('name', $badgeName)->first();
        if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id, ['awarded_at' => Carbon::now()]);
            
            // Create a database notification for badge award
            $user->notify(new \App\Notifications\BadgeAwardedNotification($badge));
        }
    }
}
