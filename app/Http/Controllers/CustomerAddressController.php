<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAddressController extends Controller
{
    public function index()
    {
        $alamat = Auth::user()->customerAddresses()
            ->orderByDesc('is_primary')
            ->orderByDesc('last_used_at')
            ->get();

        return view('roles.customer.addresses.index', compact('alamat'));
    }

    public function create()
    {
        return view('roles.customer.addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:80'],
            'recipient_name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'full_address' => ['required', 'string', 'min:10'],
            'distance_km' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'zone' => ['nullable', 'in:A,B,C'],
            'notes' => ['nullable', 'string', 'max:300'],
        ]);

        $validated['customer_id'] = Auth::id();

        $hasAddress = Auth::user()->customerAddresses()->exists();
        $wantPrimary = $request->boolean('is_primary', false);

        if (! $hasAddress) {
            $validated['is_primary'] = true;
        } elseif ($wantPrimary) {
            Auth::user()->customerAddresses()->update(['is_primary' => false]);
            $validated['is_primary'] = true;
        } else {
            $validated['is_primary'] = false;
        }

        if (empty($validated['zone']) && isset($validated['distance_km'])) {
            $km = (float) $validated['distance_km'];
            $validated['zone'] = $km > 7 ? 'C' : ($km > 3 ? 'B' : 'A');
        }

        CustomerAddress::create($validated);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit(CustomerAddress $address)
    {
        abort_if($address->customer_id !== Auth::id(), 403);

        return view('roles.customer.addresses.edit', compact('address'));
    }

    public function update(Request $request, CustomerAddress $address)
    {
        abort_if($address->customer_id !== Auth::id(), 403);

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:80'],
            'recipient_name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'full_address' => ['required', 'string', 'min:10'],
            'distance_km' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'zone' => ['nullable', 'in:A,B,C'],
            'notes' => ['nullable', 'string', 'max:300'],
        ]);

        if (empty($validated['zone']) && isset($validated['distance_km'])) {
            $km = (float) $validated['distance_km'];
            $validated['zone'] = $km > 7 ? 'C' : ($km > 3 ? 'B' : 'A');
        }

        if ($request->boolean('is_primary') && ! $address->is_primary) {
            Auth::user()->customerAddresses()->update(['is_primary' => false]);
            $validated['is_primary'] = true;
        }

        $address->update($validated);

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(CustomerAddress $address)
    {
        abort_if($address->customer_id !== Auth::id(), 403);

        $wasPrimary = $address->is_primary;

        $address->delete();

        // Kalau yang dihapus adalah alamat utama, promote alamat lain (yang
        // terbaru dipakai) jadi utama supaya customer tidak terjebak tanpa
        // alamat utama setelah delete.
        if ($wasPrimary) {
            $next = Auth::user()->customerAddresses()
                ->orderByDesc('last_used_at')
                ->orderByDesc('id')
                ->first();

            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil dihapus.');
    }

    public function setPrimary(CustomerAddress $address)
    {
        abort_if($address->customer_id !== Auth::id(), 403);

        Auth::user()->customerAddresses()->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return back()->with('success', 'Alamat utama berhasil diperbarui.');
    }
}
