<?php

namespace Tests\Feature;

use App\Models\GithubConnection;
use App\Models\Repository;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RepositoryAnalysisTest extends TestCase
{
    use RefreshDatabase;

    private function userWithTeam(): User
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        return $user->fresh();
    }

    private function categoryPayload(): array
    {
        $category = [
            'score' => 78,
            'explanation' => 'Solid overall, a few gaps.',
            'problems' => ['Missing input validation on the login form.'],
            'recommendations' => ['Add form request validation.'],
            'improvement_examples' => ['Use a FormRequest class instead of validating inline.'],
        ];

        return [
            'code_quality' => $category,
            'security' => $category,
            'performance' => $category,
            'architecture' => $category,
            'documentation' => $category,
            'technical_debt' => $category,
        ];
    }

    public function test_analyze_repository_calls_openai_and_persists_categories(): void
    {
        config(['services.openai.key' => 'test-key', 'services.openai.model' => 'gpt-4o-mini']);

        $user = $this->userWithTeam();
        $repository = Repository::factory()->create([
            'team_id' => $user->currentTeam->id,
            'full_name' => 'acme/widgets',
            'default_branch' => 'main',
        ]);

        GithubConnection::factory()->create([
            'user_id' => $user->id,
            'access_token' => 'gh-token',
        ]);

        Http::fake([
            'api.github.com/repos/acme/widgets/git/trees/main*' => Http::response([
                'tree' => [
                    ['path' => 'README.md', 'type' => 'blob'],
                    ['path' => 'app/Foo.php', 'type' => 'blob'],
                    ['path' => 'vendor/skip.php', 'type' => 'blob'],
                ],
            ]),
            'api.github.com/repos/acme/widgets/contents/*' => Http::response([
                'encoding' => 'base64',
                'content' => base64_encode('<?php echo "hi";'),
            ]),
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode(['categories' => $this->categoryPayload()])]],
                ],
            ]),
        ]);

        $response = $this->actingAs($user)->post(route('repositories.analyze', $repository));

        $response->assertRedirect(route('repositories.show', $repository));

        $this->assertDatabaseHas('analyses', [
            'repository_id' => $repository->id,
            'status' => 'completed',
            'model_used' => 'gpt-4o-mini',
        ]);

        $analysis = $repository->analyses()->latest('id')->first();
        $this->assertCount(6, $analysis->categories);

        $this->assertDatabaseHas('activity_logs', [
            'team_id' => $user->currentTeam->id,
            'action' => 'analysis.completed',
            'subject_id' => $analysis->id,
        ]);
        $this->assertSame(78, $analysis->categories->firstWhere('category', 'security')->score);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), 'api.openai.com')) {
                return false;
            }

            $userMessage = collect($request->data()['messages'] ?? [])->firstWhere('role', 'user');

            return $userMessage && str_contains($userMessage['content'], 'acme/widgets');
        });
    }

    public function test_analyze_repository_marks_failed_when_openai_key_missing(): void
    {
        config(['services.openai.key' => null]);

        $user = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $user->currentTeam->id]);

        $response = $this->actingAs($user)->post(route('repositories.analyze', $repository));

        $response->assertRedirect(route('repositories.show', $repository));
        $this->assertDatabaseHas('analyses', [
            'repository_id' => $repository->id,
            'status' => 'failed',
        ]);
    }

    public function test_analyze_repository_marks_failed_when_openai_request_fails(): void
    {
        config(['services.openai.key' => 'test-key']);

        $user = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $user->currentTeam->id]);

        Http::fake([
            'api.openai.com/*' => Http::response('server error', 500),
        ]);

        $this->actingAs($user)->post(route('repositories.analyze', $repository));

        $analysis = $repository->analyses()->latest('id')->first();
        $this->assertSame('failed', $analysis->status);
        $this->assertNotNull($analysis->failure_reason);
    }

    public function test_analysis_report_page_renders_categories(): void
    {
        config(['services.openai.key' => 'test-key']);

        $user = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $user->currentTeam->id, 'full_name' => 'acme/widgets']);

        Http::fake([
            'api.github.com/*' => Http::response([], 404),
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode(['categories' => $this->categoryPayload()])]],
                ],
            ]),
        ]);

        $this->actingAs($user)->post(route('repositories.analyze', $repository));
        $analysis = $repository->analyses()->latest('id')->first();

        $response = $this->actingAs($user)->get(route('analyses.show', $analysis));

        $response->assertOk();
        $response->assertSee('Analysis Report');
        $response->assertSee('Security');
        $response->assertSee('Missing input validation on the login form.');
    }

    public function test_user_cannot_view_another_teams_analysis_report(): void
    {
        $user = $this->userWithTeam();
        $otherUser = $this->userWithTeam();
        $repository = Repository::factory()->create(['team_id' => $otherUser->currentTeam->id]);
        $analysis = \App\Models\Analysis::factory()->create(['repository_id' => $repository->id]);

        $response = $this->actingAs($user)->get(route('analyses.show', $analysis));

        $response->assertNotFound();
    }
}
