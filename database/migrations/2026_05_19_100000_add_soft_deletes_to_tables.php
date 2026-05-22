<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom deleted_at ke tabel inti supaya semua model yang pakai
     * SoftDeletes konsisten. Pakai pengecekan kolom dulu — di environment lama
     * (mis. yang sudah migrate dengan softDeletes() di create_users_table awal)
     * kolom ini sudah ada, jadi skip biar idempotent.
     */
    public function up(): void
    {
        $tables = ['users', 'orders', 'customer_addresses'];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'deleted_at')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->softDeletes();
            });
        }
    }

    public function down(): void
    {
        $tables = ['users', 'orders', 'customer_addresses'];

        foreach ($tables as $table) {
            if (! Schema::hasColumn($table, 'deleted_at')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropSoftDeletes();
            });
        }
    }
};
