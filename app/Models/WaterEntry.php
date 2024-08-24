<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WaterEntry extends Model
{
    use HasFactory;

    // *** Scopes

    public function scopeCurrentUser(Builder $query): void
    {
        $query->where('user_id', Auth::id());
    }
}
