<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guard transisi status pesanan.
 *
 * Aturan utama: status hanya boleh maju lewat jalur yang didefinisikan
 * di Order::TRANSITIONS. Loncat status (mis. 'menunggu' langsung
 * 'selesai') akan nge-trigger income recording, notif, dan finance
 * yang seolah pesanan beneran selesai padahal proses gak terjadi.
 */
class OrderStatusTransitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_tidak_bisa_skip_status_dari_menunggu_ke_selesai(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->buatPesanan('menunggu');

        $response = $this->actingAs($admin)
            ->from(route('admin.orders'))
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'selesai',
            ]);

        $response->assertRedirect(route('admin.orders'));
        $response->assertSessionHas('error');

        // Order tetap di status awal — tidak boleh berubah
        $this->assertSame('menunggu', $order->fresh()->status);

        // Pastikan finance gak ke-trigger
        $this->assertDatabaseCount('finance_entries', 0);
    }

    public function test_admin_tidak_bisa_skip_status_dari_dicuci_ke_selesai(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->buatPesanan('dicuci');

        $response = $this->actingAs($admin)
            ->from(route('admin.orders'))
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'selesai',
            ]);

        $response->assertSessionHas('error');
        $this->assertSame('dicuci', $order->fresh()->status);
        $this->assertDatabaseCount('finance_entries', 0);
    }

    public function test_admin_bisa_lanjut_status_sesuai_alur(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->buatPesanan('dicuci');

        $response = $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'disetrika',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertSame('disetrika', $order->fresh()->status);
    }

    public function test_admin_tidak_bisa_ubah_pesanan_yang_sudah_final(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->buatPesanan('selesai');

        $response = $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'dicuci',
            ]);

        $response->assertSessionHas('error');
        $this->assertSame('selesai', $order->fresh()->status);
    }

    public function test_driver_tidak_bisa_skip_dari_dijemput_ke_selesai(): void
    {
        $driver = User::factory()->create(['role' => 'driver']);
        $order = $this->buatPesanan('dijemput', $driver->id);

        $response = $this->actingAs($driver)
            ->post(route('driver.orders.action', $order), [
                'status' => 'selesai',
                'payment_channel' => 'cash',
            ]);

        $response->assertSessionHas('error');
        $this->assertSame('dijemput', $order->fresh()->status);
        $this->assertDatabaseCount('finance_entries', 0);
    }

    public function test_driver_bisa_konfirmasi_pickup_dari_dijemput_ke_dicuci(): void
    {
        $driver = User::factory()->create(['role' => 'driver']);
        $order = $this->buatPesanan('dijemput', $driver->id);

        $response = $this->actingAs($driver)
            ->post(route('driver.orders.action', $order), [
                'status' => 'dicuci',
                'weight_actual' => 4.5,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertSame('dicuci', $order->fresh()->status);
    }

    public function test_driver_bisa_konfirmasi_delivery_dari_dikirim_ke_selesai(): void
    {
        $driver = User::factory()->create(['role' => 'driver']);
        $order = $this->buatPesanan('dikirim', $driver->id);

        $response = $this->actingAs($driver)
            ->post(route('driver.orders.action', $order), [
                'status' => 'selesai',
                'payment_channel' => 'qris',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertSame('selesai', $order->fresh()->status);
        $this->assertTrue($order->fresh()->is_paid);
        $this->assertSame('qris', $order->fresh()->payment_channel);
    }

    public function test_driver_tidak_bisa_intervensi_pesanan_yang_sedang_dicuci(): void
    {
        // Pesanan sedang dicuci di workshop. Bahkan kalau driver_id-nya
        // tertugas (mis. waktu dijemput dia yang ambil), driver tidak
        // boleh ubah status — itu tugas operasional/admin.
        $driver = User::factory()->create(['role' => 'driver']);
        $order = $this->buatPesanan('dicuci', $driver->id);

        // Coba lompat ke 'selesai' — validator allow, tapi guard transisi
        // & guard role-driver harus blok dua-duanya.
        $response = $this->actingAs($driver)
            ->post(route('driver.orders.action', $order), [
                'status' => 'selesai',
                'payment_channel' => 'cash',
            ]);

        $response->assertSessionHas('error');
        $this->assertSame('dicuci', $order->fresh()->status);
        $this->assertDatabaseCount('finance_entries', 0);
    }

    public function test_customer_cancel_pakai_aturan_transisi_yang_sama(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = $this->buatPesanan('dicuci', null, $customer->id);

        // dicuci → dibatalkan tidak ada di TRANSITIONS, jadi harus ditolak
        $response = $this->actingAs($customer)
            ->post(route('customer.order.cancel', $order));

        $response->assertSessionHas('error');
        $this->assertSame('dicuci', $order->fresh()->status);
    }

    public function test_can_transition_to_method_di_model(): void
    {
        $order = new Order(['status' => 'menunggu']);

        $this->assertTrue($order->canTransitionTo('dijemput'));
        $this->assertTrue($order->canTransitionTo('dibatalkan'));
        $this->assertFalse($order->canTransitionTo('selesai'));
        $this->assertFalse($order->canTransitionTo('dicuci'));

        $order->status = 'selesai';
        $this->assertTrue($order->isFinal());
        $this->assertFalse($order->canTransitionTo('dicuci'));
    }

    private function buatPesanan(string $status, ?int $driverId = null, ?int $customerId = null): Order
    {
        $customer = $customerId
            ? User::find($customerId)
            : User::factory()->create(['role' => 'customer']);

        $service = Service::create([
            'name' => 'Cuci Test Transition',
            'slug' => 'cuci-test-transition-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Layanan test',
            'is_active' => true,
        ]);

        return Order::create([
            'order_code' => 'ORD-TR-'.uniqid(),
            'customer_id' => $customer->id,
            'service_id' => $service->id,
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
            'driver_id' => $driverId,
        ]);
    }
}
