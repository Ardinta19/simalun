<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Bump `users.last_assigned_at` ke microsecond precision (6).
 *
 * Default `timestamp()` di Laravel cuma second-resolution. Round-robin
 * driver assignment yang ngeurutin pakai kolom ini jadi gak deterministik
 * kalau dua mark terjadi di detik yang sama (race antara order yang
 * dibikin bersamaan, atau test yang loop cepat). Tie-breaker fallback
 * ke `id` bisa bias ke driver pertama di DB.
 *
 * Solusi: precision 6 → microsecond. Distribusi sub-second yang akurat.
 *
 * SQLite (test environment) gak punya concept precision di timestamp,
 * Laravel handle sebagai TEXT dengan format string yang lebih panjang
 * — cast `'datetime'` di model otomatis parse-nya benar.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite gak bisa ALTER COLUMN type. Tapi karena dynamic
            // typing-nya, cukup andalkan Laravel cast 'datetime' di
            // model — string format dengan microsecond tetap kebaca.
            // No-op for SQLite.
            return;
        }

        // MySQL & Postgres: alter column ke precision 6
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY last_assigned_at TIMESTAMP(6) NULL DEFAULT NULL');

            return;
        }

        // Postgres
        DB::statement('ALTER TABLE users ALTER COLUMN last_assigned_at TYPE TIMESTAMP(6)');
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY last_assigned_at TIMESTAMP NULL DEFAULT NULL');

            return;
        }

        DB::statement('ALTER TABLE users ALTER COLUMN last_assigned_at TYPE TIMESTAMP(0)');
    }
};
