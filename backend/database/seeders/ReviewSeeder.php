<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Chỉ người đã xem (đơn confirmed, suất đã chiếu xong) mới đánh giá; mỗi người 1 đánh giá / phim.
 */
class ReviewSeeder extends Seeder
{
    private const COMMENTS = [
        5 => ['Phim quá hay, xem xong vẫn còn cảm xúc!', 'Diễn xuất tuyệt vời, kịch bản chặt chẽ.', 'Đáng đồng tiền bát gạo, sẽ xem lại lần nữa.', 'Hình ảnh và âm thanh đỉnh cao.'],
        4 => ['Phim hay, đoạn giữa hơi chậm một chút.', 'Nội dung ổn, diễn viên chính rất có duyên.', 'Xem giải trí rất được, nhạc phim hay.'],
        3 => ['Phim tạm được, không quá ấn tượng.', 'Mở đầu hấp dẫn nhưng kết thúc hơi vội.', 'Xem một lần cho biết.'],
        2 => ['Kịch bản dễ đoán, khá thất vọng.', 'Phim dài lê thê, nhiều đoạn thừa.'],
        1 => ['Không hợp gu mình chút nào.', 'Rất chán, bỏ về giữa chừng.'],
    ];

    public function run(): void
    {
        $now = now();
        $watched = DB::table('bookings')
            ->join('showtimes', 'showtimes.id', '=', 'bookings.showtime_id')
            ->join('movies', 'movies.id', '=', 'showtimes.movie_id')
            ->where('bookings.status', 'confirmed')
            ->whereRaw('showtimes.start_time + INTERVAL movies.duration_minutes MINUTE < ?', [$now])
            ->orderBy('showtimes.start_time')
            ->get(['bookings.user_id', 'showtimes.movie_id', 'showtimes.start_time', 'movies.duration_minutes']);

        $reviews = [];
        foreach ($watched as $row) {
            $key = "{$row->user_id}-{$row->movie_id}";
            if (isset($reviews[$key]) || mt_rand(1, 100) > 45) {
                continue;
            }

            $roll = mt_rand(1, 100);
            $rating = $roll <= 40 ? 5 : ($roll <= 70 ? 4 : ($roll <= 85 ? 3 : ($roll <= 95 ? 2 : 1)));
            $hidden = mt_rand(1, 100) <= 6; // bị admin ẩn vì không phù hợp
            $createdAt = Carbon::parse($row->start_time)->addMinutes($row->duration_minutes + mt_rand(10, 48 * 60));
            if ($createdAt->gt($now)) {
                $createdAt = $now->copy()->subMinutes(mt_rand(1, 60));
            }

            $comments = self::COMMENTS[$rating];
            $reviews[$key] = [
                'user_id' => $row->user_id,
                'movie_id' => $row->movie_id,
                'rating' => $rating,
                'content' => $hidden ? 'Inbox mình để mua vé giá rẻ, link ở trang cá nhân!!!' : (mt_rand(1, 100) <= 85 ? $comments[array_rand($comments)] : null),
                'status' => $hidden ? 'hidden' : 'visible',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        DB::table('reviews')->insert(array_values($reviews));
    }
}
