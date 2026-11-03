<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nhật ký thao tác của admin. FK RESTRICT: admin đã có tác vụ thì không xóa
     * được (chỉ đặt users.is_active = false); admin chưa có dòng nào thì xóa được.
     */
    public function up(): void
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->restrictOnDelete();
            $table->enum('action', ['create', 'update', 'delete', 'hide', 'show']);
            $table->string('target_table', 50); // movies, showtimes, reviews...
            $table->unsignedBigInteger('target_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['admin_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
