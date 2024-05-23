<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitCategoryRequest;
use App\Http\Resources\HabitCategoryResource;
use App\Models\HabitCategory;
use App\Services\Habits\HabitCategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitCategoryController extends Controller
{
    use ApiResponse;

    private HabitCategoryService $categoryService;

    public function __construct(HabitCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = HabitCategory::query()
            ->where('user_id', Auth::id())
            ->get();

        return HabitCategoryResource::collection($categories);
    }

    public function store(StoreHabitCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = $this->categoryService->store($validated);

        $this->success("Category stored successfully.", $category);
    }
}
