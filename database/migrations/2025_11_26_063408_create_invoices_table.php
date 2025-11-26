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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->onDelete('cascade'); // 1 Booking chỉ có 1 Invoice
            $table->string('invoice_code')->unique(); // Mã hóa đơn (VD: INV-2025001)
            $table->dateTime('issued_at'); // Ngày xuất
            $table->decimal('total_amount', 12, 2); // Tổng tiền chốt
            $table->string('payment_method')->default('cash'); // Tiền mặt/Chuyển khoản
            $table->string('status')->default('paid'); // Đã thanh toán
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
