<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Technology',
                'description' => 'Software development, hardware, security, internet of things, artificial intelligence and modern tech news.',
            ],
            [
                'name' => 'Science',
                'description' => 'Physics, astronomy, biology, chemistry, and research discussions about the universe.',
            ],
            [
                'name' => 'Design',
                'description' => 'User experience (UX), interface design (UI), brand identity, illustrations, and styling guidelines.',
            ],
            [
                'name' => 'Business',
                'description' => 'Startups, entrepreneurship, finance, marketing strategies, and leadership techniques.',
            ],
            [
                'name' => 'Health',
                'description' => 'Fitness, mental well-being, nutrition, medicine, and healthy lifestyle choices.',
            ],
            [
                'name' => 'Education',
                'description' => 'E-learning, pedagogical research, study tips, academia, and pedagogical discussions.',
            ],
            [
                'name' => 'Arts',
                'description' => 'Music, cinema, painting, photography, literature, and general creative expressions.',
            ],
            [
                'name' => 'Personal Development',
                'description' => 'Productivity, career growth, habits, mindset improvement, and self-education.',
            ]
        ];

        foreach ($topics as $topic) {
            Topic::create($topic);
        }
    }
}