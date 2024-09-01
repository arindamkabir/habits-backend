<?php

namespace App\Services\Habits;

use App\Models\Habit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HabitService
{
    private HabitChartService $habitChartService;

    public function __construct(HabitChartService $habitChartService)
    {
        $this->habitChartService = $habitChartService;
    }

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

    public function chart(string $slug, string $timePeriod): array
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
            ? $this->habitChartService->monthly($slug, $start, $end)
            : $this->habitChartService->daily($slug, $start, $end);
    }

    public function pieChart(string $slug, string $timePeriod): array
    {
        return $this->habitChartService->pie($slug, $timePeriod);
    }

    public function store(array $validated): Habit
    {
        return DB::transaction(function () use ($validated) {
            $habit = new Habit;
            $habit->name = $validated['name'];
            $habit->category_id = $validated['category_id'];
            $habit->description = $validated['description'];
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
            $habit->description = isset($validated['description']) ? $validated['description'] : $habit->description;
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
