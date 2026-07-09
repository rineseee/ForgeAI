<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analysis_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analysis_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->unsignedInteger('line_start')->nullable();
            $table->unsignedInteger('line_end')->nullable();
            $table->string('severity'); // info|low|medium|high|critical
            $table->string('category');
            $table->string('title');
            $table->text('description');
            $table->text('suggestion')->nullable();
            $table->unsignedTinyInteger('ai_confidence')->nullable(); // 0-100
            $table->string('status')->default('open'); // open|resolved|ignored
            $table->timestamps();

            $table->index(['analysis_id', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analysis_findings');
    }
};
