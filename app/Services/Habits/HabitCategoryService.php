<?php

namespace App\Services\Habits;

use App\Models\HabitCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HabitCategoryService
{
    public function store(array $validated): HabitCategory
    {
        return DB::transaction(function () use ($validated) {
            $category = new HabitCategory;
            $category->name = $validated['name'];
            $category->color = $validated['color'];
            $category->user_id = Auth::id();
            $category->save();

            return $category;
        });
    }
}
