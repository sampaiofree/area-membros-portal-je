<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('dux_transactions');
        Schema::dropIfExists('dux_rules');
        Schema::dropIfExists('dux_wallets');
        Schema::dropIfExists('dux_packs');
    }

    public function down(): void
    {
        // Dux foi removido; rollback intencionalmente no-op.
    }
};
