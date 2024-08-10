<?php

namespace App\Services\Habits;

use App\Models\HabitEntry;
use Illuminate\Support\Facades\DB;

class HabitEntryService
{
    public function list(string $habitId, array $filters)
    {
        return HabitEntry::query()
            ->where('habit_id', $habitId)
            ->whereBetween('date', [$filters['start_date'], $filters['end_date']])
            ->get();
    }

    public function store(array $validated): HabitEntry
    {
        $existingEntries = HabitEntry::query()
            ->where('habit_id', $validated['habit_id'])
            ->whereDate('date', $validated['date'])
            ->get();

        if (count($existingEntries) > 0) {
            throw new \Exception('Entry already exists'); //? Create exception class
        }

        return DB::transaction(function () use ($validated) {
            $entry = new HabitEntry;
            $entry->entry = $validated['entry'];
            $entry->note = $validated['note'] ?? null;
            $entry->habit_id = $validated['habit_id'];
            $entry->date = $validated['date'];
            $entry->save();

            return $entry;
        });
    }

    public function update(array $validated, HabitEntry $entry): HabitEntry
    {
        return DB::transaction(function () use ($entry, $validated) {
            $entry->entry = $validated['entry'];
            $entry->note = $validated['note'] ?? null;
            $entry->save();

            return $entry;
        });
    }
}
