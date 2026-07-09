<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained()->cascadeOnDelete();
            $table->string('sha');
            $table->string('author');
            $table->text('message');
            $table->timestamp('committed_at');
            $table->timestamps();

            $table->unique(['repository_id', 'sha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commits');
    }
};
