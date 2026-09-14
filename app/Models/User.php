<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'reputation', 'is_expert',
        'role',
        'streak',
        'last_active_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
        'reputation' => 'integer',
        'streak' => 'integer',
        'last_active_at' => 'datetime',
    ];

    public function articles() {
        return $this->hasMany(Article::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function threads() {
        return $this->hasMany(Thread::class);
    }

    public function replies() {
        return $this->hasMany(Reply::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function collections() {
        return $this->hasMany(Collection::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }

    public function bookmarks() {
        return $this->hasMany(Bookmark::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function topics() {
        return $this->belongsToMany(Topic::class, 'topic_user');
    }

    public function badges() {
        return $this->belongsToMany(Badge::class, 'badge_user')->withPivot('awarded_at');
    }

    public function challenges() {
        return $this->belongsToMany(Challenge::class, 'challenge_user')->withPivot('progress', 'completed_at')->withTimestamps();
    }

    public function thanks() {
        return $this->hasMany(\App\Models\Thank::class);
    }

    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isModerator() {
        return $this->role === 'moderator' || $this->role === 'admin';
    }

    public function following() {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'following_id')->withTimestamps();
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'user_followers', 'following_id', 'follower_id')->withTimestamps();
    }

    public function isFollowing(User $user) {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function getRankAttribute() {
        $rep = $this->reputation;
        if ($rep >= 2000) return 'Brainly Master';
        if ($rep >= 1000) return 'Genius';
        if ($rep >= 500)  return 'Ambitious';
        if ($rep >= 100)  return 'Helping Hand';
        return 'Beginner';
    }
}

