<?php
/* ══════════════════════════════════════════════════════════════
   FILE: app/Http/Controllers/FinanceController.php
══════════════════════════════════════════════════════════════ */
namespace App\Http\Controllers;

use App\Models\FinanceEntry;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    /** GET /admin/finance */
    public function index(Request $request)
    {
        $range = $request->get('range', 'bulanan');

        $query = FinanceEntry::with('order')->latest('entry_date');

        if ($range === 'harian') {
            $query->whereDate('entry_date', today());
        } else {
            // Bulanan: bulan ini
            $query->where('period_key', now()->format('Y-m'));
        }

        $transaksi = $query->paginate(20);

        // Kalkulasi summary
        $allEntries = FinanceEntry::when(
            $range === 'harian',
            fn($q) => $q->whereDate('entry_date', today()),
            fn($q) => $q->where('period_key', now()->format('Y-m'))
        );

        $masuk  = (clone $allEntries)->where('entry_type', 'income')->sum('amount');
        $keluar = (clone $allEntries)->where('entry_type', 'expense')->sum('amount');
        $saldo  = $masuk - $keluar;

        return view('roles.admin.finance', compact('transaksi', 'masuk', 'keluar', 'saldo', 'range'));
    }

    /** POST /admin/finance — Catat pengeluaran */
    public function store(Request $request)
    {
        $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount'      => ['required', 'integer', 'min:1'],
        ]);

        FinanceEntry::create([
            'entry_date'  => today(),
            'period_key'  => now()->format('Y-m'),
            'entry_type'  => 'expense',
            'amount'      => $request->amount,
            'source_type' => 'manual',
            'notes'       => $request->description,
            'created_by'  => Auth::id(),
        ]);

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    /**
     * Catat pemasukan otomatis saat order selesai.
     * Dipanggil oleh OrderObserver ketika status berubah ke 'selesai'.
     * Cek apakah sudah ada entry untuk order ini, jika sudah update amount-nya.
     */
    public static function recordIncomeFromOrder(Order $order): void
    {
        $existing = FinanceEntry::where('order_id', $order->id)
            ->where('entry_type', 'income')
            ->where('source_type', 'order')
            ->first();

        if ($existing) {
            // Update amount jika berat aktual berubah
            $existing->update([
                'amount' => $order->total_cost,
                'notes'  => "Order {$order->order_code} (selesai)",
            ]);
        } else {
            FinanceEntry::create([
                'entry_date'  => today(),
                'period_key'  => now()->format('Y-m'),
                'entry_type'  => 'income',
                'amount'      => $order->total_cost,
                'source_type' => 'order',
                'source_id'   => $order->id,
                'order_id'    => $order->id,
                'notes'       => "Order {$order->order_code} (selesai)",
                'created_by'  => $order->customer_id,
            ]);
        }
    }

    /** GET /admin/finance/export — Export ke CSV */
    public function export(Request $request)
    {
        $range = $request->get('range', 'bulanan');

        $entries = FinanceEntry::with('order')
            ->when(
                $range === 'harian',
                fn($q) => $q->whereDate('entry_date', today()),
                fn($q) => $q->where('period_key', now()->format('Y-m'))
            )
            ->orderBy('entry_date', 'desc')
            ->get();

        $filename = 'laporan-keuangan-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($entries) {
            $handle = fopen('php://output', 'w');
            // BOM untuk Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['Tanggal', 'Keterangan', 'Tipe', 'Jumlah (Rp)', 'Order Code']);

            foreach ($entries as $e) {
                fputcsv($handle, [
                    $e->entry_date->format('d/m/Y'),
                    $e->notes ?? '-',
                    $e->entry_type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                    number_format($e->amount, 0, ',', '.'),
                    $e->order?->order_code ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}