<?php

namespace App\Domain\Github\Actions;

use App\Models\ActivityLogEntry;
use App\Models\Repository;
use App\Models\Team;
use App\Models\User;

class UpsertGithubRepository
{
    /**
     * Upserts a single GitHub API repository payload (as returned by
     * /user/repos or /repos/{owner}/{repo}) into the given team.
     */
    public function handle(Team $team, array $repo, ?User $triggeredBy = null): Repository
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

        if ($repository->wasRecentlyCreated) {
            ActivityLogEntry::create([
                'team_id' => $team->id,
                'user_id' => $triggeredBy?->id,
                'action' => 'repository.imported',
                'subject_type' => Repository::class,
                'subject_id' => $repository->id,
                'properties' => ['name' => $repository->full_name],
            ]);
        }

        return $repository;
    }
}
