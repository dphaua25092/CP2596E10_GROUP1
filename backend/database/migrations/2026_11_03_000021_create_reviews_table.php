<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('content')->nullable();
            $table->enum('status', ['visible', 'hidden'])->default('visible'); // admin kiểm duyệt
            $table->timestamps();

            $table->unique(['user_id', 'movie_id']); // mỗi người 1 đánh giá / phim
            $table->index(['movie_id', 'status', 'created_at']);
            $table->index(['movie_id', 'status', 'rating']);
        });

        DB::statement('ALTER TABLE reviews ADD CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
