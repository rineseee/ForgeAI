<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $team = $request->user()->currentTeam;

        $reports = $team
            ? Report::query()->where('team_id', $team->id)->with('repository')->latest('generated_at')->paginate(10)
            : Report::query()->whereRaw('1 = 0')->paginate(10);

        return view('reports.index', compact('reports'));
    }
}
