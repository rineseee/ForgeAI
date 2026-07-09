<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('github_repo_id');
            $table->string('name');
            $table->string('full_name');
            $table->string('default_branch')->default('main');
            $table->boolean('is_private')->default(false);
            $table->string('language')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'github_repo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};
