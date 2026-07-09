<?php

namespace App\Models;

use Database\Factories\AnalysisCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'analysis_id', 'category', 'score', 'explanation',
    'problems', 'recommendations', 'improvement_examples',
])]
class AnalysisCategory extends Model
{
    /** @use HasFactory<AnalysisCategoryFactory> */
    use HasFactory;

    public const CATEGORIES = [
        'code_quality',
        'security',
        'performance',
        'architecture',
        'documentation',
        'technical_debt',
    ];

    public const LABELS = [
        'code_quality' => 'Code Quality',
        'security' => 'Security',
        'performance' => 'Performance',
        'architecture' => 'Architecture',
        'documentation' => 'Documentation',
        'technical_debt' => 'Technical Debt',
    ];

    protected function casts(): array
    {
        return [
            'problems' => 'array',
            'recommendations' => 'array',
            'improvement_examples' => 'array',
        ];
    }

    public function analysis(): BelongsTo
    {
        return $this->belongsTo(Analysis::class);
    }
}
