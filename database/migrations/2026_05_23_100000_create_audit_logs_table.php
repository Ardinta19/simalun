<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();

            // Polimorfik supaya satu tabel bisa nyimpen jejak dari banyak entitas
            // (Order, Voucher, Service, dst) tanpa nambah kolom FK per tipe.
            $table->string('auditable_type', 100)->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();

            $table->string('action', 60); // contoh: order.assign-driver, voucher.toggle
            $table->string('summary', 255)->nullable();

            // Snapshot before/after sebagai JSON. Bisa null kalau aksinya
            // bukan perubahan data (mis. sekadar 'view export').
            $table->json('before')->nullable();
            $table->json('after')->nullable();

            $table->ipAddress('ip')->nullable();
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
