<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitEntryRequest;
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

    public function save(StoreHabitEntryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $entry = HabitEntry::query()
            ->where('habit_id', $validated['habit_id'])
            ->where('date', $validated['date'])
            ->first();

        if ($entry) {
            $responseEntry = $this->entryService->update($validated, $entry);
        } else {
            $responseEntry = $this->entryService->store($validated);
        }

        return $this->success('Entry added successfully', $responseEntry);
    }
}
