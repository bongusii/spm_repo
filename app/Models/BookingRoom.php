<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{
    protected $table = 'booking_room'; // Vì tên bảng không chuẩn số nhiều
    protected $fillable = ['booking_id', 'room_type_id', 'quantity', 'price_at_booking', 'room_id'];
    public $timestamps = true;

    // 1. Mối quan hệ: Chi tiết này thuộc về 1 Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // 2. Mối quan hệ: Chi tiết này thuộc về 1 Loại phòng
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    // Thêm quan hệ để truy xuất ngược lại phòng
    public function room() {
        return $this->belongsTo(Room::class);
    }
}
