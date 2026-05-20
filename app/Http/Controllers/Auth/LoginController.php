<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle authentication dan role-based redirect.
     *
     * Login mendukung 3 format identifier:
     *  - Email  → dicari di kolom `email`
     *  - HP raw → 081234567890, +6281234567890, 6281234567890
     *            → dinormalisasi ke format DB: 81234567890
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => ['nullable', 'string'],
            'email'      => ['nullable', 'string'],
            'password'   => ['required', 'string'],
        ], [
            'password.required' => 'Password wajib diisi',
        ]);

        $identifier = trim($credentials['identifier'] ?? $credentials['email'] ?? '');
        $password   = $credentials['password'];

        if (!$identifier) {
            return back()->withErrors([
                'identifier' => 'Email atau No. HP wajib diisi',
            ])->onlyInput('identifier');
        }

        // Tentukan apakah email atau phone
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
            $value = strtolower($identifier);
        } else {
            $field = 'phone';
            // Normalisasi ke format kanonik yang disimpan di DB: 8xxxxxxxxxx
            $value = preg_replace('/[\s\-\.]/', '', $identifier);
            $value = preg_replace('/^(\+?62|0)/', '', $value);
        }

        if (Auth::attempt([$field => $value, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user()->role);
        }

        return back()->withErrors([
            'identifier' => 'Email/No. HP atau password yang kamu masukkan salah.',
        ])->onlyInput('identifier');
    }

    /**
     * Logout handler
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/'); // Redirect ke splash/home
    }

    /**
     * Redirect berdasarkan role
     */
    private function redirectByRole(string $role)
    {
        return redirect()->intended(route('dashboard'));
    }
}