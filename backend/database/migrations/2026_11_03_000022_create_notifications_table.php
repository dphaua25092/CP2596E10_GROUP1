<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['booking_success', 'booking_failed', 'show_reminder', 'promotion']);
            $table->string('title', 200);
            $table->text('message');
            $table->foreignId('booking_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('promotion_id')->nullable()->constrained()->cascadeOnDelete();
            $table->dateTime('read_at')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unique(['booking_id', 'type']); // không nhắc lịch 2 lần cho 1 vé
            $table->index(['user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
