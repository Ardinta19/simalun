<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Admin CRUD voucher + audit log writes.
 *
 * Test ngeguard:
 *  - Validasi semantik value percent (max 100)
 *  - Code di-uppercase otomatis
 *  - max_discount di-null saat type=fixed
 *  - Toggle active/inactive
 *  - Destroy guard: voucher yang sudah dipakai gak bisa dihapus
 *  - Audit log entry tertulis di setiap mutasi
 */
class VoucherCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_buat_voucher_percent_dan_audit_tertulis(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.vouchers.store'), [
            'code' => 'newvoucher10',
            'description' => 'Diskon 10%',
            'type' => 'percent',
            'value' => 10,
            'max_discount' => 5000,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.vouchers.index'));
        $this->assertDatabaseHas('vouchers', [
            'code' => 'NEWVOUCHER10', // di-uppercase otomatis
            'type' => 'percent',
            'value' => 10,
            'max_discount' => 5000,
            'min_order' => 0, // default
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'voucher.create',
            'actor_id' => $admin->id,
        ]);
    }

    public function test_admin_buat_voucher_fixed_max_discount_di_null(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.vouchers.store'), [
            'code' => 'POTONG3K',
            'description' => 'Potong langsung',
            'type' => 'fixed',
            'value' => 3000,
            'max_discount' => 99999, // dikirim tapi harus diabaikan
            'is_active' => true,
        ]);

        // max_discount cuma valid untuk percent — fixed type harus di-null
        $this->assertDatabaseHas('vouchers', [
            'code' => 'POTONG3K',
            'type' => 'fixed',
            'max_discount' => null,
        ]);
    }

    public function test_admin_tidak_bisa_buat_voucher_percent_lebih_dari_100(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->from(route('admin.vouchers.create'))
            ->post(route('admin.vouchers.store'), [
                'code' => 'OVERPCT',
                'description' => '150% gak masuk akal',
                'type' => 'percent',
                'value' => 150,
                'is_active' => true,
            ]);

        $response->assertSessionHasErrors(['value']);
        $this->assertDatabaseCount('vouchers', 0);
    }

    public function test_admin_tidak_bisa_buat_voucher_dengan_kode_duplikat(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Voucher::create([
            'code' => 'DUP',
            'description' => 'Existing',
            'type' => 'fixed',
            'value' => 1000,
            'min_order' => 0,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.vouchers.create'))
            ->post(route('admin.vouchers.store'), [
                'code' => 'DUP',
                'description' => 'Coba bikin lagi',
                'type' => 'fixed',
                'value' => 2000,
                'is_active' => true,
            ]);

        $response->assertSessionHasErrors(['code']);
        $this->assertSame(1, Voucher::count());
    }

    public function test_toggle_voucher_dan_audit_tertulis(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $voucher = Voucher::create([
            'code' => 'TOG',
            'description' => 'Toggle test',
            'type' => 'fixed',
            'value' => 1000,
            'min_order' => 0,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($admin)->patch(route('admin.vouchers.toggle', $voucher));

        $this->assertFalse((bool) $voucher->fresh()->is_active);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'voucher.toggle',
            'actor_id' => $admin->id,
        ]);

        // Toggle lagi balik aktif
        $this->actingAs($admin)->patch(route('admin.vouchers.toggle', $voucher));
        $this->assertTrue((bool) $voucher->fresh()->is_active);
    }

    public function test_voucher_yang_sudah_dipakai_tidak_bisa_dihapus(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $voucher = Voucher::create([
            'code' => 'USED',
            'description' => 'Sudah pernah dipakai',
            'type' => 'fixed',
            'value' => 1000,
            'min_order' => 0,
            'used_count' => 3, // simulate pemakaian
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.vouchers.destroy', $voucher));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertNotNull(Voucher::find($voucher->id));
    }

    public function test_voucher_belum_dipakai_bisa_dihapus_dan_audit_tertulis(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $voucher = Voucher::create([
            'code' => 'UNUSED',
            'description' => 'Belum dipakai siapa pun',
            'type' => 'fixed',
            'value' => 1000,
            'min_order' => 0,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($admin)->delete(route('admin.vouchers.destroy', $voucher));

        $this->assertNull(Voucher::find($voucher->id));
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'voucher.delete',
            'actor_id' => $admin->id,
        ]);
    }

    public function test_endpoint_check_voucher_return_diskon_kalau_valid(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        Voucher::create([
            'code' => 'CHECKME',
            'description' => 'Test endpoint cek',
            'type' => 'percent',
            'value' => 15,
            'min_order' => 20_000,
            'max_discount' => 10_000,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)
            ->postJson(route('customer.voucher.check'), [
                'code' => 'CHECKME',
                'subtotal' => 50_000,
            ]);

        $response->assertOk();
        $response->assertJson([
            'valid' => true,
            'code' => 'CHECKME',
            'discount' => 7_500, // 15% dari 50k = 7500, di bawah cap 10k
        ]);
    }

    public function test_endpoint_check_voucher_tolak_kalau_subtotal_kurang(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        Voucher::create([
            'code' => 'MIN20K',
            'description' => 'Min belanja',
            'type' => 'fixed',
            'value' => 5000,
            'min_order' => 20_000,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)
            ->postJson(route('customer.voucher.check'), [
                'code' => 'MIN20K',
                'subtotal' => 10_000,
            ]);

        $response->assertOk();
        $response->assertJson(['valid' => false]);
    }
}
