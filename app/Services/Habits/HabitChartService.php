<?php

namespace App\Services\Habits;

use App\Models\Habit;
use App\Models\HabitEntry;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HabitChartService
{
    public function daily($slug, $start, $end)
    {
        $habit = Habit::query()
            ->where('slug', $slug)
            ->currentUser()
            ->firstOrFail();

        $entries = $habit->entries()
            ->whereBetween(
                'date',
                [$start, $end]
            )
            ->get();

        $chart = [];

        $period = CarbonPeriod::create($start, '1 day', $end);

        foreach ($period as $date) {
            $formattedDate =  $date->format('m-d');

            $chart[$formattedDate] = [
                'label' => $formattedDate,
                'value' => 0,
            ];
        }

        foreach ($entries as $entry) {
            $formattedDate = Carbon::parse($entry->date)->format('m-d');
            $chart[$formattedDate] = [
                'label' => $formattedDate,
                'value' => $entry->entry,
            ];
        }

        return array_values($chart);
    }

    public function monthly($slug, $start, $end)
    {
        $habit = Habit::query()
            ->where('slug', $slug)
            ->currentUser()
            ->firstOrFail();

        $entries = $habit->entries()
            ->whereBetween(
                'date',
                [$start, $end]
            )
            ->get();

        $chart = [];

        $period = CarbonPeriod::create($start, '1 month', $end);

        foreach ($period as $date) {
            $formattedDate = $date->format('M');

            $chart[$formattedDate] = [
                'label' => $formattedDate,
                'value' => 0,
            ];
        }

        $groupedMonthEntries = $entries->where('date', '>=', $start)
            ->groupBy(function ($entry) {
                return Carbon::parse($entry->date)->format('M');
            })
            ->map(function ($entry) {
                return [
                    'label' => Carbon::parse($entry[0]->date)->format('M'),
                    'value' => number_format((float)$entry->avg('entry'), 1, '.', ''),
                ];
            })
            ->values()
            ->all();

        foreach ($chart as $key => $value) {
            foreach ($groupedMonthEntries as $entry) {
                if ($entry['label'] === $value['label']) {
                    $chart[$key] = $entry;
                }
            }
        }

        return array_values($chart);
    }

    public function pie(string $slug, string $timePeriod)
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
}
