<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_categories', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('code', 20)->unique(); // GOLD, PLATINUM, BOX
            $table->string('name', 50);
            $table->char('color', 7)->nullable(); // màu hiển thị trên sơ đồ ghế
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_categories');
    }
};
