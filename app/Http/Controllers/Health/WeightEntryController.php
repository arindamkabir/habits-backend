<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Http\Requests\Health\SaveWeightEntryRequest;
use App\Services\Health\Weight\WeightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeightEntryController extends Controller
{
    private WeightService $weightService;

    public function __construct(WeightService $weightService)
    {
        $this->weightService = $weightService;
    }

    public function chart(Request $request): JsonResponse
    {
        if (!$request->input('time_period')) {
            return $this->error('No time period found!.', 422);
        };

        return $this->success(
            'Weight chart data retrieved successfully.',
            $this->weightService->chart($request->input('time_period'))
        );
    }

    public function show(string $date)
    {
        return $this->ok('Weight entry retrieved successfully', [
            'entry' => $this->weightService->entry($date)
        ]);
    }

    public function save(SaveWeightEntryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $entry = $this->weightService->save($validated);

        return $this->success('Weight entry saved successfully.', $entry);
    }

    public function delete(string $id): JsonResponse
    {
        $deleted = $this->weightService->delete($id);

        return $this->success('Weight entry deleted successfully.');
    }
}
