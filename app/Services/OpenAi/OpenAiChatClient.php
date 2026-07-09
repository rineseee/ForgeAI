<?php

namespace App\Services\OpenAi;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Thin wrapper around the OpenAI Chat Completions API that enforces a
 * JSON Schema response shape. No mocked/fabricated responses — callers
 * always get back either a real, schema-validated model response or an
 * exception.
 */
class OpenAiChatClient
{
    private const ENDPOINT = 'https://api.openai.com/v1/chat/completions';

    public function chatJson(array $messages, array $jsonSchema, string $schemaName, ?string $model = null): array
    {
        $apiKey = config('services.openai.key');

        if (! $apiKey) {
            throw new RuntimeException('OpenAI API key is not configured.');
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(180)
            ->post(self::ENDPOINT, [
                'model' => $model ?? config('services.openai.model'),
                'messages' => $messages,
                'temperature' => 0.2,
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => $schemaName,
                        'schema' => $jsonSchema,
                        'strict' => true,
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('OpenAI API request failed: '.$response->status().' '.$response->body());
        }

        $content = $response->json('choices.0.message.content');

        if (! is_string($content)) {
            throw new RuntimeException('OpenAI API returned an unexpected response shape.');
        }

        $decoded = json_decode($content, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('OpenAI API returned invalid JSON.');
        }

        return $decoded;
    }
}
