<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Question;
use App\Models\Thread;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function toggle($type, $id)
    {
        $bookmarkableClass = null;
        if ($type === 'article') {
            $bookmarkableClass = Article::class;
        } elseif ($type === 'question') {
            $bookmarkableClass = Question::class;
        } elseif ($type === 'thread') {
            $bookmarkableClass = Thread::class;
        }

        if (!$bookmarkableClass) {
            abort(404);
        }

        $parent = $bookmarkableClass::findOrFail($id);
        $userId = auth()->id();

        $existingBookmark = Bookmark::where('user_id', $userId)
            ->where('bookmarkable_type', $bookmarkableClass)
            ->where('bookmarkable_id', $id)
            ->first();

        if ($existingBookmark) {
            $existingBookmark->delete();
            if (schema_has_column($parent->getTable(), 'bookmarks_count')) {
                $parent->decrement('bookmarks_count');
            }
            $message = 'Removed bookmark.';
        } else {
            Bookmark::create([
                'user_id' => $userId,
                'bookmarkable_type' => $bookmarkableClass,
                'bookmarkable_id' => $id,
            ]);
            if (schema_has_column($parent->getTable(), 'bookmarks_count')) {
                $parent->increment('bookmarks_count');
            }
            $message = 'Bookmarked!';
        }

        return back()->with('success', $message);
    }
}

// Helper to avoid column checking errors
function schema_has_column($table, $column) {
    return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
}
