<?php

namespace App\Services\Habits;

use App\Models\Habit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HabitService
{
    public function store(array $validated): Habit
    {
        return DB::transaction(function () use ($validated) {
            $habit = new Habit;
            $habit->name = $validated['name'];
            $habit->category_id = $validated['category_id'];
            $habit->entry_type = $validated['entry_type'];
            $habit->user_id = Auth::id();
            $habit->save();

            return $habit;
        });
    }

    public function update(array $validated, Habit $habit): Habit
    {
        return DB::transaction(function () use ($habit, $validated) {
            $habit->name = $validated['name'];
            $habit->category_id = $validated['category_id'];
            $habit->save();

            return $habit;
        });
    }

    public function destroy(string $id): bool
    {
        $habit  = Habit::query()
            ->findOrFail($id);

        return $habit->delete();
    }
}
