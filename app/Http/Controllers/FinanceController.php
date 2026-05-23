<?php

namespace App\Http\Controllers;

use App\Models\FinanceEntry;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\FinanceService;
use App\Support\Audit;
use App\Support\Laundry;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'range' => $request->get('range', 'bulanan'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'driver_id' => $request->get('driver_id'),
            'service_id' => $request->get('service_id'),
            'type' => $request->get('type'), // income, expense, all
        ];

        $query = $this->buildFilteredQuery($filters);
        $transaksi = $query->paginate(20)->withQueryString();

        // Summary calculations using same filters
        $summaryQuery = $this->buildFilteredQuery($filters, paginate: false);
        $masuk = (clone $summaryQuery)->where('entry_type', 'income')->sum('amount');
        $keluar = (clone $summaryQuery)->where('entry_type', 'expense')->sum('amount');
        $saldo = $masuk - $keluar;

        // Revenue harian (7 hari terakhir) untuk chart
        $revenueChart = FinanceEntry::where('entry_type', 'income')
            ->where('entry_date', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('entry_date, SUM(amount) as total')
            ->groupBy('entry_date')
            ->orderBy('entry_date')
            ->get()
            ->keyBy(fn ($e) => $e->entry_date->format('Y-m-d'));

        $chartData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData->push([
                'date' => now()->subDays($i)->translatedFormat('D'),
                'total' => (int) ($revenueChart[$date]->total ?? 0),
            ]);
        }

        // Data untuk filter dropdowns
        $daftarDriver = User::where('role', 'driver')->where('is_active', true)->orderBy('name')->get();
        $daftarLayanan = Service::where('is_active', true)->orderBy('name')->get();

        return view('roles.admin.finance', compact(
            'transaksi', 'masuk', 'keluar', 'saldo',
            'filters', 'chartData', 'daftarDriver', 'daftarLayanan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'category' => ['nullable', 'string', 'in:operational,salary,investment,other'],
        ]);

        $entry = FinanceEntry::create([
            'entry_date' => today(),
            'period_key' => now()->format('Y-m'),
            'entry_type' => 'expense',
            'category' => $request->get('category', 'operational'),
            'amount' => $request->amount,
            'source_type' => 'manual',
            'notes' => $request->description,
            'created_by' => Auth::id(),
        ]);

        Audit::log('finance.expense.create', $entry,
            after: $entry->only(['entry_type', 'category', 'amount', 'notes']),
            summary: 'Mencatat pengeluaran Rp '.number_format($entry->amount, 0, ',', '.')." ({$entry->category})");

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    /**
     * Record income when order reaches 'selesai' status.
     *
     * @deprecated Pakai App\Services\FinanceService::recordIncomeFromOrder() langsung.
     * Method ini disimpan untuk backward-compat — delegate ke service.
     */
    public static function recordIncomeFromOrder(Order $order): void
    {
        FinanceService::recordIncomeFromOrder($order);
    }

    /**
     * Export PDF laporan keuangan.
     */
    public function exportPdf(Request $request)
    {
        $filters = [
            'range' => $request->get('range', 'bulanan'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'driver_id' => $request->get('driver_id'),
            'service_id' => $request->get('service_id'),
            'type' => $request->get('type'),
        ];

        $entries = $this->buildFilteredQuery($filters, paginate: false)->get();
        $masuk = $entries->where('entry_type', 'income')->sum('amount');
        $keluar = $entries->where('entry_type', 'expense')->sum('amount');
        $saldo = $masuk - $keluar;

        $laundry = Laundry::receiptHeader();

        $pdf = Pdf::loadView('exports.finance-pdf', compact(
            'entries', 'masuk', 'keluar', 'saldo', 'filters', 'laundry'
        ));

        $pdf->setPaper('a4', 'landscape');

        $filename = 'laporan-keuangan-'.now()->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Excel (XLSX) laporan keuangan via OpenSpout.
     */
    public function export(Request $request)
    {
        if (! class_exists(Writer::class)) {
            return back()->with('error', 'Fitur export Excel belum tersedia. Jalankan: composer install');
        }

        $filters = [
            'range' => $request->get('range', 'bulanan'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'driver_id' => $request->get('driver_id'),
            'service_id' => $request->get('service_id'),
            'type' => $request->get('type'),
        ];

        $entries = $this->buildFilteredQuery($filters, paginate: false)->get();

        $filename = 'laporan-keuangan-'.now()->format('Y-m-d').'.xlsx';
        $tempPath = storage_path("app/{$filename}");

        $writer = new Writer;
        $writer->openToFile($tempPath);

        // Header row
        $writer->addRow(Row::fromValues([
            'Tanggal', 'Keterangan', 'Tipe', 'Kategori', 'Jumlah (Rp)', 'Order Code', 'Customer',
        ]));

        foreach ($entries as $e) {
            $writer->addRow(Row::fromValues([
                $e->entry_date->format('d/m/Y'),
                $e->notes ?? '-',
                $e->entry_type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                ucfirst($e->category ?? '-'),
                $e->amount,
                $e->order?->order_code ?? '-',
                $e->order?->customer?->name ?? '-',
            ]));
        }

        $writer->close();

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Build filtered query for finance entries.
     */
    private function buildFilteredQuery(array $filters, bool $paginate = true)
    {
        $query = FinanceEntry::with(['order.customer', 'order.service', 'order.driver'])
            ->latest('entry_date');

        // Date range filter
        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->whereBetween('entry_date', [$filters['start_date'], $filters['end_date']]);
        } elseif ($filters['range'] === 'harian') {
            $query->whereDate('entry_date', today());
        } elseif ($filters['range'] === 'mingguan') {
            $query->where('entry_date', '>=', now()->startOfWeek());
        } else {
            $query->where('period_key', now()->format('Y-m'));
        }

        // Type filter
        if (! empty($filters['type']) && $filters['type'] !== 'all') {
            $query->where('entry_type', $filters['type']);
        }

        // Driver filter (melalui order)
        if (! empty($filters['driver_id'])) {
            $query->whereHas('order', fn ($q) => $q->where('driver_id', $filters['driver_id']));
        }

        // Service filter (melalui order)
        if (! empty($filters['service_id'])) {
            $query->whereHas('order', fn ($q) => $q->where('service_id', $filters['service_id']));
        }

        return $query;
    }
}
