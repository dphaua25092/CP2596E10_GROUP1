<?php

namespace Database\Seeders;

use App\Models\PricingRule;
use App\Models\SeatCategory;
use Illuminate\Database\Seeder;

class PricingRuleSeeder extends Seeder
{
    public function run(): void
    {
        $boxId = SeatCategory::where('code', 'BOX')->value('id');

        $rules = [
            ['name' => 'Phụ thu cuối tuần', 'day_type' => 'weekend', 'adjustment_type' => 'fixed', 'adjustment_value' => 10000, 'priority' => 1],
            ['name' => 'Giờ vàng buổi sáng', 'from_time' => '08:00:00', 'to_time' => '11:59:59', 'adjustment_type' => 'percent', 'adjustment_value' => -20, 'priority' => 2],
            ['name' => 'Suất khuya', 'from_time' => '22:00:00', 'to_time' => '23:59:59', 'adjustment_type' => 'percent', 'adjustment_value' => -10, 'priority' => 2],
            ['name' => 'Phụ thu ghế Box cuối tuần', 'day_type' => 'weekend', 'seat_category_id' => $boxId, 'adjustment_type' => 'fixed', 'adjustment_value' => 20000, 'priority' => 3],
            ['name' => 'Giảm ngày thường (tạm tắt)', 'day_type' => 'weekday', 'adjustment_type' => 'percent', 'adjustment_value' => -5, 'priority' => 0, 'is_active' => false],
        ];

        foreach ($rules as $rule) {
            PricingRule::create($rule);
        }
    }
}
