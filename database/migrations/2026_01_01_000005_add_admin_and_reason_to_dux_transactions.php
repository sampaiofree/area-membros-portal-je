<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dux_transactions', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->after('wallet_id')->constrained('users')->nullOnDelete();
            $table->string('reason')->nullable()->after('source');
        });
    }

    public function down(): void
    {
        Schema::table('dux_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admin_id');
            $table->dropColumn('reason');
        });
    }
};
