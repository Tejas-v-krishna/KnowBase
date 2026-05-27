<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Post',
                'description' => 'Awarded for publishing your first article.',
                'icon' => '📝',
                'criteria_type' => 'articles_count',
                'criteria_value' => 1
            ],
            [
                'name' => 'Curious',
                'description' => 'Awarded for asking your first question.',
                'icon' => '❓',
                'criteria_type' => 'questions_count',
                'criteria_value' => 1
            ],
            [
                'name' => 'Helpful',
                'description' => 'Awarded for receiving your first accepted answer.',
                'icon' => '✅',
                'criteria_type' => 'accepted_answers_count',
                'criteria_value' => 1
            ],
            [
                'name' => 'Rising Star',
                'description' => 'Awarded for reaching 100 reputation points.',
                'icon' => '✨',
                'criteria_type' => 'reputation',
                'criteria_value' => 100
            ],
            [
                'name' => 'Contributor',
                'description' => 'Awarded for reaching 500 reputation points.',
                'icon' => '🎓',
                'criteria_type' => 'reputation',
                'criteria_value' => 500
            ],
            [
                'name' => 'Expert',
                'description' => 'Awarded for reaching 1000 reputation points.',
                'icon' => '🏆',
                'criteria_type' => 'reputation',
                'criteria_value' => 1000
            ],
            [
                'name' => 'Prolific',
                'description' => 'Awarded for publishing 10 articles.',
                'icon' => '📚',
                'criteria_type' => 'articles_count',
                'criteria_value' => 10
            ],
            [
                'name' => 'Answer Machine',
                'description' => 'Awarded for posting 50 answers.',
                'icon' => '⚡',
                'criteria_type' => 'answers_count',
                'criteria_value' => 50
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}