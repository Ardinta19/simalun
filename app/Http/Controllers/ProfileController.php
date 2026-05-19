<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $primaryAddress = null;
        if ($user->role === 'customer') {
            $primaryAddress = $user->customerAddresses()
                ->where('is_primary', true)
                ->first();
        }

        $view = match ($user->role) {
            'admin'  => 'roles.admin.profile_edit',
            'driver' => 'roles.driver.profile-edit',
            default  => 'roles.customer.profile.edit',
        };

        if (!view()->exists($view)) {
            $view = 'roles.customer.profile.edit';
        }

        return view($view, [
            'user'           => $user,
            'primaryAddress' => $primaryAddress,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'  => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }

        $user->fill($validated)->save();

        return back()->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function customerShow()
    {
        $user = Auth::user();

        $primaryAddress = $user->customerAddresses()
            ->where('is_primary', true)
            ->first();

        $totalAddresses = $user->customerAddresses()->count();

        return view('roles.customer.profile.show', compact('user', 'primaryAddress', 'totalAddresses'));
    }

    public function adminShow()
    {
        return view('roles.admin.profile', ['user' => Auth::user()]);
    }

    public function savePrimaryAddress(Request $request)
    {
        $user = Auth::user();

        abort_unless($user->role === 'customer', 403);

        $validated = $request->validate([
            'label'          => ['required', 'string', 'max:80'],
            'recipient_name' => ['required', 'string', 'max:120'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'full_address'   => ['required', 'string', 'min:10'],
            'distance_km'    => ['nullable', 'numeric', 'min:0', 'max:50'],
            'notes'          => ['nullable', 'string', 'max:300'],
        ]);

        $km = (float) ($validated['distance_km'] ?? 0);
        $validated['zone'] = $km > 7 ? 'C' : ($km > 3 ? 'B' : 'A');
        $validated['is_primary'] = true;

        $primary = $user->customerAddresses()->where('is_primary', true)->first();

        if ($primary) {
            $primary->update($validated);
        } else {
            $user->customerAddresses()->where('is_primary', true)->update(['is_primary' => false]);
            $validated['customer_id'] = $user->id;
            CustomerAddress::create($validated);
        }

        return back()->with('status', 'address-saved');
    }
}
