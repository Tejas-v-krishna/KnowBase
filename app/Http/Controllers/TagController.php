<?php
namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $articles = $tag->articles()->where('status', 'published')->with('user')->latest()->take(10)->get();
        $questions = $tag->questions()->with('user')->latest()->take(10)->get();
        $threads = $tag->threads()->with('user')->latest()->take(10)->get();

        return view('tags.show', compact('tag', 'articles', 'questions', 'threads'));
    }
}
