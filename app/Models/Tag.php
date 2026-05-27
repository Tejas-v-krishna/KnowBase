<?php
namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['name', 'slug', 'description'];

    public function articles() {
        return $this->morphedByMany(Article::class, 'taggable');
    }

    public function questions() {
        return $this->morphedByMany(Question::class, 'taggable');
    }

    public function threads() {
        return $this->morphedByMany(Thread::class, 'taggable');
    }
}
