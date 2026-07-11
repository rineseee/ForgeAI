<?php

namespace App\Models;

use Database\Factories\RepositoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'team_id', 'github_repo_id', 'name', 'full_name', 'description', 'owner',
    'default_branch', 'is_private', 'visibility', 'html_url', 'language',
    'last_synced_at', 'github_updated_at', 'archived_at',
])]
class Repository extends Model
{
    /** @use HasFactory<RepositoryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_private' => 'boolean',
            'last_synced_at' => 'datetime',
            'github_updated_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function belongsToTeam(?Team $team): bool
    {
        return $team !== null && $this->team_id === $team->id;
    }

    public function branches(): HasMany
    {
        return $this->hasMany(RepositoryBranch::class);
    }

    public function commits(): HasMany
    {
        return $this->hasMany(Commit::class);
    }

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }

    public function webhookEvents(): HasMany
    {
        return $this->hasMany(WebhookEvent::class);
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(Analysis::class);
    }

    public function documentationArtifacts(): HasMany
    {
        return $this->hasMany(DocumentationArtifact::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
