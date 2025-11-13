<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table): void {
            $table->string('public_token')->nullable()->unique()->after('number');
        });

        DB::table('certificates')->select('id')->orderBy('id')->chunkById(100, function ($certificates): void {
            foreach ($certificates as $certificate) {
                DB::table('certificates')
                    ->where('id', $certificate->id)
                    ->update(['public_token' => (string) Str::uuid()]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table): void {
            $table->dropColumn('public_token');
        });
    }
};
