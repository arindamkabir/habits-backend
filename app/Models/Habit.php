<?php

namespace App\Models;

use App\Traits\GenerateUniqueSlugTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Habit extends Model
{
    use GenerateUniqueSlugTrait, HasFactory;

    // *** Relationships

    public function category(): BelongsTo
    {
        return $this->belongsTo(HabitCategory::class, 'category_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(HabitEntry::class, 'habit_id');
    }

    // *** Scopes

    public function scopeCurrentUser(Builder $query): void
    {
        $query->where('user_id', Auth::id());
    }
}
