<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Diễn viên, đạo diễn
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('photo_url')->nullable();
            $table->text('bio')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
