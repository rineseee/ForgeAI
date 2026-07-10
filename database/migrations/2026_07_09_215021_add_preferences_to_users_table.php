<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme_preference')->default('system')->after('avatar_url'); // light|dark|system
            $table->string('preferred_ai_model')->nullable()->after('theme_preference');
            $table->boolean('include_source_in_analysis')->default(true)->after('preferred_ai_model');
            $table->boolean('notify_analysis_complete')->default(true)->after('include_source_in_analysis');
            $table->boolean('notify_sync_complete')->default(true)->after('notify_analysis_complete');
            $table->boolean('notify_github_connected')->default(true)->after('notify_sync_complete');
            $table->boolean('notify_on_errors')->default(true)->after('notify_github_connected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'theme_preference', 'preferred_ai_model', 'include_source_in_analysis',
                'notify_analysis_complete', 'notify_sync_complete', 'notify_github_connected', 'notify_on_errors',
            ]);
        });
    }
};
