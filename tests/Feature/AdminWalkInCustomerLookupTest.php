<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Bug yang ditangkap di sini:
 *
 * 1. Sebelumnya `User::firstOrCreate(['phone' => null])` bikin SEMUA walk-in
 *    tanpa nomor merge ke 1 user (biasanya admin pertama yang phone-nya null).
 *
 * 2. Kalau nomor HP cocok dengan akun admin/driver yang aktif, order walk-in
 *    nempel ke akun staff — bukan customer baru. Itu bocor data finance &
 *    audit (admin tiba-tiba "punya pesanan").
 *
 * Test di sini ngeguard dua-duanya.
 */
class AdminWalkInCustomerLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_dua_walkin_tanpa_nomor_telpon_buat_dua_customer_terpisah(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = $this->buatLayanan();

        $this->actingAs($admin)->post(route('admin.orders.walk-in.store'), [
            'customer_name' => 'Walk-in A',
            'customer_phone' => null,
            'service_id' => $service->id,
            'weight_estimate' => 2,
            'pickup_time' => 'pagi',
        ]);

        $this->actingAs($admin)->post(route('admin.orders.walk-in.store'), [
            'customer_name' => 'Walk-in B',
            'customer_phone' => null,
            'service_id' => $service->id,
            'weight_estimate' => 1.5,
            'pickup_time' => 'siang',
        ]);

        // 2 user customer berbeda harus terbuat — walk-in B tidak boleh
        // nempel ke walk-in A.
        $this->assertDatabaseCount('users', 3); // admin + 2 walk-in
        $this->assertDatabaseHas('users', ['name' => 'Walk-in A', 'role' => 'customer']);
        $this->assertDatabaseHas('users', ['name' => 'Walk-in B', 'role' => 'customer']);

        $this->assertDatabaseCount('orders', 2);
    }

    public function test_walkin_dengan_nomor_milik_admin_ditolak_dengan_validation_error(): void
    {
        // Skenario: admin sudah login, di counter ngetik nomor HP customer
        // yang ternyata kebetulan sama dengan nomor admin sendiri. Order
        // tidak boleh nempel ke akun admin — harus tolak dengan pesan jelas.
        $admin = User::factory()->create([
            'role' => 'admin',
            'phone' => '081234567890',
        ]);
        $service = $this->buatLayanan();

        $response = $this->actingAs($admin)
            ->from(route('admin.walkin.form'))
            ->post(route('admin.orders.walk-in.store'), [
                'customer_name' => 'Pak Budi (Walk-in)',
                'customer_phone' => '081234567890',
                'service_id' => $service->id,
                'weight_estimate' => 3,
                'pickup_time' => 'siang',
            ]);

        $response->assertRedirect(route('admin.walkin.form'));
        $response->assertSessionHasErrors(['customer_phone']);

        // Tidak ada order kebuat sama sekali — admin harus pakai nomor lain
        $this->assertDatabaseCount('orders', 0);
        // Admin tetap admin (gak ada side effect ke role)
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_walkin_dengan_nomor_milik_driver_ditolak_dengan_validation_error(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create([
            'role' => 'driver',
            'phone' => '08987654321',
        ]);
        $service = $this->buatLayanan();

        $response = $this->actingAs($admin)
            ->from(route('admin.walkin.form'))
            ->post(route('admin.orders.walk-in.store'), [
                'customer_name' => 'Customer Coincidence',
                'customer_phone' => '08987654321',
                'service_id' => $service->id,
                'weight_estimate' => 2,
                'pickup_time' => 'pagi',
            ]);

        $response->assertSessionHasErrors(['customer_phone']);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_walkin_dengan_nomor_existing_customer_pakai_user_yang_sama(): void
    {
        // Behavior valid: customer existing yang sebelumnya pernah daftar
        // online, sekarang datang walk-in. Order baru harus nempel ke
        // akun customer existing (bukan bikin baru).
        $admin = User::factory()->create(['role' => 'admin']);

        $existingCustomer = User::factory()->create([
            'role' => 'customer',
            'name' => 'Bu Sari (Existing)',
            'phone' => '081111222333',
        ]);

        $service = $this->buatLayanan();

        $this->actingAs($admin)->post(route('admin.orders.walk-in.store'), [
            'customer_name' => 'Bu Sari (Walk-in)',
            'customer_phone' => '081111222333',
            'service_id' => $service->id,
            'weight_estimate' => 4,
            'pickup_time' => 'sore',
        ]);

        // User customer baru tidak terbuat — yang existing dipakai
        $this->assertSame(2, User::count()); // admin + customer existing

        $orderCustomer = Order::first()->customer;
        $this->assertSame($existingCustomer->id, $orderCustomer->id);
    }

    private function buatLayanan(): Service
    {
        $kategori = ServiceCategory::create([
            'name' => 'Kiloan',
            'pricing_model' => 'per_kg',
            'is_active' => true,
        ]);

        return Service::create([
            'name' => 'Cuci Kiloan Walk-in',
            'slug' => 'cuci-walkin-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Layanan walk-in test',
            'service_category_id' => $kategori->id,
            'is_active' => true,
        ]);
    }
}
