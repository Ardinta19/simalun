<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('pricing_model', ['per_kg', 'per_item'])->default('per_kg')->after('slug');
            $table->integer('unit_price')->nullable()->after('price_per_kg');
            $table->enum('unit_type', ['kg', 'item'])->default('kg')->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['pricing_model', 'unit_price', 'unit_type']);
        });
    }
};
