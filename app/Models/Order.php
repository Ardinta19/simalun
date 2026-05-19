<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
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
        'pickup_date'     => 'date',
        'paid_at'         => 'datetime',
        'is_paid'         => 'boolean',
        'weight_estimate' => 'decimal:1',
        'weight_actual'   => 'decimal:1',
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

    public static function generateCode(): string
    {
        $prefix = 'AL-' . date('Ymd');
        $last   = self::where('order_code', 'like', $prefix . '%')->count();

        return $prefix . '-' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu'   => 'Menunggu',
            'dijemput'   => 'Dijemput',
            'dicuci'     => 'Sedang Dicuci',
            'disetrika'  => 'Disetrika',
            'siap'       => 'Siap Dikirim',
            'dikirim'    => 'Dalam Pengiriman',
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'menunggu'   => '#F59E0B',
            'dijemput'   => '#3B82F6',
            'dicuci'     => '#0EA5E9',
            'disetrika'  => '#8B5CF6',
            'siap'       => '#10B981',
            'dikirim'    => '#06B6D4',
            'selesai'    => '#22C55E',
            'dibatalkan' => '#EF4444',
            default      => '#6B7280',
        };
    }

    public static function zoneCost(string $zone): int
    {
        return match ($zone) {
            'A'     => 5000,
            'B'     => 10000,
            'C'     => 15000,
            default => 5000,
        };
    }
}
