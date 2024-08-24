<?php

namespace App\Services\Health\Water;

use App\Models\WaterEntry;
use Illuminate\Support\Facades\Auth;

class WaterService
{
    private WaterChartService $waterChartService;

    public function __construct(WaterChartService $waterChartService)
    {
        $this->waterChartService = $waterChartService;
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
            ? $this->waterChartService->monthly($start, $end)
            : $this->waterChartService->daily($start, $end);
    }


    public function entry(string $date)
    {
        $waterEntry = WaterEntry::query()
            ->where('date', $date)
            ->where('user_id', Auth::id())
            ->first();

        return (isset($waterEntry))
            ? $waterEntry->entry * 1000
            : 0;
    }

    public function save(array $attributes): WaterEntry
    {
        $entry = WaterEntry::query()
            ->where('date', $attributes['date'])
            ->where('user_id', Auth::id())
            ->first();

        return isset($entry)
            ?  $this->update($attributes, $entry)
            : $this->store($attributes);
    }

    private function store(array $attributes): WaterEntry
    {
        $waterEntry = new WaterEntry;
        $waterEntry->entry = $attributes['entry'] / 1000;
        $waterEntry->date = $attributes['date'];
        $waterEntry->user_id = auth()->id();
        $waterEntry->save();

        return $waterEntry;
    }

    private function update(array $attributes, WaterEntry $waterEntry): WaterEntry
    {
        $waterEntry->entry = $attributes['entry'] / 1000;
        $waterEntry->date = $attributes['date'];
        $waterEntry->save();

        return $waterEntry;
    }

    public function delete(string $id): bool
    {
        $waterEntry = WaterEntry::query()->findOrFail($id);

        return $waterEntry->delete();
    }
}
