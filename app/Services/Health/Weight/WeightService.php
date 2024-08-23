<?php

namespace App\Services\Health\Weight;

use App\Models\WeightEntry;
use Illuminate\Support\Facades\Auth;

class WeightService
{
    private WeightChartService $weightChartService;

    public function __construct(WeightChartService $weightChartService)
    {
        $this->weightChartService = $weightChartService;
    }

    public function chart(string $timePeriod)
    {
        $startEnd = [
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            '2weeks' => [now()->subWeek()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            '3months' => [now()->subMonths(2)->startOfMonth(), now()->endOfMonth()],
            '6months' => [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
        ];

        [$start, $end] = $startEnd[$timePeriod];

        return ($timePeriod === 'year' || $timePeriod === '6months')
            ? $this->weightChartService->monthly($start, $end)
            : $this->weightChartService->daily($start, $end);
    }

    public function entry(string $date)
    {
        $weightEntry = WeightEntry::query()
            ->where('date', $date)
            ->where('user_id', Auth::id())
            ->first();

        return (isset($weightEntry))
            ? $weightEntry->entry
            : 0;
    }

    public function save(array $attributes): WeightEntry
    {
        $entry = WeightEntry::query()
            ->where('date', $attributes['date'])
            ->where('user_id', Auth::id())
            ->first();

        return isset($entry)
            ?  $this->update($attributes, $entry)
            : $this->store($attributes);
    }

    private function store(array $attributes): WeightEntry
    {
        $weightEntry = new WeightEntry;
        $weightEntry->entry = $attributes['entry'];
        $weightEntry->date = $attributes['date'];
        $weightEntry->user_id = auth()->id();
        $weightEntry->save();

        return $weightEntry;
    }

    private function update(array $attributes, WeightEntry $weightEntry): WeightEntry
    {
        $weightEntry->entry = $attributes['entry'];
        $weightEntry->date = $attributes['date'];
        $weightEntry->save();

        return $weightEntry;
    }

    public function delete(string $id): bool
    {
        $weightEntry = WeightEntry::query()->findOrFail($id);

        return $weightEntry->delete();
    }
}
