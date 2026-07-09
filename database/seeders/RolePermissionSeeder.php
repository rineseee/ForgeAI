<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Global permission catalogue. Roles are created per-team (Spatie teams
     * feature) at team-creation time, not here — see DemoDataSeeder /
     * Domain\Auth\Actions\CreateTeam for the role-per-team assignment.
     */
    public function run(): void
    {
        $permissions = [
            'team.manage',
            'repositories.view',
            'repositories.manage',
            'repositories.analyze',
            'reports.view',
            'reports.export',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }
}
