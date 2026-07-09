<?php

namespace App\Http\Controllers;

use App\Domain\Github\Actions\SyncGithubRepositories;
use App\Models\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        $count = $syncGithubRepositories->handle($team, $connection);

        return redirect()->route('repositories.index')
            ->with('status', "Synced {$count} repositories from GitHub.");
    }
}
