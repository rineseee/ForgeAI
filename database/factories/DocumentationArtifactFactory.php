<?php

namespace Database\Factories;

use App\Models\DocumentationArtifact;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentationArtifact>
 */
class DocumentationArtifactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'repository_id' => Repository::factory(),
            'type' => fake()->randomElement(['readme', 'api_doc', 'changelog', 'inline']),
            'content' => "# ".fake()->sentence()."\n\n".fake()->paragraphs(3, true),
            'generated_at' => now(),
            'is_published' => fake()->boolean(50),
        ];
    }
}
