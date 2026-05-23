<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    /**
     * Opsi B: Nomor HP WAJIB, email OPSIONAL.
     *
     * Alur:
     *  - HP digunakan sebagai identitas utama (boleh dipakai untuk login).
     *  - Email hanya pelengkap, boleh dikosongkan, tetapi kalau diisi harus valid & unik.
     *  - Alamat awal dari step-3 dipakai sebagai Alamat Utama (customer_addresses).
     */
    public function store(Request $request)
    {
        // Normalisasi nomor HP: hilangkan spasi/strip, buang awalan +62 / 62 / 0 → kanonik 8xxxxxxxxxx
        $phoneRaw = preg_replace('/[\s\-\.]/', '', (string) $request->input('phone', ''));
        $phoneRaw = preg_replace('/^(\+?62|0)/', '', $phoneRaw);
        $request->merge(['phone' => $phoneRaw]);

        // Email: kosongkan kalau memang tidak diisi (jangan paksa empty string masuk DB)
        $email = trim((string) $request->input('email', ''));
        $request->merge(['email' => $email !== '' ? strtolower($email) : null]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'gender' => ['required', 'in:L,P'],
            'phone' => [
                'required',
                'string',
                'regex:/^8[0-9]{8,12}$/',
                Rule::unique('users', 'phone')->whereNull('deleted_at'),
            ],
            'email' => [
                'nullable',
                'email:rfc',
                'max:150',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'password' => ['required', 'confirmed', Password::min(8)],
            'address' => ['required', 'string', 'min:10', 'max:300'],
            'address_note' => ['nullable', 'string', 'max:200'],
            'terms' => ['accepted'],
        ], [
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.regex' => 'Format nomor HP tidak valid (contoh: 81234567890).',
            'phone.unique' => 'Nomor HP sudah terdaftar. Silakan login.',
            'email.unique' => 'Email sudah terdaftar. Silakan pakai email lain atau kosongkan.',
            'terms.accepted' => 'Kamu harus menyetujui syarat & ketentuan.',
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = new User([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'password' => Hash::make($validated['password']),
            ]);
            // Role di-set lewat property assignment, bukan mass-fill —
            // gak ada cara $request->all() bocor ke role.
            $user->role = 'customer';
            $user->save();

            CustomerAddress::create([
                'customer_id' => $user->id,
                'label' => 'Alamat Utama',
                'recipient_name' => $user->name,
                'phone' => $user->phone,
                'full_address' => $validated['address'],
                'notes' => $validated['address_note'] ?? null,
                'zone' => 'A',
                'is_primary' => true,
            ]);

            return $user;
        });

        Auth::guard('web')->login($user);
        request()->session()->regenerate();

        return redirect()->route('customer.dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang di Azka Laundry!');
    }
}
