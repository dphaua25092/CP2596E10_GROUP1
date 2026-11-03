<?php

namespace Database\Seeders;

use App\Models\PricingRule;
use App\Models\Promotion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Đơn đặt vé + ghế + thanh toán + thông báo đặt vé / nhắc lịch.
 * Giá ghế tính như ứng dụng thật: giá gốc của suất -> quy tắc giá động -> khuyến mãi.
 * Đơn không thành công (failed / expired / cancelled) đã nhả ghế nên không có booking_seats.
 */
class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $demoCustomer = User::where('email', 'khachhang@cinebook.vn')->value('id');
        $customers = User::where('role', 'customer')->where('is_active', true)
            ->whereKeyNot($demoCustomer)->pluck('id')->all();
        $rules = PricingRule::where('is_active', true)->get();
        $promotions = Promotion::where('is_active', true)->where('discount_percent', '>', 0)->get();

        $seatsByScreen = DB::table('seats')->where('is_active', true)
            ->get(['id', 'screen_id', 'seat_category_id'])
            ->groupBy('screen_id');
        $pricesByShowtime = DB::table('showtime_prices')->get()->groupBy('showtime_id');
        $showtimes = DB::table('showtimes')
            ->join('movies', 'movies.id', '=', 'showtimes.movie_id')
            ->where('showtimes.status', 'scheduled')
            ->orderBy('showtimes.start_time')
            ->get(['showtimes.id', 'showtimes.screen_id', 'showtimes.start_time', 'movies.title']);

        $codes = [];
        $bookingSeats = [];
        $payments = [];
        $notifications = [];
        $txn = 0;

        foreach ($showtimes as $showtime) {
            $start = Carbon::parse($showtime->start_time);
            $isPast = $start->lt($now);
            // suất đã qua đông khách hơn suất sắp tới
            $bookingCount = mt_rand(1, 100) <= ($isPast ? 65 : 35) ? mt_rand(1, $isPast ? 4 : 2) : 0;

            $prices = $pricesByShowtime[$showtime->id]->pluck('base_price', 'seat_category_id');
            $available = $seatsByScreen[$showtime->screen_id]->groupBy('seat_category_id')->map->shuffle();

            for ($b = 0; $b < $bookingCount; $b++) {
                // hạng ghế: Gold 70%, Platinum 25%, Box 5%
                $roll = mt_rand(1, 100);
                $categoryId = $prices->keys()[$roll <= 70 ? 0 : ($roll <= 95 ? 1 : 2)];
                $quantity = mt_rand(1, $categoryId === $prices->keys()[2] ? 2 : 4);
                if ($available[$categoryId]->count() < $quantity) {
                    continue;
                }

                // đặt trước giờ chiếu từ 1 giờ đến 5 ngày, và không ở tương lai
                $createdAt = $start->copy()->subMinutes(mt_rand(60, 5 * 24 * 60));
                if ($createdAt->gt($now)) {
                    $createdAt = $now->copy()->subMinutes(mt_rand(5, 3 * 24 * 60));
                }

                $roll = mt_rand(1, 100);
                $status = $roll <= 88 ? 'confirmed' : ($roll <= 93 ? 'failed' : ($roll <= 97 ? 'expired' : 'cancelled'));

                $seatPrice = PricingRule::priceFor((float) $prices[$categoryId], $start, $categoryId, $rules);
                $subtotal = $seatPrice * $quantity;
                $promotion = $status === 'confirmed'
                    ? $promotions->first(fn ($p) => $p->start_date->lte($createdAt) && $p->end_date->gte($createdAt->copy()->startOfDay()))
                    : null;
                $discount = $promotion ? (int) round($subtotal * $promotion->discount_percent / 100 / 1000) * 1000 : 0;

                do {
                    $code = 'CB'.Str::upper(Str::random(8));
                } while (isset($codes[$code]));
                $codes[$code] = true;

                // tài khoản demo có khoảng 15 đơn để xem lịch sử đặt vé đã qua / sắp tới
                $userId = mt_rand(1, 150) === 1 ? $demoCustomer : $customers[array_rand($customers)];
                $confirmedAt = $status === 'confirmed' ? $createdAt->copy()->addMinutes(mt_rand(1, 8)) : null;

                $bookingId = DB::table('bookings')->insertGetId([
                    'booking_code' => $code,
                    'user_id' => $userId,
                    'showtime_id' => $showtime->id,
                    'promotion_id' => $promotion?->id,
                    'discount_amount' => $discount,
                    'status' => $status,
                    'hold_expires_at' => $createdAt->copy()->addMinutes(10),
                    'confirmed_at' => $confirmedAt,
                    'created_at' => $createdAt,
                    'updated_at' => $confirmedAt ?? $createdAt->copy()->addMinutes(10),
                ]);

                if ($status === 'confirmed') {
                    foreach ($available[$categoryId]->splice(0, $quantity) as $seat) {
                        $bookingSeats[] = ['showtime_id' => $showtime->id, 'seat_id' => $seat->id, 'booking_id' => $bookingId, 'price' => $seatPrice];
                    }
                }

                $method = mt_rand(0, 1) ? 'dummy_card' : 'dummy_wallet';
                $amount = $subtotal - $discount;
                if ($status === 'failed' || ($status === 'confirmed' && mt_rand(1, 100) <= 15)) {
                    $payments[] = ['booking_id' => $bookingId, 'amount' => $amount, 'method' => $method, 'status' => 'failed',
                        'transaction_ref' => sprintf('TXN%08d', ++$txn), 'paid_at' => $createdAt->copy()->addMinutes(1)];
                }
                if ($status === 'confirmed') {
                    $payments[] = ['booking_id' => $bookingId, 'amount' => $amount, 'method' => $method, 'status' => 'success',
                        'transaction_ref' => sprintf('TXN%08d', ++$txn), 'paid_at' => $confirmedAt];

                    $notifications[] = $this->notification($userId, 'booking_success', $bookingId, $confirmedAt, $now,
                        'Đặt vé thành công', "Đặt vé {$showtime->title} thành công. Mã đặt vé: {$code}.");

                    $remindAt = $start->copy()->subHours(2);
                    if ($remindAt->lt($now) && $remindAt->gt($confirmedAt)) {
                        $notifications[] = $this->notification($userId, 'show_reminder', $bookingId, $remindAt, $now,
                            'Sắp đến giờ chiếu', "Phim {$showtime->title} sẽ bắt đầu lúc {$start->format('H:i d/m/Y')}. Mã đặt vé: {$code}.");
                    }
                } elseif ($status === 'failed') {
                    $notifications[] = $this->notification($userId, 'booking_failed', $bookingId, $createdAt->copy()->addMinutes(1), $now,
                        'Đặt vé thất bại', "Thanh toán cho đơn {$code} không thành công, ghế đã được nhả.");
                }
            }
        }

        foreach (['booking_seats' => $bookingSeats, 'payments' => $payments, 'notifications' => $notifications] as $table => $rows) {
            foreach (array_chunk($rows, 1000) as $chunk) {
                DB::table($table)->insert($chunk);
            }
        }
    }

    private function notification(int $userId, string $type, int $bookingId, Carbon $at, Carbon $now, string $title, string $message): array
    {
        return [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'booking_id' => $bookingId,
            'promotion_id' => null,
            // thông báo cũ hơn 1 ngày phần lớn đã đọc
            'read_at' => $at->lt($now->copy()->subDay()) && mt_rand(1, 100) <= 80 ? $at->copy()->addMinutes(mt_rand(5, 600)) : null,
            'created_at' => $at,
        ];
    }
}
