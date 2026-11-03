<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Dữ liệu mẫu tính theo ngày chạy seed: luôn có phim đang chiếu / sắp chiếu,
     * suất chiếu 14 ngày trước đến 6 ngày tới, đơn đặt vé đã qua và sắp tới.
     */
    public function run(): void
    {
        mt_srand(2026);
        fake()->seed(2026);

        $this->call([
            LanguageSeeder::class,
            CitySeeder::class,
            UserSeeder::class,
            GenreSeeder::class,
            MovieSeeder::class,
            PromotionSeeder::class,
            SeatCategorySeeder::class,
            TheaterSeeder::class,
            PricingRuleSeeder::class,
            ShowtimeSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
            PromotionNotificationSeeder::class,
            AdminLogSeeder::class,
        ]);
    }
}
