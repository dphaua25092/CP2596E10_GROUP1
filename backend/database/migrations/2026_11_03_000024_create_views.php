<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Các giá trị tính được (thay vì lưu trùng, giữ 3NF).
     * Thứ tự quan trọng: view sau dùng view trước.
     */
    private array $views = [
        'v_movie_ratings' => "
            SELECT m.id AS movie_id,
                   ROUND(AVG(r.rating), 1) AS avg_rating,
                   COUNT(r.id)             AS review_count
            FROM movies m
            LEFT JOIN reviews r ON r.movie_id = m.id AND r.status = 'visible'
            GROUP BY m.id",

        'v_screen_capacity' => '
            SELECT sc.id AS screen_id, sc.theater_id, COUNT(s.id) AS seating_capacity
            FROM screens sc
            LEFT JOIN seats s ON s.screen_id = sc.id AND s.is_active
            GROUP BY sc.id, sc.theater_id',

        'v_theater_capacity' => '
            SELECT t.id AS theater_id, COALESCE(SUM(c.seating_capacity), 0) AS seating_capacity
            FROM theaters t
            LEFT JOIN screens sc          ON sc.theater_id = t.id AND sc.is_active
            LEFT JOIN v_screen_capacity c ON c.screen_id = sc.id
            GROUP BY t.id',

        // Hóa đơn: tổng tiền mỗi đơn = tổng giá ghế - tiền giảm
        'v_booking_totals' => '
            SELECT b.id AS booking_id, b.user_id, b.showtime_id, b.status, b.confirmed_at,
                   COUNT(bs.seat_id)                              AS ticket_count,
                   COALESCE(SUM(bs.price), 0)                     AS subtotal,
                   b.discount_amount,
                   COALESCE(SUM(bs.price), 0) - b.discount_amount AS total
            FROM bookings b
            LEFT JOIN booking_seats bs ON bs.booking_id = b.id
            GROUP BY b.id, b.user_id, b.showtime_id, b.status, b.confirmed_at, b.discount_amount',

        // Số vé và doanh thu theo ngày (gom tiếp theo tuần/tháng bằng YEARWEEK / DATE_FORMAT)
        'v_ticket_sales_daily' => "
            SELECT DATE(bt.confirmed_at) AS sale_date,
                   SUM(bt.ticket_count)  AS tickets_sold,
                   SUM(bt.total)         AS revenue
            FROM v_booking_totals bt
            WHERE bt.status = 'confirmed'
            GROUP BY DATE(bt.confirmed_at)",

        'v_revenue_by_theater' => "
            SELECT t.id AS theater_id, t.name AS theater_name,
                   SUM(bt.ticket_count) AS tickets_sold,
                   SUM(bt.total)        AS revenue
            FROM v_booking_totals bt
            JOIN showtimes st ON st.id = bt.showtime_id
            JOIN screens sc   ON sc.id = st.screen_id
            JOIN theaters t   ON t.id = sc.theater_id
            WHERE bt.status = 'confirmed'
            GROUP BY t.id, t.name",

        'v_popular_movies' => "
            SELECT m.id AS movie_id, m.title,
                   SUM(bt.ticket_count) AS tickets_sold,
                   SUM(bt.total)        AS revenue
            FROM v_booking_totals bt
            JOIN showtimes st ON st.id = bt.showtime_id
            JOIN movies m     ON m.id = st.movie_id
            WHERE bt.status = 'confirmed'
            GROUP BY m.id, m.title",

        // Người dùng đăng ký & lượt đặt vé
        'v_user_booking_stats' => "
            SELECT u.id AS user_id, u.full_name, u.email, u.created_at AS registered_at,
                   COUNT(bt.booking_id)              AS booking_count,
                   COALESCE(SUM(bt.ticket_count), 0) AS tickets_bought,
                   COALESCE(SUM(bt.total), 0)        AS total_spent
            FROM users u
            LEFT JOIN v_booking_totals bt ON bt.user_id = u.id AND bt.status = 'confirmed'
            WHERE u.role = 'customer'
            GROUP BY u.id, u.full_name, u.email, u.created_at",
    ];

    public function up(): void
    {
        // OR REPLACE: migrate:fresh chỉ xóa bảng, không xóa view (trừ khi thêm --drop-views)
        foreach ($this->views as $name => $sql) {
            DB::statement("CREATE OR REPLACE VIEW {$name} AS {$sql}");
        }
    }

    public function down(): void
    {
        foreach (array_reverse(array_keys($this->views)) as $name) {
            DB::statement("DROP VIEW IF EXISTS {$name}");
        }
    }
};
