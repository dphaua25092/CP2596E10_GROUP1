<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Thanh toán giả lập
class Payment extends Model
{
    public $timestamps = false;

    protected $fillable = ['booking_id', 'amount', 'method', 'status', 'transaction_ref', 'paid_at'];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
