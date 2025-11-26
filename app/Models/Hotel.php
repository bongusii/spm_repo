<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    // Thêm đoạn này vào
    protected $fillable = [
        'name',
        'address',
        'hotline',
        'description',
        'manager_id', // Nếu bạn có cột này
    ];
}