<?php

namespace App\Models;

use Database\Factories\GithubConnectionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'github_id', 'nickname', 'access_token', 'refresh_token', 'scopes', 'connected_at'])]
#[Hidden(['access_token', 'refresh_token'])]
class GithubConnection extends Model
{
    /** @use HasFactory<GithubConnectionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'scopes' => 'array',
            'connected_at' => 'datetime',
            'access_token' => 'encrypted',
            'refresh_token' => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
