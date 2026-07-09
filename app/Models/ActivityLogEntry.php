<?php

namespace App\Models;

use Database\Factories\ActivityLogEntryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['team_id', 'user_id', 'action', 'subject_type', 'subject_id', 'properties'])]
class ActivityLogEntry extends Model
{
    /** @use HasFactory<ActivityLogEntryFactory> */
    use HasFactory;

    protected $table = 'activity_logs';

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
