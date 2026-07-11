<?php

namespace App\Domain\Github\Actions;

use App\Models\GithubConnection;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SyncGithubRepositories
{
    public function __construct(
        private readonly UpsertGithubRepository $upsertGithubRepository,
    ) {}

    /**
     * Pulls every repository the connected GitHub account can see and
     * upserts it (plus its default branch) into the given team.
     *
     * @return int number of repositories imported/updated
     */
    public function handle(Team $team, GithubConnection $connection, ?User $triggeredBy = null): int
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
                $this->upsertGithubRepository->handle($team, $repo, $triggeredBy);
                $count++;
            }

            $page++;
        } while (count($repos) === 100);

        return $count;
    }
}
