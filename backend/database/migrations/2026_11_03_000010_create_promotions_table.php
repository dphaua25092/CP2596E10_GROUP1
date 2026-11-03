<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Khuyến mãi & thông báo trang chủ
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('content')->nullable();
            $table->string('banner_url')->nullable();
            $table->unsignedTinyInteger('discount_percent')->default(0); // giảm đồng loạt mọi vé; 0 = chỉ là thông báo
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'start_date', 'end_date']);
        });

        DB::statement('ALTER TABLE promotions
            ADD CONSTRAINT chk_promotions_dates CHECK (end_date >= start_date),
            ADD CONSTRAINT chk_promotions_discount CHECK (discount_percent <= 100)');
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
