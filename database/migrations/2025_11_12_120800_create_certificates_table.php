<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->timestamp('issued_at');
            $table->text('front_content');
            $table->text('back_content');
            $table->timestamps();

            $table->unique(['course_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
