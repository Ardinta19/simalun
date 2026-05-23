<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit log writes — test bahwa setiap action admin yang penting
 * tercatat ke tabel audit_logs.
 *
 * Pattern: Audit::log(action, model, before, after, summary).
 * Action codes yang aktif di kode app:
 *  - voucher.create / .toggle / .delete  (sudah ditest di VoucherCrudTest)
 *  - service.create / .update / .toggle / .delete
 *  - service-category.create / .update / .toggle / .delete
 *  - order.assign-driver
 *  - order.status
 */
class AuditLogTrailTest extends TestCase
{
    use RefreshDatabase;

    public function test_assign_driver_tertulis_di_audit_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $driver = User::factory()->create(['role' => 'driver']);
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan();

        $order = Order::create([
            'order_code' => 'ORD-AUDIT-001',
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'address' => 'Jl. Audit No. 1',
            'address_note' => null,
            'zone' => 'A',
            'pickup_cost' => 5000,
            'pickup_date' => now()->toDateString(),
            'pickup_time' => 'siang',
            'weight_estimate' => 2,
            'service_cost' => 14000,
            'discount' => 0,
            'total_cost' => 19000,
            'status' => 'menunggu',
            'payment_method' => 'cod',
            'is_paid' => false,
        ]);

        $this->actingAs($admin)->post(route('admin.orders.assign-driver', $order), [
            'driver_id' => $driver->id,
            'assignment_type' => 'pickup',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'order.assign-driver',
            'actor_id' => $admin->id,
            'auditable_type' => Order::class,
            'auditable_id' => $order->id,
        ]);
    }

    public function test_update_status_tertulis_di_audit_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = $this->buatLayanan();
        $order = Order::create([
            'order_code' => 'ORD-AUDIT-002',
            'customer_id' => User::factory()->create(['role' => 'customer'])->id,
            'service_id' => $service->id,
            'address' => 'Jl. Audit No. 2',
            'zone' => 'A',
            'pickup_cost' => 5000,
            'pickup_date' => now()->toDateString(),
            'pickup_time' => 'siang',
            'weight_estimate' => 2,
            'service_cost' => 14000,
            'discount' => 0,
            'total_cost' => 19000,
            'status' => 'dicuci',
            'payment_method' => 'cod',
            'is_paid' => false,
        ]);

        $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'disetrika',
        ]);

        $audit = AuditLog::where('action', 'order.status')->first();
        $this->assertNotNull($audit);
        $this->assertSame($admin->id, $audit->actor_id);
        $this->assertSame(Order::class, $audit->auditable_type);
        $this->assertSame($order->id, $audit->auditable_id);

        // before/after snapshots
        $this->assertSame('dicuci', $audit->before['status'] ?? null);
        $this->assertSame('disetrika', $audit->after['status'] ?? null);
    }

    public function test_create_service_tertulis_di_audit_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = ServiceCategory::create([
            'name' => 'Audit Kategori',
            'pricing_model' => 'per_kg',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->post(
            route('admin.services.store', $kategori),
            [
                'name' => 'Cuci Audit',
                'pricing_model' => 'per_kg',
                'unit_price' => 8000,
                'unit_type' => 'kg',
                'estimated_hours' => 24,
                'description' => 'Test audit',
                'is_active' => true,
            ]
        );

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'service.create',
            'actor_id' => $admin->id,
        ]);
    }

    public function test_audit_log_tidak_tertulis_kalau_action_gagal(): void
    {
        // Sanity: kalau update status gagal (transisi tidak valid),
        // audit log harusnya gak nyimpan entry. Ini ngeguard agar
        // Audit::log dipanggil setelah transaksi sukses.
        $admin = User::factory()->create(['role' => 'admin']);
        $service = $this->buatLayanan();
        $order = Order::create([
            'order_code' => 'ORD-AUDIT-FAIL',
            'customer_id' => User::factory()->create(['role' => 'customer'])->id,
            'service_id' => $service->id,
            'address' => 'Jl. Fail No. 1',
            'zone' => 'A',
            'pickup_cost' => 5000,
            'pickup_date' => now()->toDateString(),
            'pickup_time' => 'siang',
            'weight_estimate' => 2,
            'service_cost' => 14000,
            'discount' => 0,
            'total_cost' => 19000,
            'status' => 'menunggu',
            'payment_method' => 'cod',
            'is_paid' => false,
        ]);

        // Coba lompat status — di-block oleh transition guard
        $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'selesai',
        ]);

        // Tidak ada audit log untuk order.status terkait order ini
        $this->assertDatabaseMissing('audit_logs', [
            'action' => 'order.status',
            'auditable_id' => $order->id,
        ]);
    }

    private function buatLayanan(): Service
    {
        return Service::create([
            'name' => 'Cuci Audit Test',
            'slug' => 'cuci-audit-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Test audit',
            'is_active' => true,
        ]);
    }
}
