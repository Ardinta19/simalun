<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Drop kolom-kolom users yang udah lama gak dipakai di code:
 *
 *  - no_hp (varchar 20, unique) — peninggalan migration awal yang
 *    rencananya dipakai login. Sekarang login pakai kolom `phone`
 *    (ditambah di migration 2026_05_11_150420). no_hp gak pernah
 *    di-write atau di-read.
 *
 *  - zona_driver — placeholder untuk feature 'driver per zona' yang
 *    gak jadi diimplementasikan. Driver assignment sekarang via
 *    DriverAssigner (round-robin / load-based).
 *
 *  - alamat — duplikat data dengan customer_addresses table. Cuma
 *    di-write 1× di RegisterController, gak pernah dibaca. Sumber
 *    truth alamat customer adalah customer_addresses (yang juga
 *    di-create di flow register).
 *
 * Idempotent: cek dulu kolom & index ada sebelum drop, supaya migration
 * gak crash kalau dijalanin berulang atau di environment yang udah
 * pernah dropped manual.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop unique index dulu sebelum drop column (MySQL strict).
            if ($this->indexExists('users', 'users_no_hp_unique')) {
                $table->dropUnique('users_no_hp_unique');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['no_hp', 'zona_driver', 'alamat'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp', 20)->unique()->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'zona_driver')) {
                $table->string('zona_driver')->nullable()->after('role');
            }
            if (! Schema::hasColumn('users', 'alamat')) {
                $table->string('alamat')->nullable()->after('role');
            }
        });
    }

    /**
     * Cek index keberadaan via information_schema. Kompatibel dengan MySQL
     * dan SQLite (untuk testing).
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            $rows = Schema::getConnection()->select(
                "SELECT name FROM sqlite_master WHERE type='index' AND name=?",
                [$indexName]
            );

            return count($rows) > 0;
        }

        $rows = Schema::getConnection()->select(
            'SHOW INDEX FROM '.$table.' WHERE Key_name = ?',
            [$indexName]
        );

        return count($rows) > 0;
    }
};
