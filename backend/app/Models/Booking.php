<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'showtime_id',
        'promotion_id',
        'discount_amount',
        'status',
        'hold_expires_at',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_amount' => 'integer',
            'hold_expires_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    // Khi attach phải truyền kèm showtime_id và price:
    // $booking->seats()->attach($seatId, ['showtime_id' => $booking->showtime_id, 'price' => $price])
    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class, 'booking_seats')
            ->withPivot('showtime_id', 'price');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
