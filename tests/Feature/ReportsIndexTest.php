<?php

namespace Tests\Feature;

use App\Models\Analysis;
use App\Models\AnalysisCategory;
use App\Models\Repository;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsIndexTest extends TestCase
{
    use RefreshDatabase;

    private function userWithTeam(): User
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        return $user->fresh();
    }

    private function completedAnalysisWithCategories(Repository $repository): Analysis
    {
        $analysis = Analysis::factory()->create([
            'repository_id' => $repository->id,
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        foreach (AnalysisCategory::CATEGORIES as $category) {
            AnalysisCategory::factory()->create([
                'analysis_id' => $analysis->id,
                'category' => $category,
                'score' => 70,
            ]);
        }

        return $analysis;
    }

    public function test_reports_page_lists_completed_analyses_with_scores(): void
    {
        $user = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $user->currentTeam->id, 'full_name' => 'acme/widgets']);
        $this->completedAnalysisWithCategories($repository);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee('acme/widgets');
        $response->assertSee('Code Quality');
        $response->assertSee('Security');
        $response->assertSee('Technical Debt');
        $response->assertSee('70', false);
    }

    public function test_reports_page_excludes_incomplete_or_failed_analyses(): void
    {
        $user = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $user->currentTeam->id]);

        Analysis::factory()->create(['repository_id' => $repository->id, 'status' => 'failed']);
        Analysis::factory()->create(['repository_id' => $repository->id, 'status' => 'running']);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee('No reports yet');
    }

    public function test_reports_page_only_shows_current_teams_reports(): void
    {
        $user = $this->userWithTeam();
        $otherUser = $this->userWithTeam();
        $otherRepository = Repository::factory()->create(['team_id' => $otherUser->currentTeam->id, 'full_name' => 'other/secret']);
        $this->completedAnalysisWithCategories($otherRepository);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertOk();
        $response->assertDontSee('other/secret');
    }
}
