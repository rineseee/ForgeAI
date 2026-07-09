<?php

namespace App\Models;

use Database\Factories\AnalysisMetricFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['analysis_id', 'metric_key', 'metric_value'])]
class AnalysisMetric extends Model
{
    /** @use HasFactory<AnalysisMetricFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'metric_value' => 'array',
        ];
    }

    public function analysis(): BelongsTo
    {
        return $this->belongsTo(Analysis::class);
    }
}
