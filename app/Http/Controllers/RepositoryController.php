<?php

namespace App\Http\Controllers;

use App\Domain\Github\Actions\SyncGithubRepositories;
use App\Models\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RepositoryController extends Controller
{
    public function index(Request $request): View
    {
        $team = $request->user()->currentTeam;

        $repositories = $team
            ? Repository::query()
                ->where('team_id', $team->id)
                ->withCount('analyses', 'pullRequests')
                ->orderByDesc('last_synced_at')
                ->paginate(10)
            : Repository::query()->whereRaw('1 = 0')->paginate(10);

        $hasGithubConnection = $request->user()->githubConnections()->exists();

        return view('repositories.index', compact('repositories', 'hasGithubConnection'));
    }

    public function sync(Request $request, SyncGithubRepositories $syncGithubRepositories): RedirectResponse
    {
        $user = $request->user();
        $team = $user->currentTeam;
        $connection = $user->githubConnections()->latest('connected_at')->first();

        if (! $team || ! $connection) {
            return redirect()->route('repositories.index')
                ->with('status', 'Connect your GitHub account before syncing repositories.');
        }

        $count = $syncGithubRepositories->handle($team, $connection, $user);

        return redirect()->route('repositories.index')
            ->with('status', "Synced {$count} repositories from GitHub.");
    }

    public function show(Request $request, Repository $repository): View
    {
        $team = $request->user()->currentTeam;

        if (! $team || $repository->team_id !== $team->id) {
            throw new NotFoundHttpException;
        }

        $repository->loadCount('analyses', 'pullRequests', 'commits');

        $branches = $repository->branches()
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        $recentCommits = $repository->commits()
            ->orderByDesc('committed_at')
            ->take(10)
            ->get();

        $lastAnalysis = $repository->analyses()
            ->with('metrics', 'categories')
            ->withCount(['findings as critical_findings_count' => fn ($q) => $q->whereIn('severity', ['critical', 'high'])])
            ->latest('id')
            ->first();

        return view('repositories.show', [
            'repository' => $repository,
            'branches' => $branches,
            'recentCommits' => $recentCommits,
            'lastAnalysis' => $lastAnalysis,
        ]);
    }
}
