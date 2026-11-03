<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('screen_id');
            $table->char('row_label', 2); // A, B, C...
            $table->unsignedTinyInteger('seat_number'); // 1, 2, 3...
            $table->unsignedTinyInteger('seat_category_id');
            $table->boolean('is_active')->default(true); // ghế hỏng / lối đi

            $table->unique(['screen_id', 'row_label', 'seat_number']);
            $table->foreign('screen_id')->references('id')->on('screens')->cascadeOnDelete();
            $table->foreign('seat_category_id')->references('id')->on('seat_categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
