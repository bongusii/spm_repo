<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'hotel_id', 'check_in', 'check_out', 'total_price', 'status', 'payment_status', 'notes', 'promotion_code', 'discount_amount'
    ];

    // 1. Đơn đặt này thuộc về Khách hàng nào?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. Đơn đặt này thuộc Khách sạn nào?
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    // 3. Chi tiết đặt loại phòng nào? (Liên kết qua bảng trung gian booking_room)
    public function bookingRooms()
    {
        // Giả sử quan hệ nhiều-nhiều hoặc 1-nhiều tùy cách bạn thiết kế bảng booking_room
        // Ở bài trước mình dùng BookingRoom làm model trung gian
        return $this->hasMany(BookingRoom::class);
    }
    public function invoice() {
        return $this->hasOne(Invoice::class);
    }
}
