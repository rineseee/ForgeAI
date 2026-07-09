<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // openai|github
            $table->text('encrypted_key');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['team_id', 'provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
