<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDriverAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_assign_driver_pickup_dan_membuat_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $driver = User::factory()->create(['role' => 'driver']);
        $customer = User::factory()->create(['role' => 'customer']);
        $layanan = $this->buatLayanan();

        $order = Order::create([
            'order_code' => 'ORD-ASSIGN-001',
            'customer_id' => $customer->id,
            'service_id' => $layanan->id,
            'address' => 'Jl. Test No. 10',
            'address_note' => null,
            'zone' => 'A',
            'pickup_cost' => 5000,
            'pickup_date' => now()->toDateString(),
            'pickup_time' => 'siang',
            'weight_estimate' => 2,
            'weight_actual' => null,
            'service_cost' => 14000,
            'discount' => 0,
            'total_cost' => 19000,
            'status' => 'menunggu',
            'notes' => null,
            'driver_id' => null,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.assign-driver', $order), [
            'driver_id' => $driver->id,
            'assignment_type' => 'pickup',
            'scheduled_date' => now()->toDateString(),
            'scheduled_time_start' => '09:00',
            'scheduled_time_end' => '11:00',
            'route_notes' => 'Prioritas pagi',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'driver_id' => $driver->id,
            'status' => 'dijemput',
        ]);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'status_code' => 'dijemput',
            'updated_by' => $admin->id,
        ]);
    }

    public function test_admin_bisa_assign_driver_delivery(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $driver = User::factory()->create(['role' => 'driver']);
        $customer = User::factory()->create(['role' => 'customer']);
        $layanan = $this->buatLayanan();

        $order = Order::create([
            'order_code' => 'ORD-ASSIGN-002',
            'customer_id' => $customer->id,
            'service_id' => $layanan->id,
            'address' => 'Jl. Test No. 11',
            'address_note' => null,
            'zone' => 'B',
            'pickup_cost' => 10000,
            'pickup_date' => now()->toDateString(),
            'pickup_time' => 'siang',
            'weight_estimate' => 3,
            'weight_actual' => null,
            'service_cost' => 21000,
            'discount' => 0,
            'total_cost' => 31000,
            'status' => 'siap',
            'notes' => null,
            'driver_id' => null,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.assign-driver', $order), [
            'driver_id' => $driver->id,
            'assignment_type' => 'delivery',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'driver_id' => $driver->id,
            'status' => 'dikirim',
        ]);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'status_code' => 'dikirim',
            'updated_by' => $admin->id,
        ]);
    }

    private function buatLayanan(): Service
    {
        return Service::create([
            'name' => 'Cuci Test Admin',
            'slug' => 'cuci-test-admin-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Layanan test admin',
            'is_active' => true,
        ]);
    }
}
