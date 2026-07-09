<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analysis_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analysis_id')->constrained()->cascadeOnDelete();
            $table->string('metric_key');
            $table->json('metric_value');
            $table->timestamps();

            $table->unique(['analysis_id', 'metric_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analysis_metrics');
    }
};
