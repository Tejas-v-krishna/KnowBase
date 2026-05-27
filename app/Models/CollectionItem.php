<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    protected $fillable = ['collection_id', 'collectable_type', 'collectable_id', 'order'];

    public function collection() {
        return $this->belongsTo(Collection::class);
    }

    public function collectable() {
        return $this->morphTo();
    }
}
