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
        if (! $repository->belongsToTeam($request->user()->currentTeam)) {
            throw new NotFoundHttpException;
        }

        $analysis = $analysisService->queue($repository, $request->user());

        return redirect()->route('analyses.show', $analysis)
            ->with('status', "Analysis started for {$repository->full_name} — this page will update automatically when it finishes.");
    }

    public function show(Request $request, Analysis $analysis): View
    {
        if (! $analysis->repository->belongsToTeam($request->user()->currentTeam)) {
            throw new NotFoundHttpException;
        }

        $analysis->load('repository', 'categories', 'triggeredBy');

        return view('analyses.show', compact('analysis'));
    }
}
