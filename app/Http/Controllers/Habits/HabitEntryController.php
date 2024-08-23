<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitEntryRequest;
use App\Models\Habit;
use App\Models\HabitEntry;
use App\Services\Habits\HabitEntryService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HabitEntryController extends Controller
{
    use ApiResponse;

    private HabitEntryService $entryService;

    public function __construct(HabitEntryService $entryService)
    {
        $this->entryService = $entryService;
    }

    public function list(Request $request, string $slug)
    {
        if (!$request->input('start_date') || !$request->input('end_date')) {
            return $this->error('No start date and/or end date found!.', 422);
        }

        $habit = Habit::query()
            ->where('slug', $slug)
            ->first();

        if (!$habit || $habit->user_id !== auth()->id()) {
            return $this->error('No habit id found!.', 422);
        }

        return $this->ok(
            'Habit entries retrieved successfully',
            $this->entryService->list($habit->id, $request->all())
        );
    }

    public function save(StoreHabitEntryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $entry = HabitEntry::query()
            ->where('habit_id', $validated['habit_id'])
            ->where('date', $validated['date'])
            ->first();

        if (isset($entry)) {
            $responseEntry = $this->entryService->update($validated, $entry);
        } else {
            $responseEntry = $this->entryService->store($validated);
        }

        return $this->success('Entry added successfully', $responseEntry);
    }
}
