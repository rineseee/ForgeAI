<?php

namespace App\Http\Controllers;

use App\Domain\Github\Actions\ImportPublicRepository;
use App\Domain\Github\Actions\SyncGithubRepositories;
use App\Models\Repository;
use App\Support\Toast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        $connection = $user->latestGithubConnection();

        if (! $team || ! $connection) {
            return redirect()->route('repositories.index')
                ->with('status', 'Connect your GitHub account before syncing repositories.');
        }

        try {
            $count = $syncGithubRepositories->handle($team, $connection, $user);
        } catch (Throwable $e) {
            Log::error('Repository sync failed: '.$e->getMessage(), ['exception' => $e]);

            Toast::error($user, 'Repository sync failed. Please try again.');

            return redirect()->route('repositories.index')
                ->with('status', 'Repository sync failed. Please try again.');
        }

        Toast::success($user, 'notify_sync_complete', "Synced {$count} repositories from GitHub.");

        return redirect()->route('repositories.index')
            ->with('status', "Synced {$count} repositories from GitHub.");
    }

    public function import(Request $request, ImportPublicRepository $importPublicRepository): RedirectResponse
    {
        $user = $request->user();
        $team = $user->currentTeam;

        if (! $team) {
            return redirect()->route('repositories.index')
                ->with('status', 'No active team found.');
        }

        $validated = $request->validate([
            'repository' => ['required', 'string', 'max:255'],
        ]);

        try {
            $repository = $importPublicRepository->handle(
                $team,
                $validated['repository'],
                $user->latestGithubConnection(),
                $user
            );
        } catch (InvalidArgumentException|RuntimeException $e) {
            return redirect()->route('repositories.index')->with('status', $e->getMessage());
        } catch (Throwable $e) {
            Log::error('Repository import failed: '.$e->getMessage(), ['exception' => $e]);

            return redirect()->route('repositories.index')
                ->with('status', 'Repository import failed. Please try again.');
        }

        Toast::success($user, 'notify_sync_complete', "Imported {$repository->full_name} from GitHub.");

        return redirect()->route('repositories.index')->with('status', "Imported {$repository->full_name}.");
    }

    public function show(Request $request, Repository $repository): View
    {
        if (! $repository->belongsToTeam($request->user()->currentTeam)) {
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
