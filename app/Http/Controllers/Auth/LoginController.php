<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle authentication with SECURE role-based redirect
     * Role diambil dari DATABASE, bukan dari input user
     */
    public function authenticate(Request $request)
    {
        // Validasi input HANYA identifier & password
        $credentials = $request->validate([
            'identifier' => ['nullable', 'string'],
            'email'      => ['nullable', 'string'],
            'password'   => ['required', 'string'],
        ], [
            'password.required'   => 'Password wajib diisi',
        ]);

        $identifier = $credentials['identifier'] ?? $credentials['email'] ?? null;
        $password   = $credentials['password'];

        if (!$identifier) {
            return back()->withErrors([
                'identifier' => 'Email atau No. HP wajib diisi',
            ]);
        }

        // Cek apakah identifier adalah email atau phone
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        // Attempt auth TANPA role di array credentials
        // Role akan dicek otomatis oleh Laravel dari tabel users
        if (Auth::attempt([$field => $identifier, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // ✅ Ambil role DARI DATABASE (bukan dari input)
            $user = Auth::user();
            
            // Redirect sesuai role yang tersimpan di database
            return $this->redirectByRole($user->role);
        }

        // Jika gagal, kembali ke form dengan error (web-friendly)
        return back()->withErrors([
            'identifier' => 'Email/No. HP atau password yang Anda masukkan salah.',
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
     * Helper: Redirect berdasarkan role dari database
     */
    private function redirectByRole(string $role)
    {
        return redirect()->intended(route('dashboard'));
    }
}