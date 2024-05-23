<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Http\Resources\HabitResource;
use App\Models\Habit;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (!$start_date || !$end_date) return $this->error("No start date and/or end date found!.", 422);

        $habits = Habit::query()
            ->with(["category", "entries" => function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            }])
            ->where('user_id', Auth::id())
            ->get();

        return HabitResource::collection($habits);
    }
}
