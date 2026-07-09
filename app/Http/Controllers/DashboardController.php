<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogEntry;
use App\Models\Analysis;
use App\Models\AnalysisFinding;
use App\Models\AnalysisMetric;
use App\Models\Report;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $team = $request->user()->currentTeam;

        if (! $team) {
            return view('dashboard', [
                'team' => null,
                'stats' => ['repositories' => 0, 'analyses' => 0, 'criticalFindings' => 0, 'reports' => 0],
                'repositories' => collect(),
                'recentAnalyses' => collect(),
                'recentActivity' => collect(),
                'recentReports' => collect(),
                'analysisTypeSummary' => collect(),
                'severityBreakdown' => collect(),
                'qualityScore' => 0,
            ]);
        }

        $repositoryIds = Repository::query()->where('team_id', $team->id)->pluck('id');
        $analysisIds = Analysis::query()->whereIn('repository_id', $repositoryIds)->pluck('id');

        $stats = [
            'repositories' => $repositoryIds->count(),
            'analyses' => $analysisIds->count(),
            'criticalFindings' => AnalysisFinding::query()
                ->whereIn('analysis_id', $analysisIds)
                ->whereIn('severity', ['critical', 'high'])
                ->where('status', 'open')
                ->count(),
            'reports' => Report::query()->where('team_id', $team->id)->count(),
        ];

        $repositories = Repository::query()
            ->where('team_id', $team->id)
            ->withCount('analyses', 'pullRequests')
            ->withCount(['analyses as findings_count' => function ($query) {
                $query->join('analysis_findings', 'analysis_findings.analysis_id', '=', 'analyses.id')
                    ->whereIn('analysis_findings.severity', ['critical', 'high'])
                    ->where('analysis_findings.status', 'open');
            }])
            ->orderByDesc('last_synced_at')
            ->take(6)
            ->get();

        $recentAnalyses = Analysis::query()
            ->whereIn('repository_id', $repositoryIds)
            ->with('repository')
            ->withCount([
                'findings as critical_findings_count' => fn ($q) => $q->whereIn('severity', ['critical', 'high']),
            ])
            ->latest('completed_at')
            ->take(6)
            ->get();

        $recentActivity = ActivityLogEntry::query()
            ->where('team_id', $team->id)
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        $recentReports = Report::query()
            ->where('team_id', $team->id)
            ->with('repository')
            ->latest('generated_at')
            ->take(4)
            ->get();

        // Additive, read-only aggregates powering the AI Analysis Summary /
        // Security Overview / Code Quality Overview dashboard widgets.
        $analysisTypeSummary = Analysis::query()
            ->whereIn('repository_id', $repositoryIds)
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $severityBreakdown = AnalysisFinding::query()
            ->whereIn('analysis_id', $analysisIds)
            ->where('status', 'open')
            ->selectRaw('severity, count(*) as total')
            ->groupBy('severity')
            ->pluck('total', 'severity');

        $avgDebtScore = (float) (AnalysisMetric::query()
            ->whereIn('analysis_id', $analysisIds)
            ->where('metric_key', 'debt_score')
            ->get()
            ->avg(fn ($metric) => $metric->metric_value['value'] ?? null) ?? 0);

        // Debt score is 0-100 where higher means more debt; invert for a
        // "quality score" ring where higher is better.
        $qualityScore = round(100 - $avgDebtScore, 1);

        return view('dashboard', compact(
            'team', 'stats', 'repositories', 'recentAnalyses', 'recentActivity', 'recentReports',
            'analysisTypeSummary', 'severityBreakdown', 'qualityScore',
        ));
    }
}
