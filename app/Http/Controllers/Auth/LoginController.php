<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle authentication dan role-based redirect
     */
    public function authenticate(Request $request)
    {
        // Validasi input
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
        
        // Attempt authentication
        if (Auth::attempt([$field => $identifier, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Ambil role dari database
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
     * Redirect berdasarkan role
     */
    private function redirectByRole(string $role)
    {
        return redirect()->intended(route('dashboard'));
    }
}