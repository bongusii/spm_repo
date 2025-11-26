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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade'); // Thuộc khách sạn nào
            $table->string('name'); // Tên hạng phòng (VD: Deluxe King)
            $table->decimal('price_per_night', 10, 2); // Giá gốc
            $table->integer('capacity'); // Số lượng người tối đa
            $table->json('amenities')->nullable(); // Tiện ích (JSON: wifi, pool view...) - Module 6 dùng cái này để lọc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
