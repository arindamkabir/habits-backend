<?php

namespace App\Services\Health;

use App\Models\CigaretteEntry;
use Illuminate\Support\Facades\Auth;

class CigaretteService
{
    public function save(array $attributes): CigaretteEntry
    {
        $entry = CigaretteEntry::query()
            ->where('date', $attributes['date'])
            ->where('user_id', Auth::id())
            ->first();

        return isset($entry)
            ?  $this->update($attributes, $entry)
            : $this->store($attributes);
    }

    private function store(array $attributes): CigaretteEntry
    {
        $cigaretteEntry = new CigaretteEntry;
        $cigaretteEntry->entry = $attributes['entry'];
        $cigaretteEntry->date = $attributes['date'];
        $cigaretteEntry->user_id = auth()->id();
        $cigaretteEntry->save();

        return $cigaretteEntry;
    }

    private function update(array $attributes, CigaretteEntry $cigaretteEntry): CigaretteEntry
    {
        $cigaretteEntry->entry = $attributes['entry'];
        $cigaretteEntry->date = $attributes['date'];
        $cigaretteEntry->save();

        return $cigaretteEntry;
    }

    public function delete(string $id): bool
    {
        $cigaretteEntry = CigaretteEntry::query()->findOrFail($id);

        return $cigaretteEntry->delete();
    }
}
