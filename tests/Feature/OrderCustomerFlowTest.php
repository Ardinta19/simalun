<?php

namespace Tests\Feature;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCustomerFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_daftar_pesanan_customer_bisa_diakses(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get(route('customer.orders'));

        $response->assertOk();
    }

    public function test_filter_status_aktif_di_daftar_pesanan(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $layanan = $this->buatLayanan();

        $this->buatPesanan($customer, $layanan, 'menunggu', 'ORD-TEST-001');
        $this->buatPesanan($customer, $layanan, 'selesai', 'ORD-TEST-002');

        $response = $this->actingAs($customer)->get(route('customer.orders', ['filter' => 'aktif']));

        $response->assertOk();
        $response->assertSee('ORD-TEST-001');
        $response->assertDontSee('ORD-TEST-002');
    }

    public function test_filter_status_selesai_di_daftar_pesanan(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $layanan = $this->buatLayanan();

        $this->buatPesanan($customer, $layanan, 'dibatalkan', 'ORD-TEST-003');
        $this->buatPesanan($customer, $layanan, 'selesai', 'ORD-TEST-004');

        $response = $this->actingAs($customer)->get(route('customer.orders', ['filter' => 'selesai']));

        $response->assertOk();
        $response->assertSee('ORD-TEST-004');
        $response->assertDontSee('ORD-TEST-003');
    }

    public function test_store_order_menyimpan_customer_address_id_dari_alamat_tersimpan(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $layanan = $this->buatLayanan();

        $alamat = CustomerAddress::create([
            'customer_id' => $customer->id,
            'label' => 'Rumah',
            'recipient_name' => $customer->name,
            'phone' => '08123456789',
            'full_address' => 'Jl. Mawar No. 7',
            'zone' => 'B',
            'is_primary' => true,
            'notes' => 'Pagar hitam',
        ]);

        $response = $this->actingAs($customer)->post(route('order.store'), [
            'service_id' => $layanan->id,
            'weight_estimate' => 3,
            'zone' => 'B',
            'address' => 'Jl. Mawar No. 7',
            'address_note' => 'Pagar hitam',
            'customer_address_id' => $alamat->id,
            'pickup_date' => now()->addDay()->toDateString(),
            'pickup_time' => 'siang',
            'notes' => 'Tolong cepat',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'customer_address_id' => $alamat->id,
            'service_id' => $layanan->id,
        ]);
    }

    public function test_hapus_alamat_utama_akan_promote_alamat_lain_jadi_utama(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $alamatUtama = CustomerAddress::create([
            'customer_id' => $customer->id,
            'label' => 'Utama',
            'recipient_name' => $customer->name,
            'phone' => '0811111111',
            'full_address' => 'Jl. Utama No. 1',
            'zone' => 'A',
            'is_primary' => true,
            'last_used_at' => now()->subDays(3),
        ]);

        $alamatKedua = CustomerAddress::create([
            'customer_id' => $customer->id,
            'label' => 'Cadangan',
            'recipient_name' => $customer->name,
            'phone' => '0822222222',
            'full_address' => 'Jl. Cadangan No. 2',
            'zone' => 'B',
            'is_primary' => false,
            'last_used_at' => now(),
        ]);

        $response = $this->actingAs($customer)
            ->delete(route('customer.addresses.destroy', $alamatUtama));

        $response->assertRedirect();

        // CustomerAddress pakai SoftDeletes — yang dihapus adalah row dengan
        // deleted_at terisi, bukan hard delete.
        $this->assertSoftDeleted('customer_addresses', [
            'id' => $alamatUtama->id,
        ]);

        $this->assertDatabaseHas('customer_addresses', [
            'id' => $alamatKedua->id,
            'is_primary' => true,
        ]);
    }

    public function test_store_order_manual_pertama_otomatis_buat_customer_address(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $layanan = $this->buatLayanan();

        $response = $this->actingAs($customer)->post(route('order.store'), [
            'service_id' => $layanan->id,
            'weight_estimate' => 2,
            'zone' => 'C',
            'address' => 'Jl. Melati No. 9',
            'address_note' => 'Rumah pagar putih',
            'pickup_date' => now()->addDay()->toDateString(),
            'pickup_time' => 'pagi',
            'notes' => null,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('customer_addresses', [
            'customer_id' => $customer->id,
            'full_address' => 'Jl. Melati No. 9',
            'zone' => 'C',
            'is_primary' => true,
        ]);

        $order = Order::where('customer_id', $customer->id)->latest('id')->first();
        $this->assertNotNull($order?->customer_address_id);
    }

    public function test_pakai_alamat_tersimpan_memperbarui_last_used_at(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $layanan = $this->buatLayanan();

        $alamat = CustomerAddress::create([
            'customer_id' => $customer->id,
            'label' => 'Rumah',
            'recipient_name' => $customer->name,
            'phone' => '08123456789',
            'full_address' => 'Jl. Mawar No. 10',
            'zone' => 'A',
            'is_primary' => true,
            'notes' => 'Gerbang biru',
            'last_used_at' => now()->subDays(10),
        ]);

        $response = $this->actingAs($customer)->post(route('order.store'), [
            'service_id' => $layanan->id,
            'weight_estimate' => 4,
            'zone' => 'A',
            'address' => 'Jl. Mawar No. 10',
            'address_note' => 'Gerbang biru',
            'customer_address_id' => $alamat->id,
            'pickup_date' => now()->addDay()->toDateString(),
            'pickup_time' => 'sore',
            'notes' => 'Test update last_used_at',
        ]);

        $response->assertRedirect();

        $alamat->refresh();
        $this->assertNotNull($alamat->last_used_at);
        $this->assertTrue($alamat->last_used_at->greaterThan(now()->subMinutes(2)));
    }

    private function buatLayanan(): Service
    {
        return Service::create([
            'name' => 'Cuci Test',
            'slug' => 'cuci-test-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Layanan test',
            'is_active' => true,
        ]);
    }

    private function buatPesanan(User $customer, Service $layanan, string $status, string $kode): Order
    {
        return Order::create([
            'order_code' => $kode,
            'customer_id' => $customer->id,
            'service_id' => $layanan->id,
            'address' => 'Jl. Test No. 1',
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
            'payment_method' => 'cod',
            'is_paid' => false,
            'paid_at' => null,
            'notes' => null,
            'driver_id' => null,
        ]);
    }
}
