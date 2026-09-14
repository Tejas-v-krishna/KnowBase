<?php

namespace Database\Seeders;

use App\Models\Challenge;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $challenges = [
            [
                'title' => 'Answer 3 Questions',
                'description' => 'Help the community by answering 3 open questions this week',
                'icon' => '🎯',
                'type' => 'answer_questions',
                'target_count' => 3,
                'reward_xp' => 50,
                'is_active' => true,
            ],
            [
                'title' => 'Write Your First Article',
                'description' => 'Share your knowledge by publishing an article or guide',
                'icon' => '✍️',
                'type' => 'write_article',
                'target_count' => 1,
                'reward_xp' => 100,
                'is_active' => true,
            ],
            [
                'title' => 'Help 5 Different People',
                'description' => 'Answer questions from 5 unique users this week',
                'icon' => '🤝',
                'type' => 'help_users',
                'target_count' => 5,
                'reward_xp' => 75,
                'is_active' => true,
            ],
            [
                'title' => 'Earn 10 Upvotes',
                'description' => 'Write quality answers that earn at least 10 total upvotes',
                'icon' => '⬆️',
                'type' => 'earn_votes',
                'target_count' => 10,
                'reward_xp' => 60,
                'is_active' => true,
            ],
            [
                'title' => 'Ask 2 Great Questions',
                'description' => 'Spark discussion by asking 2 thoughtful questions',
                'icon' => '💡',
                'type' => 'ask_questions',
                'target_count' => 2,
                'reward_xp' => 40,
                'is_active' => true,
            ],
        ];

        foreach ($challenges as $challenge) {
            Challenge::create($challenge);
        }
    }
}
