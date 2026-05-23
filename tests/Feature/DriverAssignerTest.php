<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Support\DriverAssigner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Fairness algoritma DriverAssigner.
 *
 * Sebelumnya pemilihan driver pakai `->first()` — selalu driver pertama
 * yang dapet semua tugas. PR sebelumnya nambahin DriverAssigner dengan
 * dua strategi (round-robin & load-based), tapi belum ada test yang
 * verifikasi fairness-nya beneran kerja.
 *
 * Test ini ngeguard:
 *  - Round-robin: driver yang last_assigned_at-nya paling lama dipilih
 *  - Load-based: driver dengan load terkecil dipilih
 *  - markAssigned() update last_assigned_at
 *  - pick() return null kalau gak ada driver aktif
 *  - Driver inactive tidak ke-pick
 */
class DriverAssignerTest extends TestCase
{
    use RefreshDatabase;

    public function test_round_robin_pilih_driver_yang_paling_lama_gak_di_assign(): void
    {
        config(['laundry.driver_assignment_strategy' => 'round_robin']);

        $driverLama = User::factory()->create(['role' => 'driver']);
        $driverLama->forceFill(['last_assigned_at' => now()->subDays(3)])->save();

        $driverBaru = User::factory()->create(['role' => 'driver']);
        $driverBaru->forceFill(['last_assigned_at' => now()->subMinutes(5)])->save();

        $picked = DriverAssigner::pick();

        $this->assertSame($driverLama->id, $picked->id);
    }

    public function test_round_robin_prioritaskan_driver_yang_belum_pernah_assign(): void
    {
        config(['laundry.driver_assignment_strategy' => 'round_robin']);

        // Driver yang sudah pernah dapet tugas
        $driverExisting = User::factory()->create(['role' => 'driver']);
        $driverExisting->forceFill(['last_assigned_at' => now()->subDays(7)])->save();

        // Driver baru — last_assigned_at = null
        $driverBaru = User::factory()->create(['role' => 'driver']);

        $picked = DriverAssigner::pick();

        $this->assertSame($driverBaru->id, $picked->id, 'Driver baru (last_assigned_at null) harus diprioritaskan');
    }

    public function test_round_robin_distribusi_tiga_driver_enam_order_merata(): void
    {
        config(['laundry.driver_assignment_strategy' => 'round_robin']);

        $drivers = [];
        for ($i = 1; $i <= 3; $i++) {
            $drivers[$i] = User::factory()->create([
                'role' => 'driver',
                'name' => "Driver $i",
            ]);
        }

        $assignmentCount = [1 => 0, 2 => 0, 3 => 0];

        for ($order = 1; $order <= 6; $order++) {
            $picked = DriverAssigner::pick();
            $this->assertNotNull($picked);

            DriverAssigner::markAssigned($picked);

            // Cocokin driver ke index 1/2/3
            foreach ($drivers as $idx => $driver) {
                if ($driver->id === $picked->id) {
                    $assignmentCount[$idx]++;
                    break;
                }
            }
        }

        // Distribusi 6 order ke 3 driver harus 2-2-2
        $this->assertSame(2, $assignmentCount[1]);
        $this->assertSame(2, $assignmentCount[2]);
        $this->assertSame(2, $assignmentCount[3]);
    }

    public function test_load_based_pilih_driver_dengan_load_terkecil(): void
    {
        config(['laundry.driver_assignment_strategy' => 'load_based']);

        $driverSibuk = User::factory()->create(['role' => 'driver']);
        $driverSantai = User::factory()->create(['role' => 'driver']);
        $service = $this->buatLayanan();

        // Bikin 3 order aktif untuk driver sibuk
        for ($i = 1; $i <= 3; $i++) {
            $this->buatPesananUntukDriver($driverSibuk, $service, 'dijemput', "ORD-LB-S$i");
        }

        // 1 order aktif untuk driver santai
        $this->buatPesananUntukDriver($driverSantai, $service, 'dikirim', 'ORD-LB-T1');

        $picked = DriverAssigner::pick();

        $this->assertSame($driverSantai->id, $picked->id, 'Load-based harus pilih driver dengan order aktif paling sedikit');
    }

    public function test_load_based_count_hanya_status_aktif_dijemput_dan_dikirim(): void
    {
        // Order 'selesai' atau 'dicuci' (workshop) gak dihitung sebagai
        // load driver — driver bisa fokus ke pickup/delivery.
        config(['laundry.driver_assignment_strategy' => 'load_based']);

        $driver1 = User::factory()->create(['role' => 'driver']);
        $driver2 = User::factory()->create(['role' => 'driver']);
        $service = $this->buatLayanan();

        // Driver 1 punya banyak order tapi semua 'selesai' — load = 0
        for ($i = 1; $i <= 5; $i++) {
            $this->buatPesananUntukDriver($driver1, $service, 'selesai', "ORD-X-D1-$i");
        }

        // Driver 2 punya 1 order 'dijemput' — load = 1
        $this->buatPesananUntukDriver($driver2, $service, 'dijemput', 'ORD-X-D2-1');

        $picked = DriverAssigner::pick();

        $this->assertSame($driver1->id, $picked->id, 'Driver 1 dengan order selesai semua → load 0 → kepilih');
    }

    public function test_mark_assigned_update_last_assigned_at(): void
    {
        $driver = User::factory()->create(['role' => 'driver']);
        $this->assertNull($driver->last_assigned_at);

        DriverAssigner::markAssigned($driver);

        $this->assertNotNull($driver->fresh()->last_assigned_at);
        $this->assertTrue(
            $driver->fresh()->last_assigned_at->greaterThan(now()->subSeconds(5))
        );
    }

    public function test_pick_return_null_kalau_tidak_ada_driver_aktif(): void
    {
        // Semua driver inactive
        User::factory()->create(['role' => 'driver', 'is_active' => false]);
        User::factory()->create(['role' => 'driver', 'is_active' => false]);

        $this->assertNull(DriverAssigner::pick());
    }

    public function test_pick_skip_driver_inactive(): void
    {
        $driverAktif = User::factory()->create(['role' => 'driver', 'is_active' => true]);
        User::factory()->create(['role' => 'driver', 'is_active' => false]);
        User::factory()->create(['role' => 'driver', 'is_active' => false]);

        $picked = DriverAssigner::pick();
        $this->assertSame($driverAktif->id, $picked->id);
    }

    public function test_pick_return_null_kalau_tidak_ada_driver_sama_sekali(): void
    {
        // Database empty selain admin/customer
        User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'customer']);

        $this->assertNull(DriverAssigner::pick());
    }

    private function buatLayanan(): Service
    {
        return Service::create([
            'name' => 'Cuci DA Test',
            'slug' => 'cuci-da-'.uniqid(),
            'pricing_model' => 'per_kg',
            'unit_price' => 7000,
            'unit_type' => 'kg',
            'price_per_kg' => 7000,
            'estimated_hours' => 24,
            'description' => 'Test driver assigner',
            'is_active' => true,
        ]);
    }

    private function buatPesananUntukDriver(User $driver, Service $service, string $status, string $kode): Order
    {
        return Order::create([
            'order_code' => $kode,
            'customer_id' => User::factory()->create(['role' => 'customer'])->id,
            'service_id' => $service->id,
            'driver_id' => $driver->id,
            'address' => 'Jl. DA Test',
            'address_note' => null,
            'zone' => 'A',
            'pickup_cost' => 5000,
            'pickup_date' => now()->toDateString(),
            'pickup_time' => 'siang',
            'weight_estimate' => 2,
            'service_cost' => 14000,
            'discount' => 0,
            'total_cost' => 19000,
            'status' => $status,
            'payment_method' => 'cod',
            'is_paid' => false,
        ]);
    }
}
