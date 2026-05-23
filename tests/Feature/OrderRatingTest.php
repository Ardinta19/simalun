<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderRating;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Order rating — feedback customer setelah pesanan selesai.
 *
 * Aturan:
 *  - Hanya bisa rate pesanan dengan status 'selesai'
 *  - Hanya owner pesanan yang boleh rate (403 untuk customer lain)
 *  - 1 pesanan = 1 rating (unique constraint di DB)
 *  - Rating 1-5 bintang
 */
class OrderRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_bisa_kasih_rating_pesanan_selesai(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'selesai');

        $response = $this->actingAs($customer)
            ->post(route('customer.order.rating', $order), [
                'rating' => 5,
                'comment' => 'Cucian bersih, kurirnya ramah.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('order_ratings', [
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Cucian bersih, kurirnya ramah.',
        ]);
    }

    public function test_customer_tidak_bisa_rate_pesanan_yang_belum_selesai(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'dicuci');

        $response = $this->actingAs($customer)
            ->from(route('customer.order.detail', $order))
            ->post(route('customer.order.rating', $order), [
                'rating' => 5,
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('order_ratings', 0);
    }

    public function test_customer_tidak_bisa_rate_pesanan_customer_lain(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $orangLain = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'selesai');

        $response = $this->actingAs($orangLain)
            ->post(route('customer.order.rating', $order), [
                'rating' => 1,
            ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('order_ratings', 0);
    }

    public function test_rating_dua_kali_untuk_pesanan_yang_sama_di_block(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'selesai');

        OrderRating::create([
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'rating' => 4,
            'comment' => 'Pertama kali',
        ]);

        $response = $this->actingAs($customer)
            ->from(route('customer.order.detail', $order))
            ->post(route('customer.order.rating', $order), [
                'rating' => 5,
                'comment' => 'Coba update',
            ]);

        $response->assertSessionHas('error');
        // Yang lama tetap, yang baru gak nyangkut
        $this->assertSame(1, OrderRating::where('order_id', $order->id)->count());
        $this->assertSame(4, $order->fresh()->rating->rating);
    }

    public function test_rating_validation_minimum_1_maksimum_5(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan($customer, 'selesai');

        $response = $this->actingAs($customer)
            ->from(route('customer.order.detail', $order))
            ->post(route('customer.order.rating', $order), [
                'rating' => 6,
            ]);

        $response->assertSessionHasErrors(['rating']);
        $this->assertDatabaseCount('order_ratings', 0);
    }

    private function buatPesanan(User $customer, string $status): Order
    {
        $service = Service::create([
            'name' => 'Cuci Rating Test',
            'slug' => 'cuci-rating-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Test rating',
            'is_active' => true,
        ]);

        return Order::create([
            'order_code' => 'ORD-RATE-'.uniqid(),
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'address' => 'Jl. Rate No. 1',
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
