<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Lưu mã code đã dùng (nullable vì có đơn không dùng mã)
            $table->string('promotion_code')->nullable(); 

            // Lưu số tiền được giảm (để sau này báo cáo doanh thu chuẩn hơn)
            $table->decimal('discount_amount', 12, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['promotion_code', 'discount_amount']);
        });
    }
};
