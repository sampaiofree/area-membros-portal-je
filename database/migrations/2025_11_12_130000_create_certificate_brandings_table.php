<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_brandings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('front_background_path')->nullable();
            $table->string('back_background_path')->nullable();
            $table->timestamps();

            $table->unique('course_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_brandings');
    }
};
