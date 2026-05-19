<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Fields that should be sanitized (strip dangerous HTML/script tags).
     * Preserves normal text content but removes potential XSS vectors.
     */
    protected array $sanitizeFields = [
        'address',
        'address_note',
        'notes',
        'full_address',
        'label',
        'recipient_name',
        'customer_name',
        'name',
        'status_note',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            if (is_string($value) && in_array($key, $this->sanitizeFields, true)) {
                $value = $this->sanitize($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }

    /**
     * Strip dangerous tags while preserving safe text content.
     * Does NOT use htmlspecialchars (that would break display).
     * Instead, strips <script>, <iframe>, event handlers, etc.
     */
    protected function sanitize(string $value): string
    {
        // Remove null bytes
        $value = str_replace(chr(0), '', $value);

        // Remove script tags and their content
        $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);

        // Remove iframe/object/embed tags
        $value = preg_replace('/<(iframe|object|embed|link|meta)\b[^>]*>/is', '', $value);

        // Remove event handlers (onclick, onerror, onload, etc.)
        $value = preg_replace('/\bon\w+\s*=\s*["\'][^"\']*["\']/i', '', $value);
        $value = preg_replace('/\bon\w+\s*=\s*[^\s>]*/i', '', $value);

        // Remove javascript: and data: protocols in href/src
        $value = preg_replace('/(?:href|src)\s*=\s*["\']?\s*(?:javascript|data):/i', '', $value);

        // Strip remaining HTML tags (keep text only)
        $value = strip_tags($value);

        // Trim excessive whitespace
        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }
}
