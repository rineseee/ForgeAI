<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->index(['team_id', 'last_synced_at']);
        });

        Schema::table('commits', function (Blueprint $table) {
            $table->index(['repository_id', 'committed_at']);
        });

        Schema::table('github_connections', function (Blueprint $table) {
            $table->index(['user_id', 'connected_at']);
        });
    }

    public function down(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->dropIndex(['team_id', 'last_synced_at']);
        });

        Schema::table('commits', function (Blueprint $table) {
            $table->dropIndex(['repository_id', 'committed_at']);
        });

        Schema::table('github_connections', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'connected_at']);
        });
    }
};
