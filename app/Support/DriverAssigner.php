<?php

namespace App\Support;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Pemilih driver otomatis saat order baru masuk. Lebih adil daripada
 * cuma `->first()` karena menyebar tugas ke semua driver aktif.
 *
 * Strategi dipilih dari config/laundry.php key 'driver_assignment_strategy':
 *   - 'round_robin' (default): pilih driver yang last_assigned_at-nya
 *     paling lama / null. Adil untuk distribusi merata.
 *   - 'load_based': pilih driver dengan beban tugas aktif terkecil
 *     (status: dijemput/dikirim). Adil saat satu order memakan waktu
 *     lebih lama dari biasanya.
 */
class DriverAssigner
{
    /**
     * Pilih driver yang paling cocok untuk order ini. Return null kalau
     * gak ada driver aktif sama sekali — caller harus handle fallback
     * (mis. notif admin untuk assign manual).
     */
    public static function pick(): ?User
    {
        $strategy = config('laundry.driver_assignment_strategy', 'round_robin');

        return match ($strategy) {
            'load_based' => self::pickByLowestLoad(),
            default => self::pickByRoundRobin(),
        };
    }

    /**
     * Tandai driver sudah dapat tugas — update last_assigned_at supaya
     * round-robin di order berikutnya tidak nge-pick orang yang sama.
     *
     * Catatan: pakai raw DB query supaya format string ditulis dengan
     * microsecond precision (Y-m-d H:i:s.u). Default Eloquent
     * `$dateFormat` cuma 'Y-m-d H:i:s' — kalau dua mark terjadi di detik
     * yang sama, round-robin jadi non-deterministik (tie-breaker fall
     * back ke id terkecil → bias selalu ke driver pertama).
     */
    public static function markAssigned(User $driver): void
    {
        DB::table('users')
            ->where('id', $driver->id)
            ->update(['last_assigned_at' => now()->format('Y-m-d H:i:s.u')]);

        // Refresh in-memory model supaya caller yang punya reference
        // dapet nilai terbaru tanpa harus ->fresh() manual.
        $driver->forceFill(['last_assigned_at' => now()])->syncOriginal();
    }

    private static function pickByRoundRobin(): ?User
    {
        return User::where('role', 'driver')
            ->where('is_active', true)
            // null first — driver yang belum pernah dapet tugas duluan,
            // baru yang last_assigned_at-nya paling lama. Tie-breaker
            // pakai id supaya distribusi deterministik (gak random
            // saat dua driver punya last_assigned_at sama persis,
            // mis. di test atau saat seed).
            ->orderByRaw('last_assigned_at IS NULL DESC')
            ->orderBy('last_assigned_at')
            ->orderBy('id')
            ->first();
    }

    private static function pickByLowestLoad(): ?User
    {
        // Hitung jumlah order aktif per driver. Driver tanpa baris di
        // join tetap muncul lewat LEFT JOIN dengan count = 0 supaya
        // mereka jadi pilihan utama.
        return User::where('users.role', 'driver')
            ->where('users.is_active', true)
            ->leftJoin('orders', function ($join) {
                $join->on('orders.driver_id', '=', 'users.id')
                    ->whereIn('orders.status', ['dijemput', 'dikirim']);
            })
            ->select('users.*', DB::raw('COUNT(orders.id) as active_load'))
            ->groupBy('users.id')
            ->orderBy('active_load')
            ->orderBy('users.last_assigned_at')
            ->orderBy('users.id')
            ->first();
    }
}
