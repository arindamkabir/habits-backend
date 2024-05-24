<?php

namespace App\Services\Health;

use App\Models\WeightEntry;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class WeightChartService
{
    public function getChartData($start_date, $end_date): array
    {
        $entries = WeightEntry::query()
            ->whereDate("date", ">=", $start_date)
            ->whereDate("date", "<=", $end_date)
            ->get();

        $chartData = [];

        $diff = Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date));

        if ($diff > 180) {
            $step = '1 week';
        } elseif ($diff > 30) {
            $step = '2 days';
        } else {
            $step = '1 day';
        }

        $period = CarbonPeriod::create(
            Carbon::parse($start_date),
            $step,
            Carbon::parse($end_date),
        );

        foreach ($period as $date) {
            $currentEntry = $entries
                ->where('date', $date->format('Y-m-d') . " 00:00:00")
                ->first();

            if ($currentEntry)
                $chartData[] = [
                    'label' => $date->format('d M'),
                    'value' => $currentEntry->entry,
                ];
        }

        return $chartData;
    }

    public function getYearlyChartData($year): array
    {
        $entriesYearly = WeightEntry::query()
            ->select(
                DB::raw('avg(entry) as average'),
                DB::raw("DATE_FORMAT(date,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(date,'%m') as monthKey")
            )
            ->whereYear('date', $year)
            ->groupBy('months', 'monthKey')
            ->orderBy('average', 'ASC')
            ->get();

        $chartData = [];

        for ($month = 1; $month <= 12; $month++) {
            $entry = $entriesYearly
                ->where('monthKey', str()
                    ->padLeft($month, 2, '0'))
                ->first();
            $chartData[] = [
                'label' => $entry ? $entry->monthKey : str()->padLeft($month, 2, '0'),
                'value' => $entry ? round($entry->average, 2) : 0,
            ];
        }

        return $chartData;
    }
}
