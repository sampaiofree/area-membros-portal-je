<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('favicon_path')->nullable();
            $table->string('default_course_cover_path')->nullable();
            $table->string('default_module_cover_path')->nullable();
            $table->string('default_lesson_cover_path')->nullable();
            $table->string('default_certificate_front_path')->nullable();
            $table->string('default_certificate_back_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
