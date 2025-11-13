<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('final_test_attempts', function (Blueprint $table): void {
            $table->timestamp('started_at')->nullable()->after('passed');
            $table->timestamp('submitted_at')->nullable()->after('started_at');
        });
    }

    public function down(): void
    {
        Schema::table('final_test_attempts', function (Blueprint $table): void {
            $table->dropColumn(['started_at', 'submitted_at']);
        });
    }
};
