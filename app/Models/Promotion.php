<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'code', 
        'discount_percent', 
        'discount_amount', 
        'start_date', 
        'end_date'
    ];
}
