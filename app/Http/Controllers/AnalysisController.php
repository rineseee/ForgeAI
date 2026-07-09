<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
