<?php

namespace Tests\Feature;

use App\Models\CustomerAddress;
use App\Models\DriverAssignment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Coverage untuk wiring tabel driver_assignments — selama ini tabel
 * ada tapi gak diisi. PR ini nyambungin 3 jalur penulisan dan 1
 * jalur pencetakan ke nota.
 */
class DriverAssignmentHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_auto_assign_saat_store_membuat_row_pickup(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $driver = User::factory()->create(['role' => 'driver', 'is_active' => true]);
        $layanan = $this->buatLayanan();

        $response = $this->actingAs($customer)->post(route('order.store'), [
            'service_id' => $layanan->id,
            'weight_estimate' => 2.5,
            'zone' => 'A',
            'address' => 'Jl. Mawar No. 1',
            'address_note' => 'Pagar hijau',
            'pickup_date' => now()->addDay()->toDateString(),
            'pickup_time' => 'pagi',
            'notes' => null,
        ]);

        $response->assertRedirect();

        $order = Order::where('customer_id', $customer->id)->latest('id')->first();
        $this->assertNotNull($order);

        $this->assertDatabaseHas('driver_assignments', [
            'order_id' => $order->id,
            'driver_id' => $driver->id,
            'assignment_type' => 'pickup',
            'assignment_status' => 'assigned',
            'assigned_by' => $customer->id,
        ]);
    }

    public function test_admin_assign_pickup_membuat_row_pickup(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $driver = User::factory()->create(['role' => 'driver', 'is_active' => true]);
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, $this->buatLayanan(), 'menunggu', 'ORD-DA-001');

        $this->actingAs($admin)->post(route('admin.orders.assign-driver', $order), [
            'driver_id' => $driver->id,
            'assignment_type' => 'pickup',
        ])->assertRedirect();

        $this->assertDatabaseHas('driver_assignments', [
            'order_id' => $order->id,
            'driver_id' => $driver->id,
            'assignment_type' => 'pickup',
            'assignment_status' => 'assigned',
            'assigned_by' => $admin->id,
        ]);
    }

    public function test_admin_assign_delivery_membuat_row_delivery(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $driver = User::factory()->create(['role' => 'driver', 'is_active' => true]);
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, $this->buatLayanan(), 'siap', 'ORD-DA-002');

        $this->actingAs($admin)->post(route('admin.orders.assign-driver', $order), [
            'driver_id' => $driver->id,
            'assignment_type' => 'delivery',
        ])->assertRedirect();

        $this->assertDatabaseHas('driver_assignments', [
            'order_id' => $order->id,
            'driver_id' => $driver->id,
            'assignment_type' => 'delivery',
            'assignment_status' => 'assigned',
            'assigned_by' => $admin->id,
        ]);
    }

    public function test_driver_action_dicuci_mark_pickup_completed(): void
    {
        $driver = User::factory()->create(['role' => 'driver', 'is_active' => true]);
        $customer = User::factory()->create(['role' => 'customer']);
        $layanan = $this->buatLayanan();
        $order = $this->buatPesanan($customer, $layanan, 'dijemput', 'ORD-DA-003', $driver->id);

        // Simulasikan kondisi normal: pickup assignment sudah ada saat
        // status order dijemput (dibuat oleh assignDriver atau auto-assign).
        $assignment = DriverAssignment::create([
            'order_id' => $order->id,
            'driver_id' => $driver->id,
            'assignment_type' => 'pickup',
            'assignment_status' => 'assigned',
            'assigned_by' => $customer->id,
        ]);

        $this->actingAs($driver)->post(route('driver.orders.action', $order), [
            'status' => 'dicuci',
            'weight_actual' => 2.5,
        ])->assertRedirect();

        $assignment->refresh();
        $this->assertSame('completed', $assignment->assignment_status);
        $this->assertNotNull($assignment->actual_time);
    }

    public function test_driver_action_selesai_mark_delivery_completed(): void
    {
        $driver = User::factory()->create(['role' => 'driver', 'is_active' => true]);
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, $this->buatLayanan(), 'dikirim', 'ORD-DA-004', $driver->id);

        $assignment = DriverAssignment::create([
            'order_id' => $order->id,
            'driver_id' => $driver->id,
            'assignment_type' => 'delivery',
            'assignment_status' => 'assigned',
            'assigned_by' => $customer->id,
        ]);

        $this->actingAs($driver)->post(route('driver.orders.action', $order), [
            'status' => 'selesai',
            'payment_channel' => 'cash',
        ])->assertRedirect();

        $assignment->refresh();
        $this->assertSame('completed', $assignment->assignment_status);
        $this->assertNotNull($assignment->actual_time);
    }

    public function test_pickup_dan_delivery_bisa_driver_berbeda(): void
    {
        // Skenario nyata: driver A pickup, lalu off saat order siap, admin
        // assign driver B untuk delivery. Tabel harus rekam keduanya
        // independen (bukan overwrite).
        $admin = User::factory()->create(['role' => 'admin']);
        $driverA = User::factory()->create(['role' => 'driver', 'is_active' => true, 'name' => 'Pak Andi']);
        $driverB = User::factory()->create(['role' => 'driver', 'is_active' => true, 'name' => 'Mas Budi']);
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, $this->buatLayanan(), 'menunggu', 'ORD-DA-005');

        // Admin assign A untuk pickup
        $this->actingAs($admin)->post(route('admin.orders.assign-driver', $order), [
            'driver_id' => $driverA->id,
            'assignment_type' => 'pickup',
        ])->assertRedirect();

        // Order maju ke siap (workshop selesai). Update manual untuk
        // shortcut alur pengetesan.
        $order->refresh();
        $order->update(['status' => 'siap']);

        // Admin assign B untuk delivery
        $this->actingAs($admin)->post(route('admin.orders.assign-driver', $order), [
            'driver_id' => $driverB->id,
            'assignment_type' => 'delivery',
        ])->assertRedirect();

        $this->assertSame($driverA->id, $order->fresh()->pickupAssignment()?->driver_id);
        $this->assertSame($driverB->id, $order->fresh()->deliveryAssignment()?->driver_id);
        $this->assertSame(2, DriverAssignment::where('order_id', $order->id)->count());
    }

    public function test_nota_menampilkan_nama_kurir_pickup(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $driver = User::factory()->create(['role' => 'driver', 'is_active' => true, 'name' => 'Pak Andi Pickup']);
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, $this->buatLayanan(), 'dicuci', 'ORD-DA-006', $driver->id);

        DriverAssignment::create([
            'order_id' => $order->id,
            'driver_id' => $driver->id,
            'assignment_type' => 'pickup',
            'assignment_status' => 'completed',
            'actual_time' => now(),
            'assigned_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.receipt', $order));

        $response->assertOk();
        $response->assertSee('Dijemput oleh');
        $response->assertSee('Pak Andi Pickup');
    }

    private function buatLayanan(): Service
    {
        return Service::create([
            'name' => 'Cuci Test DA',
            'slug' => 'cuci-test-da-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Layanan test driver assignment',
            'is_active' => true,
        ]);
    }

    private function buatPesanan(
        User $customer,
        Service $layanan,
        string $status,
        string $kode,
        ?int $driverId = null
    ): Order {
        $alamat = CustomerAddress::create([
            'customer_id' => $customer->id,
            'label' => 'Rumah',
            'recipient_name' => $customer->name,
            'phone' => '08123456789',
            'full_address' => 'Jl. Test DA No. 1',
            'zone' => 'A',
            'is_primary' => true,
        ]);

        $order = Order::withoutEvents(fn () => Order::create([
            'order_code' => $kode,
            'customer_id' => $customer->id,
            'service_id' => $layanan->id,
            'customer_address_id' => $alamat->id,
            'address' => 'Jl. Test DA No. 1',
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
            'status' => $status,
            'notes' => null,
            'driver_id' => $driverId,
        ]));

        // OrderItem utama supaya finance recording (kalau ke-trigger
        // sampai 'selesai') punya base data yang konsisten.
        OrderItem::create([
            'order_id' => $order->id,
            'service_id' => $layanan->id,
            'item_description' => $layanan->name,
            'qty' => 1,
            'weight_kg' => 2,
            'unit_price' => $layanan->effective_unit_price,
            'line_total' => 14000,
        ]);

        return $order;
    }
}
