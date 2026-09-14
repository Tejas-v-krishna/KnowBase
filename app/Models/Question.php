<?php
namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Question extends Model
{
    use HasFactory, HasSlug, Searchable;

    protected $fillable = [
        'user_id',
        'topic_id', 'school_level',
        'title',
        'slug',
        'body',
        'status',
        'accepted_answer_id',
        'views_count',
        'answers_count',
        'votes_count',
        'bounty_amount',
    ];

    protected $casts = [
        'views_count' => 'integer',
        'answers_count' => 'integer',
        'votes_count' => 'integer',
        'bounty_amount' => 'integer',
    ];

    public function scopeWithBounty($query)
    {
        return $query->where('bounty_amount', '>', 0)->where('status', 'open');
    }

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

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function acceptedAnswer() {
        return $this->belongsTo(Answer::class, 'accepted_answer_id');
    }

    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function votes() {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function bookmarks() {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }
}

