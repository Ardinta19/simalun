<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Dipakai DriverAssigner mode 'round_robin': driver yang
            // last_assigned_at-nya paling lama atau null akan dipilih duluan.
            $table->timestamp('last_assigned_at')->nullable()->after('is_active');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Channel pembayaran aktual saat COD (cash/transfer/qris).
            // Berbeda dari payment_method yang masih 'cod' sebagai metode
            // umum — channel ini ngisi rincian buat rekonsiliasi finance.
            $table->string('payment_channel', 20)->nullable()->after('is_paid');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_assigned_at');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_channel');
        });
    }
};
