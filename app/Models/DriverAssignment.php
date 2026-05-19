<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverAssignment extends Model
{
    protected $fillable = [
        'order_id',
        'driver_id',
        'assignment_type',
        'scheduled_date',
        'scheduled_time_start',
        'scheduled_time_end',
        'actual_time',
        'route_notes',
        'distance_km',
        'transport_fee',
        'assignment_status',
        'assigned_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time_start' => 'datetime:H:i',
        'scheduled_time_end' => 'datetime:H:i',
        'actual_time' => 'datetime',
        'distance_km' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
