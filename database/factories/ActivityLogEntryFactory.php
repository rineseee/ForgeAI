<?php

namespace Database\Factories;

use App\Models\ActivityLogEntry;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLogEntry>
 */
class ActivityLogEntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'action' => fake()->randomElement(['repository.imported', 'analysis.completed', 'report.generated']),
            'properties' => ['note' => fake()->sentence()],
        ];
    }
}
