<?php

namespace Database\Seeders;

use App\Models\ActivityLogEntry;
use App\Models\AnalysisFinding;
use App\Models\ApiKey;
use App\Models\Commit;
use App\Models\DocumentationArtifact;
use App\Models\GithubConnection;
use App\Models\PullRequest;
use App\Models\Report;
use App\Models\ReportSchedule;
use App\Models\Repository;
use App\Models\RepositoryBranch;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::factory()->create([
            'name' => 'Rinesa Krasniqi',
            'email' => 'rineskraasniqi@gmail.com',
            'email_verified_at' => now(),
        ]);

        $developer = User::factory()->create(['email_verified_at' => now()]);

        $team = Team::factory()->create([
            'owner_id' => $owner->id,
            'name' => 'Forge AI Demo Team',
        ]);

        $owner->update(['current_team_id' => $team->id]);
        $developer->update(['current_team_id' => $team->id]);
        $team->members()->syncWithoutDetaching([$owner->id, $developer->id]);

        $this->assignTeamRoles($team, $owner, $developer);

        GithubConnection::factory()->create(['user_id' => $owner->id]);

        ApiKey::factory()->create(['team_id' => $team->id, 'provider' => 'openai']);
        ApiKey::factory()->create(['team_id' => $team->id, 'provider' => 'github']);

        Repository::factory(3)
            ->create(['team_id' => $team->id])
            ->each(function (Repository $repository, int $index) use ($team, $owner, $developer) {
                RepositoryBranch::factory()->create([
                    'repository_id' => $repository->id,
                    'name' => $repository->default_branch,
                    'is_default' => true,
                ]);

                Commit::factory(5)->create(['repository_id' => $repository->id]);

                $pullRequests = PullRequest::factory(3)->create(['repository_id' => $repository->id]);

                ActivityLogEntry::create([
                    'team_id' => $team->id,
                    'user_id' => $owner->id,
                    'action' => 'repository.imported',
                    'subject_type' => Repository::class,
                    'subject_id' => $repository->id,
                    'properties' => ['name' => $repository->full_name],
                    'created_at' => now()->subDays(3)->addHours($index),
                ]);

                foreach (['code_review', 'security', 'quality', 'tech_debt'] as $i => $type) {
                    $analysis = $repository->analyses()->create([
                        'pull_request_id' => $pullRequests->first()->id,
                        'triggered_by_user_id' => $developer->id,
                        'type' => $type,
                        'status' => 'completed',
                        'model_used' => 'gpt-4.1',
                        'started_at' => now()->subHours(2 + $i),
                        'completed_at' => now()->subHours(1 + $i)->subMinutes(30),
                    ]);

                    AnalysisFinding::factory(4)->create(['analysis_id' => $analysis->id]);

                    $analysis->metrics()->create([
                        'metric_key' => 'debt_score',
                        'metric_value' => ['value' => fake()->randomFloat(2, 0, 100)],
                    ]);

                    ActivityLogEntry::create([
                        'team_id' => $team->id,
                        'user_id' => $developer->id,
                        'action' => 'analysis.completed',
                        'subject_type' => \App\Models\Analysis::class,
                        'subject_id' => $analysis->id,
                        'properties' => ['repository' => $repository->name, 'type' => $type],
                        'created_at' => $analysis->completed_at,
                    ]);
                }

                DocumentationArtifact::factory()->create([
                    'repository_id' => $repository->id,
                    'type' => 'readme',
                ]);

                $report = Report::factory()->create([
                    'team_id' => $repository->team_id,
                    'repository_id' => $repository->id,
                    'generated_by_user_id' => $repository->team->owner_id,
                ]);

                ActivityLogEntry::create([
                    'team_id' => $team->id,
                    'user_id' => $owner->id,
                    'action' => 'report.generated',
                    'subject_type' => Report::class,
                    'subject_id' => $report->id,
                    'properties' => ['title' => $report->title],
                    'created_at' => $report->generated_at,
                ]);
            });

        ReportSchedule::factory()->create(['team_id' => $team->id, 'repository_id' => null]);
    }

    private function assignTeamRoles(Team $team, User $owner, User $developer): void
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId($team->id);

        $owner->unsetRelation('roles');
        $developer->unsetRelation('roles');

        $ownerRole = Role::findOrCreate('owner', 'web');
        $developerRole = Role::findOrCreate('developer', 'web');

        $ownerRole->syncPermissions(Permission::all());
        $developerRole->syncPermissions([
            'repositories.view', 'repositories.analyze', 'reports.view', 'reports.export',
        ]);

        $owner->assignRole($ownerRole);
        $developer->assignRole($developerRole);
    }
}
