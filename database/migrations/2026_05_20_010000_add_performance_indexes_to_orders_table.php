<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'idx_orders_status');
            $table->index(['customer_id', 'status'], 'idx_orders_customer_status');
            $table->index(['driver_id', 'status'], 'idx_orders_driver_status');
            $table->index('pickup_date', 'idx_orders_pickup_date');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_customer_status');
            $table->dropIndex('idx_orders_driver_status');
            $table->dropIndex('idx_orders_pickup_date');
        });
    }
};
