<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_test_answers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_test_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('final_test_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('final_test_question_option_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_test_answers');
    }
};
