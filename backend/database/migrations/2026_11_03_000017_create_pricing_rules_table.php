<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Giá động: cuối tuần, giờ cao điểm, hạng ghế
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100); // "Phụ thu cuối tuần", "Giờ vàng"...
            $table->enum('day_type', ['all', 'weekday', 'weekend'])->default('all');
            $table->time('from_time')->nullable(); // NULL = cả ngày
            $table->time('to_time')->nullable();
            $table->unsignedTinyInteger('seat_category_id')->nullable(); // NULL = mọi hạng ghế
            $table->enum('adjustment_type', ['percent', 'fixed']);
            $table->decimal('adjustment_value', 10, 2); // âm = giảm giá
            $table->smallInteger('priority')->default(0);
            $table->boolean('is_active')->default(true);

            $table->foreign('seat_category_id')->references('id')->on('seat_categories')->cascadeOnDelete();
        });

        // khai báo đủ cả 2 mốc giờ hoặc bỏ trống cả 2
        DB::statement('ALTER TABLE pricing_rules ADD CONSTRAINT chk_pr_time CHECK ((from_time IS NULL) = (to_time IS NULL))');
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
