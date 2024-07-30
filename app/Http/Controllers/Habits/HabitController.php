<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitRequest;
use App\Http\Requests\Habits\UpdateHabitRequest;
use App\Http\Resources\HabitResource;
use App\Models\Habit;
use App\Services\Habits\HabitService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    use ApiResponse;

    private HabitService $habitService;

    public function __construct(HabitService $habitService)
    {
        $this->habitService = $habitService;
    }

    public function index(Request $request): JsonResponse
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (! $start_date || ! $end_date) {
            return $this->error('No start date and/or end date found!.', 422);
        }

        $habits = Habit::query()
            ->with(['category', 'entries' => function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            }])
            ->where('user_id', Auth::id())
            ->get();

        return HabitResource::collection($habits);
    }

    public function show(string $slug)
    {
        $habit = Habit::query()
            ->with(['category', 'entries'])
            ->where('slug', $slug)
            ->currentUser()
            ->firstOrFail();

        return new HabitResource($habit);
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
