<?php

namespace App\Services\Analysis;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Collects real, repository-specific context (metadata, branches, commits,
 * and a bounded set of actual source file contents pulled from GitHub) to
 * ground the AI analysis prompt. Never fabricates data: when no GitHub
 * token is available, source file contents are simply omitted.
 */
class RepositoryContextBuilder
{
    private const MAX_FILES = 25;

    private const MAX_FILE_CHARS = 4000;

    private const MAX_TOTAL_CHARS = 40000;

    private const CACHE_TTL_SECONDS = 300;

    private const ALLOWED_EXTENSIONS = [
        'php', 'js', 'jsx', 'ts', 'tsx', 'vue', 'py', 'rb', 'go', 'java',
        'cs', 'cpp', 'c', 'h', 'hpp', 'rs', 'kt', 'swift', 'blade.php',
        'md', 'json', 'yml', 'yaml',
    ];

    private const EXCLUDED_PATH_SEGMENTS = [
        'vendor/', 'node_modules/', 'dist/', 'build/', '.git/',
        'storage/framework/', 'public/build/', 'package-lock.json', 'composer.lock',
    ];

    public function build(Repository $repository, ?User $triggeringUser = null, bool $includeSource = true): array
    {
        $token = $includeSource ? $this->resolveAccessToken($repository, $triggeringUser) : null;

        return [
            'metadata' => $this->metadata($repository),
            'branches' => $repository->branches()->pluck('name')->all(),
            'commits' => $this->recentCommits($repository),
            'files' => $token ? $this->fetchFiles($repository, $token) : [],
            'source_available' => (bool) $token,
        ];
    }

    private function metadata(Repository $repository): array
    {
        return [
            'full_name' => $repository->full_name,
            'description' => $repository->description,
            'language' => $repository->language,
            'default_branch' => $repository->default_branch,
            'visibility' => $repository->visibility,
            'is_private' => $repository->is_private,
        ];
    }

    private function recentCommits(Repository $repository): array
    {
        return $repository->commits()
            ->orderByDesc('committed_at')
            ->take(20)
            ->get(['author', 'message', 'committed_at'])
            ->map(fn ($commit) => [
                'author' => $commit->author,
                'message' => $commit->message,
                'committed_at' => $commit->committed_at?->toDateString(),
            ])
            ->all();
    }

    private function resolveAccessToken(Repository $repository, ?User $triggeringUser): ?string
    {
        $owner = $repository->team?->owner;

        $connection = $owner?->latestGithubConnection()
            ?? $triggeringUser?->latestGithubConnection();

        return $connection?->access_token;
    }

    private function fetchFiles(Repository $repository, string $token): array
    {
        $tree = $this->fetchTree($repository, $token);

        if ($tree === null) {
            return [];
        }

        $candidates = collect($tree)
            ->filter(fn ($entry) => ($entry['type'] ?? null) === 'blob')
            ->filter(fn ($entry) => $this->isEligible($entry['path'] ?? ''))
            ->sortBy(fn ($entry) => $this->priority($entry['path'] ?? ''))
            ->take(self::MAX_FILES);

        $files = [];
        $totalChars = 0;

        foreach ($candidates as $entry) {
            if ($totalChars >= self::MAX_TOTAL_CHARS) {
                break;
            }

            $content = $this->fetchFileContent($repository, $entry['path'], $token);

            if ($content === null) {
                continue;
            }

            $content = Str::limit($content, self::MAX_FILE_CHARS, "\n... (truncated)");
            $totalChars += strlen($content);

            $files[] = [
                'path' => $entry['path'],
                'content' => $content,
            ];
        }

        return $files;
    }

    private function fetchTree(Repository $repository, string $token): ?array
    {
        $cacheKey = "github:tree:{$repository->full_name}:{$repository->default_branch}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(30)
                ->get("https://api.github.com/repos/{$repository->full_name}/git/trees/{$repository->default_branch}", [
                    'recursive' => 1,
                ]);
        } catch (ConnectionException $e) {
            Log::warning("GitHub tree fetch failed for {$repository->full_name}: {$e->getMessage()}");

            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $tree = $response->json('tree') ?? [];

        Cache::put($cacheKey, $tree, self::CACHE_TTL_SECONDS);

        return $tree;
    }

    private function fetchFileContent(Repository $repository, string $path, string $token): ?string
    {
        $cacheKey = "github:file:{$repository->full_name}:{$repository->default_branch}:{$path}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(20)
                ->get("https://api.github.com/repos/{$repository->full_name}/contents/{$path}", [
                    'ref' => $repository->default_branch,
                ]);
        } catch (ConnectionException $e) {
            Log::warning("GitHub file fetch failed for {$repository->full_name}/{$path}: {$e->getMessage()}");

            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $encoding = $response->json('encoding');
        $content = $response->json('content');

        if ($content === null) {
            return null;
        }

        $content = $encoding === 'base64' ? base64_decode($content) : $content;

        Cache::put($cacheKey, $content, self::CACHE_TTL_SECONDS);

        return $content;
    }

    private function isEligible(string $path): bool
    {
        if ($path === '') {
            return false;
        }

        foreach (self::EXCLUDED_PATH_SEGMENTS as $segment) {
            if (str_contains($path, $segment)) {
                return false;
            }
        }

        foreach (self::ALLOWED_EXTENSIONS as $extension) {
            if (Str::endsWith($path, ".{$extension}")) {
                return true;
            }
        }

        return false;
    }

    private function priority(string $path): int
    {
        $lower = strtolower($path);

        return match (true) {
            str_starts_with($lower, 'readme') => 0,
            str_contains($lower, 'composer.json') || str_contains($lower, 'package.json') => 1,
            str_contains($lower, '/test') || str_contains($lower, '/spec') => 5,
            default => 2,
        };
    }
}
