<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_ratings', function (Blueprint $table) {
            $table->id();
            // Satu order maksimal punya satu rating — unique constraint mencegah
            // submit dobel dari refresh / klik dua kali.
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index('rating');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_ratings');
    }
};
