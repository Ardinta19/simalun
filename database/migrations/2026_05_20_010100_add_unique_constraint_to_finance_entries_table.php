<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finance_entries', function (Blueprint $table) {
            // Prevent duplicate income records for the same order (TOCTOU race condition guard)
            $table->unique(
                ['order_id', 'entry_type', 'source_type'],
                'uniq_finance_order_type_source'
            );
        });
    }

    public function down(): void
    {
        Schema::table('finance_entries', function (Blueprint $table) {
            $table->dropUnique('uniq_finance_order_type_source');
        });
    }
};
