<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pull_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('github_pr_number');
            $table->string('title');
            $table->string('author');
            $table->string('status')->default('open'); // open|merged|closed
            $table->string('base_branch');
            $table->string('head_branch');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('merged_at')->nullable();
            $table->timestamps();

            $table->unique(['repository_id', 'github_pr_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pull_requests');
    }
};
