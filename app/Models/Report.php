<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'description',
        'screenshot_path',
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'bug' => 'Bug / Error',
            'saran' => 'Saran & Masukan',
            'komplain' => 'Komplain Layanan',
            default => ucfirst($this->category),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open' => 'Menunggu',
            'in_progress' => 'Ditangani',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open' => '#f59e0b',
            'in_progress' => '#0077b6',
            'resolved' => '#059669',
            'closed' => '#6b7280',
            default => '#6b7280',
        };
    }
}
