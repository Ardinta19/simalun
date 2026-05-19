<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = [
        'name', 'slug', 'pricing_model', 'price_per_kg', 'unit_price', 'unit_type', 'estimated_hours', 'description', 'is_active', 'service_category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getEffectiveUnitPriceAttribute(): int
    {
        if (!is_null($this->unit_price)) {
            return (int) $this->unit_price;
        }

        return (int) $this->price_per_kg;
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Alias: ERD uses estimated_duration_hours, DB uses estimated_hours
     */
    public function getEstimatedDurationHoursAttribute(): ?int
    {
        return $this->estimated_hours;
    }

    /**
     * Format harga ke Rupiah singkat: 8000 → "8k"
     */
    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_kg / 1000, 0) . 'k';
    }

    /**
     * Estimasi waktu dalam format human readable
     */
    public function getEstimatedLabelAttribute(): string
    {
        $h = $this->estimated_hours;
        if ($h >= 48) return 'Estimasi ' . round($h / 24) . '-' . (round($h / 24) + 1) . ' Hari';
        return 'Estimasi ' . $h . ' Jam';
    }
}