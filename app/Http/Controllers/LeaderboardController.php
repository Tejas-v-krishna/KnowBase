<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        $topUsers = User::orderBy('reputation', 'desc')
            ->where('reputation', '>', 0)
            ->take(50)
            ->get();

        return view('leaderboard.index', compact('topUsers'));
    }
}
