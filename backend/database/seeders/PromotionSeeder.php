<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        // [tiêu đề, nội dung, % giảm, bắt đầu (+/- ngày), kết thúc (+/- ngày), bật]
        $promotions = [
            ['Ưu đãi tháng này - Giảm 10% mọi vé', 'Giảm 10% cho tất cả vé, áp dụng mọi rạp, mọi suất chiếu.', 10, -10, 20, true],
            ['Khai trương CineBook Thủ Đức', 'Rạp mới tại TP. Thủ Đức chính thức mở cửa với 3 phòng chiếu hiện đại.', 0, -5, 25, true],
            ['Black Friday - Giảm 20%', 'Giảm 20% cho mọi vé trong 4 ngày Black Friday.', 20, 15, 18, true],
            ['Mừng Quốc khánh - Giảm 15%', 'Giảm 15% mọi vé dịp lễ Quốc khánh.', 15, -70, -60, true],
            ['Tuần lễ phim Hàn Quốc', 'Chuỗi suất chiếu đặc biệt các phim Hàn Quốc (chương trình đã tạm dừng).', 0, -3, 10, false],
        ];

        foreach ($promotions as [$title, $content, $percent, $from, $to, $active]) {
            Promotion::create([
                'title' => $title,
                'content' => $content,
                'discount_percent' => $percent,
                'start_date' => today()->addDays($from),
                'end_date' => today()->addDays($to),
                'is_active' => $active,
                'created_at' => now()->addDays(min($from, 0) - 3),
                'updated_at' => now()->addDays(min($from, 0) - 3),
            ]);
        }
    }
}
