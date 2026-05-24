<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // "Reguler", "Express", "Prioritas"
            $table->string('slug')->unique();          // "reguler", "express", "prioritas"
            $table->integer('price_per_kg');           // harga per kg dalam rupiah
            $table->integer('estimated_hours');        // estimasi selesai dalam jam
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
