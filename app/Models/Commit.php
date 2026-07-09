<?php

namespace App\Models;

use Database\Factories\CommitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['repository_id', 'sha', 'author', 'message', 'committed_at'])]
class Commit extends Model
{
    /** @use HasFactory<CommitFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'committed_at' => 'datetime',
        ];
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
}
