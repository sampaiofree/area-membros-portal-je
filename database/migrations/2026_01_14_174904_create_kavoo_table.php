<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kavoo', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('customer_first_name')->nullable();
            $table->string('customer_last_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->unsignedBigInteger('item_product_id')->nullable();
            $table->string('item_product_name')->nullable();
            $table->string('transaction_code')->nullable()->unique();
            $table->string('status_code')->nullable();

            $table->json('customer')->nullable();
            $table->json('address')->nullable();
            $table->json('items')->nullable();
            $table->json('affiliate')->nullable();
            $table->json('transaction')->nullable();
            $table->json('payment')->nullable();
            $table->json('commissions')->nullable();
            $table->json('shipping')->nullable();
            $table->json('links')->nullable();
            $table->json('tracking')->nullable();
            $table->json('status')->nullable();
            $table->json('recurrence')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kavoo');
    }
};
