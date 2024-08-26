<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitCategoryRequest;
use App\Http\Resources\HabitCategoryResource;
use App\Models\HabitCategory;
use App\Services\Habits\HabitCategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class HabitCategoryController extends Controller
{
    use ApiResponse;

    private HabitCategoryService $categoryService;

    public function __construct(HabitCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): ResourceCollection
    {
        return HabitCategoryResource::collection($this->categoryService->list());
    }

    public function store(StoreHabitCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $category = $this->categoryService->store($validated);

        return $this->success('Category stored successfully.', $category);
    }
}
