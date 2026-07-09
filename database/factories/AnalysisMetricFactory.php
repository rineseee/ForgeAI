<?php

namespace Database\Factories;

use App\Models\Analysis;
use App\Models\AnalysisMetric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnalysisMetric>
 */
class AnalysisMetricFactory extends Factory
{
    public function definition(): array
    {
        return [
            'analysis_id' => Analysis::factory(),
            'metric_key' => fake()->randomElement(['complexity_score', 'duplication_percent', 'debt_score']),
            'metric_value' => ['value' => fake()->randomFloat(2, 0, 100)],
        ];
    }
}
