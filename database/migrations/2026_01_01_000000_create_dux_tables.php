<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dux_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('balance')->default(0);
            $table->timestamps();
        });

        Schema::create('dux_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('direction'); // earn | spend
            $table->unsignedBigInteger('amount');
            $table->string('type')->default('fixed'); // fixed | per_score
            $table->string('model')->comment('lesson_completed, test_passed, enrollment, certificate, retest etc');
            $table->json('conditions')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('dux_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('dux_wallets')->cascadeOnDelete();
            $table->foreignId('rule_id')->nullable()->constrained('dux_rules')->nullOnDelete();
            $table->string('direction');
            $table->bigInteger('amount');
            $table->string('source')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['wallet_id', 'created_at']);
        });

        Schema::create('dux_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('duxes');
            $table->unsignedBigInteger('price_cents');
            $table->string('currency', 8)->default('BRL');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dux_packs');
        Schema::dropIfExists('dux_transactions');
        Schema::dropIfExists('dux_rules');
        Schema::dropIfExists('dux_wallets');
    }
};
