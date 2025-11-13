<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_test_questions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_test_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('statement')->nullable();
            $table->unsignedInteger('position')->default(1);
            $table->unsignedInteger('weight')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_test_questions');
    }
};
