<?php
namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use HasFactory, HasSlug, Searchable;

    protected $fillable = [
        'user_id',
        'topic_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_image',
        'thumbnail_url',
        'status',
        'reading_time',
        'views_count',
        'likes_count',
        'bookmarks_count',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'reading_time' => 'integer',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'bookmarks_count' => 'integer',
    ];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => strip_tags($this->body),
            'topic_name' => $this->topic ? $this->topic->name : '',
            'tags' => $this->tags->pluck('name')->implode(', '),
        ];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function topic() {
        return $this->belongsTo(Topic::class);
    }

    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes() {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function bookmarks() {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->attributes['thumbnail_url'] ?? null) {
            return $this->attributes['thumbnail_url'];
        }
        
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        $slug = $this->topic ? $this->topic->slug : 'general';
        
        $stockImages = [
            'mathematics' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?auto=format&fit=crop&w=800&q=80',
            'science' => 'https://images.unsplash.com/photo-1471086569966-db3eebc25a59?auto=format&fit=crop&w=800&q=80',
            'computer-science' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=800&q=80',
            'english' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=800&q=80',
            'history' => 'https://images.unsplash.com/photo-1447069387593-a5de0862481e?auto=format&fit=crop&w=800&q=80',
            'chemistry' => 'https://images.unsplash.com/photo-1532187643603-ba119ca4109e?auto=format&fit=crop&w=800&q=80',
            'economics' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?auto=format&fit=crop&w=800&q=80',
            'health' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=800&q=80',
            'geography' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
            'general' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=800&q=80',
        ];

        return $stockImages[$slug] ?? $stockImages['general'];
    }
}
