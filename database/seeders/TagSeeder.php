<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'PHP', 'Laravel', 'JavaScript', 'Python', 'React',
            'UX Design', 'Productivity', 'HTML', 'CSS', 'Database',
            'Security', 'Docker', 'Cloud', 'Vue', 'Swift',
            'Git', 'Machine Learning', 'Agile', 'Design Patterns', 'Careers'
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'description' => "Topics, articles, and questions related to {$tag}."
            ]);
        }
    }
}