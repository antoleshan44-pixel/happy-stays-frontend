<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPhoto extends Model
{
    protected $fillable = [
        'property_id', 'photo_path', 'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}