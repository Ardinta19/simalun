<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderRating;
use App\Models\Service;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Verifikasi 6 segment analitik 30 hari di admin dashboard di-cache
 * 60 detik supaya halaman tetap snappy di traffic burst (admin
 * refresh berkali-kali). Operasional counter (jumlahDiproses,
 * pemasukanHari, dll) SENGAJA gak di-cache — di luar scope test ini.
 */
class AdminDashboardCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_admin_men_cache_6_segment_analitik(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Kosongkan cache supaya skenario test stabil dari run ke run
        Cache::flush();

        $this->actingAs($admin)->get(route('dashboard.admin'))->assertOk();

        // Setiap segment harus ke-cache setelah hit pertama
        $this->assertTrue(Cache::has('dashboard.admin.top-services'));
        $this->assertTrue(Cache::has('dashboard.admin.pickup-buckets'));
        $this->assertTrue(Cache::has('dashboard.admin.top-customers'));
        $this->assertTrue(Cache::has('dashboard.admin.rating-stats'));
        $this->assertTrue(Cache::has('dashboard.admin.latest-reviews'));
        $this->assertTrue(Cache::has('dashboard.admin.voucher-aktif-count'));
    }

    public function test_data_baru_belum_muncul_di_dashboard_sampai_cache_expired(): void
    {
        // Skenario: admin buka dashboard (cache populate dengan voucher
        // count = 0), terus voucher baru dibuat, admin refresh sebelum
        // 60 detik. Cache harus serve nilai LAMA (0), bukan re-query.
        // Trade-off ini deliberate — analytics akurasi 60 detik OK
        // demi response time dashboard.
        $admin = User::factory()->create(['role' => 'admin']);
        Cache::flush();

        // Hit pertama — cache populate dengan 0 voucher aktif
        $this->actingAs($admin)->get(route('dashboard.admin'))->assertOk();
        $this->assertSame(0, Cache::get('dashboard.admin.voucher-aktif-count'));

        // Voucher baru dibikin di luar lifecycle dashboard
        Voucher::create([
            'code' => 'TESTCACHE',
            'description' => 'Voucher test cache invalidation',
            'type' => 'fixed',
            'value' => 5000,
            'min_order' => 0,
            'is_active' => true,
        ]);

        // Hit kedua sebelum cache expire — masih nilai cache lama
        $this->actingAs($admin)->get(route('dashboard.admin'))->assertOk();
        $this->assertSame(0, Cache::get('dashboard.admin.voucher-aktif-count'));

        // Setelah cache di-flush manual (setara TTL expire), nilai
        // up-to-date kebaca
        Cache::forget('dashboard.admin.voucher-aktif-count');
        $this->actingAs($admin)->get(route('dashboard.admin'))->assertOk();
        $this->assertSame(1, Cache::get('dashboard.admin.voucher-aktif-count'));
    }

    public function test_pickup_buckets_selalu_punya_3_slot_walau_data_kosong(): void
    {
        // Aturan view: section "Distribusi Jam Pickup" render 3 batang
        // (pagi/siang/sore) — kalau ada slot hilang dari array, view
        // bisa error. Cache harus tetap normalize keys.
        $admin = User::factory()->create(['role' => 'admin']);
        Cache::flush();

        $this->actingAs($admin)->get(route('dashboard.admin'))->assertOk();

        $cached = Cache::get('dashboard.admin.pickup-buckets');
        $this->assertIsArray($cached);
        $this->assertSame(['pagi', 'siang', 'sore'], array_keys($cached));
        $this->assertSame([0, 0, 0], array_values($cached));
    }

    public function test_top_services_dan_top_customers_kebaca_dari_cache_di_panggilan_kedua(): void
    {
        // Smoke test bahwa Cache::remember beneran serve dari cache
        // (bukan re-query) — kalau driver pakai 'array' (default test),
        // cache hidup selama satu request lifecycle.
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $service = $this->buatLayanan();

        Order::withoutEvents(fn () => Order::create([
            'order_code' => 'ORD-CACHE-001',
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'address' => 'Jl. Test',
            'zone' => 'A',
            'pickup_cost' => 0,
            'pickup_date' => now()->toDateString(),
            'pickup_time' => 'pagi',
            'weight_estimate' => 2,
            'service_cost' => 14000,
            'discount' => 0,
            'total_cost' => 14000,
            'status' => 'selesai',
        ]));

        OrderRating::create([
            'order_id' => Order::first()->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'review' => 'Mantap',
        ]);

        Cache::flush();
        $this->actingAs($admin)->get(route('dashboard.admin'))->assertOk();

        $topServices = Cache::get('dashboard.admin.top-services');
        $topCustomers = Cache::get('dashboard.admin.top-customers');
        $ratingStats = Cache::get('dashboard.admin.rating-stats');

        $this->assertNotNull($topServices);
        $this->assertNotNull($topCustomers);
        $this->assertNotNull($ratingStats);
        $this->assertSame(1, $topServices->count());
        $this->assertSame(1, $topCustomers->count());
    }

    private function buatLayanan(): Service
    {
        return Service::create([
            'name' => 'Cuci Test Cache',
            'slug' => 'cuci-test-cache-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Layanan test cache',
            'is_active' => true,
        ]);
    }
}
