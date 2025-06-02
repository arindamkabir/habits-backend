<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // add logic if your water, weight, habit or cigarette entries are empty for the day
        // return response()->json([
        //     'message' => 'Welcome to your dashboard!',
        //     'data' => [
        //         'water' => [
        //             'entry' => $this->waterService->entry(date('Y-m-d')),
        //         ],
        //         'weight' => [
        //             'entry' => $this->weightService->entry(date('Y-m-d')),
        //         ],
        //         'habit' => [
        //             'entry' => $this->habitService->entry(date('Y-m-d')),
        //         ],
        //         'cigarette' => [
        //             'entry' => $this->cigaretteService->entry(date('Y-m-d')),
        //         ],
        //     ],

        // ]);

        // Also add donut chart for water, weight and cigarette entries for the day

        // add card for daily habits
    }
}
