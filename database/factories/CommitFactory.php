<?php

namespace Database\Factories;

use App\Models\Commit;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Commit>
 */
class CommitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'repository_id' => Repository::factory(),
            'sha' => fake()->unique()->sha1(),
            'author' => fake()->name(),
            'message' => fake()->sentence(),
            'committed_at' => fake()->dateTimeBetween('-3 months'),
        ];
    }
}
