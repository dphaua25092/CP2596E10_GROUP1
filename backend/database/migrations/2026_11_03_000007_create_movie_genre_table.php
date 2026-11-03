<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_genre', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('genre_id');

            $table->primary(['movie_id', 'genre_id']);
            $table->foreign('genre_id')->references('id')->on('genres')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_genre');
    }
};
