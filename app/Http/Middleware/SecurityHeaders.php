<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Set HTTP security headers untuk semua response web.
 *
 * Tujuan masing-masing:
 *  - X-Content-Type-Options: nosniff — browser gak boleh ngira-ngira
 *    MIME type, mencegah MIME confusion attack (mis. .jpg di-render
 *    sebagai HTML/JS karena content-nya kelihatan begitu).
 *
 *  - X-Frame-Options: DENY — mencegah clickjacking. Halaman SIMALUN
 *    tidak punya use case di-embed di iframe situs lain.
 *
 *  - Referrer-Policy: strict-origin-when-cross-origin — saat user klik
 *    link keluar (mis. ke maps.app.goo.gl), cuma origin yang dikirim,
 *    bukan path lengkap dengan order_code dll.
 *
 *  - Permissions-Policy: matikan API browser yang gak dipakai (camera,
 *    microphone, geolocation). Mengurangi attack surface kalau ada
 *    XSS yang lolos.
 *
 *  - X-XSS-Protection: 0 — sengaja di-disable. Filter XSS lawas browser
 *    sekarang jadi vektor serangan tambahan (XS-Leaks). Kita andalkan
 *    Blade auto-escape + SanitizeInput middleware + CSP via nginx kalau
 *    perlu di production.
 *
 * CSP TIDAK dipasang di sini — terlalu disruptif untuk inline <style>
 * yang banyak dipakai role views. Pasang di reverse proxy (nginx)
 * dengan nonce-based supaya bisa digarap incremental.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('X-XSS-Protection', '0');

        return $response;
    }
}
