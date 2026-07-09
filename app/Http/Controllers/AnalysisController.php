<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Repository;
use App\Services\Analysis\RepositoryAnalysisService;
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
            ->with('repository', 'categories')
            ->latest('id')
            ->paginate(15);

        return view('analyses.index', compact('analyses'));
    }

    public function store(Request $request, Repository $repository, RepositoryAnalysisService $analysisService): RedirectResponse
    {
        $team = $request->user()->currentTeam;

        if (! $team || $repository->team_id !== $team->id) {
            throw new NotFoundHttpException;
        }

        $analysis = $analysisService->run($repository, $request->user());

        if ($analysis->status === 'failed') {
            return redirect()->route('repositories.show', $repository)
                ->with('status', 'Analysis failed: '.$analysis->failure_reason);
        }

        return redirect()->route('repositories.show', $repository)
            ->with('status', 'Analysis completed.');
    }

    public function show(Request $request, Analysis $analysis): View
    {
        $team = $request->user()->currentTeam;

        if (! $team || $analysis->repository->team_id !== $team->id) {
            throw new NotFoundHttpException;
        }

        $analysis->load('repository', 'categories', 'triggeredBy');

        return view('analyses.show', compact('analysis'));
    }
}
