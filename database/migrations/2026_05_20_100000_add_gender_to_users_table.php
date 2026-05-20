<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['L', 'P'])->nullable()->after('phone');
            }

            // Pastikan phone unik agar bisa dipakai sebagai identitas login.
            if (Schema::hasColumn('users', 'phone')) {
                try {
                    $table->unique('phone');
                } catch (\Throwable $e) {
                    // Index sudah ada, abaikan.
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }

            try {
                $table->dropUnique(['phone']);
            } catch (\Throwable $e) {
                // Abaikan jika index tidak ada.
            }
        });
    }
};
