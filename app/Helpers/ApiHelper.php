<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class ApiHelper
{
    public static function toPropertyObject($data)
    {
        if (!$data) return null;

        $property = new \stdClass();
        $property->id = $data['id'] ?? null;
        $property->title = $data['title'] ?? 'Property';
        $property->description = $data['description'] ?? 'No description available';
        $property->price_per_night = $data['pricePerNight'] ?? $data['price_per_night'] ?? 0;
        $property->location = $data['location'] ?? 'Unknown location';
        $property->property_type = $data['propertyType'] ?? $data['property_type'] ?? 'Property';
        $property->created_at = $data['createdAt'] ?? $data['created_at'] ?? now();
        $property->owner_name = $data['ownerName'] ?? $data['owner_name'] ?? 'Host';
        $property->averageRating = $data['averageRating'] ?? $data['average_rating'] ?? 4.5;
        $property->status = $data['status'] ?? 'PENDING';
        $property->bedrooms = $data['bedrooms'] ?? 1;
        $property->bathrooms = $data['bathrooms'] ?? 1;
        $property->video_path = $data['videoPath'] ?? $data['video_path'] ?? null;
        $property->video_thumbnail = $data['videoThumbnail'] ?? $data['video_thumbnail'] ?? null;

        // Handle photos
        $property->photos = [];
        if (isset($data['photos']) && is_array($data['photos'])) {
            foreach ($data['photos'] as $photo) {
                $photoObj = new \stdClass();
                $photoObj->id = $photo['id'] ?? null;
                $photoObj->photo_path = $photo['photoPath'] ?? $photo['photo_path'] ?? null;
                $photoObj->is_primary = $photo['isPrimary'] ?? $photo['is_primary'] ?? false;
                $photoObj->order_index = $photo['orderIndex'] ?? $photo['order_index'] ?? 0;
                $property->photos[] = $photoObj;
            }
        }

        // Handle videos - NEW: Parse videos array from API
        $property->videos = [];
        if (isset($data['videos']) && is_array($data['videos'])) {
            foreach ($data['videos'] as $video) {
                $videoObj = new \stdClass();
                $videoObj->id = $video['id'] ?? null;
                $videoObj->video_path = $video['videoPath'] ?? $video['video_path'] ?? null;
                $videoObj->title = $video['title'] ?? null;
                $videoObj->description = $video['description'] ?? null;
                $videoObj->is_featured = $video['isFeatured'] ?? $video['is_featured'] ?? false;
                $videoObj->order_index = $video['orderIndex'] ?? $video['order_index'] ?? 0;
                $property->videos[] = $videoObj;
            }
        }

        // Handle amenities
        $property->amenities = [];
        if (isset($data['amenities'])) {
            if (is_array($data['amenities'])) {
                $property->amenities = $data['amenities'];
            } elseif (is_string($data['amenities'])) {
                $property->amenities = json_decode($data['amenities'], true) ?? [];
            }
        }

        // Handle bookings
        $property->bookings = collect();
        if (isset($data['bookings']) && is_array($data['bookings'])) {
            foreach ($data['bookings'] as $booking) {
                $bookingObj = new \stdClass();
                $bookingObj->id = $booking['id'] ?? null;
                $bookingObj->status = $booking['status'] ?? 'pending';
                $bookingObj->total_price = $booking['totalPrice'] ?? $booking['total_price'] ?? 0;
                $bookingObj->check_in_date = $booking['checkInDate'] ?? $booking['check_in_date'] ?? null;
                $bookingObj->check_out_date = $booking['checkOutDate'] ?? $booking['check_out_date'] ?? null;
                $property->bookings->push($bookingObj);
            }
        }

        // Handle reviews
        $property->reviews = collect();
        if (isset($data['reviews']) && is_array($data['reviews'])) {
            foreach ($data['reviews'] as $review) {
                $reviewObj = new \stdClass();
                $reviewObj->id = $review['id'] ?? null;
                $reviewObj->rating = $review['rating'] ?? 5;
                $reviewObj->comment = $review['comment'] ?? '';
                $reviewObj->created_at = $review['createdAt'] ?? $review['created_at'] ?? now();

                $reviewObj->user = new \stdClass();
                $reviewObj->user->id = $review['user']['id'] ?? null;
                $reviewObj->user->name = $review['user']['name'] ?? 'Guest';
                $property->reviews->push($reviewObj);
            }
        }

        return $property;
    }

    public static function toPropertyCollection($data)
    {
        if (!$data || !is_array($data)) {
            return collect();
        }

        return collect($data)->map(function($item) {
            return self::toPropertyObject($item);
        });
    }

    public static function toBookingObject($data)
    {
        if (!$data) return null;

        $booking = new \stdClass();
        $booking->id = $data['id'] ?? null;
        $booking->check_in_date = $data['checkInDate'] ?? $data['check_in_date'] ?? null;
        $booking->check_out_date = $data['checkOutDate'] ?? $data['check_out_date'] ?? null;
        $booking->guests = $data['guests'] ?? 1;
        $booking->total_price = $data['totalPrice'] ?? $data['total_price'] ?? 0;
        $booking->status = strtolower($data['status'] ?? 'pending');
        $booking->created_at = $data['createdAt'] ?? $data['created_at'] ?? now();

        // Calculate nights
        if ($booking->check_in_date && $booking->check_out_date) {
            $checkIn = \Carbon\Carbon::parse($booking->check_in_date);
            $checkOut = \Carbon\Carbon::parse($booking->check_out_date);
            $booking->nights = $checkOut->diffInDays($checkIn);
        } else {
            $booking->nights = 0;
        }

        // Handle property
        $booking->property = new \stdClass();
        if (isset($data['property'])) {
            $booking->property = self::toPropertyObject($data['property']);
        } else {
            $booking->property->id = $data['propertyId'] ?? null;
            $booking->property->title = $data['propertyTitle'] ?? 'Property';
            $booking->property->location = $data['propertyLocation'] ?? 'Unknown';
        }

        // Handle customer
        $booking->customer_name = $data['customerName'] ?? $data['customer']['name'] ?? 'Guest';

        return $booking;
    }

    public static function toBookingCollection($data)
    {
        if (!$data || !is_array($data)) {
            return collect();
        }

        return collect($data)->map(function($item) {
            return self::toBookingObject($item);
        });
    }
}
