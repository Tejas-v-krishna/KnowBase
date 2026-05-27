<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\Topic;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $topics = Topic::all()->keyBy('slug');
        $tags = Tag::all();

        if ($users->isEmpty() || $topics->isEmpty()) return;

        $questionsData = [
            // Mathematics
            ['topic' => 'mathematics', 'title' => 'What is the difference between a permutation and a combination?', 'body' => '<p>I keep getting confused between permutations and combinations. Can someone explain when to use each one with a clear real-world example?</p>', 'level' => 'high_school'],
            ['topic' => 'mathematics', 'title' => 'How do I solve a quadratic equation using the quadratic formula?', 'body' => '<p>My teacher showed us the quadratic formula ax² + bx + c = 0, but I don\'t understand how to plug in the values correctly. Can someone walk me through a step-by-step example?</p>', 'level' => 'high_school'],
            ['topic' => 'mathematics', 'title' => 'What is the Pythagorean theorem and when do I use it?', 'body' => '<p>I know the formula is a² + b² = c², but I\'m not sure which side is "c" and when exactly I should apply this theorem. Can someone clarify?</p>', 'level' => 'middle_school'],
            ['topic' => 'mathematics', 'title' => 'How do you find the area and perimeter of a circle?', 'body' => '<p>What are the formulas for area and perimeter (circumference) of a circle? And what exactly does π (pi) represent?</p>', 'level' => 'middle_school'],
            ['topic' => 'mathematics', 'title' => 'What is the difference between mean, median, and mode?', 'body' => '<p>I understand these are all types of averages, but when would I use each one? Which is the best measure of central tendency and why?</p>', 'level' => 'high_school'],
            ['topic' => 'mathematics', 'title' => 'How do I calculate compound interest?', 'body' => '<p>What is the formula for compound interest and how is it different from simple interest? Can you show me an example with actual numbers?</p>', 'level' => 'high_school'],

            // Science / Physics
            ['topic' => 'science', 'title' => 'What is Newton\'s Second Law of Motion in simple terms?', 'body' => '<p>I understand F = ma but I\'m struggling with what force, mass, and acceleration actually mean physically. Can someone explain this with a real-life example?</p>', 'level' => 'high_school'],
            ['topic' => 'science', 'title' => 'What is the difference between speed and velocity?', 'body' => '<p>My textbook says speed is scalar and velocity is a vector. Can someone explain what this means and why it matters in physics problems?</p>', 'level' => 'high_school'],
            ['topic' => 'science', 'title' => 'How does photosynthesis work step by step?', 'body' => '<p>I know plants use sunlight and CO2 to make food, but what are the actual chemical reactions involved? What is the role of chlorophyll?</p>', 'level' => 'middle_school'],
            ['topic' => 'science', 'title' => 'What is the difference between mitosis and meiosis?', 'body' => '<p>Both involve cell division but they seem to produce different results. Can someone explain the key differences and when each process occurs in the body?</p>', 'level' => 'high_school'],
            ['topic' => 'science', 'title' => 'What causes earthquakes and how are they measured?', 'body' => '<p>I understand it has something to do with tectonic plates, but what exactly happens underground? And what is the Richter scale?</p>', 'level' => 'middle_school'],

            // Computer Science / Programming
            ['topic' => 'computer-science', 'title' => 'What is the difference between a stack and a queue data structure?', 'body' => '<p>I\'ve heard about stacks and queues in computer science class. Can someone explain how each works and give a real-world example of where each is used?</p>', 'level' => 'college'],
            ['topic' => 'computer-science', 'title' => 'How does a binary search algorithm work?', 'body' => '<p>My teacher mentioned binary search is faster than linear search. Can someone explain how it works and why it requires a sorted array?</p>', 'level' => 'high_school'],
            ['topic' => 'computer-science', 'title' => 'What is the difference between object-oriented and functional programming?', 'body' => '<p>I\'ve been learning Python and keep hearing about OOP vs functional programming. What are the main differences and when should I use each approach?</p>', 'level' => 'college'],
            ['topic' => 'computer-science', 'title' => 'What is recursion and how does it actually work in code?', 'body' => '<p>I understand recursion calls itself, but I get confused about the base case and how the call stack works. Can someone show me a simple example?</p>', 'level' => 'high_school'],
            ['topic' => 'computer-science', 'title' => 'How does RAM work and why does it matter for performance?', 'body' => '<p>I keep hearing that more RAM means a faster computer. Can someone explain what RAM actually does and how it differs from storage (SSD/HDD)?</p>', 'level' => 'high_school'],

            // English / Literature
            ['topic' => 'english', 'title' => 'What is the difference between a simile and a metaphor?', 'body' => '<p>Both compare two things but in different ways. Can someone give clear examples of each and explain how to identify them in a text?</p>', 'level' => 'middle_school'],
            ['topic' => 'english', 'title' => 'How do I write a strong thesis statement for an essay?', 'body' => '<p>My English teacher always says my thesis statement is weak. What makes a good thesis statement and how do I structure it correctly?</p>', 'level' => 'high_school'],
            ['topic' => 'english', 'title' => 'What are the main themes in Shakespeare\'s Hamlet?', 'body' => '<p>I\'m studying Hamlet for my literature class. What are the central themes and how do they connect to Hamlet\'s character development throughout the play?</p>', 'level' => 'high_school'],

            // History / Social Studies
            ['topic' => 'history', 'title' => 'What were the main causes of World War I?', 'body' => '<p>I know the assassination of Archduke Franz Ferdinand started the war, but what were the underlying causes that made Europe ready to explode into conflict?</p>', 'level' => 'high_school'],
            ['topic' => 'history', 'title' => 'What was the significance of the Industrial Revolution?', 'body' => '<p>How did the Industrial Revolution change everyday life for ordinary people? Was it mostly positive or did it create serious problems?</p>', 'level' => 'high_school'],
            ['topic' => 'history', 'title' => 'What is democracy and how did it originate?', 'body' => '<p>My civics class is covering democracy. Can someone explain what democracy actually means and trace its origins back to ancient Greece?</p>', 'level' => 'middle_school'],

            // Chemistry
            ['topic' => 'chemistry', 'title' => 'What is the difference between an atom and a molecule?', 'body' => '<p>I keep mixing these up in chemistry. Can someone explain the difference clearly, with examples of each?</p>', 'level' => 'middle_school'],
            ['topic' => 'chemistry', 'title' => 'What is the periodic table and how is it organized?', 'body' => '<p>I understand the periodic table lists all the elements, but why is it arranged the way it is? What does each column and row represent?</p>', 'level' => 'high_school'],
            ['topic' => 'chemistry', 'title' => 'How do ionic and covalent bonds differ?', 'body' => '<p>My chemistry teacher keeps mentioning ionic and covalent bonds but I\'m confused about what makes them different. Can someone explain both with simple examples?</p>', 'level' => 'high_school'],

            // Geography
            ['topic' => 'geography', 'title' => 'What is the difference between latitude and longitude?', 'body' => '<p>I understand these are coordinate lines on the globe, but I always mix up which is horizontal and which is vertical. Can someone explain clearly?</p>', 'level' => 'middle_school'],
            ['topic' => 'geography', 'title' => 'What causes the seasons to change throughout the year?', 'body' => '<p>A lot of people think seasons are caused by Earth\'s distance from the Sun. Is that correct? If not, what actually causes seasons?</p>', 'level' => 'middle_school'],

            // Economics
            ['topic' => 'economics', 'title' => 'What is the law of supply and demand?', 'body' => '<p>Can someone explain how supply and demand affect prices? And what happens when one changes but the other doesn\'t?</p>', 'level' => 'high_school'],
            ['topic' => 'economics', 'title' => 'What is inflation and why does it matter?', 'body' => '<p>I keep hearing about inflation in the news. Can someone explain what it is, what causes it, and how it affects everyday people?</p>', 'level' => 'high_school'],

            // Health
            ['topic' => 'health', 'title' => 'How does the human immune system fight off disease?', 'body' => '<p>When we get sick and then recover, what exactly happened inside our body? How does the immune system recognise and destroy pathogens?</p>', 'level' => 'high_school'],
            ['topic' => 'health', 'title' => 'What is the difference between aerobic and anaerobic exercise?', 'body' => '<p>My PE teacher uses these terms a lot. Can someone explain the difference and give examples of each type of exercise?</p>', 'level' => 'middle_school'],
        ];

        $englishAnswers = [
            'Great question! The key thing to understand here is that this concept builds on fundamental principles you\'ve already learned. Let me walk you through it step by step.',
            'I struggled with this too! The easiest way to think about it is to break the problem into smaller parts and tackle each one individually.',
            'This is a classic concept that appears in many exams. The most important thing to remember is to always write down what you know first, then apply the formula.',
            'Think of it this way: the formula gives you the relationship between the variables. Once you understand what each variable represents, substituting values becomes straightforward.',
            'The trick here is to identify what the question is really asking before you start solving. Read it twice, underline the key numbers, and then apply the correct method.',
        ];

        foreach ($questionsData as $data) {
            $topic = $topics->get($data['topic']) ?? $topics->first();

            $question = Question::create([
                'user_id'       => $users->random()->id,
                'topic_id'      => $topic?->id ?? $topics->first()->id,
                'title'         => $data['title'],
                'body'          => $data['body'],
                'status'        => 'open',
                'school_level'  => $data['level'],
                'views_count'   => rand(30, 600),
                'answers_count' => 0,
                'votes_count'   => rand(0, 20),
            ]);

            if ($tags->count()) {
                $question->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());
            }

            // Add 0-3 answers
            $numAnswers = rand(0, 3);
            $createdAnswers = [];
            for ($j = 0; $j < $numAnswers; $j++) {
                $answerer = $users->where('id', '!=', $question->user_id)->random();
                $answerText = $englishAnswers[array_rand($englishAnswers)];
                $createdAnswers[] = Answer::create([
                    'question_id' => $question->id,
                    'user_id'     => $answerer->id,
                    'body'        => '<p>' . $answerText . '</p>',
                    'is_accepted' => false,
                    'votes_count' => rand(0, 10),
                ]);
            }

            $question->update(['answers_count' => count($createdAnswers)]);

            // 35% chance of being resolved
            if (count($createdAnswers) > 0 && rand(1, 100) <= 35) {
                $accepted = $createdAnswers[array_rand($createdAnswers)];
                $accepted->update(['is_accepted' => true]);
                $question->update([
                    'status'             => 'resolved',
                    'accepted_answer_id' => $accepted->id,
                ]);
            }
        }
    }
}