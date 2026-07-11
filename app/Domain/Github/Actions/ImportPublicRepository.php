<?php

namespace App\Domain\Github\Actions;

use App\Models\GithubConnection;
use App\Models\Repository;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class ImportPublicRepository
{
    public function __construct(
        private readonly UpsertGithubRepository $upsertGithubRepository,
    ) {}

    /**
     * Imports a single public GitHub repository, identified by an
     * "owner/repo" string or a full GitHub URL, into the given team. This
     * does not require the repository to be owned by or accessible through
     * the triggering user's own GitHub connection.
     */
    public function handle(Team $team, string $identifier, ?GithubConnection $connection = null, ?User $triggeredBy = null): Repository
    {
        [$owner, $repo] = $this->parseIdentifier($identifier);

        $request = Http::acceptJson()->timeout(15);

        if ($connection) {
            $request = $request->withToken($connection->access_token);
        }

        $response = $request->get("https://api.github.com/repos/{$owner}/{$repo}");

        if ($response->status() === 404) {
            throw new RuntimeException("Repository \"{$owner}/{$repo}\" was not found on GitHub.");
        }

        if ($response->failed()) {
            throw new RuntimeException('GitHub API request failed: '.$response->status());
        }

        $data = $response->json();

        if ($data['private'] ?? false) {
            throw new RuntimeException('This repository is private. Connect the owning GitHub account and sync instead.');
        }

        return $this->upsertGithubRepository->handle($team, $data, $triggeredBy);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function parseIdentifier(string $identifier): array
    {
        $identifier = trim($identifier);
        $identifier = preg_replace('#^https?://(www\.)?github\.com/#i', '', $identifier) ?? $identifier;
        $identifier = preg_replace('#\.git$#i', '', $identifier) ?? $identifier;
        $identifier = trim($identifier, '/');

        $parts = explode('/', $identifier);

        if (count($parts) < 2 || $parts[0] === '' || $parts[1] === '') {
            throw new InvalidArgumentException('Enter a GitHub repository as "owner/repo" or a full GitHub URL.');
        }

        return [$parts[0], $parts[1]];
    }
}
