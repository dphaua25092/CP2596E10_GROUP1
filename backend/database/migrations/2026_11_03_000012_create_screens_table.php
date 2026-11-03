<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Phòng chiếu; sức chứa = số ghế is_active, xem v_screen_capacity
    public function up(): void
    {
        Schema::create('screens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('theater_id');
            $table->string('code', 20); // mã số phòng: P01, IMAX...
            $table->string('location', 100)->nullable(); // vị trí phòng: Tầng 3, khu B...
            $table->boolean('is_active')->default(true);

            $table->unique(['theater_id', 'code']);
            $table->foreign('theater_id')->references('id')->on('theaters')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};
