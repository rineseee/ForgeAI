<?php

namespace App\Models;

use Database\Factories\DocumentationArtifactFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['repository_id', 'type', 'content', 'generated_at', 'is_published'])]
class DocumentationArtifact extends Model
{
    /** @use HasFactory<DocumentationArtifactFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
}
