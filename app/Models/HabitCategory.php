<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HabitCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected function isDefault(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['user_id'] === null,
        );
    }
}
