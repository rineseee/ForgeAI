<?php

namespace Tests\Feature;

use App\Models\Analysis;
use App\Models\AnalysisCategory;
use App\Models\Repository;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function userWithTeam(): User
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        return $user->fresh();
    }

    public function test_dashboard_renders_without_a_team(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee("You're not part of a team yet");
    }

    public function test_dashboard_shows_real_scores_from_latest_analyses(): void
    {
        $user = $this->userWithTeam();
        $repository = Repository::factory()->create([
            'team_id' => $user->currentTeam->id,
            'full_name' => 'acme/widgets',
            'language' => 'PHP',
        ]);

        $analysis = Analysis::factory()->create([
            'repository_id' => $repository->id,
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        foreach (AnalysisCategory::CATEGORIES as $category) {
            AnalysisCategory::factory()->create([
                'analysis_id' => $analysis->id,
                'category' => $category,
                'score' => 80,
            ]);
        }

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Total Repositories');
        $response->assertSee('Average Health Score');
        $response->assertSee('Security Score');
        $response->assertSee('Code Quality Score');
        $response->assertSee('Technical Debt');
        $response->assertSee('Repository Distribution');
        $response->assertSee('Analysis History');
        $response->assertSee('acme/widgets');
        $response->assertSee('80', false);
    }

    public function test_dashboard_only_shows_current_teams_data(): void
    {
        $user = $this->userWithTeam();
        $otherUser = $this->userWithTeam();
        Repository::factory()->create(['team_id' => $otherUser->currentTeam->id, 'full_name' => 'other/secret']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertDontSee('other/secret');
    }
}
