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
        Schema::table('repositories', function (Blueprint $table) {
            $table->text('description')->nullable()->after('full_name');
            $table->string('owner')->nullable()->after('description');
            $table->string('visibility')->default('public')->after('is_private');
            $table->string('html_url')->nullable()->after('visibility');
            $table->timestamp('github_updated_at')->nullable()->after('html_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->dropColumn(['description', 'owner', 'visibility', 'html_url', 'github_updated_at']);
        });
    }
};
