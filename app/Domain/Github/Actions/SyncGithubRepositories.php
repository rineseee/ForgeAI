<?php

namespace App\Domain\Github\Actions;

use App\Models\GithubConnection;
use App\Models\Repository;
use App\Models\Team;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SyncGithubRepositories
{
    /**
     * Pulls every repository the connected GitHub account can see and
     * upserts it (plus its default branch) into the given team.
     *
     * @return int number of repositories imported/updated
     */
    public function handle(Team $team, GithubConnection $connection): int
    {
        $count = 0;
        $page = 1;

        do {
            $response = Http::withToken($connection->access_token)
                ->acceptJson()
                ->get('https://api.github.com/user/repos', [
                    'per_page' => 100,
                    'page' => $page,
                    'affiliation' => 'owner,collaborator,organization_member',
                    'sort' => 'updated',
                ]);

            if ($response->failed()) {
                throw new RuntimeException('GitHub API request failed: '.$response->status());
            }

            $repos = $response->json();

            foreach ($repos as $repo) {
                $this->upsert($team, $repo);
                $count++;
            }

            $page++;
        } while (count($repos) === 100);

        return $count;
    }

    private function upsert(Team $team, array $repo): void
    {
        $repository = Repository::updateOrCreate(
            [
                'team_id' => $team->id,
                'github_repo_id' => $repo['id'],
            ],
            [
                'name' => $repo['name'],
                'full_name' => $repo['full_name'],
                'description' => $repo['description'],
                'owner' => $repo['owner']['login'] ?? null,
                'default_branch' => $repo['default_branch'] ?? 'main',
                'is_private' => (bool) $repo['private'],
                'visibility' => $repo['visibility'] ?? ($repo['private'] ? 'private' : 'public'),
                'html_url' => $repo['html_url'],
                'language' => $repo['language'],
                'last_synced_at' => now(),
                'github_updated_at' => $repo['updated_at'] ?? null,
            ]
        );

        $repository->branches()->updateOrCreate(
            ['name' => $repository->default_branch],
            ['is_default' => true]
        );
    }
}
