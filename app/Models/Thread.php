<?php
namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use HasFactory, HasSlug, Searchable;

    protected $fillable = [
        'user_id',
        'topic_id',
        'title',
        'slug',
        'body',
        'is_pinned',
        'is_resolved',
        'replies_count',
        'views_count',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_resolved' => 'boolean',
        'replies_count' => 'integer',
        'views_count' => 'integer',
    ];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
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

    public function replies() {
        return $this->hasMany(Reply::class);
    }

    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
