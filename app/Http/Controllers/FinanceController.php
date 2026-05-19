<?php

namespace App\Http\Controllers;

use App\Models\FinanceEntry;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'bulanan');

        $query = FinanceEntry::with('order')->latest('entry_date');

        if ($range === 'harian') {
            $query->whereDate('entry_date', today());
        } else {
            $query->where('period_key', now()->format('Y-m'));
        }

        $transaksi = $query->paginate(20);

        $base = FinanceEntry::when(
            $range === 'harian',
            fn($q) => $q->whereDate('entry_date', today()),
            fn($q) => $q->where('period_key', now()->format('Y-m'))
        );

        $masuk  = (clone $base)->where('entry_type', 'income')->sum('amount');
        $keluar = (clone $base)->where('entry_type', 'expense')->sum('amount');
        $saldo  = $masuk - $keluar;

        return view('roles.admin.finance', compact('transaksi', 'masuk', 'keluar', 'saldo', 'range'));
    }

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

    public static function recordIncomeFromOrder(Order $order): void
    {
        $entry = FinanceEntry::where('order_id', $order->id)
            ->where('entry_type', 'income')
            ->where('source_type', 'order')
            ->first();

        $payload = [
            'amount' => $order->total_cost,
            'notes'  => "Order {$order->order_code} (selesai)",
        ];

        if ($entry) {
            $entry->update($payload);
            return;
        }

        FinanceEntry::create(array_merge($payload, [
            'entry_date'  => today(),
            'period_key'  => now()->format('Y-m'),
            'entry_type'  => 'income',
            'source_type' => 'order',
            'source_id'   => $order->id,
            'order_id'    => $order->id,
            'created_by'  => $order->customer_id,
        ]));
    }

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
