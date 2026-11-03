<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Showtime extends Model
{
    protected $fillable = ['movie_id', 'screen_id', 'start_time', 'status'];

    protected function casts(): array
    {
        return ['start_time' => 'datetime'];
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    // Giá gốc theo hạng ghế (bảng showtime_prices), giá ở pivot base_price
    public function seatCategories(): BelongsToMany
    {
        return $this->belongsToMany(SeatCategory::class, 'showtime_prices')
            ->withPivot('base_price');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Ghế đã có người giữ/đặt trong suất này
    public function bookedSeats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class, 'booking_seats')
            ->withPivot('booking_id', 'price');
    }
}
