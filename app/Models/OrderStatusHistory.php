<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    public const CREATED_AT = 'updated_at';

    public const UPDATED_AT = null;

    protected $fillable = [
        'order_id',
        'status_code',
        'status_note',
        'updated_by',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
