<?php

declare(strict_types=1);

namespace App\Traits;

trait GenerateUniqueSlugTrait
{
    public static function bootGenerateUniqueSlugTrait(): void
    {
        static::saving(function ($model) {
            if ($model->isDirty('name')) {
                $slug = str()->slug($model->name);
                $count = $model->where('id', '!=', $model->id)
                    ->whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")
                    ->count();
                $model->slug = $count ? "{$slug}-{$count}" : $slug;
            }
        });
    }
}
