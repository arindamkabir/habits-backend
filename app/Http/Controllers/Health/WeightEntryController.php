<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Http\Requests\Health\StoreWeightEntryRequest;
use App\Http\Requests\Health\UpdateWeightEntryRequest;
use App\Http\Resources\Health\WeightEntryResource;
use App\Models\WeightEntry;
use App\Services\Health\WeightChartService;
use App\Services\Health\WeightService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class WeightEntryController extends Controller
{
    use ApiResponse;

    private WeightService $weightService;
    private WeightChartService $weightChartService;

    public function __construct(WeightService $weightService, WeightChartService $weightChartService)
    {
        $this->weightService = $weightService;
        $this->weightChartService = $weightChartService;
    }

    public function index(Request $request): ResourceCollection
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (!$start_date || !$end_date) {
            return $this->error('No start date and/or end date found!.', 403);
        }

        $entries = WeightEntry::query()
            ->whereBetween('date', [$start_date, $end_date])
            ->where('user_id', Auth::id())
            ->get();

        return WeightEntryResource::collection($entries);
    }

    public function details(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if ($start_date && $end_date) {
            $data = $this->weightChartService->getChartData($start_date, $end_date);
        } elseif ($year) {
            $data = $this->weightChartService->getYearlyChartData($year);
        } else {
            $data = $this->weightChartService->getYearlyChartData(date('Y'));
        }

        return response()->json(["data" => $data], 200);
    }

    public function store(StoreWeightEntryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $entry = $this->weightService->storeEntry($validated);

        return $this->success('Weight entry added successfully.', $entry);
    }

    public function update(UpdateWeightEntryRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $entry = $this->weightService->updateEntry($validated, $id);

        return $this->success('Weight entry updated successfully.', $entry);
    }

    public function delete(string $id): JsonResponse
    {
        $deleted = $this->weightService->deleteEntry($id);

        return $this->success('Weight entry deleted successfully.');
    }
}
