<?php

namespace App\Domain\Auth\Actions;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CreateUserWithTeam
{
    /**
     * Registers a user with their own team and makes them that team's Owner.
     * Every user belongs to at least one team, matching the team-scoped
     * role/permission model (config('permission.teams')).
     */
    public function handle(string $name, string $email, string $hashedPassword): User
    {
        return DB::transaction(function () use ($name, $email, $hashedPassword) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
            ]);

            $team = Team::create([
                'owner_id' => $user->id,
                'name' => $name."'s Team",
                'slug' => Str::slug($name).'-'.Str::random(6),
            ]);

            $user->update(['current_team_id' => $team->id]);
            $team->members()->syncWithoutDetaching([$user->id]);

            app(PermissionRegistrar::class)->setPermissionsTeamId($team->id);
            $ownerRole = Role::findOrCreate('owner', 'web');
            $ownerRole->syncPermissions(\Spatie\Permission\Models\Permission::all());
            $user->assignRole($ownerRole);

            return $user;
        });
    }
}
