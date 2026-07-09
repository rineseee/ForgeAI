<?php

namespace App\Models;

use Database\Factories\ReportScheduleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['team_id', 'repository_id', 'frequency', 'recipients', 'last_run_at', 'is_active'])]
class ReportSchedule extends Model
{
    /** @use HasFactory<ReportScheduleFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'recipients' => 'array',
            'last_run_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
}
