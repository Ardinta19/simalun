<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use SoftDeletes;

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
