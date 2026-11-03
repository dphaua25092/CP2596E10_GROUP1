<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Giá gốc theo hạng ghế của từng suất
    public function up(): void
    {
        Schema::create('showtime_prices', function (Blueprint $table) {
            $table->foreignId('showtime_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('seat_category_id');
            $table->decimal('base_price', 10, 0);

            $table->primary(['showtime_id', 'seat_category_id']);
            $table->foreign('seat_category_id')->references('id')->on('seat_categories');
        });

        DB::statement('ALTER TABLE showtime_prices ADD CONSTRAINT chk_sp_price CHECK (base_price >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('showtime_prices');
    }
};
