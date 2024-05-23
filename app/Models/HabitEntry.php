<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class HabitEntry extends Model
{
    use HasFactory;

    // *** Relationships

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }

    // *** Scopes

    public function scopeCurrentUser(Builder $query): void
    {
        $query->whereHas('habit', function ($query2) {
            $query2->where('user_id', Auth::id());
        });
    }
}
