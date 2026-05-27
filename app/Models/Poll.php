<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    protected $fillable = ['question', 'options'];

    protected $casts = [
        'options' => 'array',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }
}
