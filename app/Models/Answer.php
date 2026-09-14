<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'user_id', 'body', 'is_accepted', 'is_brainliest', 'is_verified', 'votes_count'];

    protected $casts = [
        'is_accepted' => 'boolean',
        'is_brainliest' => 'boolean',
        'votes_count' => 'integer',
    ];

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function votes() {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function thanks() {
        return $this->hasMany(\App\Models\Thank::class);
    }
}

