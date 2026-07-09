<?php

namespace Database\Factories;

use App\Models\GithubConnection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<GithubConnection>
 */
class GithubConnectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'github_id' => (string) fake()->unique()->numberBetween(10000, 999999),
            'nickname' => fake()->userName(),
            'access_token' => Str::random(40),
            'refresh_token' => Str::random(40),
            'scopes' => ['repo', 'read:org'],
            'connected_at' => now(),
        ];
    }
}
