<?php

namespace App\Services\Goals;

use App\Models\Goal;

class GoalService
{
    public function save($attributes)
    {
        $goal = Goal::where('user_id', auth()->id())->first();

        return $goal
            ? $this->update($attributes, $goal)
            : $this->store($attributes);
    }

    private function store($attributes)
    {
        $goal = new Goal;
        $goal->user_id = auth()->id();

        if ($attributes->water_goal) {
            $goal->water_goal = $attributes->water_goal;
        }
        if ($attributes->cigarette_goal) {
            $goal->cigarette_goal = $attributes->cigarette_goal;
        }
        if ($attributes->weight_goal) {
            $goal->weight_goal = $attributes->weight_goal;
        }

        $goal->save();
    }

    private function update($attributes, $goal)
    {
        if ($attributes->water_goal) {
            $goal->water_goal = $attributes->water_goal;
        }
        if ($attributes->cigarette_goal) {
            $goal->cigarette_goal = $attributes->cigarette_goal;
        }
        if ($attributes->weight_goal) {
            $goal->weight_goal = $attributes->weight_goal;
        }

        $goal->save();
    }
}
