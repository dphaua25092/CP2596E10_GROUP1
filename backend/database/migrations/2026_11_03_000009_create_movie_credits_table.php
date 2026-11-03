<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_credits', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->enum('role', ['actor', 'director']);
            $table->unsignedSmallInteger('display_order')->default(0);

            // có cả role nên 1 người vừa đạo diễn vừa đóng vai trong cùng phim được
            $table->primary(['movie_id', 'person_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_credits');
    }
};
