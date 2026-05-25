<?php
// File: app/Http/Controllers/OwnerController.php

namespace App\Http\Controllers;

use App\Services\SpringBootApiService;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OwnerController extends Controller
{
    protected $api;

    public function __construct(SpringBootApiService $api)
    {
        $this->api = $api;
    }

    // ============================================
    // DASHBOARD
    // ============================================

    public function dashboard()
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('login');
        }

        $ownerName = is_array($user) ? ($user['name'] ?? 'Owner') : 'Owner';

        $rawProperties = $this->api->getMyProperties();
        $properties    = collect($rawProperties);

        $totalProperties   = $properties->count();
        $activeProperties  = $properties->where('status', 'APPROVED')->count();
        $pendingProperties = $properties->where('status', 'PENDING')->count();

        $rawBookings  = $this->api->getMyBookings();
        $bookings     = collect($rawBookings);
        $propertyIds  = $properties->pluck('id')->toArray();

        $ownerBookings = $bookings->filter(function ($b) use ($propertyIds) {
            return in_array($b['property']['id'] ?? null, $propertyIds);
        });

        $totalBookings = $ownerBookings->count();
        $totalEarnings = $ownerBookings->where('status', 'COMPLETED')->sum('totalPrice');
        $activeBookings = $ownerBookings->where('status', 'CONFIRMED')
            ->where('checkInDate', '<=', now()->format('Y-m-d'))
            ->where('checkOutDate', '>=', now()->format('Y-m-d'))
            ->count();

        $monthlyEarnings = [];
        for ($i = 5; $i >= 0; $i--) {
            $month    = now()->subMonths($i);
            $earnings = $ownerBookings->filter(function ($b) use ($month) {
                return isset($b['createdAt']) && str_contains($b['createdAt'], $month->format('Y-m'));
            })->where('status', 'COMPLETED')->sum('totalPrice');

            $monthlyEarnings[] = [
                'month'      => $month->format('M'),
                'earnings'   => $earnings,
                'percentage' => $totalEarnings > 0 ? round(($earnings / $totalEarnings) * 100) : 0,
            ];
        }

        $activeBookingsList = $ownerBookings->where('status', 'CONFIRMED')
            ->where('checkInDate', '>=', now()->format('Y-m-d'))
            ->take(4)
            ->map(function ($booking) {
                return [
                    'property_title'  => $booking['property']['title'] ?? 'Property',
                    'guest_name'      => $booking['customer']['name'] ?? 'Guest',
                    'check_in'        => $booking['checkInDate'] ?? null,
                    'price_per_night' => $booking['property']['pricePerNight'] ?? 0,
                    'status'          => $booking['status'] ?? 'CONFIRMED',
                ];
            });

        return view('owner.dashboard', compact(
            'ownerName', 'totalProperties', 'activeProperties', 'pendingProperties',
            'totalBookings', 'totalEarnings', 'activeBookings', 'monthlyEarnings', 'activeBookingsList'
        ));
    }

    // ============================================
    // MY PROPERTIES LIST
    // ============================================

    public function myProperties()
    {
        $rawProperties = $this->api->getMyProperties();
        $properties    = ApiHelper::toPropertyCollection($rawProperties);
        return view('owner.my-properties', compact('properties'));
    }

    public function reorderProperties(Request $request)
    {
        return response()->json(['success' => true]);
    }

    // ============================================
    // CREATE PROPERTY
    // ============================================

    public function createProperty()
    {
        $propertyTypes = ['Villa', 'Apartment', 'Cottage', 'House', 'Townhouse', 'Farmhouse'];
        $amenitiesList = [
            '24/7 Security Guard', 'CCTV Cameras', 'Secure Parking', 'Backup Generator',
            'Free High-Speed WiFi', '55" Smart TV / Netflix', 'Sound System',
            'Fully Equipped Kitchen', 'Microwave & Oven', 'Coffee/Tea Maker', 'Dishwasher',
            'Stunning City View', 'Ocean/Mountain View', 'Private Balcony', 'Garden/Patio',
            'Fully Equipped Gym', 'Swimming Pool', 'Spa/Hot Tub',
            'Washing Machine', 'Dryer', 'Free Housekeeping Service',
            'Rejuvenating Hot Shower', 'Fresh Clean Water', 'Hair Dryer', 'Bath Towels & Toiletries',
            'Welcome Drinks', 'Fresh Flowers in Room', 'Airport Pickup (Extra)', 'Tour Desk / Concierge',
        ];
        return view('owner.create-property', compact('propertyTypes', 'amenitiesList'));
    }

    public function storeProperty(Request $request)
    {
        Log::info('=== STORE PROPERTY START ===', $request->all());

        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'location'        => 'required|string',
            'property_type'   => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'bedrooms'        => 'required|integer|min:1',
            'bathrooms'       => 'required|integer|min:1',
            'amenities'       => 'nullable|array',
        ]);

        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $propertyData = [
            'title'         => $validated['title'],
            'description'   => $validated['description'],
            'location'      => $validated['location'],
            'propertyType'  => $validated['property_type'],
            'pricePerNight' => (float) $validated['price_per_night'],
            'bedrooms'      => (int) $validated['bedrooms'],
            'bathrooms'     => (int) $validated['bathrooms'],
            'amenities'     => json_encode(array_values($request->amenities ?? [])),
            'ownerId'       => $user['id'],
            'status'        => 'PENDING',
        ];

        try {
            $result = $this->api->createProperty($propertyData);

            if ($result && isset($result['id'])) {
                return redirect()->route('owner.property.photos', $result['id'])
                    ->with('success', 'Property created! Now upload at least 5 photos.');
            }

            return back()->with('error', 'Failed to create property.')->withInput();

        } catch (\Exception $e) {
            Log::error('Property creation exception: ' . $e->getMessage());
            return back()->with('error', 'Failed to create property: ' . $e->getMessage())->withInput();
        }
    }

    // ============================================
    // EDIT & UPDATE PROPERTY
    // ============================================

    public function editProperty($id)
    {
        try {
            Log::info('editProperty - START', ['id' => $id]);

            $rawProperty = $this->api->getProperty($id, true);

            Log::info('editProperty - rawProperty type', [
                'type' => gettype($rawProperty),
                'is_array' => is_array($rawProperty),
                'keys' => is_array($rawProperty) ? array_keys($rawProperty) : 'not array'
            ]);

            if (!$rawProperty) {
                Log::error('editProperty - Property not found', ['id' => $id]);
                return redirect()->route('owner.properties')->with('error', 'Property not found');
            }

            $property = $rawProperty;

            $propertyTypes = ['Villa', 'Apartment', 'Cottage', 'House', 'Townhouse', 'Farmhouse'];
            $amenitiesList = [
                '24/7 Security Guard', 'CCTV Cameras', 'Secure Parking', 'Backup Generator',
                'Free High-Speed WiFi', '55" Smart TV / Netflix', 'Sound System',
                'Fully Equipped Kitchen', 'Microwave & Oven', 'Coffee/Tea Maker', 'Dishwasher',
                'Stunning City View', 'Ocean/Mountain View', 'Private Balcony', 'Garden/Patio',
                'Fully Equipped Gym', 'Swimming Pool', 'Spa/Hot Tub',
                'Washing Machine', 'Dryer', 'Free Housekeeping Service',
                'Rejuvenating Hot Shower', 'Fresh Clean Water', 'Hair Dryer', 'Bath Towels & Toiletries',
                'Welcome Drinks', 'Fresh Flowers in Room', 'Airport Pickup (Extra)', 'Tour Desk / Concierge',
            ];

            $currentAmenities = [];
            if (isset($property['amenities'])) {
                if (is_array($property['amenities'])) {
                    $currentAmenities = $property['amenities'];
                } elseif (is_string($property['amenities'])) {
                    $currentAmenities = json_decode($property['amenities'], true) ?? [];
                }
            }

            Log::info('editProperty - About to load view', [
                'property_id' => $property['id'] ?? 'unknown',
                'property_title' => $property['title'] ?? 'unknown'
            ]);

            return view('owner.edit-property', compact('property', 'propertyTypes', 'amenitiesList', 'currentAmenities'));

        } catch (\Exception $e) {
            Log::error('editProperty - EXCEPTION', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('owner.properties')->with('error', 'Error loading edit page: ' . $e->getMessage());
        }
    }

    public function updateProperty(Request $request, $id)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'location'        => 'required|string',
            'description'     => 'required|string',
            'property_type'   => 'required|in:Villa,Apartment,Cottage,House,Townhouse,Farmhouse',
            'bedrooms'        => 'required|integer|min:1',
            'bathrooms'       => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'amenities'       => 'array|nullable',
        ]);

        $updateData = [
            'title'         => $validated['title'],
            'location'      => $validated['location'],
            'description'   => $validated['description'],
            'propertyType'  => $validated['property_type'],
            'bedrooms'      => (int) $validated['bedrooms'],
            'bathrooms'     => (int) $validated['bathrooms'],
            'pricePerNight' => (float) $validated['price_per_night'],
            'amenities'     => json_encode($request->amenities ?? []),
        ];

        $result = $this->api->updateProperty($id, $updateData);

        if ($result) {
            return redirect()->route('owner.properties')->with('success', 'Property updated successfully.');
        }

        return back()->withErrors(['error' => 'Failed to update property.']);
    }

    public function deleteProperty($id)
    {
        $result = $this->api->deleteProperty($id);

        if ($result) {
            return redirect()->route('owner.properties')->with('success', 'Property deleted successfully.');
        }

        return back()->with('error', 'Cannot delete property with existing bookings.');
    }

    // ============================================
    // PHOTO MANAGEMENT
    // ============================================

    public function showUploadPhotos($propertyId)
    {
        Log::info('=== showUploadPhotos START ===', ['propertyId' => $propertyId]);

        $property = $this->api->getProperty($propertyId, true);

        Log::info('showUploadPhotos - raw property from API', [
            'propertyId' => $propertyId,
            'property_exists' => !empty($property),
            'has_photos_key' => isset($property['photos']),
            'photos_value_type' => isset($property['photos']) ? gettype($property['photos']) : 'NOT SET',
            'photos_count' => isset($property['photos']) ? count($property['photos']) : 0,
            'all_keys' => $property ? array_keys($property) : [],
        ]);

        if (!$property) {
            Log::error('showUploadPhotos - Property not found', ['propertyId' => $propertyId]);
            return redirect()->route('owner.properties')->with('error', 'Property not found');
        }

        $existingPhotos = collect($property['photos'] ?? [])->map(function ($photo) {
            Log::debug('showUploadPhotos - processing single photo', [
                'original_photo' => $photo
            ]);

            return [
                'id'         => $photo['id'] ?? null,
                'photo_path' => $photo['photoPath'] ?? $photo['photo_path'] ?? null,
                'is_primary' => $photo['isPrimary'] ?? $photo['is_primary'] ?? false,
            ];
        })->toArray();

        Log::info('showUploadPhotos - AFTER NORMALIZATION', [
            'existingPhotos_count' => count($existingPhotos),
            'existingPhotos' => $existingPhotos,
        ]);

        $remainingSlots = 15 - count($existingPhotos);

        Log::info('showUploadPhotos - FINAL', [
            'remainingSlots' => $remainingSlots,
            'will_pass_to_view' => count($existingPhotos) . ' photos',
        ]);

        return view('owner.upload-photos', compact('property', 'existingPhotos', 'remainingSlots'));
    }

    public function uploadPhotos(Request $request, $propertyId)
    {
        $request->validate([
            'photos'   => 'required|array|min:1|max:15',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $property = $this->api->getProperty($propertyId, true);

        if (!$property) {
            return redirect()->route('owner.property.photos', $propertyId)
                ->with('error', 'Property not found.');
        }

        $existingCount = count($property['photos'] ?? []);
        $maxUpload     = 15 - $existingCount;
        $incoming      = $request->file('photos');

        if (count($incoming) > $maxUpload) {
            return redirect()->route('owner.property.photos', $propertyId)
                ->with('error', "You can only upload up to {$maxUpload} more photo(s). You currently have {$existingCount}/15.");
        }

        $uploaded = $this->api->uploadPhotos($propertyId, $incoming);

        if (count($uploaded) > 0) {
            return redirect()->route('owner.property.photos', $propertyId)
                ->with('success', count($uploaded) . ' photo(s) uploaded successfully!');
        }

        return redirect()->route('owner.property.photos', $propertyId)
            ->with('error', 'Failed to upload photos. Please check your connection and try again.');
    }

    /**
     * Upload both video and photos in a single request
     */
    public function uploadAllMedia(Request $request, $propertyId)
    {
        Log::info('UploadAllMedia started', ['propertyId' => $propertyId]);

        try {
            $request->validate([
                'video' => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:102400',
                'video_title' => 'nullable|string|max:255',
                'video_description' => 'nullable|string|max:1000',
                'photos' => 'nullable|array|min:1|max:15',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            $property = $this->api->getProperty($propertyId, true);

            if (!$property) {
                return redirect()->back()->with('error', 'Property not found.');
            }

            $currentPhotoCount = count($property['photos'] ?? []);
            $newPhotoCount = $request->hasFile('photos') ? count($request->file('photos')) : 0;
            $totalPhotos = $currentPhotoCount + $newPhotoCount;

            if ($totalPhotos < 5) {
                return redirect()->back()->with('error', "You need at least 5 photos. You currently have {$currentPhotoCount} photo(s) and are adding {$newPhotoCount} new photo(s). Please add " . (5 - $totalPhotos) . " more photo(s).");
            }

            if ($totalPhotos > 15) {
                return redirect()->back()->with('error', "Maximum 15 photos allowed. You currently have {$currentPhotoCount} photo(s) and are trying to add {$newPhotoCount} more.");
            }

            if ($request->hasFile('video')) {
                Log::info('Uploading video', ['propertyId' => $propertyId]);

                $videoFile = $request->file('video');
                $videoTitle = $request->input('video_title');
                $videoDescription = $request->input('video_description');

                $videoResult = $this->api->uploadVideo($propertyId, $videoFile, $videoTitle, $videoDescription);

                if (!$videoResult) {
                    Log::error('Video upload failed', ['propertyId' => $propertyId]);
                    return redirect()->back()->with('error', 'Video upload failed. Please check your connection and try again.');
                }

                Log::info('Video uploaded successfully', ['propertyId' => $propertyId, 'video_id' => $videoResult['id'] ?? null]);
            }

            if ($request->hasFile('photos')) {
                Log::info('Uploading photos', [
                    'propertyId' => $propertyId,
                    'photoCount' => $newPhotoCount
                ]);

                $photos = $request->file('photos');
                $photoResults = $this->api->uploadPhotos($propertyId, $photos);

                if (empty($photoResults)) {
                    Log::error('Photo upload failed', ['propertyId' => $propertyId]);
                    return redirect()->back()->with('error', 'Photo upload failed. Please check your connection and try again.');
                }

                Log::info('Photos uploaded successfully', [
                    'propertyId' => $propertyId,
                    'uploaded' => count($photoResults)
                ]);
            } else {
                if ($currentPhotoCount < 5) {
                    return redirect()->back()->with('error', "You need at least 5 photos. You currently have {$currentPhotoCount} photo(s). Please upload more photos.");
                }
            }

            return redirect()->route('owner.complete.property', $propertyId)
                ->with('success', 'Media uploaded successfully! Property setup complete.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Upload validation failed', [
                'propertyId' => $propertyId,
                'errors' => $e->errors()
            ]);

            return redirect()->back()->with('error', 'Validation failed: ' . implode(', ', array_collapse($e->errors())));

        } catch (\Exception $e) {
            Log::error('UploadAllMedia exception', [
                'propertyId' => $propertyId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * ✅ UPDATED: Delete a photo - Returns JSON for AJAX requests
     */
    public function deletePhoto($propertyId, $photoId)
    {
        Log::info('deletePhoto called', ['propertyId' => $propertyId, 'photoId' => $photoId]);

        $result = $this->api->deletePhoto($propertyId, $photoId);

        if ($result) {
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
            }
            return back()->with('success', 'Photo deleted successfully.');
        }

        if (request()->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to delete photo.'], 500);
        }
        return back()->with('error', 'Failed to delete photo. Please try again.');
    }

    /**
     * ✅ UPDATED: Set primary photo - Returns JSON for AJAX requests
     */
    public function setPrimaryPhoto($propertyId, $photoId)
    {
        Log::info('setPrimaryPhoto called', ['propertyId' => $propertyId, 'photoId' => $photoId]);

        $result = $this->api->setPrimaryPhoto($propertyId, $photoId);

        if ($result) {
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Primary photo updated successfully.']);
            }
            return back()->with('success', 'Primary photo updated successfully.');
        }

        if (request()->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to set primary photo.'], 500);
        }
        return back()->with('error', 'Failed to set primary photo. Please try again.');
    }

    // ============================================
    // VIDEO MANAGEMENT
    // ============================================

    public function manageVideos($propertyId)
    {
        $property = $this->api->getProperty($propertyId, true);

        if (!$property) {
            return redirect()->route('owner.properties')->with('error', 'Property not found');
        }

        $videos = collect($property['videos'] ?? [])->map(function ($video, $index) {
            return [
                'id'          => $video['id'] ?? $index,
                'video_path'  => $video['videoPath'] ?? $video['video_path'] ?? null,
                'title'       => $video['title'] ?? null,
                'description' => $video['description'] ?? null,
                'is_featured' => $video['isFeatured'] ?? $video['is_featured'] ?? ($index === 0),
            ];
        })->toArray();

        return view('owner.manage-videos', compact('property', 'videos'));
    }

    public function uploadVideo(Request $request, $propertyId)
    {
        $request->validate([
            'video'       => 'required|file|mimes:mp4,mov,avi,webm,mkv|max:102400',
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $property = $this->api->getProperty($propertyId, true);

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        $existingVideos = $property['videos'] ?? [];
        if (count($existingVideos) >= 5) {
            return back()->with('error', 'You have reached the maximum of 5 videos for this property.');
        }

        $result = $this->api->uploadVideo(
            $propertyId,
            $request->file('video'),
            $request->input('title'),
            $request->input('description')
        );

        if ($result) {
            $referer = request()->headers->get('referer', '');
            if (str_contains($referer, 'photos')) {
                return redirect()->route('owner.property.photos', $propertyId)
                    ->with('success', 'Video uploaded successfully!');
            }
            return redirect()->route('owner.videos.manage', $propertyId)
                ->with('success', 'Video uploaded successfully!');
        }

        return back()->with('error', 'Failed to upload video. Please check your connection and try again.');
    }

    public function deleteVideo($propertyId, $videoId)
    {
        $result = $this->api->deleteVideo($propertyId, $videoId);

        if ($result) {
            return redirect()->route('owner.videos.manage', $propertyId)
                ->with('success', 'Video deleted successfully.');
        }

        return redirect()->route('owner.videos.manage', $propertyId)
            ->with('error', 'Failed to delete video. Please try again.');
    }

    public function setFeaturedVideo($propertyId, $videoId)
    {
        $result = $this->api->setFeaturedVideo($propertyId, $videoId);

        if ($result) {
            return back()->with('success', 'Featured video updated.');
        }

        return back()->with('error', 'Failed to update featured video.');
    }

    public function reorderVideos(Request $request, $propertyId)
    {
        return response()->json(['success' => true]);
    }

    // ============================================
    // COMPLETE PROPERTY SETUP
    // ============================================

    public function completeProperty($propertyId)
    {
        $property = $this->api->getProperty($propertyId, true);

        if (!$property) {
            return redirect()->route('owner.properties')->with('error', 'Property not found');
        }

        $photoCount = count($property['photos'] ?? []);

        if ($photoCount < 5) {
            return redirect()->route('owner.property.photos', $propertyId)
                ->with('error', "Please upload at least 5 photos before completing setup. You have {$photoCount}/5.");
        }

        return redirect()->route('owner.properties')
            ->with('success', 'Property setup complete! It has been submitted for admin review.');
    }

    // ============================================
    // PROPERTY BOOKINGS
    // ============================================

    public function propertyBookings($id)
    {
        $rawProperty = $this->api->getProperty($id, true);

        if (!$rawProperty) {
            return redirect()->route('owner.properties')->with('error', 'Property not found');
        }

        $property    = ApiHelper::toPropertyObject($rawProperty);
        $rawBookings = $this->api->getMyBookings();

        $bookings = collect($rawBookings)->filter(function ($b) use ($id) {
            return ($b['property']['id'] ?? null) == $id;
        });

        $stats = [
            'total'         => $bookings->count(),
            'confirmed'     => $bookings->where('status', 'CONFIRMED')->count(),
            'pending'       => $bookings->where('status', 'PENDING')->count(),
            'completed'     => $bookings->where('status', 'COMPLETED')->count(),
            'cancelled'     => $bookings->where('status', 'CANCELLED')->count(),
            'total_revenue' => $bookings->where('status', 'COMPLETED')->sum('totalPrice'),
        ];

        return view('owner.property-bookings', compact('property', 'bookings', 'stats'));
    }

    // ============================================
    // EARNINGS
    // ============================================

    public function earnings()
    {
        $rawProperties = $this->api->getMyProperties();
        $properties    = collect($rawProperties);
        $rawBookings   = $this->api->getMyBookings();
        $bookings      = collect($rawBookings);

        $totalRevenue           = 0;
        $propertyEarnings       = [];
        $totalCompletedBookings = 0;
        $activeProperties       = 0;

        foreach ($properties as $property) {
            $propertyBookings = $bookings->filter(function ($b) use ($property) {
                return ($b['property']['id'] ?? null) == $property['id'];
            });

            $completedCount = $propertyBookings->where('status', 'COMPLETED')->count();
            $earnings       = $propertyBookings->where('status', 'COMPLETED')->sum('totalPrice');

            $totalRevenue           += $earnings;
            $totalCompletedBookings += $completedCount;

            if ($property['status'] == 'APPROVED') {
                $activeProperties++;
            }

            $propertyEarnings[] = [
                'property'       => (object) $property,
                'earnings'       => $earnings,
                'bookings_count' => $completedCount,
            ];
        }

        $monthlyEarnings = [];
        for ($i = 11; $i >= 0; $i--) {
            $month    = now()->subMonths($i);
            $earnings = $bookings->filter(function ($b) use ($month) {
                return isset($b['createdAt']) && str_contains($b['createdAt'], $month->format('Y-m'));
            })->where('status', 'COMPLETED')->sum('totalPrice');

            $monthlyEarnings[] = [
                'month'    => $month->format('M Y'),
                'earnings' => $earnings,
            ];
        }

        return view('owner.earnings', compact(
            'properties', 'totalRevenue', 'propertyEarnings',
            'monthlyEarnings', 'totalCompletedBookings', 'activeProperties'
        ));
    }

    public function exportEarnings()
    {
        $rawProperties = $this->api->getMyProperties();
        $properties    = collect($rawProperties);
        $rawBookings   = $this->api->getMyBookings();
        $bookings      = collect($rawBookings);

        $propertyEarnings = [];
        foreach ($properties as $property) {
            $propertyBookings = $bookings->filter(function ($b) use ($property) {
                return ($b['property']['id'] ?? null) == $property['id'];
            });
            $earnings = $propertyBookings->where('status', 'COMPLETED')->sum('totalPrice');

            $propertyEarnings[] = (object) [
                'property'       => (object) $property,
                'earnings'       => $earnings,
                'bookings_count' => $propertyBookings->where('status', 'COMPLETED')->count(),
            ];
        }

        $totalRevenue = array_sum(array_column($propertyEarnings, 'earnings'));
        $filename     = 'earnings_report_' . date('Y-m-d') . '.csv';

        $callback = function () use ($propertyEarnings, $totalRevenue) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Earnings Report - ' . date('Y-m-d')]);
            fputcsv($file, []);
            fputcsv($file, ['Total Lifetime Earnings', 'KES ' . number_format($totalRevenue)]);
            fputcsv($file, []);
            fputcsv($file, ['Property', 'Location', 'Completed Bookings', 'Total Earnings']);
            foreach ($propertyEarnings as $item) {
                fputcsv($file, [
                    $item->property->title,
                    $item->property->location,
                    $item->bookings_count,
                    'KES ' . number_format($item->earnings),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
