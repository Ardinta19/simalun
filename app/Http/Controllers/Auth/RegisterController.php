<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::query()->create([
            'name'     => $request->name,
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
        // Note: we redirect back to register page to show success state
        // Alternatively, redirect to login: return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
    }
}