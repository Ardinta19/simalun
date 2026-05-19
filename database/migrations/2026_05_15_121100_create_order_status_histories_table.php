<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('status_code', 40);
            $table->string('status_note')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('updated_at')->useCurrent();

            $table->index(['order_id', 'updated_at']);
            $table->index('status_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
