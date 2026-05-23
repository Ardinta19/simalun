<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Seed dua kategori baseline (Kiloan, Satuan) lalu auto-link service
     * existing yang belum punya kategori berdasarkan pricing_model. Aman
     * dijalankan ulang — pakai firstOrCreate + filter null.
     */
    public function run(): void
    {
        $kiloan = ServiceCategory::firstOrCreate(
            ['name' => 'Kiloan'],
            [
                'pricing_model' => 'per_kg',
                'description' => 'Layanan dihitung per kilogram.',
                'is_active' => true,
            ]
        );

        $satuan = ServiceCategory::firstOrCreate(
            ['name' => 'Satuan'],
            [
                'pricing_model' => 'per_item',
                'description' => 'Layanan dihitung per item (jas, bedcover, dll).',
                'is_active' => true,
            ]
        );

        Service::whereNull('service_category_id')
            ->where('pricing_model', 'per_kg')
            ->update(['service_category_id' => $kiloan->id]);

        Service::whereNull('service_category_id')
            ->where('pricing_model', 'per_item')
            ->update(['service_category_id' => $satuan->id]);
    }
}
