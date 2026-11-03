<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 1) Không cho 2 suất 'scheduled' chồng giờ trong cùng phòng
     *    (giờ kết thúc = start_time + movies.duration_minutes)
     * 2) Ghế đặt phải nằm trong phòng của suất chiếu, đang dùng được, và suất chưa bị hủy
     */
    public function up(): void
    {
        foreach (['ins' => 'INSERT', 'upd' => 'UPDATE'] as $suffix => $event) {
            $excludeSelf = $event === 'UPDATE' ? 'AND s.id <> NEW.id' : '';

            DB::unprepared("
                CREATE TRIGGER trg_showtimes_no_overlap_{$suffix} BEFORE {$event} ON showtimes
                FOR EACH ROW
                BEGIN
                    DECLARE v_duration SMALLINT UNSIGNED;
                    SELECT duration_minutes INTO v_duration FROM movies WHERE id = NEW.movie_id;
                    IF NEW.status = 'scheduled' AND EXISTS (
                        SELECT 1 FROM showtimes s JOIN movies m ON m.id = s.movie_id
                        WHERE s.screen_id = NEW.screen_id AND s.status = 'scheduled' {$excludeSelf}
                          AND s.start_time < NEW.start_time + INTERVAL v_duration MINUTE
                          AND NEW.start_time < s.start_time + INTERVAL m.duration_minutes MINUTE
                    ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Suat chieu bi trung gio trong cung phong';
                    END IF;
                END
            ");
        }

        DB::unprepared("
            CREATE TRIGGER trg_booking_seats_check BEFORE INSERT ON booking_seats
            FOR EACH ROW
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM seats s JOIN showtimes st ON st.screen_id = s.screen_id
                    WHERE s.id = NEW.seat_id AND st.id = NEW.showtime_id
                      AND s.is_active AND st.status = 'scheduled'
                ) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ghe khong hop le cho suat chieu nay';
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_booking_seats_check');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_showtimes_no_overlap_upd');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_showtimes_no_overlap_ins');
    }
};
