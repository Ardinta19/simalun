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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $userRole = Auth::user()->role;

        if ($userRole === $role) {
            return $next($request);
        }

        return match ($userRole) {
            'admin'  => redirect()->route('dashboard.admin'),
            'driver' => redirect()->route('dashboard.driver'),
            default  => redirect()->route('customer.dashboard'),
        };
    }
}
