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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên chi nhánh
            $table->string('address'); // Địa chỉ
            $table->string('hotline'); // Điện thoại chi nhánh
            $table->text('description')->nullable();
            // Liên kết với user (người quản lý chi nhánh này)
            $table->foreignId('manager_id')->nullable()->constrained('users'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
