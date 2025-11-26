<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'booking_id', 'invoice_code', 'issued_at', 
        'total_amount', 'payment_method', 'status'
    ];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }
}
