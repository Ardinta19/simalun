<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->string('assignment_type', 20);
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time_start')->nullable();
            $table->time('scheduled_time_end')->nullable();
            $table->dateTime('actual_time')->nullable();
            $table->text('route_notes')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->unsignedInteger('transport_fee')->default(0);
            $table->string('assignment_status', 20)->default('assigned');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['driver_id', 'assignment_status']);
            $table->index(['order_id', 'assignment_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_assignments');
    }
};
