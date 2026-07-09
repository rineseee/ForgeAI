<?php

namespace Database\Factories;

use App\Models\Analysis;
use App\Models\AnalysisCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnalysisCategory>
 */
class AnalysisCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'analysis_id' => Analysis::factory(),
            'category' => fake()->randomElement(AnalysisCategory::CATEGORIES),
            'score' => fake()->numberBetween(40, 95),
            'explanation' => fake()->paragraph(),
            'problems' => fake()->sentences(3),
            'recommendations' => fake()->sentences(3),
            'improvement_examples' => fake()->sentences(2),
        ];
    }
}
