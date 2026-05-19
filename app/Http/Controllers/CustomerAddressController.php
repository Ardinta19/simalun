<?php
/* ══════════════════════════════════════════════════════════════
   FILE: app/Http/Controllers/CustomerAddressController.php
══════════════════════════════════════════════════════════════ */
namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAddressController extends Controller
{
    /** GET /customer/addresses */
    public function index()
    {
        $alamat = Auth::user()->customerAddresses()
            ->orderByDesc('is_primary')
            ->orderByDesc('last_used_at')
            ->get();

        return view('roles.customer.addresses.index', compact('alamat'));
    }

    /** GET /customer/addresses/create */
    public function create()
    {
        return view('roles.customer.addresses.create');
    }

    /** POST /customer/addresses */
    public function store(Request $request)
    {
        // Cegah membuat terlalu banyak alamat per customer.
        $existing = Auth::user()->customerAddresses()->count();
        if ($existing >= 10) {
            return back()
                ->withInput()
                ->with('error', 'Maksimum 10 alamat tersimpan. Hapus salah satu sebelum menambahkan baru.');
        }

        $validated = $request->validate([
            'label'          => ['required', 'string', 'max:80'],
            'recipient_name' => ['required', 'string', 'min:2', 'max:120'],
            'phone'          => ['nullable', 'string', 'min:8', 'max:30', 'regex:/^[0-9+\-\s]+$/'],
            'full_address'   => ['required', 'string', 'min:10', 'max:500'],
            'distance_km'    => ['nullable', 'numeric', 'min:0', 'max:50'],
            'zone'           => ['nullable', 'in:A,B,C'],
            'notes'          => ['nullable', 'string', 'max:300'],
        ], [
            'phone.regex' => 'Nomor HP hanya boleh berisi angka, +, -, atau spasi.',
        ]);

        $validated['customer_id'] = Auth::id();

        // Jika belum punya alamat, jadikan utama otomatis
        $count = Auth::user()->customerAddresses()->count();
        if ($count === 0) {
            $validated['is_primary'] = true;
        } else {
            $validated['is_primary'] = $request->boolean('is_primary', false);
            // Jika dijadikan primary, reset yang lain
            if ($validated['is_primary']) {
                Auth::user()->customerAddresses()->update(['is_primary' => false]);
            }
        }

        // Auto-set zone dari distance
        if (!isset($validated['zone']) && isset($validated['distance_km'])) {
            $km = (float) $validated['distance_km'];
            $validated['zone'] = $km <= 3 ? 'A' : ($km <= 7 ? 'B' : 'C');
        }

        CustomerAddress::create($validated);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan.');
    }

    /** GET /customer/addresses/{address}/edit */
    public function edit(CustomerAddress $address)
    {
        abort_if($address->customer_id !== Auth::id(), 403);
        return view('roles.customer.addresses.edit', compact('address'));
    }

    /** PUT /customer/addresses/{address} */
    public function update(Request $request, CustomerAddress $address)
    {
        abort_if($address->customer_id !== Auth::id(), 403);

        $validated = $request->validate([
            'label'          => ['required', 'string', 'max:80'],
            'recipient_name' => ['required', 'string', 'min:2', 'max:120'],
            'phone'          => ['nullable', 'string', 'min:8', 'max:30', 'regex:/^[0-9+\-\s]+$/'],
            'full_address'   => ['required', 'string', 'min:10', 'max:500'],
            'distance_km'    => ['nullable', 'numeric', 'min:0', 'max:50'],
            'zone'           => ['nullable', 'in:A,B,C'],
            'notes'          => ['nullable', 'string', 'max:300'],
        ], [
            'phone.regex' => 'Nomor HP hanya boleh berisi angka, +, -, atau spasi.',
        ]);

        $address->update($validated);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil diperbarui.');
    }

    /** DELETE /customer/addresses/{address} */
    public function destroy(CustomerAddress $address)
    {
        abort_if($address->customer_id !== Auth::id(), 403);

        // Jangan hapus alamat utama jika masih ada yang lain
        if ($address->is_primary && Auth::user()->customerAddresses()->count() > 1) {
            return back()->with('error', 'Tidak bisa menghapus alamat utama. Jadikan alamat lain sebagai utama terlebih dahulu.');
        }

        $address->delete();

        // Auto-set primary ke yang pertama jika tidak ada primary lagi
        $remaining = Auth::user()->customerAddresses()->first();
        if ($remaining && !Auth::user()->customerAddresses()->where('is_primary', true)->exists()) {
            $remaining->update(['is_primary' => true]);
        }

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil dihapus.');
    }

    /** PATCH /customer/addresses/{address}/primary */
    public function setPrimary(CustomerAddress $address)
    {
        abort_if($address->customer_id !== Auth::id(), 403);

        // Reset semua, set yang ini sebagai primary
        Auth::user()->customerAddresses()->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return back()->with('success', 'Alamat utama berhasil diperbarui.');
    }
}