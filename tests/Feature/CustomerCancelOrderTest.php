<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Customer cancel order — guard status, ownership, dan side effect.
 *
 * Aturan:
 *  - Hanya bisa cancel selama status 'menunggu' atau 'dijemput'
 *    (sebelum cucian masuk workshop)
 *  - Cuma owner order yang boleh cancel (403 untuk customer lain)
 *  - Status history tertulis dengan reason kalau diisi
 */
class CustomerCancelOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_bisa_cancel_pesanan_status_menunggu(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'menunggu');

        $response = $this->actingAs($customer)
            ->post(route('customer.order.cancel', $order), [
                'cancel_reason' => 'Salah pilih layanan',
            ]);

        $response->assertRedirect(route('customer.orders'));
        $response->assertSessionHas('success');

        $this->assertSame('dibatalkan', $order->fresh()->status);
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'status_code' => 'dibatalkan',
            'updated_by' => $customer->id,
        ]);
    }

    public function test_customer_bisa_cancel_pesanan_status_dijemput(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'dijemput');

        $response = $this->actingAs($customer)
            ->post(route('customer.order.cancel', $order));

        $response->assertSessionHas('success');
        $this->assertSame('dibatalkan', $order->fresh()->status);
    }

    public function test_customer_tidak_bisa_cancel_pesanan_yang_sudah_dicuci(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'dicuci');

        $response = $this->actingAs($customer)
            ->post(route('customer.order.cancel', $order));

        $response->assertSessionHas('error');
        $this->assertSame('dicuci', $order->fresh()->status);
    }

    public function test_customer_tidak_bisa_cancel_pesanan_customer_lain(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $orangLain = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'menunggu');

        $response = $this->actingAs($orangLain)
            ->post(route('customer.order.cancel', $order));

        $response->assertForbidden();
        $this->assertSame('menunggu', $order->fresh()->status);
    }

    public function test_cancel_reason_disimpan_di_status_note(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'menunggu');

        $this->actingAs($customer)->post(route('customer.order.cancel', $order), [
            'cancel_reason' => 'Mendadak ada acara, jadwal jemput bentrok.',
        ]);

        // OrderObserver::updated() juga nulis history "Status diperbarui menjadi"
        // — kita cari history yang ditulis controller (yang bawa reason).
        $historyDenganReason = $order->statusHistories()
            ->where('status_code', 'dibatalkan')
            ->where('status_note', 'like', '%Mendadak ada acara%')
            ->first();

        $this->assertNotNull($historyDenganReason, 'History dengan reason harus tertulis');
        $this->assertSame($customer->id, $historyDenganReason->updated_by);
    }

    private function buatPesanan(User $customer, string $status): Order
    {
        $service = Service::create([
            'name' => 'Cuci Cancel Test',
            'slug' => 'cuci-cancel-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Test cancel',
            'is_active' => true,
        ]);

        return Order::create([
            'order_code' => 'ORD-CANCEL-'.uniqid(),
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'address' => 'Jl. Cancel No. 1',
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
        ]);
    }
}
