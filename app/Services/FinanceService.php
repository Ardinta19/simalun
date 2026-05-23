<?php

namespace App\Services;

use App\Models\FinanceEntry;
use App\Models\Order;
use Illuminate\Database\QueryException;

/**
 * Layanan pencatatan keuangan terkait order.
 *
 * Sebelumnya `FinanceController::recordIncomeFromOrder()` static
 * dipanggil lintas-controller (dari OrderController) — Service ini
 * memindahkan logic ke layer Service supaya:
 * - Controller cuma jadi orkestrasi HTTP, bukan tempat business rule
 *   yang dipakai controller lain.
 * - Test-able tanpa rute dan request lifecycle.
 * - Mudah di-mock kalau nanti ada kebutuhan inject (mis. queue worker).
 *
 * FinanceController::recordIncomeFromOrder() tetap ada untuk
 * backward-compat tapi cuma delegate ke service ini.
 */
class FinanceService
{
    /**
     * Catat pemasukan saat order mencapai status 'selesai'.
     *
     * Idempotent — pemanggilan berulang aman, tidak menghasilkan
     * entry duplikat. Diproteksi 3 lapis:
     *   1. PHP-level exists() check (fast path)
     *   2. Unique constraint DB `uniq_finance_order_type_source`
     *      (race-safe fallback)
     *   3. try/catch QueryException sebagai jaring pengaman terakhir
     */
    public static function recordIncomeFromOrder(Order $order): void
    {
        $exists = FinanceEntry::where('order_id', $order->id)
            ->where('entry_type', 'income')
            ->where('source_type', 'order')
            ->exists();

        if ($exists) {
            return;
        }

        // Hitung ulang dari komponen agar tidak bergantung pada
        // total_cost yang mungkin sudah outdated kalau ada perubahan
        // berat / item / pickup / diskon di tengah jalan.
        $serviceCost = (int) ($order->service_cost ?? 0);
        $itemTotal = (int) $order->items()
            ->where('service_id', '!=', $order->service_id)
            ->sum('line_total');
        $pickupCost = (int) ($order->pickup_cost ?? 0);
        $discount = (int) ($order->discount ?? 0);
        $calculatedTotal = $serviceCost + $itemTotal + $pickupCost - $discount;

        try {
            FinanceEntry::create([
                'entry_date' => today(),
                'period_key' => now()->format('Y-m'),
                'entry_type' => 'income',
                'category' => 'income',
                'amount' => $calculatedTotal,
                'source_type' => 'order',
                'source_id' => $order->id,
                'order_id' => $order->id,
                'notes' => "Pesanan #{$order->order_code} selesai",
                'created_by' => $order->customer_id,
            ]);
        } catch (QueryException $e) {
            // Unique constraint violation — race condition antara
            // dua proses yang lolos PHP-level check. Aman di-ignore
            // karena baris yang berhasil dibuat duluan sudah valid.
            if (str_contains($e->getMessage(), 'uniq_finance_order_type_source') || $e->getCode() === '23000') {
                return;
            }
            throw $e;
        }
    }
}
