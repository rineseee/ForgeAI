<?php

namespace App\Models;

use Database\Factories\AnalysisFindingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'analysis_id', 'file_path', 'line_start', 'line_end', 'severity', 'category',
    'title', 'description', 'suggestion', 'ai_confidence', 'status',
])]
class AnalysisFinding extends Model
{
    /** @use HasFactory<AnalysisFindingFactory> */
    use HasFactory;

    public function analysis(): BelongsTo
    {
        return $this->belongsTo(Analysis::class);
    }
}
