<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\HabitCategory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;

class HabitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed habit categories
        $category = new HabitCategory();
        $category->name = 'Health';
        $category->color = 'rose';
        $category->user_id = 1;
        $category->save();

        $category = new HabitCategory();
        $category->name = 'Education';
        $category->color = 'amber'; // Purple color in hex
        $category->user_id = 1;
        $category->save();

        $category = new HabitCategory();
        $category->name = 'Fitness';
        $category->color = 'emerald'; // Orange color in hex
        $category->user_id = 1;
        $category->save();

        // Seed habits
        $habit = new \App\Models\Habit();
        $habit->name = 'Drink water 3 bottles of water';
        $habit->category_id = 1; // Health category
        $habit->entry_type = 'number';
        $habit->user_id = 1;
        $habit->save();

        $habit = new \App\Models\Habit();
        $habit->name = 'Read a book';
        $habit->category_id = 2; // Education category
        $habit->entry_type = 'boolean';
        $habit->user_id = 1;
        $habit->save();

        $habit = new \App\Models\Habit();
        $habit->name = 'Exercise';
        $habit->category_id = 3; // Fitness category
        $habit->entry_type = 'boolean';
        $habit->user_id = 1;
        $habit->save();

        $habit = new \App\Models\Habit();
        $habit->name = 'Meditate for 10 minutes';
        $habit->category_id = 1; // Health category
        $habit->entry_type = 'boolean';
        $habit->user_id = 1;
        $habit->save();

        $habit = new \App\Models\Habit();
        $habit->name = 'Learn a new language';
        $habit->category_id = 2; // Education category
        $habit->entry_type = 'boolean';
        $habit->user_id = 1;
        $habit->save();

        $habit = new \App\Models\Habit();
        $habit->name = 'Run 5 kilometers';
        $habit->category_id = 3; // Fitness category
        $habit->entry_type = 'number';
        $habit->user_id = 1;
        $habit->save();


        $habits = \App\Models\Habit::all();
        Log::info('Habit count: ' . $habit->count());

        $period = CarbonPeriod::create(Carbon::now()->subMonths(3), '1 day', Carbon::now());
        $days = [];

        foreach ($period as $date) {
            if ($date->day % 2 === 0) {
                $days[] = $date;
            }
        }

        Log::info($days);

        foreach ($habits as $habit) {
            foreach ($days as $day) {
                $entry = new \App\Models\HabitEntry();
                $entry->habit_id = $habit->id;
                $entry->date = $day;

                if ($habit->entry_type === 'number') {
                    $entry->entry = rand(1, 10); // Generate a random number between 1 and 10
                } elseif ($habit->entry_type === 'boolean') {
                    $entry->entry = rand(0, 1); // Generate a random boolean value (0 or 1)
                }

                $saved = $entry->save();

                if (!$saved) {
                    Log::error('Failed to save entry for habit: ' . $habit->id);
                }
            }
        }
    }
}
