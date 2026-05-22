<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order',
        'max_discount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    /**
     * Cek apakah voucher masih bisa dipakai sekarang (tanggal aktif &
     * usage_limit belum penuh). Tidak mengecek min_order — itu domain
     * lain (tergantung total order).
     */
    public function isCurrentlyValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $today = now()->toDateString();
        if ($this->valid_from && $this->valid_from->toDateString() > $today) {
            return false;
        }
        if ($this->valid_until && $this->valid_until->toDateString() < $today) {
            return false;
        }
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Hitung diskon untuk subtotal tertentu (service_cost + item_total + pickup_cost).
     * Mengembalikan int rupiah, sudah memperhitungkan max_discount cap untuk
     * type percent. Asumsinya caller sudah memastikan voucher valid &
     * subtotal >= min_order.
     */
    public function calculateDiscount(int $subtotal): int
    {
        if ($this->type === 'percent') {
            $raw = (int) floor($subtotal * $this->value / 100);
            if ($this->max_discount !== null) {
                return min($raw, $this->max_discount);
            }

            return $raw;
        }

        // type=fixed
        return min((int) $this->value, $subtotal);
    }

    /**
     * Format ringkas untuk ditampilkan ke customer (mis. "10% (maks Rp 5.000)").
     */
    public function getDisplayValueAttribute(): string
    {
        if ($this->type === 'percent') {
            $base = $this->value.'%';
            if ($this->max_discount) {
                $base .= ' (maks Rp '.number_format($this->max_discount, 0, ',', '.').')';
            }

            return $base;
        }

        return 'Rp '.number_format($this->value, 0, ',', '.');
    }
}
