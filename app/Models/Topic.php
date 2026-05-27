<?php
namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['name', 'slug', 'description', 'cover_image', 'followers_count'];

    public function articles() {
        return $this->hasMany(Article::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function threads() {
        return $this->hasMany(Thread::class);
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'topic_user');
    }
}
