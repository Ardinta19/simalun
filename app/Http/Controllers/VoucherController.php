<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Support\Audit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    /**
     * Halaman list voucher untuk admin.
     */
    public function index()
    {
        $vouchers = Voucher::orderByDesc('is_active')
            ->orderByDesc('id')
            ->paginate(15);

        return view('roles.admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('roles.admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:vouchers,code'],
            'description' => ['required', 'string', 'max:200'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'integer', 'min:1', 'max:100000'],
            'min_order' => ['nullable', 'integer', 'min:0'],
            'max_discount' => ['nullable', 'integer', 'min:1'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'code.unique' => 'Kode voucher ini sudah dipakai.',
            'value.max' => 'Nilai terlalu besar — untuk persen maksimal 100, untuk nominal maksimal Rp 100.000.',
        ]);

        // Tambahan rule semantik: kalau type=percent, value tidak boleh > 100.
        if ($data['type'] === 'percent' && $data['value'] > 100) {
            return back()->withInput()
                ->withErrors(['value' => 'Persen voucher tidak boleh lebih dari 100.']);
        }

        $data['code'] = Str::upper($data['code']);
        $data['min_order'] = $data['min_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);
        // max_discount cuma berlaku untuk percent
        if ($data['type'] === 'fixed') {
            $data['max_discount'] = null;
        }

        $voucher = Voucher::create($data);

        Audit::log('voucher.create', $voucher, after: $voucher->only(['code', 'type', 'value', 'min_order', 'is_active']),
            summary: "Membuat voucher {$voucher->code}");

        return redirect()->route('admin.vouchers.index')
            ->with('success', "Voucher {$data['code']} berhasil dibuat.");
    }

    public function toggle(Voucher $voucher)
    {
        $before = $voucher->is_active;
        $voucher->update(['is_active' => ! $voucher->is_active]);

        $statusLabel = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';

        Audit::log('voucher.toggle', $voucher,
            before: ['is_active' => $before],
            after: ['is_active' => $voucher->is_active],
            summary: "Voucher {$voucher->code} {$statusLabel}");

        return back()->with('success', "Voucher {$voucher->code} {$statusLabel}.");
    }

    public function destroy(Voucher $voucher)
    {
        // Tidak hapus voucher yang sudah pernah dipakai — biar audit utuh.
        if ($voucher->used_count > 0) {
            return back()->with('error', 'Voucher sudah pernah dipakai, tidak bisa dihapus. Nonaktifkan saja.');
        }

        $code = $voucher->code;
        $snapshot = $voucher->only(['code', 'type', 'value', 'min_order']);
        $voucher->delete();

        Audit::log('voucher.delete', null,
            before: $snapshot,
            summary: "Menghapus voucher {$code}");

        return back()->with('success', "Voucher {$code} dihapus.");
    }

    /**
     * Endpoint customer cek voucher di form order create.
     * Mengembalikan info diskon (dalam rupiah) berdasarkan subtotal yang
     * dikirim dari frontend, atau pesan error kalau voucher tidak berlaku.
     */
    public function check(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:30'],
            'subtotal' => ['required', 'integer', 'min:0'],
        ]);

        $voucher = Voucher::where('code', Str::upper($data['code']))->first();

        if (! $voucher || ! $voucher->isCurrentlyValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher tidak ditemukan atau sudah tidak berlaku.',
            ]);
        }

        if ($data['subtotal'] < $voucher->min_order) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimum order Rp '.number_format($voucher->min_order, 0, ',', '.').' untuk pakai voucher ini.',
            ]);
        }

        $discount = $voucher->calculateDiscount($data['subtotal']);

        return response()->json([
            'valid' => true,
            'code' => $voucher->code,
            'description' => $voucher->description,
            'discount' => $discount,
            'discount_label' => 'Rp '.number_format($discount, 0, ',', '.'),
        ]);
    }
}
