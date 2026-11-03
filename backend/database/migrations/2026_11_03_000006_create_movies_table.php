<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200); // tìm bằng LIKE '%...%' (collation bỏ qua dấu)
            $table->text('synopsis')->nullable();
            $table->unsignedSmallInteger('duration_minutes');
            $table->unsignedInteger('language_id');
            $table->enum('age_rating', ['P', 'K', 'T13', 'T16', 'T18'])->default('P');
            $table->date('release_date'); // đang chiếu / sắp chiếu suy ra từ 2 cột ngày
            $table->date('end_date')->nullable();
            $table->string('poster_url')->nullable();
            $table->string('trailer_url')->nullable();
            $table->boolean('is_featured')->default(false); // phim nổi bật ở trang chủ
            $table->timestamps();

            $table->foreign('language_id')->references('id')->on('languages');
            $table->index(['release_date', 'end_date']);
        });

        DB::statement('ALTER TABLE movies
            ADD CONSTRAINT chk_movies_dates CHECK (end_date IS NULL OR end_date >= release_date),
            ADD CONSTRAINT chk_movies_duration CHECK (duration_minutes > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
