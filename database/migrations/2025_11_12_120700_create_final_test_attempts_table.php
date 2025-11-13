<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_test_attempts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_test_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->boolean('passed')->default(false);
            $table->timestamp('attempted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_test_attempts');
    }
};
