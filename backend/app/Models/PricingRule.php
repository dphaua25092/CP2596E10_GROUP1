<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Giá động: cuối tuần, giờ cao điểm, hạng ghế
class PricingRule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'day_type',
        'from_time',
        'to_time',
        'seat_category_id',
        'adjustment_type',
        'adjustment_value',
        'priority',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'seat_category_id' => 'integer',
            'adjustment_value' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function seatCategory(): BelongsTo
    {
        return $this->belongsTo(SeatCategory::class);
    }

    public function matches(CarbonInterface $startTime, int $seatCategoryId): bool
    {
        if ($this->seat_category_id !== null && $this->seat_category_id !== $seatCategoryId) {
            return false;
        }

        if ($this->day_type === 'weekend' && ! $startTime->isWeekend()
            || $this->day_type === 'weekday' && $startTime->isWeekend()) {
            return false;
        }

        if ($this->from_time !== null) {
            $time = $startTime->format('H:i:s');

            return $time >= $this->from_time && $time <= $this->to_time;
        }

        return true;
    }

    /**
     * Giá một ghế = giá gốc của suất, áp lần lượt các quy tắc khớp (priority tăng dần),
     * làm tròn đến 1.000đ.
     *
     * @param  iterable<PricingRule>  $rules  các quy tắc đang bật
     */
    public static function priceFor(float $basePrice, CarbonInterface $startTime, int $seatCategoryId, iterable $rules): int
    {
        $price = $basePrice;

        foreach (collect($rules)->sortBy('priority') as $rule) {
            if (! $rule->matches($startTime, $seatCategoryId)) {
                continue;
            }

            $price += $rule->adjustment_type === 'percent'
                ? $price * $rule->adjustment_value / 100
                : $rule->adjustment_value;
        }

        return (int) max(0, round($price / 1000) * 1000);
    }
}
