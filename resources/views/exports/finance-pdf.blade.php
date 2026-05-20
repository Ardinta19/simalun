<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Keuangan — {{ $laundry['name'] }}</title>
<style>
@page { margin: 12mm; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #1a1a2e; line-height: 1.4; }

.header { text-align: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #0b5394; }
.brand { font-size: 16pt; font-weight: bold; color: #0b5394; }
.brand-sub { font-size: 8pt; color: #4a5568; margin-top: 2px; }
.report-title { font-size: 12pt; font-weight: bold; color: #1a1a2e; margin-top: 10px; }
.report-period { font-size: 8pt; color: #4a5568; margin-top: 2px; }

.summary { margin-bottom: 16px; }
.summary-table { width: 100%; border-collapse: collapse; }
.summary-table td { padding: 10px 14px; text-align: center; width: 33.33%; }
.summary-label { font-size: 7.5pt; font-weight: bold; color: #8896a6; text-transform: uppercase; letter-spacing: 0.3px; }
.summary-value { font-size: 13pt; font-weight: bold; margin-top: 3px; }
.summary-value.income { color: #059669; }
.summary-value.expense { color: #dc2626; }
.summary-value.balance { color: #0b5394; }

.data-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
.data-table th {
    background-color: #f8fafc;
    font-size: 7.5pt;
    font-weight: bold;
    color: #8896a6;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 8px 6px;
    text-align: left;
    border-bottom: 1.5px solid #e2e8f0;
}
.data-table td {
    font-size: 8.5pt;
    padding: 7px 6px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: top;
}
.data-table .right { text-align: right; }

.badge { padding: 2px 8px; border-radius: 3px; font-size: 7pt; font-weight: bold; }
.badge-in { background-color: #ecfdf5; color: #059669; }
.badge-out { background-color: #fef2f2; color: #dc2626; }

.footer { margin-top: 20px; text-align: center; font-size: 7.5pt; color: #8896a6; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head>
<body>

<div class="header">
    <div class="brand">{{ $laundry['name'] }}</div>
    <div class="brand-sub">{{ $laundry['address'] }} | {{ $laundry['phone'] }}</div>
    <div class="report-title">Laporan Keuangan</div>
    <div class="report-period">
        @if(!empty($filters['start_date']) && !empty($filters['end_date']))
            Periode: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}
        @elseif($filters['range'] === 'harian')
            Tanggal: {{ now()->translatedFormat('d F Y') }}
        @elseif($filters['range'] === 'mingguan')
            Minggu ini ({{ now()->startOfWeek()->format('d/m') }} - {{ now()->endOfWeek()->format('d/m/Y') }})
        @else
            Periode: {{ now()->translatedFormat('F Y') }}
        @endif
        | Dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

<div class="summary">
    <table class="summary-table">
        <tr>
            <td style="background:#ecfdf5; border-radius:6px;">
                <div class="summary-label">Total Pemasukan</div>
                <div class="summary-value income">Rp {{ number_format($masuk, 0, ',', '.') }}</div>
            </td>
            <td style="background:#fef2f2; border-radius:6px;">
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-value expense">Rp {{ number_format($keluar, 0, ',', '.') }}</div>
            </td>
            <td style="background:#e8f4fd; border-radius:6px;">
                <div class="summary-label">Saldo Bersih</div>
                <div class="summary-value balance">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Tipe</th>
            <th class="right">Jumlah (Rp)</th>
            <th>Order</th>
        </tr>
    </thead>
    <tbody>
        @forelse($entries as $i => $e)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $e->entry_date->format('d/m/Y') }}</td>
            <td>{{ Str::limit($e->notes ?? '-', 40) }}</td>
            <td><span class="badge {{ $e->entry_type === 'income' ? 'badge-in' : 'badge-out' }}">{{ $e->entry_type === 'income' ? 'Masuk' : 'Keluar' }}</span></td>
            <td class="right" style="color:{{ $e->entry_type === 'income' ? '#059669' : '#dc2626' }}">{{ number_format($e->amount, 0, ',', '.') }}</td>
            <td>{{ $e->order?->order_code ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center; padding:20px; color:#8896a6;">Tidak ada data.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    {{ $laundry['name'] }} &mdash; Laporan ini dibuat secara otomatis oleh sistem.
</div>

</body>
</html>
