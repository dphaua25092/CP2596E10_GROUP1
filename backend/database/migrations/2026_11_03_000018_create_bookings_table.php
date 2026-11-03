<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->char('booking_code', 10)->unique(); // mã in trên vé điện tử
            $table->foreignId('user_id')->constrained();
            $table->foreignId('showtime_id')->constrained();
            $table->foreignId('promotion_id')->nullable()->constrained()->nullOnDelete(); // khuyến mãi đã áp dụng
            $table->decimal('discount_amount', 12, 0)->default(0); // số tiền giảm chốt lúc đặt
            $table->enum('status', ['pending', 'confirmed', 'failed', 'cancelled', 'expired'])->default('pending');
            $table->dateTime('hold_expires_at'); // hết hạn giữ ghế khi chưa thanh toán
            $table->dateTime('confirmed_at')->nullable();
            $table->timestamps();

            $table->unique(['id', 'showtime_id']); // cho khóa ngoại kép ở booking_seats
            $table->index(['user_id', 'status']);
            $table->index(['status', 'confirmed_at']);
        });

        DB::statement('ALTER TABLE bookings ADD CONSTRAINT chk_bookings_discount CHECK (discount_amount >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
