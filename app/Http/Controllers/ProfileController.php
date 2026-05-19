<?php
/* ══════════════════════════════════════════════════════════════
   FILE: app/Http/Controllers/ProfileController.php
══════════════════════════════════════════════════════════════ */
namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /** GET /profile/edit — Form edit profil (semua role) */
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /** PATCH /profile — Update nama, email, phone, avatar */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'phone'  => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Reset email_verified_at jika email berubah
        if ($user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }

        $user->fill($validated)->save();

        return back()->with('status', 'profile-updated');
    }

    /** DELETE /profile — Hapus akun (customer only) */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /** GET /customer/profile — Profil customer */
    public function customerShow()
    {
        return view('roles.customer.profile.show', ['user' => Auth::user()]);
    }

    /** GET /admin/profile — Profil admin */
    public function adminShow()
    {
        return view('roles.admin.profile', ['user' => Auth::user()]);
    }
}