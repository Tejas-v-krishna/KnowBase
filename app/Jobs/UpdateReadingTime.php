<?php
namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateReadingTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function handle(): void
    {
        $text = strip_tags($this->article->body);
        $wordCount = str_word_count($text);
        
        // Spec: ceil(word_count / 200) minutes
        $readingTime = (int) ceil($wordCount / 200);
        if ($readingTime < 1) {
            $readingTime = 1;
        }

        $this->article->updateQuietly(['reading_time' => $readingTime]);
    }
}
