<?php

namespace App\Services\Health;

use App\Models\WeightEntry;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class WeightChartService
{
    public function daily($start, $end)
    {
        $entries = WeightEntry::query()
            ->currentUser()
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
                'value' => null,
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

    public function monthly($start, $end)
    {
        $entries = WeightEntry::query()
            ->currentUser()
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
                'value' => null,
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
}
