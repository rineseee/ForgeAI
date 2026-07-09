<?php

namespace Database\Factories;

use App\Models\Repository;
use App\Models\WebhookEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WebhookEvent>
 */
class WebhookEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'repository_id' => Repository::factory(),
            'event_type' => fake()->randomElement(['push', 'pull_request', 'release']),
            'payload' => ['ref' => 'refs/heads/main', 'sample' => true],
            'processed_at' => now(),
        ];
    }
}
