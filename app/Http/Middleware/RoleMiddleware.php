<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Jika bukan role yang diminta, redirect ke dashboard masing-masing atau 403
            if (Auth::check()) {
                $userRole = Auth::user()->role;
                return match ($userRole) {
                    'admin'  => redirect()->route('dashboard.admin'),
                    'driver' => redirect()->route('dashboard.driver'),
                    default  => redirect()->route('customer.dashboard'),
                };
            }
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}