<?php

namespace App\Support;

/**
 * Helper statis untuk akses data usaha laundry.
 * Semua data diambil dari config/laundry.php — single source of truth.
 */
class Laundry
{
    public static function name(): string
    {
        return config('laundry.name', 'Azka Laundry');
    }

    public static function tagline(): string
    {
        return config('laundry.tagline', 'Layanan Laundry Terpercaya');
    }

    public static function version(): string
    {
        return config('laundry.version', '2.4.0');
    }

    public static function address(): string
    {
        return config('laundry.address', '');
    }

    public static function googleMaps(): string
    {
        return config('laundry.google_maps', '#');
    }

    public static function phone(): string
    {
        return config('laundry.phone', '');
    }

    public static function phoneDisplay(): string
    {
        return config('laundry.phone_display', '');
    }

    public static function whatsapp(): string
    {
        return config('laundry.whatsapp', '');
    }

    public static function waLink(string $message = ''): string
    {
        $number = static::whatsapp();
        $url = "https://wa.me/{$number}";

        if ($message) {
            $url .= '?text='.urlencode($message);
        }

        return $url;
    }

    public static function hours(string $type = 'weekday'): string
    {
        return config("laundry.hours.{$type}", '08:00 - 21:00');
    }

    public static function social(string $platform): string
    {
        return config("laundry.social.{$platform}", '');
    }

    /**
     * Data lengkap untuk struk/receipt.
     */
    public static function receiptHeader(): array
    {
        return [
            'name' => static::name(),
            'address' => static::address(),
            'phone' => static::phoneDisplay(),
            'maps' => static::googleMaps(),
        ];
    }
}
