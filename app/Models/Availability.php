<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = [
        'property_id', 'date', 'is_available', 'status', 'custom_price'
    ];

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean',
        'custom_price' => 'decimal:2'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}