<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->timestamp('last_used_at')->nullable()->after('is_primary');
            $table->index('last_used_at');
        });
    }

    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropIndex(['last_used_at']);
            $table->dropColumn('last_used_at');
        });
    }
};
