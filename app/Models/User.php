<?php

namespace App\Models;

use App\Notifications\Auth\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * Sensitive privilege columns (`role`, `is_active`, `email_verified_at`)
     * sengaja TIDAK di-fillable. Set lewat property assignment supaya
     * gak ada cara `User::create($request->all())` accidentally bikin
     * customer dengan role=admin. Caller yang sah harus eksplisit:
     *
     *     $user = new User([...mass-fields]);
     *     $user->role = 'customer';
     *     $user->save();
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * `last_assigned_at` pakai precision microsecond (.u) supaya round-robin
     * driver assignment deterministik bahkan saat dua mark terjadi di
     * detik yang sama (mis. order bersamaan).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_assigned_at' => 'datetime:Y-m-d H:i:s.u',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function customerAddresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    public function customerOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function driverOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
