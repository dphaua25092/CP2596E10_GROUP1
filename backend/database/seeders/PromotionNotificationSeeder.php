<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Thông báo khuyến mãi gửi tới mọi khách hàng khi chương trình bắt đầu.
 */
class PromotionNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $customers = User::where('role', 'customer')->get(['id', 'created_at']);
        $promotions = Promotion::where('is_active', true)->whereDate('start_date', '<=', today())->get();

        $rows = [];
        foreach ($promotions as $promotion) {
            $sentAt = $promotion->start_date->copy()->setTime(8, 0);
            foreach ($customers as $customer) {
                if ($customer->created_at->gt($sentAt)) {
                    continue;
                }
                $rows[] = [
                    'user_id' => $customer->id,
                    'type' => 'promotion',
                    'title' => $promotion->title,
                    'message' => $promotion->content,
                    'booking_id' => null,
                    'promotion_id' => $promotion->id,
                    'read_at' => $sentAt->lt($now->copy()->subDay()) && mt_rand(1, 100) <= 60 ? $sentAt->copy()->addHours(mt_rand(1, 24)) : null,
                    'created_at' => $sentAt,
                ];
            }
        }

        DB::table('notifications')->insert($rows);
    }
}
