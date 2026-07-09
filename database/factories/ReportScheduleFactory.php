<?php

namespace Database\Factories;

use App\Models\ReportSchedule;
use App\Models\Repository;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReportSchedule>
 */
class ReportScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'repository_id' => Repository::factory(),
            'frequency' => fake()->randomElement(['weekly', 'monthly']),
            'recipients' => [fake()->safeEmail()],
            'is_active' => true,
        ];
    }
}
