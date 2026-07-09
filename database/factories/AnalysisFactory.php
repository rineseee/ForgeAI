<?php

namespace Database\Factories;

use App\Models\Analysis;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Analysis>
 */
class AnalysisFactory extends Factory
{
    public function definition(): array
    {
        $started = fake()->dateTimeBetween('-1 month');

        return [
            'repository_id' => Repository::factory(),
            'pull_request_id' => null,
            'type' => fake()->randomElement(['code_review', 'security', 'quality', 'tech_debt', 'documentation']),
            'status' => 'completed',
            'model_used' => 'gpt-4.1',
            'started_at' => $started,
            'completed_at' => fake()->dateTimeBetween($started),
        ];
    }
}
