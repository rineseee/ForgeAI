<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnalysisController extends Controller
{
    public function index(Request $request): View
    {
        $team = $request->user()->currentTeam;
        $repositoryIds = $team ? Repository::query()->where('team_id', $team->id)->pluck('id') : collect();

        $analyses = Analysis::query()
            ->whereIn('repository_id', $repositoryIds)
            ->with('repository')
            ->withCount(['findings as critical_findings_count' => fn ($q) => $q->whereIn('severity', ['critical', 'high'])])
            ->latest('completed_at')
            ->paginate(15);

        return view('analyses.index', compact('analyses'));
    }

    public function store(Request $request, Repository $repository): RedirectResponse
    {
        $team = $request->user()->currentTeam;

        if (! $team || $repository->team_id !== $team->id) {
            throw new NotFoundHttpException;
        }

        Analysis::create([
            'repository_id' => $repository->id,
            'type' => 'quality',
            'status' => 'queued',
            'triggered_by_user_id' => $request->user()->id,
        ]);

        return redirect()->route('repositories.show', $repository)
            ->with('status', 'Analysis queued. This may take a few minutes.');
    }
}
