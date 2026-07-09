<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Repository;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'repository_id' => Repository::factory(),
            'title' => fake()->sentence(4),
            'type' => fake()->randomElement(['code_review', 'security', 'quality', 'tech_debt', 'full']),
            'format' => fake()->randomElement(['pdf', 'csv']),
            'file_path' => 'reports/'.fake()->uuid().'.pdf',
            'generated_at' => now(),
        ];
    }
}
