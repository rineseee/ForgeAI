<?php

namespace Tests\Feature;

use App\Models\Analysis;
use App\Models\AnalysisMetric;
use App\Models\Commit;
use App\Models\Repository;
use App\Models\RepositoryBranch;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepositoryShowTest extends TestCase
{
    use RefreshDatabase;

    private function userWithTeam(): User
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        return $user->fresh();
    }

    public function test_repository_detail_page_renders_with_full_data(): void
    {
        $user = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $user->currentTeam->id]);

        RepositoryBranch::factory()->create(['repository_id' => $repository->id, 'name' => 'main', 'is_default' => true]);
        RepositoryBranch::factory()->create(['repository_id' => $repository->id, 'name' => 'develop', 'is_default' => false]);

        Commit::factory()->count(3)->create(['repository_id' => $repository->id]);

        $analysis = Analysis::factory()->create([
            'repository_id' => $repository->id,
            'status' => 'completed',
        ]);

        AnalysisMetric::factory()->create([
            'analysis_id' => $analysis->id,
            'metric_key' => 'debt_score',
            'metric_value' => ['value' => 30],
        ]);

        $response = $this->actingAs($user)->get(route('repositories.show', $repository));

        $response->assertOk();
        $response->assertSee($repository->full_name);
        $response->assertSee('Repository Information');
        $response->assertSee('Branches');
        $response->assertSee('Recent Commits');
        $response->assertSee('Languages');
        $response->assertSee('Last Analysis');
        $response->assertSee('AI Status');
        $response->assertSee('Analyze Repository');
    }

    public function test_repository_detail_page_renders_with_no_data(): void
    {
        $user = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $user->currentTeam->id, 'language' => null]);

        $response = $this->actingAs($user)->get(route('repositories.show', $repository));

        $response->assertOk();
        $response->assertSee('No analyses yet');
    }

    public function test_user_cannot_view_another_teams_repository(): void
    {
        $user = $this->userWithTeam();
        $otherUser = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $otherUser->currentTeam->id]);

        $response = $this->actingAs($user)->get(route('repositories.show', $repository));

        $response->assertNotFound();
    }

    public function test_analyze_repository_creates_an_analysis_run(): void
    {
        $user = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $user->currentTeam->id]);

        $response = $this->actingAs($user)->post(route('repositories.analyze', $repository));

        $response->assertRedirect(route('repositories.show', $repository));
        $this->assertDatabaseHas('analyses', [
            'repository_id' => $repository->id,
            'triggered_by_user_id' => $user->id,
        ]);
    }
}
