<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::orderBy('name')->paginate(15);
        return view('admin.topics.index', compact('topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:topics,name',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only('name', 'description');
        
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('uploads/topics', 'public');
            $data['cover_image'] = $path;
        }

        Topic::create($data);

        return back()->with('success', 'Topic created successfully.');
    }

    public function update(Request $request, $id)
    {
        $topic = Topic::findOrFail($id);
        $request->validate([
            'name' => "required|string|max:100|unique:topics,name,{$id}",
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only('name', 'description');

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('uploads/topics', 'public');
            $data['cover_image'] = $path;
        }

        $topic->update($data);

        return back()->with('success', 'Topic updated.');
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete();
        return back()->with('success', 'Topic deleted.');
    }
}
