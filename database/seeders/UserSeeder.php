<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admins
        User::create([
            'name' => 'Tejas V Krishna',
            'username' => 'tejas',
            'email' => 'admin@knowbase.dev',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'reputation' => 1000,
            'bio' => 'Platform Administrator. Here to manage the knowledge base.',
        ]);

        User::create([
            'name' => 'Amal C',
            'username' => 'amal',
            'email' => 'amal@knowbase.dev',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'reputation' => 1000,
            'bio' => 'Platform Administrator. Here to manage the knowledge base.',
        ]);

        User::create([
            'name' => 'Mohammad Jaish',
            'username' => 'jaish',
            'email' => 'jaish@knowbase.dev',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'reputation' => 1000,
            'bio' => 'Platform Administrator. Here to manage the knowledge base.',
        ]);

        // Moderator
        User::create([
            'name' => 'Priya Sharma',
            'username' => 'moderator',
            'email' => 'moderator@knowbase.dev',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'reputation' => 500,
            'bio' => 'Community Moderator. Keeping the platform safe and clean.',
        ]);

        // Regular users
        User::factory()->count(10)->create();

        // Seed random activities for the last 365 days to populate the heatmap
        $allUsers = User::all();
        $activityTypes = ['question_asked', 'answer_posted', 'bounty_won', 'badge_earned', 'article_created'];
        
        foreach ($allUsers as $user) {
            // Give each user 50 to 150 random activities scattered across the last 365 days
            $activityCount = rand(50, 150);
            for ($i = 0; $i < $activityCount; $i++) {
                $createdAt = now()->subDays(rand(0, 364))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                \App\Models\Activity::create([
                    'user_id' => $user->id,
                    'type' => $activityTypes[array_rand($activityTypes)],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}