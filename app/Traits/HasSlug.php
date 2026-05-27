<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            $titleSource = $model->title ?? $model->name;
            $slug = Str::slug($titleSource);
            $originalSlug = $slug;
            $count = 1;

            while (static::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-" . Str::lower(Str::random(4));
                if ($count > 5) {
                    $slug = "{$originalSlug}-{$count}";
                }
                $count++;
            }

            $model->slug = $slug;
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
