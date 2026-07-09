<?php

namespace Database\Factories;

use App\Models\Repository;
use App\Models\RepositoryBranch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RepositoryBranch>
 */
class RepositoryBranchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'repository_id' => Repository::factory(),
            'name' => fake()->randomElement(['main', 'develop', 'feature/'.fake()->word()]),
            'is_default' => false,
        ];
    }
}
