<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mỗi ghế của mỗi suất chỉ thuộc 1 booking: khóa chính (showtime_id, seat_id)
     * chống đặt trùng ghế ngay ở tầng CSDL. Booking hết hạn/hủy/thất bại thì xóa dòng để nhả ghế.
     * Ghế phải thuộc đúng phòng của suất chiếu: xem trigger trg_booking_seats_check.
     */
    public function up(): void
    {
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->unsignedBigInteger('showtime_id');
            $table->foreignId('seat_id')->constrained();
            $table->unsignedBigInteger('booking_id');
            $table->decimal('price', 10, 0); // giá chốt lúc đặt (quy tắc giá có thể đổi sau)

            $table->primary(['showtime_id', 'seat_id']);
            $table->index('booking_id');
            $table->foreign(['booking_id', 'showtime_id'])
                ->references(['id', 'showtime_id'])->on('bookings')
                ->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE booking_seats ADD CONSTRAINT chk_bs_price CHECK (price >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_seats');
    }
};
