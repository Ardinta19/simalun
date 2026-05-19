<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceEntry extends Model
{
    protected $fillable = [
        'entry_date',
        'period_key',
        'entry_type',
        'category', // income, operational, salary, investment
        'amount',
        'source_type',
        'source_id',
        'order_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
