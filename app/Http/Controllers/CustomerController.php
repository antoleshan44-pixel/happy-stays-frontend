<?php

namespace App\Http\Controllers;

use App\Services\SpringBootApiService;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    protected $api;

    public function __construct(SpringBootApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Display customer dashboard with statistics and recent bookings
     */
    public function dashboard()
    {
        try {
            $user = $this->api->getCurrentUser();
            $rawBookings = $this->api->getMyBookings();
            $bookings = ApiHelper::toBookingCollection($rawBookings);
            $favorites = session()->get('favorites', []);
            $favoriteCount = is_array($favorites) ? count($favorites) : 0;

            $totalBookings = $bookings->count();
            $upcomingBookings = $bookings->filter(function($b) {
                return $b->status === 'confirmed' && $b->check_in_date && $b->check_in_date >= now()->format('Y-m-d');
            })->count();

            $totalSpent = $bookings->sum('total_price');
            $recentBookings = $bookings->take(5);

            return view('customer.dashboard', compact('user', 'totalBookings', 'upcomingBookings', 'totalSpent', 'favoriteCount', 'recentBookings'));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return view('customer.dashboard', [
                'user' => session('user', ['name' => 'Guest']),
                'totalBookings' => 0,
                'upcomingBookings' => 0,
                'totalSpent' => 0,
                'favoriteCount' => 0,
                'recentBookings' => collect()
            ]);
        }
    }

    /**
     * Browse and filter properties
     */
    public function browseProperties(Request $request)
    {
        try {
            $rawProperties = $this->api->getProperties();
            $properties = ApiHelper::toPropertyCollection($rawProperties);

            // Apply filters
            if ($request->location) {
                $properties = $properties->filter(fn($p) => stripos($p->location, $request->location) !== false);
            }
            if ($request->min_price) {
                $properties = $properties->filter(fn($p) => $p->price_per_night >= $request->min_price);
            }
            if ($request->max_price) {
                $properties = $properties->filter(fn($p) => $p->price_per_night <= $request->max_price);
            }
            if ($request->property_type) {
                $properties = $properties->filter(fn($p) => $p->property_type === $request->property_type);
            }

            // Sorting
            $sortBy = $request->get('sort', 'newest');
            switch ($sortBy) {
                case 'price_low':
                    $properties = $properties->sortBy('price_per_night');
                    break;
                case 'price_high':
                    $properties = $properties->sortByDesc('price_per_night');
                    break;
                default:
                    $properties = $properties->sortByDesc('created_at');
                    break;
            }

            // Pagination
            $currentPage = $request->get('page', 1);
            $perPage = 12;
            $paginated = $properties->slice(($currentPage - 1) * $perPage, $perPage);
            $propertiesPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginated, $properties->count(), $perPage, $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('customer.browse-properties', ['properties' => $propertiesPaginated]);
        } catch (\Exception $e) {
            Log::error('Browse properties error: ' . $e->getMessage());
            return view('customer.browse-properties', ['properties' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12, 1)]);
        }
    }

    /**
     * Search properties by keyword
     */
    public function searchResults(Request $request)
    {
        try {
            $searchTerm = $request->get('search', '');
            $filters = $request->only(['location', 'min_price', 'max_price', 'property_type']);

            if ($searchTerm) {
                $rawProperties = $this->api->searchProperties($searchTerm, $filters);
            } else {
                $rawProperties = $this->api->getProperties();
            }

            $properties = ApiHelper::toPropertyCollection($rawProperties);
            $totalCount = $properties->count();

            // Pagination
            $perPage = 12;
            $currentPage = $request->get('page', 1);
            $paginated = $properties->slice(($currentPage - 1) * $perPage, $perPage);
            $properties = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginated, $totalCount, $perPage, $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('customer.search-results', compact('properties', 'searchTerm', 'totalCount'));
        } catch (\Exception $e) {
            Log::error('Search results error: ' . $e->getMessage());
            return view('customer.search-results', [
                'properties' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12, 1),
                'searchTerm' => $request->get('search', ''),
                'totalCount' => 0
            ]);
        }
    }

    /**
     * Display single property details
     */
    public function propertyDetail($id)
    {
        try {
            $rawProperty = $this->api->getProperty($id);
            if (!$rawProperty) abort(404);

            $property = ApiHelper::toPropertyObject($rawProperty);
            $user = $this->api->getCurrentUser();

            // Get similar properties
            $allProperties = $this->api->getProperties();
            $similarProperties = ApiHelper::toPropertyCollection($allProperties)
                ->filter(fn($p) => $p->property_type === $property->property_type && $p->id != $property->id)
                ->take(3);

            $averageRating = $property->averageRating ?? 4.5;
            $isSaved = $this->isPropertySaved($id);

            return view('customer.property-detail', compact('property', 'user', 'similarProperties', 'averageRating', 'isSaved'));
        } catch (\Exception $e) {
            Log::error('Property detail error: ' . $e->getMessage());
            abort(404, 'Property not found');
        }
    }

    /**
     * Display property reviews
     */
    public function propertyReviews($id)
    {
        try {
            $rawProperty = $this->api->getProperty($id);
            if (!$rawProperty) abort(404);

            $property = ApiHelper::toPropertyObject($rawProperty);
            $averageRating = $property->reviews->avg('rating') ?? 4.5;
            $user = $this->api->getCurrentUser();

            return view('customer.property-reviews', compact('property', 'averageRating', 'user'));
        } catch (\Exception $e) {
            Log::error('Property reviews error: ' . $e->getMessage());
            abort(404, 'Property not found');
        }
    }

    /**
     * Submit a review for a property
     */
    public function submitPropertyReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $result = $this->api->submitPropertyReview($id, $request->only(['rating', 'comment']));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Review submitted']);
        }

        return redirect()->route('customer.property.detail', $id)->with('success', 'Thank you for your review!');
    }

    /**
     * Create a new booking
     */
    public function createBooking(Request $request, $propertyId)
    {
        Log::info('=== CREATE BOOKING REQUEST ===', ['property_id' => $propertyId]);
        Log::info('Request data:', $request->all());

        $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1|max:20'
        ]);

        $bookingData = [
            'propertyId' => (int)$propertyId,
            'checkInDate' => $request->check_in_date,
            'checkOutDate' => $request->check_out_date,
            'guests' => (int)$request->guests
        ];

        Log::info('Sending to API:', $bookingData);

        $result = $this->api->createBooking($bookingData);

        if ($result && is_array($result)) {
            Log::info('API Response:', $result);

            if (isset($result['id'])) {
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'booking_id' => $result['id'],
                        'redirect' => route('payment.mpesa', $result['id'])
                    ]);
                }
                return redirect()->route('payment.mpesa', $result['id'])->with('success', 'Booking created! Complete payment.');
            }

            $errorMessage = $result['message'] ?? 'Booking failed';
        } else {
            Log::error('API Response was null or invalid', ['result' => $result]);
            $errorMessage = 'No response from booking service';
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $errorMessage], 400);
        }

        return back()->with('error', $errorMessage)->withInput();
    }

    /**
     * Display user's all bookings
     */
    public function myBookings(Request $request)
    {
        try {
            $rawBookings = $this->api->getMyBookings();
            $bookings = ApiHelper::toBookingCollection($rawBookings);

            $stats = [
                'total' => $bookings->count(),
                'confirmed' => $bookings->where('status', 'confirmed')->count(),
                'pending' => $bookings->where('status', 'pending')->count(),
                'completed' => $bookings->where('status', 'completed')->count(),
                'cancelled' => $bookings->where('status', 'cancelled')->count(),
            ];

            $currentPage = $request->get('page', 1);
            $perPage = 10;
            $paginated = $bookings->slice(($currentPage - 1) * $perPage, $perPage);
            $bookings = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginated, $bookings->count(), $perPage, $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('customer.my-bookings', compact('bookings', 'stats'));
        } catch (\Exception $e) {
            Log::error('My bookings error: ' . $e->getMessage());
            return view('customer.my-bookings', [
                'bookings' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1),
                'stats' => ['total' => 0, 'confirmed' => 0, 'pending' => 0, 'completed' => 0, 'cancelled' => 0]
            ]);
        }
    }

    /**
     * Display single booking details
     */
    public function bookingDetails($id)
    {
        try {
            $rawBooking = $this->api->getBookingDetails($id);
            if (!$rawBooking) abort(404);

            $booking = ApiHelper::toBookingObject($rawBooking);
            return view('customer.booking-details', compact('booking'));
        } catch (\Exception $e) {
            Log::error('Booking details error: ' . $e->getMessage());
            abort(404, 'Booking not found');
        }
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking($id)
    {
        try {
            $result = $this->api->cancelBooking($id);
            if ($result) {
                return redirect()->route('customer.bookings')->with('success', 'Booking cancelled successfully.');
            }
            return redirect()->route('customer.bookings')->with('error', 'Failed to cancel booking.');
        } catch (\Exception $e) {
            Log::error('Cancel booking error: ' . $e->getMessage());
            return redirect()->route('customer.bookings')->with('error', 'An error occurred while cancelling the booking.');
        }
    }

    /**
     * Submit a review for a completed booking
     */
    public function submitReview(Request $request, $bookingId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $result = $this->api->submitReview($bookingId, $request->only(['rating', 'comment']));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Review submitted']);
        }

        return redirect()->route('customer.booking.details', $bookingId)->with('success', 'Thank you for your review!');
    }

    /**
     * Display customer calendar with all bookings
     */
    public function calendar()
    {
        try {
            $user = $this->api->getCurrentUser();
            $rawBookings = $this->api->getMyBookings();
            $bookings = ApiHelper::toBookingCollection($rawBookings);

            $events = [];
            $upcomingCount = 0;
            $completedCount = 0;
            $totalNights = 0;

            foreach ($bookings as $booking) {
                // Calculate nights
                if ($booking->check_in_date && $booking->check_out_date) {
                    $checkIn = \Carbon\Carbon::parse($booking->check_in_date);
                    $checkOut = \Carbon\Carbon::parse($booking->check_out_date);
                    $nights = $checkOut->diffInDays($checkIn);
                    $totalNights += $nights;
                }

                // Check if upcoming
                $isFuture = $booking->check_in_date && $booking->check_in_date > now()->format('Y-m-d');

                if ($booking->status == 'confirmed' && $isFuture) {
                    $upcomingCount++;
                } elseif ($booking->status == 'completed' || ($booking->status == 'confirmed' && !$isFuture)) {
                    $completedCount++;
                }

                // Create calendar event
                $events[] = [
                    'id' => $booking->id,
                    'title' => $booking->property->title ?? 'Booking',
                    'start' => $booking->check_in_date,
                    'end' => $booking->check_out_date,
                    'url' => route('customer.booking.details', $booking->id),
                    'guests' => $booking->guests,
                    'status' => $booking->status,
                    'property' => $booking->property->title ?? 'Property',
                    'location' => $booking->property->location ?? 'Unknown',
                    'color' => $booking->status == 'confirmed' ? '#22c55e' : ($booking->status == 'pending' ? '#eab308' : '#6b7280')
                ];
            }

            $totalBookings = $bookings->count();

            return view('customer.calendar', compact('events', 'totalBookings', 'upcomingCount', 'completedCount', 'totalNights', 'user'));
        } catch (\Exception $e) {
            Log::error('Calendar error: ' . $e->getMessage());
            return view('customer.calendar', [
                'events' => [],
                'totalBookings' => 0,
                'upcomingCount' => 0,
                'completedCount' => 0,
                'totalNights' => 0,
                'user' => session('user', ['name' => 'Guest'])
            ]);
        }
    }

    /**
     * Display saved properties (wishlist)
     */
    public function savedProperties()
    {
        try {
            $favoriteIds = session()->get('favorites', []);
            $customWishlists = session()->get('custom_wishlists', []);

            if (empty($favoriteIds)) {
                $properties = collect();
            } else {
                $allProperties = $this->api->getProperties();
                $properties = collect($allProperties)->filter(fn($p) => in_array($p['id'], $favoriteIds));
                $properties = ApiHelper::toPropertyCollection($properties);
            }

            return view('customer.wishlist', compact('properties', 'customWishlists'));
        } catch (\Exception $e) {
            Log::error('Saved properties error: ' . $e->getMessage());
            return view('customer.wishlist', [
                'properties' => collect(),
                'customWishlists' => []
            ]);
        }
    }

    /**
     * Save a property to wishlist
     */
    public function saveProperty($id)
    {
        try {
            $favorites = session()->get('favorites', []);
            if (!in_array($id, $favorites)) {
                $favorites[] = $id;
                session()->put('favorites', $favorites);
                $this->api->addToWishlist($id);
            }

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Property saved to favorites!']);
            }
            return back()->with('success', 'Property saved to favorites!');
        } catch (\Exception $e) {
            Log::error('Save property error: ' . $e->getMessage());
            return back()->with('error', 'Failed to save property.');
        }
    }

    /**
     * Remove a property from wishlist
     */
    public function removeSavedProperty($id)
    {
        try {
            $favorites = session()->get('favorites', []);
            if (($key = array_search($id, $favorites)) !== false) {
                unset($favorites[$key]);
                session()->put('favorites', $favorites);
                $this->api->removeFromWishlist($id);
            }

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Property removed from favorites.']);
            }
            return back()->with('success', 'Property removed from favorites.');
        } catch (\Exception $e) {
            Log::error('Remove saved property error: ' . $e->getMessage());
            return back()->with('error', 'Failed to remove property.');
        }
    }

    /**
     * Check if property is saved (private method)
     */
    private function isPropertySaved($propertyId)
    {
        $favorites = session()->get('favorites', []);
        return in_array($propertyId, $favorites);
    }

    /**
     * Create a custom wishlist (stored in session)
     */
    public function createCustomWishlist(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100|min:2'
            ]);

            $customWishlists = session()->get('custom_wishlists', []);

            $newWishlist = [
                'id' => time() . rand(100, 999),
                'name' => $request->name,
                'property_count' => 0,
                'created_at' => now()->toISOString()
            ];

            $customWishlists[] = $newWishlist;
            session()->put('custom_wishlists', $customWishlists);

            return response()->json([
                'success' => true,
                'wishlist' => $newWishlist,
                'message' => 'Wishlist created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Create custom wishlist error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create wishlist'], 500);
        }
    }

    /**
     * Delete a custom wishlist from session
     */
    public function deleteCustomWishlist($id)
    {
        try {
            $customWishlists = session()->get('custom_wishlists', []);

            $customWishlists = array_filter($customWishlists, function($wishlist) use ($id) {
                return $wishlist['id'] != $id;
            });

            session()->put('custom_wishlists', array_values($customWishlists));

            return response()->json(['success' => true, 'message' => 'Wishlist deleted']);
        } catch (\Exception $e) {
            Log::error('Delete custom wishlist error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete wishlist'], 500);
        }
    }

    /**
     * Display profile management page
     */
    public function profileManagement()
    {
        try {
            $user = $this->api->getCurrentUser();
            return view('customer.profile-management', compact('user'));
        } catch (\Exception $e) {
            Log::error('Profile management error: ' . $e->getMessage());
            return view('customer.profile-management', ['user' => session('user', ['name' => 'Guest'])]);
        }
    }

    /**
     * Display user's own profile
     */
    public function profile()
    {
        try {
            $user = $this->api->getCurrentUser();
            $recentReviews = $this->api->getUserReviews();
            $totalReviews = is_array($recentReviews) ? count($recentReviews) : 0;
            return view('customer.profile', compact('user', 'recentReviews', 'totalReviews'));
        } catch (\Exception $e) {
            Log::error('Profile error: ' . $e->getMessage());
            return view('customer.profile', [
                'user' => session('user', ['name' => 'Guest']),
                'recentReviews' => [],
                'totalReviews' => 0
            ]);
        }
    }

    /**
     * Display another user's profile
     */
    public function userProfile($id)
    {
        try {
            $profileUser = $this->api->getUserProfile($id);
            $recentReviews = $this->api->getUserReviews($id);
            $totalReviews = is_array($recentReviews) ? count($recentReviews) : 0;
            $currentUser = $this->api->getCurrentUser();

            return view('customer.profile', compact('profileUser', 'recentReviews', 'totalReviews', 'currentUser'));
        } catch (\Exception $e) {
            Log::error('User profile error: ' . $e->getMessage());
            abort(404, 'User not found');
        }
    }

    /**
     * Display user's reviews
     */
    public function myReviews()
    {
        try {
            $pendingReviews = $this->api->getPendingReviews();
            $pastReviews = $this->api->getUserReviews();

            // Ensure they are arrays
            if (!is_array($pendingReviews)) $pendingReviews = [];
            if (!is_array($pastReviews)) $pastReviews = [];

            return view('customer.my-reviews', compact('pendingReviews', 'pastReviews'));
        } catch (\Exception $e) {
            Log::error('My reviews error: ' . $e->getMessage());
            return view('customer.my-reviews', [
                'pendingReviews' => [],
                'pastReviews' => []
            ]);
        }
    }

    /**
     * Display notifications
     */
    public function notifications()
    {
        try {
            $notifications = $this->api->getNotifications();

            if (!is_array($notifications)) {
                $notifications = [];
            }

            $grouped = [];
            foreach ($notifications as $notif) {
                $date = \Carbon\Carbon::parse($notif['created_at'] ?? now())->format('F j, Y');
                if (!isset($grouped[$date])) $grouped[$date] = [];
                $grouped[$date][] = $notif;
            }

            return view('customer.notifications', compact('grouped'));
        } catch (\Exception $e) {
            Log::error('Notifications error: ' . $e->getMessage());
            return view('customer.notifications', ['grouped' => []]);
        }
    }

    /**
     * Mark a single notification as read
     */
    public function markNotificationRead($id)
    {
        try {
            $this->api->markNotificationRead($id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Mark notification read error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead()
    {
        try {
            $this->api->markAllNotificationsRead();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Mark all notifications read error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Display help center page
     */
    public function helpCenter()
    {
        return view('customer.help-center');
    }

    /**
     * Display map view of properties
     */
    public function mapView(Request $request)
    {
        try {
            $rawProperties = $this->api->getProperties();

            // Ensure we have an array
            if (!is_array($rawProperties)) {
                $rawProperties = [];
            }

            // Apply location filter if provided
            if ($request->location && !empty($rawProperties)) {
                $rawProperties = collect($rawProperties)->filter(function($p) use ($request) {
                    return stripos($p['location'] ?? '', $request->location) !== false;
                })->values()->all();
            }

            Log::info('Map view properties count: ' . count($rawProperties));

            return view('customer.map-view', ['properties' => $rawProperties]);

        } catch (\Exception $e) {
            Log::error('Map view error: ' . $e->getMessage());
            return view('customer.map-view', ['properties' => []]);
        }
    }

    /**
     * Display booking payment page
     */
    public function bookingPayment($propertyId, Request $request)
    {
        try {
            $rawProperty = $this->api->getProperty($propertyId);
            if (!$rawProperty) abort(404);

            $property = ApiHelper::toPropertyObject($rawProperty);
            $user = $this->api->getCurrentUser();

            $checkIn = $request->get('check_in', now()->format('Y-m-d'));
            $checkOut = $request->get('check_out', now()->addDays(3)->format('Y-m-d'));
            $nights = \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut));
            $guests = $request->get('guests', 2);

            $cleaningFee = 2500;
            $serviceFee = 4200;
            $taxes = 1800;
            $totalPrice = ($property->price_per_night * $nights) + $cleaningFee + $serviceFee + $taxes;
            $cancelDeadline = \Carbon\Carbon::parse($checkIn)->subDays(2)->format('M j, Y');

            return view('customer.booking-payment', compact('property', 'user', 'checkIn', 'checkOut', 'nights', 'guests', 'cleaningFee', 'serviceFee', 'taxes', 'totalPrice', 'cancelDeadline'));
        } catch (\Exception $e) {
            Log::error('Booking payment error: ' . $e->getMessage());
            abort(404, 'Property not found');
        }
    }

    /**
     * Display booking confirmation page
     */
    public function confirmation($bookingId)
    {
        try {
            $rawBooking = $this->api->getBookingDetails($bookingId);
            if (!$rawBooking) abort(404);

            $booking = ApiHelper::toBookingObject($rawBooking);
            $user = $this->api->getCurrentUser();

            return view('customer.confirmation', compact('booking', 'user'));
        } catch (\Exception $e) {
            Log::error('Confirmation error: ' . $e->getMessage());
            abort(404, 'Booking not found');
        }
    }

    /**
     * Update user profile via API
     */
    public function updateProfile(Request $request)
    {
        try {
            $result = $this->api->updateProfile($request->only(['name', 'email', 'phone', 'bio']));
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            Log::error('Update profile error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update profile'], 500);
        }
    }

    /**
     * Change user password via API
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed'
            ]);

            $result = $this->api->changePassword($request->only(['current_password', 'password', 'password_confirmation']));
            return response()->json(['success' => $result]);
        } catch (\Exception $e) {
            Log::error('Change password error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to change password'], 500);
        }
    }
}
