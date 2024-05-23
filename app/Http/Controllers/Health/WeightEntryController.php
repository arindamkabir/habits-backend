<?php

namespace App\Http\Controllers;

use App\Http\Requests\Health\StoreWeightEntryRequest;
use App\Http\Requests\Health\UpdateWeightEntryRequest;
use App\Services\WeightService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class WeightEntryController extends Controller
{
    use ApiResponse;

    private WeightService $weightService;

    public function __construct(WeightService $weightService)
    {
        $this->weightService = $weightService;
    }

    public function store(StoreWeightEntryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $entry = $this->weightService->storeEntry($validated);

        return $this->success("Weight entry added successfully.", $entry);
    }

    public function update(UpdateWeightEntryRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $entry = $this->weightService->updateEntry($validated, $id);

        return $this->success("Weight entry updated successfully.", $entry);
    }

    public function delete(string $id): JsonResponse
    {
        $deleted = $this->weightService->deleteEntry($id);

        return $this->success("Weight entry deleted successfully.");
    }
}
