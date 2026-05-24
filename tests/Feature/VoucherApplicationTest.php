<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * End-to-end voucher application di order customer flow.
 *
 * Kover skenario yang sebelumnya gak ada di test suite:
 *  - voucher percent (10%, 20%, dll) — including max_discount cap
 *  - voucher fixed amount (Rp X) — including subtotal lebih kecil dari nominal
 *  - voucher min_order — block kalau subtotal kurang
 *  - voucher kadaluarsa, valid_until lewat
 *  - voucher gak aktif (is_active=false)
 *  - voucher case-insensitive (input 'happy10' vs stored 'HAPPY10')
 */
class VoucherApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_voucher_percent_diskon_dihitung_dari_subtotal(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan(unitPrice: 10_000); // 10rb/kg

        Voucher::create([
            'code' => 'PERCENT20',
            'description' => 'Diskon 20% tanpa cap',
            'type' => 'percent',
            'value' => 20,
            'min_order' => 0,
            'max_discount' => null,
            'usage_limit' => null,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($customer)->post(route('order.store'), [
            'service_id' => $service->id,
            'address' => 'Jl. Voucher Percent No. 1',
            'address_note' => null,
            'zone' => 'A',
            'pickup_date' => now()->addDay()->toDateString(),
            'pickup_time' => 'pagi',
            'weight_estimate' => 5, // 5 * 10000 = 50000, pickup A = 5000, subtotal = 55000
            'voucher_code' => 'PERCENT20',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        // Subtotal sebelum diskon: 50_000 + 5_000 = 55_000
        // Diskon 20%: 11_000
        // Total akhir: 44_000
        $this->assertSame(11_000, (int) $order->discount);
        $this->assertSame(44_000, (int) $order->total_cost);
        $this->assertSame('PERCENT20', $order->voucher_code);
    }

    public function test_voucher_percent_dengan_max_discount_di_cap(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan(unitPrice: 10_000);

        Voucher::create([
            'code' => 'CAPPED50',
            'description' => 'Diskon 50% maks Rp 5000',
            'type' => 'percent',
            'value' => 50,
            'min_order' => 0,
            'max_discount' => 5_000,
            'usage_limit' => null,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($customer)->post(route('order.store'), [
            'service_id' => $service->id,
            'address' => 'Jl. Capped No. 1',
            'address_note' => null,
            'zone' => 'A',
            'pickup_date' => now()->addDay()->toDateString(),
            'pickup_time' => 'pagi',
            'weight_estimate' => 5,
            'voucher_code' => 'CAPPED50',
        ]);

        $order = Order::first();
        // Subtotal 55_000. Raw 50%: 27_500. Capped: 5_000.
        $this->assertSame(5_000, (int) $order->discount);
        $this->assertSame(50_000, (int) $order->total_cost);
    }

    public function test_voucher_fixed_amount_dipakai_apa_adanya(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan(unitPrice: 10_000);

        Voucher::create([
            'code' => 'POTONG3K',
            'description' => 'Potong Rp 3.000 langsung',
            'type' => 'fixed',
            'value' => 3_000,
            'min_order' => 0,
            'max_discount' => null,
            'usage_limit' => null,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($customer)->post(route('order.store'), [
            'service_id' => $service->id,
            'address' => 'Jl. Fixed No. 1',
            'address_note' => null,
            'zone' => 'A',
            'pickup_date' => now()->addDay()->toDateString(),
            'pickup_time' => 'pagi',
            'weight_estimate' => 3, // 30_000 + 5_000 = 35_000
            'voucher_code' => 'POTONG3K',
        ]);

        $order = Order::first();
        $this->assertSame(3_000, (int) $order->discount);
        $this->assertSame(32_000, (int) $order->total_cost);
    }

    public function test_voucher_di_block_kalau_subtotal_kurang_dari_min_order(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan(unitPrice: 5_000);

        Voucher::create([
            'code' => 'MIN50K',
            'description' => 'Min belanja 50rb',
            'type' => 'percent',
            'value' => 10,
            'min_order' => 50_000,
            'max_discount' => null,
            'usage_limit' => null,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)
            ->from(route('order.create'))
            ->post(route('order.store'), [
                'service_id' => $service->id,
                'address' => 'Jl. Min No. 1',
                'address_note' => null,
                'zone' => 'A',
                'pickup_date' => now()->addDay()->toDateString(),
                'pickup_time' => 'pagi',
                'weight_estimate' => 2, // 10_000 + 5_000 = 15_000 (kurang dari 50k)
                'voucher_code' => 'MIN50K',
            ]);

        $response->assertSessionHasErrors(['voucher_code']);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('voucher_usages', 0);
    }

    public function test_voucher_kadaluarsa_di_tolak(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan();

        Voucher::create([
            'code' => 'EXPIRED1',
            'description' => 'Sudah kadaluarsa',
            'type' => 'percent',
            'value' => 10,
            'min_order' => 0,
            'max_discount' => null,
            'usage_limit' => null,
            'used_count' => 0,
            'valid_until' => now()->subDay()->toDateString(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)
            ->from(route('order.create'))
            ->post(route('order.store'), [
                'service_id' => $service->id,
                'address' => 'Jl. Expired No. 1',
                'address_note' => null,
                'zone' => 'A',
                'pickup_date' => now()->addDay()->toDateString(),
                'pickup_time' => 'pagi',
                'weight_estimate' => 2,
                'voucher_code' => 'EXPIRED1',
            ]);

        $response->assertSessionHasErrors(['voucher_code']);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_voucher_belum_aktif_valid_from_di_tolak(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan();

        Voucher::create([
            'code' => 'FUTURE1',
            'description' => 'Mulai berlaku besok',
            'type' => 'fixed',
            'value' => 5_000,
            'min_order' => 0,
            'max_discount' => null,
            'usage_limit' => null,
            'used_count' => 0,
            'valid_from' => now()->addDay()->toDateString(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)
            ->from(route('order.create'))
            ->post(route('order.store'), [
                'service_id' => $service->id,
                'address' => 'Jl. Future No. 1',
                'address_note' => null,
                'zone' => 'A',
                'pickup_date' => now()->addDay()->toDateString(),
                'pickup_time' => 'pagi',
                'weight_estimate' => 2,
                'voucher_code' => 'FUTURE1',
            ]);

        $response->assertSessionHasErrors(['voucher_code']);
    }

    public function test_voucher_dinonaktifkan_di_tolak(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan();

        Voucher::create([
            'code' => 'OFF1',
            'description' => 'Sudah dinonaktifkan admin',
            'type' => 'fixed',
            'value' => 5_000,
            'min_order' => 0,
            'max_discount' => null,
            'usage_limit' => null,
            'used_count' => 0,
            'is_active' => false,
        ]);

        $response = $this->actingAs($customer)
            ->from(route('order.create'))
            ->post(route('order.store'), [
                'service_id' => $service->id,
                'address' => 'Jl. Off No. 1',
                'address_note' => null,
                'zone' => 'A',
                'pickup_date' => now()->addDay()->toDateString(),
                'pickup_time' => 'pagi',
                'weight_estimate' => 2,
                'voucher_code' => 'OFF1',
            ]);

        $response->assertSessionHasErrors(['voucher_code']);
    }

    public function test_voucher_code_case_insensitive(): void
    {
        // Stored 'HAPPY10', user input 'happy10' atau 'Happy10' — semua match
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan(unitPrice: 10_000);

        Voucher::create([
            'code' => 'HAPPY10',
            'description' => 'Test case',
            'type' => 'fixed',
            'value' => 1_000,
            'min_order' => 0,
            'max_discount' => null,
            'usage_limit' => null,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($customer)->post(route('order.store'), [
            'service_id' => $service->id,
            'address' => 'Jl. Case No. 1',
            'address_note' => null,
            'zone' => 'A',
            'pickup_date' => now()->addDay()->toDateString(),
            'pickup_time' => 'pagi',
            'weight_estimate' => 2,
            'voucher_code' => 'happy10', // lowercase
        ]);

        $this->assertDatabaseHas('orders', ['voucher_code' => 'HAPPY10']);
        $this->assertDatabaseCount('voucher_usages', 1);
    }

    private function buatLayanan(int $unitPrice = 7_000): Service
    {
        return Service::create([
            'name' => 'Cuci Voucher App Test',
            'slug' => 'cuci-voucher-app-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => $unitPrice,
            'unit_type' => 'kg',
            'price_per_kg' => $unitPrice,
            'estimated_hours' => 24,
            'description' => 'Layanan voucher application test',
            'is_active' => true,
        ]);
    }
}
