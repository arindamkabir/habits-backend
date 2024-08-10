<?php

namespace App\Services\Habits;

use App\Models\Habit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HabitService
{
    public function list(array $filters)
    {
        return Habit::query()
            ->when($filters, function ($query, $filters) {
                $query->with(['category', 'entries' => function ($query) use ($filters) {
                    $query->whereBetween('date', [$filters["start_date"], $filters["end_date"]]);
                }]);
            })
            ->when(!$filters, function ($query) {
                $query->with(['category']);
            })
            ->currentUser()
            ->get();
    }

    public function details(string $slug, $filters = []): Habit
    {
        return Habit::query()
            ->when($filters, function ($query, $filters) {
                $query->with(['category', 'entries' => function ($query) use ($filters) {
                    $query->whereBetween('date', [$filters["start_date"], $filters["end_date"]]);
                }]);
            })
            ->when(!$filters, function ($query) {
                $query->with(['category']);
            })
            ->where('slug', $slug)
            ->currentUser()
            ->firstOrFail();
    }

    public function monthlyChart(string $slug): array
    {
        $habit = Habit::query()
            ->where('slug', $slug)
            ->currentUser()
            ->firstOrFail();

        $entries = $habit->entries()
            ->whereBetween(
                'date',
                [now()->startOfMonth(), now()->endOfMonth()]
            )
            ->get();

        $chart = [];

        $period = CarbonPeriod::create(now()->startOfMonth(), '1 day', now()->endOfMonth());

        foreach ($period as $date) {
            $chart[$date->format('m-d')] = [
                'label' => $date->format('m-d'),
                'value' => 0,
            ];
        }

        foreach ($entries as $entry) {
            $chart[Carbon::parse($entry->date)->format('m-d')] = [
                'label' => Carbon::parse($entry->date)->format('m-d'),
                'value' => $entry->entry,
            ];
        }

        return array_values($chart);
    }

    public function pieChart(string $slug, string $timePeriod): array
    {
        $habit = Habit::query()
            ->where('slug', $slug)
            ->currentUser()
            ->firstOrFail();

        $entryType = $habit->entry_type;

        $entries = $habit->entries()
            ->when($timePeriod === 'week', function ($query) {
                $query->whereBetween(
                    'date',
                    [now()->subWeek(), now()]
                );
            })
            ->when($timePeriod === '2weeks', function ($query) {
                $query->whereBetween(
                    'date',
                    [now()->subWeeks(2), now()]
                );
            })
            ->when($timePeriod === 'month', function ($query) {
                $query->whereBetween(
                    'date',
                    [now()->subMonth(), now()]
                );
            })
            ->when($timePeriod === '3months', function ($query) {
                $query->whereBetween(
                    'date',
                    [now()->subMonths(3), now()]
                );
            })
            ->when($timePeriod === 'year', function ($query) {
                $query->whereBetween(
                    'date',
                    [now()->subYear(), now()]
                );
            })
            ->get();

        $chart = [];

        $chart['yes'] = [
            'label' => 'Yes',
            'value' => $entryType === "boolean"
                ? $entries->where('entry', 1)->count()
                : $entries->where('entry', '>', 0)->count(),
        ];

        $chart['no'] = [
            'label' => 'No',
            'value' => $entries->where('entry', 0)->count(),
        ];

        return array_values($chart);
    }

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

    public function update(array $validated, string $slug): Habit
    {
        return DB::transaction(function () use ($slug, $validated) {
            $habit = Habit::query()
                ->where('slug', $slug)
                ->firstOrFail();

            $habit->name = $validated['name'];
            $habit->category_id = $validated['category_id'];
            $habit->save();

            return $habit;
        });
    }

    public function destroy(string $id): bool
    {
        $habit = Habit::query()
            ->findOrFail($id);

        return $habit->delete();
    }
}
