<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name', 'email', 'password', 'avatar_url', 'github_id', 'current_team_id',
    'theme_preference', 'preferred_ai_model', 'include_source_in_analysis',
    'notify_analysis_complete', 'notify_sync_complete', 'notify_github_connected', 'notify_on_errors',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    public const AI_MODELS = ['gpt-4o-mini', 'gpt-4o'];

    public const THEMES = ['light', 'dark', 'system'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'include_source_in_analysis' => 'boolean',
            'notify_analysis_complete' => 'boolean',
            'notify_sync_complete' => 'boolean',
            'notify_github_connected' => 'boolean',
            'notify_on_errors' => 'boolean',
        ];
    }

    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    public function githubConnections(): HasMany
    {
        return $this->hasMany(GithubConnection::class);
    }

    public function latestGithubConnection(): ?GithubConnection
    {
        return $this->githubConnections()->latest('connected_at')->first();
    }

    public function triggeredAnalyses(): HasMany
    {
        return $this->hasMany(Analysis::class, 'triggered_by_user_id');
    }
}
