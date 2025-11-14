<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->string('certificate_payment_url')->nullable()->after('promo_video_url');
            $table->decimal('certificate_price', 10, 2)->nullable()->after('certificate_payment_url');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->dropColumn(['certificate_payment_url', 'certificate_price']);
        });
    }
};
