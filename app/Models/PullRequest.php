<?php

namespace App\Models;

use Database\Factories\PullRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'repository_id', 'github_pr_number', 'title', 'author', 'status',
    'base_branch', 'head_branch', 'opened_at', 'merged_at',
])]
class PullRequest extends Model
{
    /** @use HasFactory<PullRequestFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'merged_at' => 'datetime',
        ];
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(Analysis::class);
    }
}
