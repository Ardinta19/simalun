<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel users SIMALUN.
 * Mendukung 3 role: customer, admin, driver
 * Login bisa pakai email ATAU no_hp
 *
 * Jalankan: php artisan migrate
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            // Login bisa pakai email ATAU no_hp
            $table->string('email')->unique()->nullable();
            $table->string('no_hp', 20)->unique()->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Role: customer | admin | driver
            $table->enum('role', ['customer', 'admin', 'driver'])->default('customer');

            // Field tambahan per role
            $table->string('alamat')->nullable();          // untuk customer
            $table->string('zona_driver')->nullable();     // untuk driver (zona antar)
            $table->boolean('is_active')->default(true);  // bisa nonaktifkan akun

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // supaya data tidak hilang permanen

            // Pastikan minimal salah satu diisi (enforced di app level)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};