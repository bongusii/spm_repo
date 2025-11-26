<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'room_number',
        'status',
    ];

    // Quan hệ: 1 Phòng thuộc về 1 Loại phòng
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}