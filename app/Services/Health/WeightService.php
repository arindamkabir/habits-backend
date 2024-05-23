<?php

namespace App\Services;

use App\Models\WeightEntry;

class WeightService
{
    public function storeEntry(array $attributes): WeightEntry
    {
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
