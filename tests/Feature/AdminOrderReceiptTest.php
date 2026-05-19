<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_membuka_halaman_cetak_resi_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $service = Service::create([
            'name' => 'Express 1 Hari',
            'slug' => 'express-1-hari-' . uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 12000,
            'unit_type' => 'kg',
            'price_per_kg' => 12000,
            'estimated_hours' => 24,
            'description' => 'Layanan express',
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_code' => 'ORD-RESI-001',
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'address' => 'Jl. Outlet 1',
            'address_note' => null,
            'zone' => 'A',
            'pickup_cost' => 0,
            'pickup_date' => now()->toDateString(),
            'pickup_time' => 'siang',
            'weight_estimate' => 3,
            'weight_actual' => null,
            'service_cost' => 36000,
            'discount' => 0,
            'total_cost' => 36000,
            'status' => 'menunggu',
            'notes' => 'walk-in',
            'driver_id' => null,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.receipt', $order));

        $response->assertOk();
        $response->assertSee('Resi Pesanan');
        $response->assertSee('ORD-RESI-001');
        $response->assertSee('Express 1 Hari');
    }
}
