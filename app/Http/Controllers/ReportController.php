<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $team = $request->user()->currentTeam;
        $repositoryIds = $team ? Repository::query()->where('team_id', $team->id)->pluck('id') : collect();

        $reports = Analysis::query()
            ->whereIn('repository_id', $repositoryIds)
            ->where('status', 'completed')
            ->whereHas('categories')
            ->with('repository', 'categories', 'triggeredBy')
            ->latest('completed_at')
            ->paginate(10);

        return view('reports.index', compact('reports'));
    }
}
