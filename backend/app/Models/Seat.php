<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    public $timestamps = false;

    protected $fillable = ['screen_id', 'row_label', 'seat_number', 'seat_category_id', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SeatCategory::class, 'seat_category_id');
    }
}
