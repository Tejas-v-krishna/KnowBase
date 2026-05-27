<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:guest,member,moderator,admin']);
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);
        return back()->with('success', "Role for {$user->name} updated to {$request->role}.");
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        // Setting role to guest as simple ban representation
        $user->update(['role' => 'guest']);
        return back()->with('success', "User {$user->name} has been banned (demoted to guest).");
    }
}
