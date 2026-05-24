<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->string('label', 80)->default('Alamat Utama');
            $table->string('recipient_name', 120);
            $table->string('phone', 30)->nullable();
            $table->text('full_address');
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->string('zone', 4)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'is_primary']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('customer_address_id')->nullable()->after('customer_id')->constrained('customer_addresses')->nullOnDelete();
            $table->index('customer_address_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_address_id');
        });

        Schema::dropIfExists('customer_addresses');
    }
};
