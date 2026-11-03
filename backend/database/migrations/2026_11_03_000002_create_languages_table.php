<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 10)->unique(); // vi, en, ko
            $table->string('name', 50)->unique(); // Tiếng Việt, English...
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
