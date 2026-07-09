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
        Schema::create('analysis_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analysis_id')->constrained()->cascadeOnDelete();
            $table->string('category'); // code_quality|security|performance|architecture|documentation|technical_debt
            $table->unsignedTinyInteger('score');
            $table->text('explanation');
            $table->json('problems');
            $table->json('recommendations');
            $table->json('improvement_examples');
            $table->timestamps();

            $table->unique(['analysis_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_categories');
    }
};
