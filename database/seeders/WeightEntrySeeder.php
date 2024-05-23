<?php

namespace Database\Seeders;

use App\Models\WeightEntry;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class WeightEntrySeeder extends Seeder
{
    public function run(): void
    {
        $period = CarbonPeriod::create(
            Carbon::now()->subDays(60)->format('Y-m-d'),
            '1 days',
            Carbon::now()->format('Y-m-d')
        );

        foreach ($period as $key => $date) {
            $weightEntry = new WeightEntry();
            $weightEntry->entry = fake()->numberBetween(78, 86);
            $weightEntry->note = fake()->text(100);
            $weightEntry->date = $date->format('Y-m-d');
            $weightEntry->user_id = 1;

            $weightEntry->save();
        }
    }
}
