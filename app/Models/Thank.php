<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thank extends Model
{
    use HasFactory;

    /**
     * Disable the updated_at timestamp; only created_at is used.
     */
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'answer_id',
        'message',
        'xp_amount',
    ];

    /**
     * The user who sent the thank.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The answer being thanked.
     */
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
