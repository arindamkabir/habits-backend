<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitRequest;
use App\Http\Requests\Habits\UpdateHabitRequest;
use App\Http\Resources\HabitResource;
use App\Services\Habits\HabitService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HabitController extends Controller
{
    use ApiResponse;

    private HabitService $habitService;

    public function __construct(HabitService $habitService)
    {
        $this->habitService = $habitService;
    }

    public function index(Request $request): ResourceCollection
    {
        if (!$request->input('start_date') || !$request->input('end_date')) {
            return $this->error('No start date and/or end date found!.', 422);
        };

        return HabitResource::collection(
            $this->habitService->list($request->all())
        );
    }

    public function show(string $slug): JsonResource
    {
        return HabitResource::make(
            $this->habitService->details($slug)
        );
    }

    public function chart(string $slug): JsonResponse
    {
        return $this->success(
            'Habit chart data retrieved successfully.',
            $this->habitService->monthlyChart($slug)
        );
    }

    public function pieChart(Request $request, string $slug): JsonResponse
    {
        if (!$request->input('time_period')) {
            return $this->error('No time period found!.', 422);
        };

        return $this->success(
            'Habit pie chart data retrieved successfully.',
            $this->habitService->pieChart($slug, $request->time_period)
        );
    }

    public function store(StoreHabitRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $habit = $this->habitService->store($validated);

        return $this->success('Habit stored successfully.', $habit);
    }

    public function update(UpdateHabitRequest $request, string $slug): JsonResponse
    {
        $validated = $request->validated();

        $this->habitService->update($validated, $slug);

        return $this->success('Habit updated successfully.');
    }

    public function destroy(string $id): JsonResponse
    {
        $this->habitService->destroy($id);

        return $this->success('Habit deleted successfully.');
    }
}
