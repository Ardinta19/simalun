<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'label',
        'recipient_name',
        'phone',
        'full_address',
        'distance_km',
        'zone',
        'is_primary',
        'last_used_at',
        'notes',
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'is_primary' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
