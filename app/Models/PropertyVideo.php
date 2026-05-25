<?php
// File: app/Models/PropertyVideo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyVideo extends Model
{
    protected $table = 'property_videos';
    
    protected $fillable = [
        'property_id', 'video_path', 'video_thumbnail', 'title', 
        'description', 'order', 'is_featured', 'status'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'order' => 'integer'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function getVideoUrlAttribute()
    {
        return $this->video_path ? asset('storage/' . $this->video_path) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->video_thumbnail ? asset('storage/' . $this->video_thumbnail) : null;
    }
}