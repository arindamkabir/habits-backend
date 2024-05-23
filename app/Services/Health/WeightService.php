<?php

namespace App\Services\Health;

use App\Models\WeightEntry;
use Illuminate\Support\Facades\Auth;

class WeightService
{
    public function storeEntry(array $attributes): WeightEntry
    {
        $existingEntries = WeightEntry::query()
            ->where('user_id', Auth::id())
            ->whereDate('date', $attributes['date'])
            ->get();

        if (count($existingEntries) > 0) {
            throw new \Exception('Entry already exists'); //? Create exception class
        }

        $weightEntry = new WeightEntry;
        $weightEntry->entry = $attributes['entry'];
        $weightEntry->note = isset($attributes['note']) ? $attributes['note'] : null;
        $weightEntry->date = $attributes['date'];
        $weightEntry->user_id = auth()->id();
        $weightEntry->save();

        return $weightEntry;
    }

    public function updateEntry(array $attributes, string $id): WeightEntry
    {
        $weightEntry = WeightEntry::query()->findOrFail($id);

        $weightEntry->entry = $attributes['entry'];
        $weightEntry->note = isset($attributes['note']) ? $attributes['note'] : null;
        $weightEntry->date = $attributes['date'];
        $weightEntry->save();

        return $weightEntry;
    }

    public function deleteEntry(string $id): bool
    {
        $weightEntry = WeightEntry::query()->findOrFail($id);

        return $weightEntry->delete();
    }
}
