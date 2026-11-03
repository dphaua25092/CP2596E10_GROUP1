<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained();
            $table->unsignedInteger('screen_id'); // rạp, thành phố suy ra qua screen
            $table->dateTime('start_time'); // giờ kết thúc = start_time + duration phim
            $table->enum('status', ['scheduled', 'cancelled'])->default('scheduled');
            $table->timestamps();

            $table->index(['screen_id', 'start_time']); // chống trùng giờ: xem migration create_triggers
            $table->index(['movie_id', 'start_time']);
            $table->foreign('screen_id')->references('id')->on('screens');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
