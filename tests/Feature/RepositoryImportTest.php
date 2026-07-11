<?php

namespace Tests\Feature;

use App\Models\Repository;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RepositoryImportTest extends TestCase
{
    use RefreshDatabase;

    private function userWithTeam(): User
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        return $user->fresh();
    }

    public function test_user_can_import_a_public_repository_by_owner_repo_string(): void
    {
        $user = $this->userWithTeam();

        Http::fake([
            'api.github.com/repos/facebook/react' => Http::response([
                'id' => 999888,
                'name' => 'react',
                'full_name' => 'facebook/react',
                'description' => 'A declarative UI library.',
                'owner' => ['login' => 'facebook'],
                'default_branch' => 'main',
                'private' => false,
                'visibility' => 'public',
                'html_url' => 'https://github.com/facebook/react',
                'language' => 'JavaScript',
                'updated_at' => now()->toIso8601String(),
            ], 200),
        ]);

        $response = $this->actingAs($user)
            ->post(route('repositories.import'), ['repository' => 'facebook/react']);

        $response->assertRedirect(route('repositories.index'));

        $this->assertDatabaseHas('repositories', [
            'team_id' => $user->currentTeam->id,
            'full_name' => 'facebook/react',
            'is_private' => false,
        ]);
    }

    public function test_user_can_import_a_public_repository_by_full_url(): void
    {
        $user = $this->userWithTeam();

        Http::fake([
            'api.github.com/repos/facebook/react' => Http::response([
                'id' => 999888,
                'name' => 'react',
                'full_name' => 'facebook/react',
                'description' => null,
                'owner' => ['login' => 'facebook'],
                'default_branch' => 'main',
                'private' => false,
                'visibility' => 'public',
                'html_url' => 'https://github.com/facebook/react',
                'language' => 'JavaScript',
                'updated_at' => now()->toIso8601String(),
            ], 200),
        ]);

        $response = $this->actingAs($user)
            ->post(route('repositories.import'), ['repository' => 'https://github.com/facebook/react']);

        $response->assertRedirect(route('repositories.index'));

        $this->assertDatabaseHas('repositories', [
            'team_id' => $user->currentTeam->id,
            'full_name' => 'facebook/react',
        ]);
    }

    public function test_importing_a_private_repository_is_rejected(): void
    {
        $user = $this->userWithTeam();

        Http::fake([
            'api.github.com/repos/acme/secret' => Http::response([
                'id' => 111222,
                'name' => 'secret',
                'full_name' => 'acme/secret',
                'owner' => ['login' => 'acme'],
                'default_branch' => 'main',
                'private' => true,
                'visibility' => 'private',
                'html_url' => 'https://github.com/acme/secret',
                'language' => null,
                'updated_at' => now()->toIso8601String(),
            ], 200),
        ]);

        $response = $this->actingAs($user)
            ->post(route('repositories.import'), ['repository' => 'acme/secret']);

        $response->assertRedirect(route('repositories.index'));

        $this->assertDatabaseMissing('repositories', [
            'full_name' => 'acme/secret',
        ]);
    }

    public function test_importing_a_nonexistent_repository_is_handled_gracefully(): void
    {
        $user = $this->userWithTeam();

        Http::fake([
            'api.github.com/repos/*' => Http::response([], 404),
        ]);

        $response = $this->actingAs($user)
            ->post(route('repositories.import'), ['repository' => 'nobody/nothing']);

        $response->assertRedirect(route('repositories.index'));
        $this->assertDatabaseCount('repositories', 0);
    }

    public function test_import_rejects_malformed_input(): void
    {
        $user = $this->userWithTeam();

        $response = $this->actingAs($user)
            ->post(route('repositories.import'), ['repository' => 'not-a-valid-identifier']);

        $response->assertRedirect(route('repositories.index'));
        $this->assertDatabaseCount('repositories', 0);
    }
}
