<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true); // còn rạp thì không xóa được (FK theaters), chỉ tắt
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
