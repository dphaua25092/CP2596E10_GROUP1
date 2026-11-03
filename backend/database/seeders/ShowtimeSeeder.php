<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Screen;
use App\Models\SeatCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Suất chiếu từ 14 ngày trước đến 6 ngày tới cho mọi phòng của rạp đang hoạt động.
 * Các khung giờ cách nhau 3,5 giờ, dài hơn phim dài nhất nên không bao giờ chồng giờ.
 */
class ShowtimeSeeder extends Seeder
{
    private const SLOTS = ['09:00', '12:30', '16:00', '19:30', '22:45'];

    // Giá gốc theo hạng ghế; thành phố lớn đắt hơn
    private const PRICES = [
        'default' => ['GOLD' => 75000, 'PLATINUM' => 100000, 'BOX' => 160000],
        'big_city' => ['GOLD' => 90000, 'PLATINUM' => 120000, 'BOX' => 190000],
    ];

    public function run(): void
    {
        $movies = Movie::all();
        $categoryIds = SeatCategory::pluck('id', 'code');
        $screens = Screen::with('theater.city')
            ->whereHas('theater', fn ($q) => $q->where('is_active', true))
            ->get();

        $rows = [];
        for ($day = -14; $day <= 6; $day++) {
            $date = today()->addDays($day);
            $showing = $movies->filter(fn (Movie $m) => $m->release_date->lte($date)
                && ($m->end_date === null || $m->end_date->gte($date)))->values();

            foreach ($screens as $s => $screen) {
                foreach (self::SLOTS as $k => $slot) {
                    $movie = $showing[($s + $k + $day + 14) % $showing->count()];
                    $start = $date->copy()->setTimeFromTimeString($slot);
                    $rows[] = [
                        'movie_id' => $movie->id,
                        'screen_id' => $screen->id,
                        'start_time' => $start,
                        // vài suất sắp tới bị hủy
                        'status' => $day > 0 && mt_rand(1, 60) === 1 ? 'cancelled' : 'scheduled',
                        'created_at' => $start->copy()->subDays(10),
                        'updated_at' => $start->copy()->subDays(10),
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('showtimes')->insert($chunk);
        }

        $prices = [];
        $showtimes = DB::table('showtimes')
            ->join('screens', 'screens.id', '=', 'showtimes.screen_id')
            ->join('theaters', 'theaters.id', '=', 'screens.theater_id')
            ->join('cities', 'cities.id', '=', 'theaters.city_id')
            ->get(['showtimes.id', 'cities.name as city']);
        foreach ($showtimes as $showtime) {
            $table = in_array($showtime->city, ['TP. Hồ Chí Minh', 'Hà Nội']) ? 'big_city' : 'default';
            foreach (self::PRICES[$table] as $code => $price) {
                $prices[] = ['showtime_id' => $showtime->id, 'seat_category_id' => $categoryIds[$code], 'base_price' => $price];
            }
        }

        foreach (array_chunk($prices, 1000) as $chunk) {
            DB::table('showtime_prices')->insert($chunk);
        }
    }
}
