<?php

namespace Database\Factories;

use App\Models\PullRequest;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PullRequest>
 */
class PullRequestFactory extends Factory
{
    public function definition(): array
    {
        $opened = fake()->dateTimeBetween('-2 months');
        $status = fake()->randomElement(['open', 'merged', 'closed']);

        return [
            'repository_id' => Repository::factory(),
            'github_pr_number' => fake()->unique()->numberBetween(1, 5000),
            'title' => fake()->sentence(6),
            'author' => fake()->userName(),
            'status' => $status,
            'base_branch' => 'main',
            'head_branch' => 'feature/'.fake()->word(),
            'opened_at' => $opened,
            'merged_at' => $status === 'merged' ? fake()->dateTimeBetween($opened) : null,
        ];
    }
}
