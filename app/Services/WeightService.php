<?php

namespace App\Services;

use App\Models\WeightEntry;

class WeightService
{
    public function storeEntry(array $attributes)
    {
        $weightEntry = new WeightEntry;
        $weightEntry->entry = $attributes['entry'];
        $weightEntry->note = isset($attributes['note']) ? $attributes['note'] : null;
        $weightEntry->date = $attributes['date'];
        $weightEntry->user_id = auth()->id();
        $weightEntry->save();

        return $weightEntry;
    }
}
