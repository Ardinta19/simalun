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
     */
    public static function markAssigned(User $driver): void
    {
        // Pakai forceFill biar bypass mass-assignment check (kolom ini
        // sengaja gak di-fillable karena bukan input form).
        $driver->forceFill(['last_assigned_at' => now()])->saveQuietly();
    }

    private static function pickByRoundRobin(): ?User
    {
        return User::where('role', 'driver')
            ->where('is_active', true)
            // null first — driver yang belum pernah dapet tugas duluan,
            // baru yang last_assigned_at-nya paling lama.
            ->orderByRaw('last_assigned_at IS NULL DESC')
            ->orderBy('last_assigned_at')
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
            ->first();
    }
}
