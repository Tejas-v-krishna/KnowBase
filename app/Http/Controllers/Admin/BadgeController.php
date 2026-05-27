<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::orderBy('name')->paginate(15);
        return view('admin.badges.index', compact('badges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:badges,name',
            'description' => 'required|string',
            'icon' => 'required|string',
            'criteria_type' => 'required|string',
            'criteria_value' => 'required|integer',
        ]);

        Badge::create($request->only('name', 'description', 'icon', 'criteria_type', 'criteria_value'));

        return back()->with('success', 'Badge created successfully.');
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();
        return back()->with('success', 'Badge deleted.');
    }
}
