<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TopicSeeder::class,
            TagSeeder::class,
            BadgeSeeder::class,
            ChallengeSeeder::class,
            QuestionSeeder::class,
            ArticleSeeder::class,
            ThreadSeeder::class,
        ]);
    }
}