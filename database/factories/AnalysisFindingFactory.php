<?php

namespace Database\Factories;

use App\Models\Analysis;
use App\Models\AnalysisFinding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnalysisFinding>
 */
class AnalysisFindingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'analysis_id' => Analysis::factory(),
            'file_path' => 'app/'.fake()->word().'/'.fake()->word().'.php',
            'line_start' => fake()->numberBetween(1, 200),
            'line_end' => fake()->numberBetween(200, 220),
            'severity' => fake()->randomElement(['info', 'low', 'medium', 'high', 'critical']),
            'category' => fake()->randomElement(['sql_injection', 'complexity', 'duplication', 'naming', 'n_plus_one']),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'suggestion' => fake()->sentence(10),
            'ai_confidence' => fake()->numberBetween(60, 99),
            'status' => 'open',
        ];
    }
}
