<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Http\Requests\Health\SaveWaterEntryRequest;
use App\Services\Health\Water\WaterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WaterEntryController extends Controller
{
    private WaterService $waterService;

    public function __construct(WaterService $waterService)
    {
        $this->waterService = $waterService;
    }

    public function chart(Request $request): JsonResponse
    {
        if (!$request->input('time_period')) {
            return $this->error('No time period found!.', 422);
        };

        return $this->success(
            'Water chart data retrieved successfully.',
            $this->waterService->chart($request->input('time_period'))
        );
    }

    public function show(string $date)
    {
        return $this->ok('Water entry retrieved successfully', [
            'entry' => $this->waterService->entry($date)
        ]);
    }

    public function save(SaveWaterEntryRequest $request)
    {
        $validated = $request->validated();

        $entry = $this->waterService->save($validated);

        return $this->success('Water entry added successfully', $entry);
    }

    public function delete(string $id)
    {
        $this->waterService->delete($id);

        return $this->success('Water entry deleted successfully');
    }
}
