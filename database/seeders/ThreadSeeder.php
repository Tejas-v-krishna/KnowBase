<?php

namespace Database\Seeders;

use App\Models\Thread;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ThreadSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $topics = Topic::all();
        $tags = Tag::all();

        if ($users->isEmpty() || $topics->isEmpty()) {
            return;
        }

        // Real, high-quality community discussions
        $realThreads = [
            [
                'topic' => 'Technology',
                'title' => 'How to get started with Git and GitHub? A beginner\'s guide',
                'body' => '<p>Hey everyone! I often see students struggling with version control when working on group projects. What are the best resources, interactive tutorials, or cheatsheets you\'ve found to master the basic Git commands (push, pull, commit, branch) without breaking your repository? Let\'s compile a list!</p>',
                'replies' => [
                    'The best way to learn is definitely through the interactive game "Git Hug" or "Learn Git Branching" online! Visualizing the commits as nodes makes a huge difference.',
                    'Always remember: pull before you push! Sticking to this one simple habit saves you from 90% of merge conflict headaches.',
                    'I highly recommend keeping a Git cheatsheet pinned on your desk. Commands like `git status` and `git diff` are your best friends when you aren\'t sure what changes you\'ve made.'
                ]
            ],
            [
                'topic' => 'Science',
                'title' => 'Tips for memorizing the Periodic Table of Elements',
                'body' => '<p>I have a chemistry mid-term coming up next week and I need to memorize the first 20 elements of the periodic table along with their atomic numbers. Does anyone have any fun mnemonics, songs, or visual techniques that worked for you?</p>',
                'replies' => [
                    'Try the classic mnemonic: "Harry He Likes Beer But Can Not Obtain Four Nuts." (H, He, Li, Be, B, C, N, O, F, Ne). It makes memorizing the first ten a breeze!',
                    'There is an incredibly catchy song by ASAP Science on YouTube that covers the entire periodic table. I sang it in my head during my exams and got an A!',
                    'Flashcards are highly underrated. Use Anki to set up spaced repetition for the elements you struggle with most.'
                ]
            ],
            [
                'topic' => 'Design',
                'title' => 'UI/UX Case Study: Critique my student portfolio project!',
                'body' => '<p>Hey community! I just finished designing my first high-fidelity mobile application mockup for a local library book-booking app. I would love to get your honest critique on my spacing, typography hierarchy, and accessibility contrast choices. Please let me know what you think!</p>',
                'replies' => [
                    'This looks incredibly clean! However, I noticed that the search bar contrast in the header might not pass WCAG AA standards. Try darkening the placeholder text slightly.',
                    'Your typography choices are excellent, especially the Outfit font for headers! I would suggest increasing the tap target area of the navigation icons to at least 48x48px for better mobile accessibility.'
                ]
            ],
            [
                'topic' => 'Business',
                'title' => 'What is the difference between a Sole Proprietorship and an LLC?',
                'body' => '<p>I am planning to launch a small freelance graphic design business this summer. I\'m trying to understand the legal, liability, and tax differences between operating as a Sole Proprietor versus registering as a Limited Liability Company (LLC). Any business majors here who can break it down simply?</p>',
                'replies' => [
                    'The biggest difference is liability! As a Sole Proprietor, your personal assets (car, savings) are on the line if your business gets sued. An LLC creates a "corporate shield" that protects your personal assets.',
                    'Tax-wise, both are typically "pass-through" entities, meaning business profits go directly onto your personal tax return. Registering an LLC costs a bit upfront but the liability protection is 100% worth it.'
                ]
            ],
            [
                'topic' => 'Health',
                'title' => 'Best ergonomic setups for long study and coding sessions',
                'body' => '<p>Lately, I\'ve been experiencing lower back and wrist pain after studying at my desk for more than 4 hours. What are some budget-friendly ergonomic chairs, laptop stands, seat cushions, or posture habits that you all use to stay healthy and pain-free during exam seasons?</p>',
                'replies' => [
                    'Invest in a cheap external keyboard and a laptop stand! Raising your screen to eye level prevents you from hunching forward, which relieves immense neck strain.',
                    'Try the 20-20-20 rule for your eyes and back: Every 20 minutes, look at something 20 feet away for 20 seconds, and stand up to stretch your lower back.',
                    'A simple foam seat cushion and a rolled-up towel behind your lower back (lumbar support) works wonders if you can\'t afford a high-end ergonomic chair right now.'
                ]
            ],
            [
                'topic' => 'Education',
                'title' => 'Active Recall vs. Re-reading: What is the most effective study method?',
                'body' => '<p>I\'ve been reading cognitive psychology papers and they all praise Active Recall and Spaced Repetition over highlighted re-reading. How do you implement active recall in your daily study routine? Do you use Anki, flashcards, or write summary questions?</p>',
                'replies' => [
                    'I use the "Feynman Technique." I try to write down and explain a complex topic as if I am teaching it to a 10-year-old child. If I struggle to explain it simply, it means I don\'t truly understand it yet.',
                    'Anki is a lifesaver for medical and language students! Spaced repetition completely changes how information moves from short-term to long-term memory.',
                    'Instead of highlighting text while reading, I write questions on the margins. When reviewing, I cover the text and try to answer my own margin questions.'
                ]
            ],
            [
                'topic' => 'Personal Development',
                'title' => 'How to overcome procrastination using the Pomodoro Technique',
                'body' => '<p>I always find myself delaying assignments until the night before they are due. I started trying the Pomodoro Technique (25 mins work, 5 mins break) yesterday and it helped a bit. How do you stay disciplined during those 25 minutes? Any app recommendations?</p>',
                'replies' => [
                    'Put your phone in a completely different room during your work sessions. Out of sight, out of mind is the best procrastination cure!',
                    'I recommend the app "Forest"! It gamifies focus: you plant a virtual seed, and it grows into a tree as long as you don\'t leave the app. If you close the app to check social media, your tree dies.',
                    'Start with just a 15-minute timer if 25 minutes feels too daunting. Often, the hardest part is just starting.'
                ]
            ],
            [
                'topic' => 'Arts',
                'title' => 'Getting started with Digital Painting: Wacom vs. iPad Pro',
                'body' => '<p>I want to transition from traditional sketching to digital painting. I am debating whether to purchase a Wacom drawing tablet for my laptop or invest in an iPad Pro with an Apple Pencil. Which device offers a better learning curve and experience for student artists?</p>',
                'replies' => [
                    'The iPad Pro with Procreate offers an incredibly intuitive and portable experience since you draw directly on the screen. It feels much closer to drawing on paper.',
                    'Wacom is the industry standard for professional concept art and 3D texturing. If you plan to work in a studio using Photoshop or ZBrush, learning on a graphic tablet is highly valuable.'
                ]
            ],
            [
                'topic' => 'Technology',
                'title' => 'Why is Python so popular for Data Science and Machine Learning?',
                'body' => '<p>I\'m starting my data science journey and noticed that almost every course uses Python instead of languages like Java or C++. Can someone explain what makes Python\'s ecosystem, libraries (Pandas, NumPy, Scikit-Learn), and syntax so well-suited for statistical analysis?</p>',
                'replies' => [
                    'Python has an incredibly clean, readable syntax that reads almost like English. This allows researchers and data scientists to focus on math and logic instead of complex syntax.',
                    'The community support is massive! Libraries like Pandas for data manipulation and TensorFlow for deep learning are highly optimized and have millions of users helping each other.'
                ]
            ],
            [
                'topic' => 'Science',
                'title' => 'Understanding the difference between Mitosis and Meiosis',
                'body' => '<p>I keep getting confused between the phases of cell division. Can someone provide a simple comparison of Mitosis vs. Meiosis? Specifically, how do the chromosome counts and genetic variations differ between the daughter cells in each process?</p>',
                'replies' => [
                    'Think of Mitosis as "My-Toe-sis" (normal body cells like your toe!). It results in two identical diploid cells for growth and repair. Meiosis results in four unique haploid sex cells (gametes).',
                    'Mitosis has 1 division stage, while Meiosis has 2 division stages. Meiosis also involves "crossing over" during Prophase I, which is why siblings look different despite having the same parents.'
                ]
            ],
            [
                'topic' => 'Education',
                'title' => 'How to apply for international student scholarships?',
                'body' => '<p>Hello everyone! I\'m planning to apply for master\'s programs in Europe and Canada next year. The tuition fees are quite high, so I am looking for reputable international scholarships or grants. What is the application process like, and what makes a statement of purpose stand out?</p>',
                'replies' => [
                    'Start your research at least a year in advance! Write a statement of purpose that focuses on your specific research goals and how the host university\'s faculty align with your thesis.',
                    'Don\'t just list your resume achievements in your essay. Tell a story about a specific academic challenge you faced and how you overcame it.'
                ]
            ],
            [
                'topic' => 'Business',
                'title' => 'The rise of Micro-credentials: Are they worth it?',
                'body' => '<p>With online certificates from Coursera, Google, and Harvard becoming so common, do employers actually value micro-credentials on resumes? Has anyone successfully landed an internship or job primarily because of a specialized online certification?</p>',
                'replies' => [
                    'They are excellent for showing initiative and curiosity! However, a certificate alone won\'t land you a job; you must apply that knowledge to build a portfolio of real projects.',
                    'Many tech recruiters look favorably on Google or AWS certifications because they test practical, hands-on skills that aren\'t always covered in standard college curricula.'
                ]
            ],
            [
                'topic' => 'Personal Development',
                'title' => 'How to build a consistent morning routine for productivity',
                'body' => '<p>I usually wake up tired and rush straight to my desk. I want to build a mindful morning routine (maybe involving meditation, reading, or light exercise) that sets a positive, high-energy tone for the day. What does your ideal morning look like, and how do you stick to it?</p>',
                'replies' => [
                    'The secret to a great morning routine actually starts the night before! Establish a wind-down habit, avoid screens before bed, and get 8 hours of sleep.',
                    'Keep it simple! Don\'t try to build a 2-hour morning routine immediately. Start with just drinking a glass of water and stretching for 5 minutes right after waking up.'
                ]
            ],
            [
                'topic' => 'Design',
                'title' => 'Skeuomorphism vs. Flat Design: The evolution of user interfaces',
                'body' => '<p>Remember when iOS had leather textures and glossy buttons? Now everything is flat and minimal. Do you think we will ever see a return to realistic skeuomorphic textures, or is flat/neumorphic design here to stay? What are the pros and cons of each style?</p>',
                'replies' => [
                    'Flat design is excellent for speed and loading times on mobile devices. However, skeuomorphism is making a huge comeback in VR/AR headsets (like Apple Vision Pro) where depth cues are critical for interaction!',
                    'I think modern UI is shifting towards "fluent design" — flat elements with realistic lighting, glassmorphism, and physical shadows.'
                ]
            ],
            [
                'topic' => 'Arts',
                'title' => 'The role of art and design in modern marketing campaigns',
                'body' => '<p>Art isn\'t just for galleries! From branding to commercials, visual design shapes how consumers perceive companies. Let\'s discuss some of the most visually stunning or creative marketing campaigns you\'ve seen recently, and analyze why their design was so effective.</p>',
                'replies' => [
                    'Apple\'s "Shot on iPhone" billboard campaign is a masterpiece of design. It lets the raw artistic beauty of the photographs stand by themselves with minimal text.',
                    'Branding is everything. Minimal, harmonious color palettes can make a brand feel premium and trustworthy at first glance.'
                ]
            ],
        ];

        foreach ($realThreads as $index => $data) {
            $topic = Topic::where('name', $data['topic'])->first();
            if (!$topic) {
                $topic = $topics->random();
            }

            // Create thread with realistic data
            $thread = Thread::create([
                'user_id' => $users->random()->id,
                'topic_id' => $topic->id,
                'title' => $data['title'],
                'body' => $data['body'],
                'is_pinned' => $index < 2, // First 2 threads are pinned
                'is_resolved' => $index % 3 === 0, // Some are resolved
                'replies_count' => 0,
                'views_count' => rand(30, 300),
                'created_at' => now()->subDays(rand(1, 10))->subHours(rand(1, 12)),
            ]);

            // Attach 1 to 3 random tags
            $thread->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());

            // Add real replies
            foreach ($data['replies'] as $replyBody) {
                $replier = $users->random();
                while ($replier->id === $thread->user_id) {
                    $replier = $users->random();
                }

                Reply::create([
                    'thread_id' => $thread->id,
                    'user_id' => $replier->id,
                    'parent_id' => null,
                    'body' => '<p>' . $replyBody . '</p>',
                    'created_at' => $thread->created_at->addHours(rand(1, 24)),
                ]);
            }

            // Update replies count
            $thread->update(['replies_count' => Reply::where('thread_id', $thread->id)->count()]);
        }
    }
}