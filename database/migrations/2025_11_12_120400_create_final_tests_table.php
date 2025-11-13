<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_tests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->unsignedInteger('passing_score')->default(70);
            $table->unsignedInteger('max_attempts')->default(1);
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_tests');
    }
};
