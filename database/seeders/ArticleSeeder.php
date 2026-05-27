<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use App\Models\Topic;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $users  = User::all();
        $topics = Topic::all()->keyBy('slug');
        $tags   = Tag::all();

        if ($users->isEmpty() || $topics->isEmpty()) return;

        $articlesData = [
            [
                'topic' => 'mathematics',
                'title' => 'Understanding Quadratic Equations: A Complete Beginner\'s Guide',
                'excerpt' => 'Quadratic equations appear in everything from physics to economics. This guide breaks down the quadratic formula and shows you exactly how to use it.',
                'body' => '<h2>What is a Quadratic Equation?</h2><p>A quadratic equation is any equation in the form ax² + bx + c = 0, where a, b, and c are constants and a ≠ 0. The word "quadratic" comes from "quadratum," the Latin word for square.</p><h2>The Quadratic Formula</h2><p>The quadratic formula is one of the most important formulas in mathematics: x = (-b ± √(b² - 4ac)) / 2a. The ± symbol means you get two possible answers — one using addition and one using subtraction.</p><h2>The Discriminant: What it Tells You</h2><p>The part under the square root — b² - 4ac — is called the discriminant. If it is positive, the equation has two real solutions. If it equals zero, there is exactly one solution. If it is negative, there are no real solutions.</p><h2>Step-by-Step Example</h2><p>Let\'s solve: x² + 5x + 6 = 0. Here a = 1, b = 5, c = 6. Plugging in: x = (-5 ± √(25 - 24)) / 2 = (-5 ± 1) / 2. So x = -2 or x = -3.</p>',
            ],
            [
                'topic' => 'science',
                'title' => 'How Photosynthesis Works: From Sunlight to Sugar',
                'excerpt' => 'Plants are incredible chemical factories. Discover the step-by-step process by which they convert sunlight, water, and carbon dioxide into glucose and oxygen.',
                'body' => '<h2>The Big Picture</h2><p>Photosynthesis is the process by which plants, algae, and some bacteria convert light energy into chemical energy stored as glucose. The overall equation is: 6CO₂ + 6H₂O + light energy → C₆H₁₂O₆ + 6O₂</p><h2>Where Does Photosynthesis Happen?</h2><p>Photosynthesis occurs in the chloroplasts — organelles found primarily in the cells of leaves. Inside chloroplasts, there is a green pigment called chlorophyll that absorbs light, mainly red and blue wavelengths.</p><h2>The Two Stages</h2><p>Photosynthesis has two main stages: the light-dependent reactions and the Calvin cycle (light-independent reactions). In the first stage, chlorophyll absorbs sunlight and uses it to split water molecules, releasing oxygen as a byproduct and producing ATP and NADPH. In the Calvin cycle, the plant uses the ATP and NADPH to build glucose from carbon dioxide.</p><h2>Why This Matters</h2><p>Photosynthesis is the foundation of nearly all life on Earth. It produces the oxygen we breathe and forms the base of the food chain by providing energy-rich molecules that animals eat.</p>',
            ],
            [
                'topic' => 'computer-science',
                'title' => 'Introduction to Algorithms: Big O Notation Explained Simply',
                'excerpt' => 'Big O notation is how computer scientists measure and compare the efficiency of algorithms. Learn what O(n), O(log n), and O(n²) actually mean.',
                'body' => '<h2>What is an Algorithm?</h2><p>An algorithm is a step-by-step procedure for solving a problem. Just as there are multiple routes to a destination, there are usually multiple algorithms to solve any given problem. The question is: which one is fastest?</p><h2>Why We Need Big O Notation</h2><p>When we talk about algorithm efficiency, we do not measure time in seconds — because a fast computer and a slow computer would give different results. Instead, we count operations. Big O notation describes how the number of operations grows as the input size grows.</p><h2>Common Big O Complexities</h2><p>O(1) — Constant time: the algorithm always takes the same number of steps regardless of input size. Example: accessing an array element by its index.</p><p>O(n) — Linear time: the number of operations grows proportionally with the input. Example: searching through an unsorted list.</p><p>O(log n) — Logarithmic time: each step halves the remaining work. Example: binary search on a sorted array.</p><p>O(n²) — Quadratic time: the number of operations is the square of the input size. Example: bubble sort with nested loops.</p><h2>Choosing the Right Algorithm</h2><p>When your dataset is small, any algorithm will do. But when you scale to millions of records, the difference between O(n log n) and O(n²) can mean the difference between one second and several hours of processing.</p>',
            ],
            [
                'topic' => 'english',
                'title' => 'How to Write a Perfect Essay: Structure, Argument & Style',
                'excerpt' => 'A well-structured essay is clear, persuasive, and engaging. This guide walks you through every section — from writing a strong thesis to crafting a memorable conclusion.',
                'body' => '<h2>The Foundation: A Strong Thesis Statement</h2><p>Every great essay begins with a clear thesis statement — a single sentence that states your central argument. A weak thesis is vague ("Shakespeare was a great writer"). A strong thesis is specific and arguable ("In Hamlet, Shakespeare uses the motif of performance and acting to argue that all social roles are fundamentally artificial").</p><h2>The Introduction</h2><p>Your introduction should hook the reader, provide context, and end with your thesis. Open with something compelling — a thought-provoking question, a surprising fact, or a vivid scene. Avoid starting with "In this essay I will..." — it is weak and unnecessary.</p><h2>Body Paragraphs: The PEEL Structure</h2><p>Each body paragraph should follow the PEEL structure: Point (your topic sentence), Evidence (a quote or example), Explanation (how the evidence supports your point), and Link (transition to the next idea). Never quote without explaining.</p><h2>The Conclusion</h2><p>Your conclusion should not just repeat the introduction. Instead, synthesize your argument — show how all your points come together to prove your thesis. End with a wider implication or a thought that gives your reader something to think about.</p>',
            ],
            [
                'topic' => 'history',
                'title' => 'The Causes of World War I: A Deep Dive',
                'excerpt' => 'World War I was not caused by a single assassination. Long-simmering tensions, alliance systems, and imperial rivalry made Europe a powder keg by 1914.',
                'body' => '<h2>The MAIN Causes</h2><p>Historians often use the acronym MAIN to summarize the causes of World War I: Militarism, Alliance systems, Imperialism, and Nationalism.</p><h2>Militarism</h2><p>By 1914, the major European powers had been building up their military forces for decades. Germany had dramatically expanded its navy, triggering a naval arms race with Britain. This culture of militarism meant that war was seen as both inevitable and even desirable by some military leaders.</p><h2>Alliance Systems</h2><p>Europe was divided into two armed camps: the Triple Alliance (Germany, Austria-Hungary, Italy) and the Triple Entente (France, Russia, Britain). These alliances meant that a local conflict could quickly draw in all major powers — like a chain reaction.</p><h2>Imperialism and Nationalism</h2><p>Competition for overseas colonies created friction between the powers, while rising nationalism — especially in the Balkans, where Slavic peoples sought independence from Austria-Hungary — created flashpoints. It was in this context that the assassination of Archduke Franz Ferdinand in Sarajevo in June 1914 lit the fuse.</p>',
            ],
            [
                'topic' => 'chemistry',
                'title' => 'The Periodic Table: How to Read and Understand It',
                'excerpt' => 'The periodic table is one of the most powerful tools in science. Once you understand how it is organised, chemistry becomes much easier.',
                'body' => '<h2>What is the Periodic Table?</h2><p>The periodic table is a systematic arrangement of all known chemical elements, organised by their atomic number (the number of protons in the nucleus). Currently, there are 118 confirmed elements.</p><h2>Rows and Columns</h2><p>The horizontal rows are called periods. Elements in the same period have the same number of electron shells. The vertical columns are called groups. Elements in the same group have similar chemical properties because they have the same number of electrons in their outermost shell.</p><h2>Metals, Non-Metals, and Metalloids</h2><p>The periodic table is divided into three main categories. Metals (on the left) are shiny, conduct electricity, and are generally solid at room temperature. Non-metals (on the right) are poor conductors and are often gases. Metalloids (along the staircase line) have properties of both.</p><h2>Reading an Element Box</h2><p>Each box in the periodic table contains: the element symbol (e.g., Fe for iron), the atomic number (number of protons), and the atomic mass (average mass of the atom). Understanding these three pieces of information unlocks most of basic chemistry.</p>',
            ],
            [
                'topic' => 'economics',
                'title' => 'Supply and Demand: The Core of All Economics',
                'excerpt' => 'Why do prices go up when something is scarce? Why do wages fall in oversaturated job markets? It all comes back to the law of supply and demand.',
                'body' => '<h2>What is Demand?</h2><p>Demand is the quantity of a product or service that consumers are willing and able to buy at different prices. The law of demand states that, all else being equal, as price increases, quantity demanded decreases. This creates the downward-sloping demand curve.</p><h2>What is Supply?</h2><p>Supply is the quantity of a product that producers are willing and able to offer for sale at different prices. The law of supply states that as price increases, producers are willing to supply more. This creates an upward-sloping supply curve.</p><h2>Equilibrium: Where They Meet</h2><p>The market equilibrium is the price at which the quantity demanded equals the quantity supplied. At this price, there is no surplus (too much supply) or shortage (too much demand). Markets naturally tend toward equilibrium.</p><h2>Shifts in Supply and Demand</h2><p>When something changes in the market — a new technology, a change in consumer tastes, a natural disaster — the supply or demand curves shift, creating a new equilibrium at a different price. This is how prices adjust dynamically in a free market economy.</p>',
            ],
            [
                'topic' => 'health',
                'title' => 'How Your Immune System Protects You From Disease',
                'excerpt' => 'Your immune system is an incredibly complex network of cells, tissues, and organs working 24/7 to keep you healthy. Here is how it actually works.',
                'body' => '<h2>The Two Lines of Defence</h2><p>Your immune system has two main components: the innate immune system (your first, fast-response line of defence) and the adaptive immune system (a slower but more targeted response).</p><h2>The Innate Immune System</h2><p>When a pathogen enters your body, the innate immune system responds immediately. Physical barriers like skin and mucus membranes prevent most pathogens from entering. If they do get in, white blood cells called neutrophils and macrophages engulf and destroy them in a process called phagocytosis. Inflammation — redness, swelling, heat — is a sign this battle is happening.</p><h2>The Adaptive Immune System</h2><p>If the innate response is not enough, the adaptive immune system kicks in. B cells produce antibodies — proteins that specifically target the invading pathogen. T cells coordinate the response and directly kill infected cells. Critically, the adaptive immune system has memory: once it has fought a pathogen, it remembers it, allowing a much faster response if the same pathogen attacks again. This is the principle behind vaccines.</p>',
            ],
            [
                'topic' => 'geography',
                'title' => 'Why Do Seasons Change? The Science Behind the Seasons',
                'excerpt' => 'Many people think seasons are caused by Earth\'s distance from the Sun. They are wrong! Discover the real reason we have summer and winter.',
                'body' => '<h2>The Common Misconception</h2><p>It might seem logical that summer is caused by Earth being closer to the Sun. But this is incorrect. In fact, Earth is actually slightly closer to the Sun during the Northern Hemisphere\'s winter. Distance from the Sun does not cause seasons.</p><h2>The Real Reason: Earth\'s Tilt</h2><p>Seasons are caused by the 23.5-degree tilt of Earth\'s axis relative to its orbit around the Sun. This tilt means that different parts of the Earth receive more direct sunlight at different times of the year.</p><h2>Summer and Winter Explained</h2><p>When the Northern Hemisphere is tilted toward the Sun (around June), it receives more direct sunlight over more hours of the day — this is summer. At the same time, the Southern Hemisphere is tilted away from the Sun and experiences winter. Six months later, the situation reverses.</p><h2>The Equinoxes and Solstices</h2><p>The solstices (around June 21 and December 21) are the longest and shortest days of the year. The equinoxes (around March 21 and September 21) are the two days when day and night are equal in length across the entire globe.</p>',
            ],
            [
                'topic' => 'computer-science',
                'title' => '10 Python Tips Every Beginner Should Know',
                'excerpt' => 'Python is one of the most beginner-friendly languages in the world, but these ten tips will help you write cleaner, more efficient, and more Pythonic code from day one.',
                'body' => '<h2>1. Use f-strings for Formatting</h2><p>Instead of "Hello, " + name + "!", use f"Hello, {name}!" — it is cleaner and faster.</p><h2>2. List Comprehensions</h2><p>Instead of a for loop to build a list, try: squares = [x**2 for x in range(10)]. One line is more Pythonic.</p><h2>3. The enumerate() Function</h2><p>When you need both the index and value in a loop, use enumerate(my_list) instead of manually tracking a counter variable.</p><h2>4. Use zip() to Pair Lists</h2><p>To iterate over two lists simultaneously, zip(list1, list2) gives you pairs without needing index access.</p><h2>5. Swap Variables Elegantly</h2><p>In Python you can swap two variables in one line: a, b = b, a. No temporary variable needed.</p><h2>6. The get() Method for Dictionaries</h2><p>Use my_dict.get("key", default_value) instead of checking if a key exists first — it returns the default if the key is missing.</p><h2>7. Use _ for Throwaway Variables</h2><p>When you do not need a variable in a loop, use _ as the name: for _ in range(10).</p><h2>8. Use any() and all()</h2><p>These built-in functions check if any or all items in an iterable are truthy — much cleaner than manual for loops with flags.</p><h2>9. Virtual Environments</h2><p>Always use python -m venv venv to create a virtual environment for each project, keeping your dependencies isolated.</p><h2>10. Read the Error Messages</h2><p>Python error messages are among the clearest in any language. Read the full traceback — the last line almost always tells you exactly what went wrong.</p>',
            ],
        ];

        foreach ($articlesData as $data) {
            $topic = $topics->get($data['topic']) ?? $topics->first();
            $wordCount = str_word_count(strip_tags($data['body']));

            $article = Article::create([
                'user_id'          => $users->random()->id,
                'topic_id'         => $topic?->id ?? $topics->first()->id,
                'title'            => $data['title'],
                'excerpt'          => $data['excerpt'],
                'body'             => $data['body'],
                'status'           => 'published',
                'reading_time'     => max(1, (int) ceil($wordCount / 200)),
                'likes_count'      => rand(5, 80),
                'views_count'      => rand(100, 2000),
                'bookmarks_count'  => rand(2, 40),
                'published_at'     => now()->subDays(rand(1, 60)),
            ]);

            if ($tags->count()) {
                $article->tags()->attach($tags->random(rand(2, 4))->pluck('id')->toArray());
            }
        }
    }
}