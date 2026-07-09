<?php

namespace App\Services\Analysis;

use App\Models\ActivityLogEntry;
use App\Models\Analysis;
use App\Models\AnalysisCategory;
use App\Models\Repository;
use App\Models\User;
use App\Services\OpenAi\OpenAiChatClient;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

/**
 * Orchestrates a full repository analysis run: collects real repository
 * context, sends a structured prompt to OpenAI, and persists the resulting
 * per-category report. The Analysis row always reflects the true outcome
 * (completed or failed) — nothing is faked when the AI call cannot run.
 */
class RepositoryAnalysisService
{
    public function __construct(
        private readonly RepositoryContextBuilder $contextBuilder,
        private readonly OpenAiChatClient $openAi,
    ) {}

    public function run(Repository $repository, User $triggeringUser): Analysis
    {
        $analysis = Analysis::create([
            'repository_id' => $repository->id,
            'type' => 'quality',
            'status' => 'running',
            'triggered_by_user_id' => $triggeringUser->id,
            'model_used' => config('services.openai.model'),
            'started_at' => now(),
        ]);

        try {
            $context = $this->contextBuilder->build($repository, $triggeringUser);
            $messages = $this->buildMessages($repository, $context);
            $result = $this->openAi->chatJson($messages, $this->jsonSchema(), 'repository_analysis');
            $categories = $this->validate($result);

            DB::transaction(function () use ($analysis, $categories) {
                foreach ($categories as $key => $category) {
                    AnalysisCategory::create([
                        'analysis_id' => $analysis->id,
                        'category' => $key,
                        'score' => $category['score'],
                        'explanation' => $category['explanation'],
                        'problems' => $category['problems'],
                        'recommendations' => $category['recommendations'],
                        'improvement_examples' => $category['improvement_examples'],
                    ]);
                }

                $analysis->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            });

            ActivityLogEntry::create([
                'team_id' => $repository->team_id,
                'user_id' => $triggeringUser->id,
                'action' => 'analysis.completed',
                'subject_type' => Analysis::class,
                'subject_id' => $analysis->id,
                'properties' => ['name' => $repository->full_name],
            ]);
        } catch (Throwable $e) {
            $analysis->update([
                'status' => 'failed',
                'failure_reason' => $e->getMessage(),
                'completed_at' => now(),
            ]);
        }

        return $analysis->fresh('categories');
    }

    private function buildMessages(Repository $repository, array $context): array
    {
        $system = <<<'EOT'
            You are a senior staff software engineer performing a rigorous code review
            for a code quality platform. You are given real metadata, commit history,
            and (when available) real source file contents from a GitHub repository.
            Base every judgement strictly on the material provided. If source file
            contents were not provided, say so explicitly in your explanations rather
            than inventing specifics about the code. Respond only with the requested
            JSON structure.
            EOT;

        $lines = [];
        $lines[] = 'Repository: '.$context['metadata']['full_name'];
        $lines[] = 'Description: '.($context['metadata']['description'] ?? 'None provided');
        $lines[] = 'Primary language: '.($context['metadata']['language'] ?? 'Unknown');
        $lines[] = 'Default branch: '.$context['metadata']['default_branch'];
        $lines[] = 'Visibility: '.$context['metadata']['visibility'];
        $lines[] = 'Branches: '.(implode(', ', $context['branches']) ?: 'None synced');
        $lines[] = '';

        if (! empty($context['commits'])) {
            $lines[] = 'Recent commits:';
            foreach ($context['commits'] as $commit) {
                $lines[] = "- [{$commit['committed_at']}] {$commit['author']}: {$commit['message']}";
            }
        } else {
            $lines[] = 'Recent commits: none synced.';
        }

        $lines[] = '';

        if ($context['source_available'] && ! empty($context['files'])) {
            $lines[] = 'Source files (path followed by contents):';
            foreach ($context['files'] as $file) {
                $lines[] = "\n### {$file['path']}\n```\n{$file['content']}\n```";
            }
        } else {
            $lines[] = 'Source file contents were not available for this run (no connected '
                .'GitHub access token, or the repository tree could not be fetched). Base your '
                .'analysis only on the metadata and commit history above, and note the missing '
                .'source access in each category explanation.';
        }

        return [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => implode("\n", $lines)],
        ];
    }

    private function jsonSchema(): array
    {
        $categorySchema = [
            'type' => 'object',
            'properties' => [
                'score' => ['type' => 'integer', 'minimum' => 0, 'maximum' => 100],
                'explanation' => ['type' => 'string'],
                'problems' => ['type' => 'array', 'items' => ['type' => 'string']],
                'recommendations' => ['type' => 'array', 'items' => ['type' => 'string']],
                'improvement_examples' => ['type' => 'array', 'items' => ['type' => 'string']],
            ],
            'required' => ['score', 'explanation', 'problems', 'recommendations', 'improvement_examples'],
            'additionalProperties' => false,
        ];

        return [
            'type' => 'object',
            'properties' => [
                'categories' => [
                    'type' => 'object',
                    'properties' => array_fill_keys(AnalysisCategory::CATEGORIES, $categorySchema),
                    'required' => AnalysisCategory::CATEGORIES,
                    'additionalProperties' => false,
                ],
            ],
            'required' => ['categories'],
            'additionalProperties' => false,
        ];
    }

    private function validate(array $result): array
    {
        $categories = $result['categories'] ?? null;

        if (! is_array($categories)) {
            throw new RuntimeException('AI response was missing the "categories" object.');
        }

        foreach (AnalysisCategory::CATEGORIES as $key) {
            $category = $categories[$key] ?? null;

            if (! is_array($category)
                || ! isset($category['score'], $category['explanation'])
                || ! is_array($category['problems'] ?? null)
                || ! is_array($category['recommendations'] ?? null)
                || ! is_array($category['improvement_examples'] ?? null)
            ) {
                throw new RuntimeException("AI response was missing or malformed for category [{$key}].");
            }
        }

        return $categories;
    }
}
