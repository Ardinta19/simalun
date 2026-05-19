<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();     // AL-20240512-001
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('service_id')->constrained('services');

            // Alamat
            $table->text('address');
            $table->string('address_note')->nullable();   // patokan
            $table->string('zone')->default('A');          // A, B, C
            $table->integer('pickup_cost');                // biaya jemput per zone

            // Jadwal
            $table->date('pickup_date');
            $table->enum('pickup_time', ['pagi', 'siang', 'sore']);

            // Berat & Biaya
            $table->decimal('weight_estimate', 5, 1);     // estimasi berat kg
            $table->decimal('weight_actual', 5, 1)->nullable(); // berat aktual setelah ditimbang
            $table->integer('service_cost');              // harga layanan × berat
            $table->integer('discount')->default(0);      // diskon promo
            $table->integer('total_cost');                // total akhir

            // Status tracking (SCP-04)
            $table->enum('status', [
                'menunggu',     // order masuk, belum ada driver
                'dijemput',     // driver sudah ditugaskan, dalam perjalanan jemput
                'dicuci',       // sedang dicuci
                'disetrika',    // sedang disetrika
                'siap',         // selesai, siap diantar
                'dikirim',      // driver sedang mengantar
                'selesai',      // terima di tangan customer
                'dibatalkan',
            ])->default('menunggu');

            // Pembayaran (SCP-06)
            $table->enum('payment_method', ['cod'])->default('cod');
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};