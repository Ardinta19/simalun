<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    public const STATUS_AKTIF = ['menunggu', 'dijemput', 'dicuci', 'disetrika', 'siap', 'dikirim'];

    public const STATUS_SELESAI = 'selesai';

    public static function statusAktifSemua(): array
    {
        return self::STATUS_AKTIF;
    }

    public static function statusSelesaiSemua(): array
    {
        return [self::STATUS_SELESAI];
    }

    protected $fillable = [
        'order_code',
        'customer_id',
        'service_id',
        'address',
        'address_note',
        'zone',
        'pickup_cost',
        'pickup_date',
        'pickup_time',
        'weight_estimate',
        'weight_actual',
        'service_cost',
        'discount',
        'voucher_code',
        'total_cost',
        'status',
        'notes',
        'driver_id',
        'customer_address_id',
        'payment_method',
        'is_paid',
        'paid_at',
        'proof_image',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'paid_at' => 'datetime',
        'is_paid' => 'boolean',
        'weight_estimate' => 'decimal:1',
        'weight_actual' => 'decimal:1',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function customerAddress(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'customer_address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(DriverAssignment::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function rating(): HasOne
    {
        return $this->hasOne(OrderRating::class);
    }

    public static function generateCode(): string
    {
        $prefix = 'AL-'.date('Ymd');

        // Atomic: lock the latest row with this prefix to prevent race condition
        $lastOrder = self::where('order_code', 'like', $prefix.'%')
            ->lockForUpdate()
            ->orderByDesc('order_code')
            ->first();

        if ($lastOrder) {
            // Extract the sequence number from the last code (e.g., AL-20260520-007 → 7)
            $lastSeq = (int) substr($lastOrder->order_code, -3);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        return $prefix.'-'.str_pad($nextSeq, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'dijemput' => 'Dijemput',
            'dicuci' => 'Sedang Dicuci',
            'disetrika' => 'Disetrika',
            'siap' => 'Siap Dikirim',
            'dikirim' => 'Dalam Pengiriman',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => '#F59E0B',
            'dijemput' => '#3B82F6',
            'dicuci' => '#0EA5E9',
            'disetrika' => '#8B5CF6',
            'siap' => '#10B981',
            'dikirim' => '#06B6D4',
            'selesai' => '#22C55E',
            'dibatalkan' => '#EF4444',
            default => '#6B7280',
        };
    }

    public static function zoneCost(string $zone): int
    {
        return match ($zone) {
            'A' => 5000,
            'B' => 10000,
            'C' => 15000,
            default => 5000,
        };
    }

    /**
     * Hitung total dari komponen aktual (bukan dari kolom total_cost yang mungkin outdated).
     * Digunakan di list views agar konsisten.
     */
    public function getCalculatedTotalAttribute(): int
    {
        $serviceCost = (int) ($this->service_cost ?? 0);
        $itemTotal = $this->relationLoaded('items')
            ? (int) $this->items->where('service_id', '!=', $this->service_id)->sum('line_total')
            : (int) $this->items()->where('service_id', '!=', $this->service_id)->sum('line_total');
        $pickupCost = (int) ($this->pickup_cost ?? 0);
        $discount = (int) ($this->discount ?? 0);

        return $serviceCost + $itemTotal + $pickupCost - $discount;
    }
}
