<?php

namespace Database\Factories;

use App\Models\Repository;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Repository>
 */
class RepositoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->slug(2);
        $owner = fake()->userName();
        $isPrivate = fake()->boolean(30);

        return [
            'team_id' => Team::factory(),
            'github_repo_id' => fake()->unique()->numberBetween(100000, 9999999),
            'name' => $name,
            'full_name' => $owner.'/'.$name,
            'description' => fake()->optional()->sentence(),
            'owner' => $owner,
            'default_branch' => 'main',
            'is_private' => $isPrivate,
            'visibility' => $isPrivate ? 'private' : 'public',
            'html_url' => 'https://github.com/'.$owner.'/'.$name,
            'language' => fake()->randomElement(['PHP', 'JavaScript', 'TypeScript', 'Python', 'Go']),
            'last_synced_at' => now(),
            'github_updated_at' => now(),
        ];
    }
}
