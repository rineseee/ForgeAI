<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogEntry;
use App\Models\Analysis;
use App\Models\AnalysisCategory;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $team = $request->user()->currentTeam;

        if (! $team) {
            return view('dashboard', [
                'team' => null,
                'stats' => [
                    'repositories' => 0, 'analyses' => 0, 'reports' => 0,
                    'healthScore' => 0, 'securityScore' => 0, 'codeQualityScore' => 0, 'technicalDebtScore' => 0,
                ],
                'repositories' => collect(),
                'recentAnalyses' => collect(),
                'recentActivity' => collect(),
                'recentReports' => collect(),
                'analysisTypeSummary' => collect(),
                'repositoryDistribution' => collect(),
                'analysisHistory' => collect(),
            ]);
        }

        $repositoryIds = Repository::query()->where('team_id', $team->id)->pluck('id');
        $analysisIds = Analysis::query()->whereIn('repository_id', $repositoryIds)->pluck('id');

        // "Latest analysis per repository" keeps health scores current —
        // older re-runs of the same repository don't drag the average down.
        $latestAnalysisIds = Analysis::query()
            ->whereIn('repository_id', $repositoryIds)
            ->where('status', 'completed')
            ->whereHas('categories')
            ->select('repository_id', DB::raw('MAX(id) as id'))
            ->groupBy('repository_id')
            ->pluck('id');

        $latestCategories = AnalysisCategory::query()
            ->whereIn('analysis_id', $latestAnalysisIds)
            ->get();

        $stats = [
            'repositories' => $repositoryIds->count(),
            'analyses' => $analysisIds->count(),
            'reports' => $latestAnalysisIds->count(),
            'healthScore' => $latestCategories->isNotEmpty() ? round($latestCategories->avg('score')) : 0,
            'securityScore' => $this->averageFor($latestCategories, 'security'),
            'codeQualityScore' => $this->averageFor($latestCategories, 'code_quality'),
            'technicalDebtScore' => $this->averageFor($latestCategories, 'technical_debt'),
        ];

        $repositories = Repository::query()
            ->where('team_id', $team->id)
            ->withCount('analyses', 'pullRequests')
            ->orderByDesc('last_synced_at')
            ->take(6)
            ->get();

        $recentAnalyses = Analysis::query()
            ->whereIn('repository_id', $repositoryIds)
            ->with('repository', 'categories')
            ->latest('id')
            ->take(6)
            ->get();

        $recentActivity = ActivityLogEntry::query()
            ->where('team_id', $team->id)
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        $recentReports = Analysis::query()
            ->whereIn('id', $latestAnalysisIds)
            ->with('repository', 'categories')
            ->latest('completed_at')
            ->take(4)
            ->get();

        $analysisTypeSummary = Analysis::query()
            ->whereIn('repository_id', $repositoryIds)
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $repositoryDistribution = Repository::query()
            ->where('team_id', $team->id)
            ->whereNotNull('language')
            ->selectRaw('language, count(*) as total')
            ->groupBy('language')
            ->orderByDesc('total')
            ->pluck('total', 'language');

        $analysisHistory = Analysis::query()
            ->whereIn('repository_id', $repositoryIds)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', now()->subWeeks(8))
            ->selectRaw("strftime('%Y-%W', completed_at) as period, count(*) as total")
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        return view('dashboard', compact(
            'team', 'stats', 'repositories', 'recentAnalyses', 'recentActivity', 'recentReports',
            'analysisTypeSummary', 'repositoryDistribution', 'analysisHistory',
        ));
    }

    private function averageFor($categories, string $key): int
    {
        $matching = $categories->where('category', $key);

        return $matching->isNotEmpty() ? (int) round($matching->avg('score')) : 0;
    }
}
