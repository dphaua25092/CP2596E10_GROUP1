<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Hạng ghế: GOLD, PLATINUM, BOX
class SeatCategory extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'name', 'color'];

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
