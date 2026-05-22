<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWalkInOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_buat_order_walkin_dengan_item_satuan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $kategoriKiloan = ServiceCategory::create([
            'name' => 'Kiloan',
            'pricing_model' => 'per_kg',
            'is_active' => true,
        ]);

        $kategoriItem = ServiceCategory::create([
            'name' => 'Satuan',
            'pricing_model' => 'per_item',
            'is_active' => true,
        ]);

        $kgService = Service::create([
            'name' => 'Cuci Kiloan',
            'slug' => 'cuci-kiloan-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 8000,
            'unit_type' => 'kg',
            'price_per_kg' => 8000,
            'estimated_hours' => 24,
            'description' => 'Kiloan',
            'service_category_id' => $kategoriKiloan->id,
            'is_active' => true,
        ]);

        $itemService = Service::create([
            'name' => 'Bedcover Jumbo',
            'slug' => 'bedcover-jumbo-'.uniqid(),
            'pricing_model' => 'per_item',
            'unit_price' => 25000,
            'unit_type' => 'item',
            'price_per_kg' => 25000,
            'estimated_hours' => 24,
            'description' => 'Item',
            'service_category_id' => $kategoriItem->id,
            'is_active' => true,
        ]);

        $itemServiceSatuKategoriKilo = Service::create([
            'name' => 'Sepatu Sneakers',
            'slug' => 'sepatu-sneakers-'.uniqid(),
            'pricing_model' => 'per_item',
            'unit_price' => 30000,
            'unit_type' => 'item',
            'price_per_kg' => 30000,
            'estimated_hours' => 24,
            'description' => 'Item Kiloan',
            'service_category_id' => $kategoriKiloan->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.walk-in.store'), [
            'customer_name' => 'Walk In Test',
            'customer_phone' => '08123',
            'service_category_id' => $kategoriKiloan->id,
            'service_id' => $kgService->id,
            'weight_estimate' => 2,
            'pickup_time' => 'siang',
            'item_lines' => [
                ['service_id' => $itemServiceSatuKategoriKilo->id, 'qty' => 2],
            ],
            'notes' => 'test walkin',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 2);
        $this->assertDatabaseHas('order_items', [
            'service_id' => $kgService->id,
            'weight_kg' => 2.0,
        ]);
        $this->assertDatabaseHas('order_items', [
            'service_id' => $itemServiceSatuKategoriKilo->id,
            'qty' => 2,
        ]);

        // Income belum dicatat di walk-in: order dibuat dengan status 'dicuci',
        // pemasukan baru tercatat saat order ditandai 'selesai' (lihat
        // FinanceController::recordIncomeFromOrder).
        $this->assertDatabaseCount('finance_entries', 0);
    }

    public function test_walkin_gagal_jika_kategori_tidak_cocok_dengan_layanan_utama(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $kategoriKiloan = ServiceCategory::create([
            'name' => 'Kiloan',
            'pricing_model' => 'per_kg',
            'is_active' => true,
        ]);

        $kategoriLain = ServiceCategory::create([
            'name' => 'Karpet',
            'pricing_model' => 'per_item',
            'is_active' => true,
        ]);

        $kgService = Service::create([
            'name' => 'Cuci Kiloan Pro',
            'slug' => 'cuci-kiloan-pro-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 9000,
            'unit_type' => 'kg',
            'price_per_kg' => 9000,
            'estimated_hours' => 24,
            'description' => 'Kiloan Pro',
            'service_category_id' => $kategoriKiloan->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->from(route('admin.orders'))->post(route('admin.orders.walk-in.store'), [
            'customer_name' => 'Walk In Mismatch',
            'service_category_id' => $kategoriLain->id,
            'service_id' => $kgService->id,
            'weight_estimate' => 2,
            'pickup_time' => 'siang',
        ]);

        $response->assertRedirect(route('admin.orders'));
        $response->assertSessionHasErrors(['service_category_id']);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_walkin_sukses_jika_kategori_cocok_dengan_layanan_utama(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $kategoriKiloan = ServiceCategory::create([
            'name' => 'Kiloan',
            'pricing_model' => 'per_kg',
            'is_active' => true,
        ]);

        $kgService = Service::create([
            'name' => 'Cuci Kiloan Reguler',
            'slug' => 'cuci-kiloan-reguler-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 8000,
            'unit_type' => 'kg',
            'price_per_kg' => 8000,
            'estimated_hours' => 24,
            'description' => 'Kiloan',
            'service_category_id' => $kategoriKiloan->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.walk-in.store'), [
            'customer_name' => 'Walk In Match',
            'service_category_id' => $kategoriKiloan->id,
            'service_id' => $kgService->id,
            'weight_estimate' => 1.5,
            'pickup_time' => 'pagi',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', [
            'service_id' => $kgService->id,
            'weight_estimate' => 1.5,
        ]);
    }
}
