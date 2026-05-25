<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'owner_id', 'title', 'location', 'description', 'property_type',
        'bedrooms', 'bathrooms', 'price_per_night', 'amenities',
        'status', 'admin_status', 'archived_data', 'video_path', 'video_thumbnail'
    ];

    protected $casts = [
        'amenities' => 'array',
        'archived_data' => 'array',
        'price_per_night' => 'decimal:2'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function photos()
    {
        return $this->hasMany(PropertyPhoto::class);
    }

    /**
     * Get all videos for this property
     */
    public function videos()
    {
        return $this->hasMany(PropertyVideo::class)->orderBy('order', 'asc');
    }

    /**
     * Get the featured video for this property
     */
    public function featuredVideo()
    {
        return $this->hasOne(PropertyVideo::class)->where('is_featured', true);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getPrimaryPhotoAttribute()
    {
        return $this->photos()->where('is_primary', true)->first() 
               ?? $this->photos()->first();
    }

    /**
     * Check if property has any videos uploaded
     */
    public function hasVideo()
    {
        // Check both old single video and new multiple videos
        if ($this->videos()->count() > 0) {
            return true;
        }
        return !is_null($this->video_path);
    }

    /**
     * Get all videos (combined old and new)
     */
    public function getAllVideos()
    {
        $videos = collect();
        
        // Add old single video if exists
        if ($this->video_path) {
            $oldVideo = new \stdClass();
            $oldVideo->id = 0;
            $oldVideo->video_path = $this->video_path;
            $oldVideo->video_thumbnail = $this->video_thumbnail;
            $oldVideo->title = 'Property Video';
            $oldVideo->description = '';
            $oldVideo->is_featured = true;
            $oldVideo->order = -1;
            $videos->push($oldVideo);
        }
        
        // Add new multiple videos
        foreach ($this->videos as $video) {
            $videos->push($video);
        }
        
        return $videos;
    }

    /**
     * Get video URL (from old single video system)
     */
    public function getVideoUrlAttribute()
    {
        return $this->video_path ? asset('storage/' . $this->video_path) : null;
    }

    /**
     * Get video thumbnail URL (from old single video system)
     */
    public function getVideoThumbnailUrlAttribute()
    {
        return $this->video_thumbnail ? asset('storage/' . $this->video_thumbnail) : null;
    }

    /**
     * Check if property has a featured video
     */
    public function hasFeaturedVideo()
    {
        if ($this->featuredVideo) {
            return true;
        }
        return !is_null($this->video_path);
    }

    /**
     * Get featured video URL
     */
    public function getFeaturedVideoUrlAttribute()
    {
        if ($this->featuredVideo) {
            return asset('storage/' . $this->featuredVideo->video_path);
        }
        return $this->video_url;
    }

    public function isAvailable($checkIn, $checkOut)
    {
        $existingBookings = $this->bookings()
            ->where('status', 'confirmed')
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                      ->orWhere(function($q) use ($checkIn, $checkOut) {
                          $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                      });
            })
            ->exists();
        
        return !$existingBookings && $this->admin_status === 'active' && $this->status === 'approved';
    }
}