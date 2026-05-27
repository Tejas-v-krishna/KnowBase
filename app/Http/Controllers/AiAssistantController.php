<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Article;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiAssistantController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:1000',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $query = $request->input('q');
        $file = $request->file('file');

        if (empty($query) && !$file) {
            return response()->json(['error' => 'Please provide a prompt or a file.'], 400);
        }

        // 1. Search existing KnowBase content
        $relatedQuestions = collect();
        $relatedArticles = collect();
        $relatedThreads = collect();

        if (!empty($query)) {
            $relatedQuestions = Question::where('title', 'like', "%{$query}%")
                ->orWhere('body', 'like', "%{$query}%")
                ->select('id', 'title', 'slug')
                ->take(3)
                ->get();

            $relatedArticles = Article::where('status', 'published')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('body', 'like', "%{$query}%");
                })
                ->select('id', 'title', 'slug')
                ->take(3)
                ->get();

            $relatedThreads = Thread::where('title', 'like', "%{$query}%")
                ->orWhere('body', 'like', "%{$query}%")
                ->select('id', 'title', 'slug')
                ->take(3)
                ->get();
        }

        // 2. Call Gemini API
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return response()->json([
                'ai_response' => 'API Key is missing. Please add GEMINI_API_KEY to your .env file.',
                'related' => [
                    'questions' => $relatedQuestions,
                    'articles' => $relatedArticles,
                    'threads' => $relatedThreads,
                ]
            ]);
        }

        $parts = [];

        // Build the system prompt
        $prompt = "You are a helpful learning assistant for KnowBase, a student knowledge platform. ";
        $prompt .= "Answer the user's question directly, clearly, and concisely. Use Markdown for formatting. ";
        $prompt .= "At the very end of your response, always provide exactly 3 suggested follow-up questions the user could ask next, formatted as a bulleted list under the heading '### Suggested Follow-ups'. ";

        if ($relatedQuestions->isNotEmpty() || $relatedArticles->isNotEmpty() || $relatedThreads->isNotEmpty()) {
            $prompt .= "\n\nFor context, here are some existing discussions on our platform that might be related:\n";
            foreach ($relatedQuestions as $q) {
                $prompt .= "- Question: {$q->title}\n";
            }
            foreach ($relatedArticles as $a) {
                $prompt .= "- Article: {$a->title}\n";
            }
        }

        if (!empty($query)) {
            $prompt .= "\n\nUser Question: " . $query;
        }

        $parts[] = ['text' => $prompt];

        // Handle file upload
        if ($file) {
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $parts[] = [
                'inlineData' => [
                    'mimeType' => $mimeType,
                    'data' => $base64
                ]
            ];
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => $parts
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? "I couldn't generate an answer.";
                
                // Extract follow-ups
                $suggestions = [];
                if (preg_match('/### Suggested Follow-ups\s*(.*)/is', $aiText, $matches)) {
                    $aiText = str_replace($matches[0], '', $aiText); // Remove from main text
                    // Extract bullet points
                    preg_match_all('/[-\*]\s*(.*)/', $matches[1], $bulletMatches);
                    if (!empty($bulletMatches[1])) {
                        $suggestions = array_map('trim', $bulletMatches[1]);
                    }
                }

            } else {
                $aiText = "Failed to communicate with AI API: " . $response->body();
            }

        } catch (\Exception $e) {
            $aiText = "An error occurred: " . $e->getMessage();
        }

        return response()->json([
            'ai_response' => trim($aiText),
            'suggestions' => $suggestions ?? [],
            'related' => [
                'questions' => $relatedQuestions,
                'articles' => $relatedArticles,
                'threads' => $relatedThreads,
            ]
        ]);
    }
}
