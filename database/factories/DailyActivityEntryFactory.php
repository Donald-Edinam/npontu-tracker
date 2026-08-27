<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\DailyActivityEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyActivityEntry>
 */
class DailyActivityEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'activity_id' => Activity::factory(),
            'date' => today(),
            'status' => 'pending',
            'expected_value' => null,
            'actual_value' => null,
            'variance' => null,
            'assigned_to' => null,
        ];
    }
}
