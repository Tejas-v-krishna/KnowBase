<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use App\Models\Question;
use App\Models\Thread;

class DashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $articlesCount = Article::count();
        $questionsCount = Question::count();
        $threadsCount = Thread::count();
        
        // Simple mock counts since we don't have reports table
        $reportsCount = 0;

        return view('admin.dashboard', compact('usersCount', 'articlesCount', 'questionsCount', 'threadsCount', 'reportsCount'));
    }
}
