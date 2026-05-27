<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TriviaController extends Controller
{
    public function random()
    {
        try {
            // Fetch one multiple-choice general knowledge/academic trivia question
            $response = Http::withoutVerifying()->get('https://opentdb.com/api.php', [
                'amount' => 1,
                'type' => 'multiple'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['results'][0])) {
                    $questionData = $data['results'][0];
                    
                    $question = html_entity_decode($questionData['question'], ENT_QUOTES, 'UTF-8');
                    $correctAnswer = html_entity_decode($questionData['correct_answer'], ENT_QUOTES, 'UTF-8');
                    $incorrectAnswers = array_map(function($ans) {
                        return html_entity_decode($ans, ENT_QUOTES, 'UTF-8');
                    }, $questionData['incorrect_answers']);

                    // Merge and shuffle answers
                    $answers = array_merge([$correctAnswer], $incorrectAnswers);
                    shuffle($answers);

                    // Store correct answer in user session to prevent cheating
                    session(['trivia_correct_answer' => $correctAnswer]);

                    return response()->json([
                        'question' => $question,
                        'category' => $questionData['category'],
                        'difficulty' => ucfirst($questionData['difficulty']),
                        'answers' => $answers,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Fallback question if API call fails
            $fallback = [
                'question' => 'Which fundamental force keeps the planets in orbit around the Sun?',
                'category' => 'Science: Physics',
                'difficulty' => 'Easy',
                'answers' => ['Gravity', 'Electromagnetism', 'Strong Nuclear Force', 'Weak Nuclear Force'],
                'correct' => 'Gravity'
            ];
            session(['trivia_correct_answer' => $fallback['correct']]);

            return response()->json([
                'question' => $fallback['question'],
                'category' => $fallback['category'],
                'difficulty' => $fallback['difficulty'],
                'answers' => $fallback['answers'],
            ]);
        }

        return response()->json(['error' => 'Could not fetch trivia.'], 500);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        $userAnswer = $request->input('answer');
        $correctAnswer = session('trivia_correct_answer');

        if (!$correctAnswer) {
            return response()->json(['error' => 'No active trivia session.'], 400);
        }

        $isCorrect = (trim(strtolower($userAnswer)) === trim(strtolower($correctAnswer)));
        
        $xpAwarded = 0;
        $message = 'Incorrect answer! Better luck next time.';

        if ($isCorrect) {
            $user = auth()->user();
            
            // Check if user already got XP for today's trivia
            $today = date('Y-m-d');
            $alreadyAnsweredToday = session('trivia_answered_today') === $today;

            if ($user && !$alreadyAnsweredToday) {
                $user->increment('reputation', 5);
                session(['trivia_answered_today' => $today]);
                $xpAwarded = 5;
                $message = 'Correct! +5 XP has been added to your profile.';
            } elseif ($user && $alreadyAnsweredToday) {
                $message = 'Correct! You have already claimed your XP for today.';
            } else {
                $message = 'Correct! Sign in to earn XP next time.';
            }
        }

        return response()->json([
            'correct' => $isCorrect,
            'correct_answer' => $correctAnswer,
            'xp_awarded' => $xpAwarded,
            'message' => $message
        ]);
    }
}
