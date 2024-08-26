<?php

namespace Database\Seeders;

use App\Models\HabitCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitCategorySeeder extends Seeder
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
    }
}
