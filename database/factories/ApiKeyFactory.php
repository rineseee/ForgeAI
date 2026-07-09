<?php

namespace Database\Factories;

use App\Models\ApiKey;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ApiKey>
 */
class ApiKeyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'provider' => fake()->randomElement(['openai', 'github']),
            'encrypted_key' => Str::random(48),
            'is_active' => true,
        ];
    }
}
