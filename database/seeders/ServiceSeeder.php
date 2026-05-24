<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cuci Saja',
                'slug' => 'cuci-saja',
                'pricing_model' => 'per_kg',
                'unit_price' => 5000,
                'unit_type' => 'kg',
                'price_per_kg' => 5000,
                'estimated_hours' => 48,
                'description' => 'Cuci tanpa setrika.',
                'is_active' => true,
            ],
            [
                'name' => 'Cuci + Setrika',
                'slug' => 'cuci-setrika',
                'pricing_model' => 'per_kg',
                'unit_price' => 7000,
                'unit_type' => 'kg',
                'price_per_kg' => 7000,
                'estimated_hours' => 48,
                'description' => 'Paket reguler harian.',
                'is_active' => true,
            ],
            [
                'name' => 'Express 1 Hari',
                'slug' => 'express-1-hari',
                'pricing_model' => 'per_kg',
                'unit_price' => 10000,
                'unit_type' => 'kg',
                'price_per_kg' => 10000,
                'estimated_hours' => 24,
                'description' => 'Selesai dalam 24 jam.',
                'is_active' => true,
            ],
            [
                'name' => 'Jas / Kemeja',
                'slug' => 'jas-kemeja',
                'pricing_model' => 'per_item',
                'unit_price' => 20000,
                'unit_type' => 'item',
                'price_per_kg' => 20000,
                'estimated_hours' => 48,
                'description' => 'Layanan satuan per item.',
                'is_active' => true,
            ],
            [
                'name' => 'Bedcover Kecil',
                'slug' => 'bedcover-kecil',
                'pricing_model' => 'per_item',
                'unit_price' => 15000,
                'unit_type' => 'item',
                'price_per_kg' => 15000,
                'estimated_hours' => 48,
                'description' => 'Layanan satuan per item.',
                'is_active' => true,
            ],
            [
                'name' => 'Bedcover Sedang',
                'slug' => 'bedcover-sedang',
                'pricing_model' => 'per_item',
                'unit_price' => 20000,
                'unit_type' => 'item',
                'price_per_kg' => 20000,
                'estimated_hours' => 48,
                'description' => 'Layanan satuan per item.',
                'is_active' => true,
            ],
            [
                'name' => 'Bedcover Jumbo',
                'slug' => 'bedcover-jumbo',
                'pricing_model' => 'per_item',
                'unit_price' => 30000,
                'unit_type' => 'item',
                'price_per_kg' => 30000,
                'estimated_hours' => 48,
                'description' => 'Layanan satuan per item.',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service);
        }
    }
}
