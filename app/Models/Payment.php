<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'user_id', 'amount', 'payment_method', 
        'transaction_id', 'status', 'mpesa_response'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'mpesa_response' => 'array'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}