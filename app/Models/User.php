<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships

    /**
     * Get the goals for the user.
     */
    public function goals(): HasOne
    {
        return $this->hasOne(Goal::class);
    }

    /**
     * Get the streaks for the user.
     */
    public function streaks(): HasOne
    {
        return $this->hasOne(Streak::class);
    }

    /**
     * Get the current streaks for the user.
     */
    public function currentWaterStreak(): HasOne
    {
        return $this->hasOne(Streak::class)->ofMany([], function (Builder $query) {
            $query->whereNull('end_date')
                ->where();
        });
    }
}
