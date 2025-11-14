<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->string('default_logo_path')->nullable()->after('favicon_path');
            $table->string('default_logo_dark_path')->nullable()->after('default_logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropColumn(['default_logo_path', 'default_logo_dark_path']);
        });
    }
};
