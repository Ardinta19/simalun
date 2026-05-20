<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * Smart back-navigation helper.
 *
 * Logika:
 * 1. Cek query param `?back=` (explicit override dari link pengirim)
 * 2. Cek HTTP Referer — hanya jika masih same-host & bukan halaman sendiri
 * 3. Fallback ke route yang diberikan
 *
 * Cara pakai di view:
 *   <x-back-button fallback="customer.orders" />
 *   atau manual: BackUrl::resolve($request, 'customer.orders')
 */
class BackUrl
{
    /**
     * Resolve URL tujuan tombol "Kembali".
     */
    public static function resolve(Request $request, string $fallbackRoute, array $fallbackParams = []): string
    {
        // 1. Explicit ?back= param
        if ($back = $request->query('back')) {
            $decoded = urldecode($back);
            if (static::isSafeUrl($decoded)) {
                return $decoded;
            }
        }

        // 2. HTTP Referer (same-host, bukan current URL)
        $referer = $request->headers->get('referer');
        if ($referer && static::isSafeUrl($referer) && !static::isSameAs($referer, $request->fullUrl())) {
            return $referer;
        }

        // 3. Fallback ke named route
        try {
            return route($fallbackRoute, $fallbackParams);
        } catch (\Exception $e) {
            return url('/dashboard');
        }
    }

    /**
     * Generate query string `?back=current_url` untuk ditempel di link.
     * Berguna saat navigasi dari notifikasi/halaman lain.
     */
    public static function param(): string
    {
        return 'back=' . urlencode(url()->current());
    }

    /**
     * Cek apakah URL aman (same-host).
     */
    protected static function isSafeUrl(string $url): bool
    {
        $parsed = parse_url($url);

        if (!isset($parsed['host'])) {
            // Relative URL — aman
            return str_starts_with($url, '/');
        }

        return $parsed['host'] === request()->getHost();
    }

    /**
     * Cek apakah dua URL mengarah ke halaman yang sama (ignore query string).
     */
    protected static function isSameAs(string $url1, string $url2): bool
    {
        return strtok($url1, '?') === strtok($url2, '?');
    }
}
