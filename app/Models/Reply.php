<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['thread_id', 'user_id', 'parent_id', 'body'];

    public function thread() {
        return $this->belongsTo(Thread::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function parent() {
        return $this->belongsTo(Reply::class, 'parent_id');
    }

    public function replies() {
        return $this->hasMany(Reply::class, 'parent_id');
    }
}
