<?php
// File: routes/web.php
// LOCATION: C:\xampp\htdocs\eserian-homes\routes\web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PriceAlertController;
use App\Http\Controllers\HostCalendarController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ============================================
// PUBLIC ROUTES (No login required)
// ============================================

Route::get('/', function () {
    return view('welcome');
})->name('home');

// About Us Page
Route::view('/about', 'about')->name('about.us');

// Regular User Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// ADMIN AUTHENTICATION ROUTES (Public - No login required)
// ============================================

Route::prefix('admin')->group(function () {
    // Login routes
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    
    // 2FA routes
    Route::get('/2fa/verify', [AdminAuthController::class, 'show2faVerification'])->name('admin.2fa.verify');
    Route::post('/2fa/verify', [AdminAuthController::class, 'verify2fa'])->name('admin.2fa.verify.post');
    
    // Logout route
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Password reset routes
    Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPassword'])->name('admin.password.request');
    Route::post('/forgot-password', [AdminAuthController::class, 'sendResetLink'])->name('admin.password.email');
    Route::get('/reset-password/{token}', [AdminAuthController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('/reset-password', [AdminAuthController::class, 'resetPassword'])->name('admin.password.update');
});

// ============================================
// AUTHENTICATED HOME PAGE (After login)
// ============================================

Route::get('/home', function () {
    $api        = app(App\Services\SpringBootApiService::class);
    $user       = $api->getCurrentUser();
    $properties = $api->getProperties();
    return view('home', compact('user', 'properties'));
})->name('home.authenticated')->middleware('spring.auth');

// Public property browsing (no login required)
Route::get('/properties', [CustomerController::class, 'browseProperties'])->name('public.browse');
Route::get('/property/{id}', [CustomerController::class, 'propertyDetail'])->name('public.property.detail');
Route::get('/map', [CustomerController::class, 'mapView'])->name('public.map');

// Referral Routes (Public)
Route::get('/refer/{code}', [ReferralController::class, 'redeem'])->name('referral.redeem');

// Invoice download route
Route::get('/invoice/download/{bookingId}', [InvoiceController::class, 'downloadInvoice'])->name('invoice.download');

// ============================================
// CUSTOMER ROUTES (Requires Spring Boot authentication)
// ============================================

Route::prefix('customer')->middleware(['spring.auth'])->group(function () {

    // Dashboard & Home
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/browse',    [CustomerController::class, 'browseProperties'])->name('customer.browse');
    Route::get('/search',    [CustomerController::class, 'searchResults'])->name('customer.search');
    Route::get('/map',       [CustomerController::class, 'mapView'])->name('customer.map');

    // Properties
    Route::get('/property/{id}',         [CustomerController::class, 'propertyDetail'])->name('customer.property.detail');
    Route::get('/property/{id}/reviews', [CustomerController::class, 'propertyReviews'])->name('customer.property.reviews');
    Route::post('/property/{id}/review', [CustomerController::class, 'submitPropertyReview'])->name('customer.submit.property.review');

    // Bookings
    Route::post('/booking/{propertyId}',       [CustomerController::class, 'createBooking'])->name('customer.create.booking');
    Route::get('/my-bookings',                 [CustomerController::class, 'myBookings'])->name('customer.bookings');
    Route::get('/booking/{id}',                [CustomerController::class, 'bookingDetails'])->name('customer.booking.details');
    Route::delete('/booking/{id}/cancel',      [CustomerController::class, 'cancelBooking'])->name('customer.cancel.booking');
    Route::post('/booking/{bookingId}/review', [CustomerController::class, 'submitReview'])->name('customer.submit.review');
    Route::get('/calendar',                    [CustomerController::class, 'calendar'])->name('customer.calendar');

    // Saved Properties (Wishlist)
    Route::get('/saved-properties',          [CustomerController::class, 'savedProperties'])->name('customer.saved');
    Route::post('/property/{id}/save',       [CustomerController::class, 'saveProperty'])->name('customer.save.property');
    Route::delete('/property/{id}/remove',   [CustomerController::class, 'removeSavedProperty'])->name('customer.remove.property');

    // Custom Wishlist Routes (Session-based)
    Route::post('/create-wishlist',          [CustomerController::class, 'createCustomWishlist'])->name('customer.create.wishlist');
    Route::delete('/wishlist/{id}/delete',   [CustomerController::class, 'deleteCustomWishlist'])->name('customer.delete.wishlist');

    // User Profile
    Route::get('/profile',                          [CustomerController::class, 'profile'])->name('customer.profile');
    Route::get('/profile/{id}',                     [CustomerController::class, 'userProfile'])->name('customer.user.profile');
    Route::get('/profile-management',               [CustomerController::class, 'profileManagement'])->name('customer.profile.management');
    Route::put('/api/user/profile',                 [CustomerController::class, 'updateProfile'])->name('customer.update.profile');
    Route::post('/api/user/change-password',        [CustomerController::class, 'changePassword'])->name('customer.change.password');

    // Reviews
    Route::get('/my-reviews', [CustomerController::class, 'myReviews'])->name('customer.my.reviews');

    // Notifications
    Route::get('/notifications',                         [CustomerController::class, 'notifications'])->name('customer.notifications');
    Route::post('/api/notifications/{id}/read',          [CustomerController::class, 'markNotificationRead'])->name('customer.notification.read');
    Route::post('/api/notifications/read-all',           [CustomerController::class, 'markAllNotificationsRead'])->name('customer.notifications.read-all');

    // Messages
    Route::get('/messages',                              [MessageController::class, 'index'])->name('customer.messages');
    Route::get('/messages/conversation/{userId}',        [MessageController::class, 'conversation'])->name('customer.conversation');
    Route::post('/messages/send',                        [MessageController::class, 'send'])->name('customer.send.message');
    Route::get('/api/messages/{conversationId}',         [MessageController::class, 'getMessages'])->name('customer.get.messages');
    Route::post('/api/messages/send',                    [MessageController::class, 'sendApi'])->name('customer.send.message.api');

    // Help Center
    Route::get('/help-center', [CustomerController::class, 'helpCenter'])->name('customer.help.center');

    // Payment & Booking Flow
    Route::get('/booking-payment/{propertyId}', [CustomerController::class, 'bookingPayment'])->name('customer.booking.payment');
    Route::get('/confirmation/{bookingId}',     [CustomerController::class, 'confirmation'])->name('customer.confirmation');

    // Referral Routes
    Route::get('/referrals', [ReferralController::class, 'index'])->name('customer.referrals');
});

// ============================================
// OWNER ROUTES (Requires Spring Boot authentication)
// ============================================

Route::prefix('owner')->middleware(['spring.auth'])->group(function () {

    // Dashboard & Properties
    Route::get('/dashboard',                   [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/properties',                  [OwnerController::class, 'myProperties'])->name('owner.properties');
    Route::post('/properties/reorder',         [OwnerController::class, 'reorderProperties'])->name('owner.properties.reorder');
    Route::get('/property/create',             [OwnerController::class, 'createProperty'])->name('owner.property.create');
    Route::post('/property/store',             [OwnerController::class, 'storeProperty'])->name('owner.property.store');
    Route::get('/property/{id}/edit',          [OwnerController::class, 'editProperty'])->name('owner.property.edit');
    Route::put('/property/{id}',               [OwnerController::class, 'updateProperty'])->name('owner.property.update');
    Route::delete('/property/{id}',            [OwnerController::class, 'deleteProperty'])->name('owner.property.delete');
    Route::get('/property/{id}/bookings',      [OwnerController::class, 'propertyBookings'])->name('owner.property.bookings');
    Route::get('/property/{id}/complete',      [OwnerController::class, 'completeProperty'])->name('owner.complete.property');

    // SIMPLE UPLOAD TEST ROUTE - ADDED HERE
    Route::get('/property/{id}/upload-simple', function($id) {
        $api = app(App\Services\SpringBootApiService::class);
        $property = $api->getProperty($id, true);
        return view('owner.upload-simple', compact('property'));
    })->name('owner.upload.simple');

    // Earnings
    Route::get('/earnings',        [OwnerController::class, 'earnings'])->name('owner.earnings');
    Route::get('/earnings/export', [OwnerController::class, 'exportEarnings'])->name('owner.earnings.export');

    // Photo Management — handled by PhotoController
    Route::get('/property/{id}/photos',                           [PhotoController::class, 'index'])->name('owner.property.photos');
    Route::post('/property/{id}/photos/upload',                   [PhotoController::class, 'upload'])->name('owner.property.upload');

    // NEW: Combined upload for photos and video together
    Route::post('/property/{propertyId}/upload-all',              [OwnerController::class, 'uploadAllMedia'])->name('owner.property.upload.all');

    Route::delete('/property/{propertyId}/photos/{photoId}',      [PhotoController::class, 'destroy'])->name('owner.photo.delete');
    Route::put('/property/{propertyId}/photos/{photoId}/primary', [PhotoController::class, 'setPrimary'])->name('owner.photo.primary');
    Route::post('/property/{id}/photos/reorder',                  [PhotoController::class, 'reorder'])->name('owner.photos.reorder');

    // Video Management — handled by OwnerController
    Route::get('/property/{id}/videos',                                [OwnerController::class, 'manageVideos'])->name('owner.videos.manage');
    Route::post('/property/{id}/video/upload',                         [OwnerController::class, 'uploadVideo'])->name('owner.video.upload');
    Route::delete('/property/{propertyId}/video/{videoId}',            [OwnerController::class, 'deleteVideo'])->name('owner.video.delete');
    Route::put('/property/{propertyId}/video/{videoId}/featured',      [OwnerController::class, 'setFeaturedVideo'])->name('owner.video.featured');
    Route::post('/property/{id}/videos/reorder',                       [OwnerController::class, 'reorderVideos'])->name('owner.videos.reorder');

    // Calendar
    Route::get('/calendar/{id}',                      [HostCalendarController::class, 'index'])->name('owner.calendar');
    Route::post('/property/{id}/calendar/block',      [HostCalendarController::class, 'blockDate'])->name('owner.calendar.block');
    Route::post('/property/{id}/calendar/unblock',    [HostCalendarController::class, 'unblockDate'])->name('owner.calendar.unblock');
    Route::post('/property/{id}/calendar/bulk',       [HostCalendarController::class, 'bulkBlockDates'])->name('owner.calendar.bulk');
    Route::post('/property/{id}/calendar/price',      [HostCalendarController::class, 'setCustomPrice'])->name('owner.calendar.price');

    // Messaging
    Route::get('/messages',                           [MessageController::class, 'index'])->name('owner.messages');
    Route::get('/messages/conversation/{userId}',     [MessageController::class, 'conversation'])->name('owner.conversation');
    Route::post('/messages/send',                     [MessageController::class, 'send'])->name('owner.send.message');
});

// ============================================
// ADMIN PROTECTED ROUTES (Requires admin authentication)
// ============================================

Route::prefix('admin')->middleware(['admin.auth'])->group(function () {
    Route::get('/dashboard',                    [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/pending-properties',           [AdminController::class, 'pendingProperties'])->name('admin.pending');
    Route::post('/property/{id}/approve',       [AdminController::class, 'approveProperty'])->name('admin.property.approve');
    Route::post('/property/{id}/reject',        [AdminController::class, 'rejectProperty'])->name('admin.property.reject');
    Route::post('/property/{id}/suspend',       [AdminController::class, 'suspendProperty'])->name('admin.property.suspend');
    Route::post('/property/{id}/archive',       [AdminController::class, 'archiveProperty'])->name('admin.property.archive');
    Route::delete('/property/{id}',             [AdminController::class, 'deleteProperty'])->name('admin.property.delete');
    Route::get('/all-properties',               [AdminController::class, 'allProperties'])->name('admin.properties');
    Route::get('/users',                        [AdminController::class, 'users'])->name('admin.users');
    Route::get('/payments',                     [AdminController::class, 'payments'])->name('admin.payments');
    Route::get('/revenue',                      [AdminController::class, 'revenueReport'])->name('admin.revenue');
    Route::get('/referrals',                    [ReferralController::class, 'adminIndex'])->name('admin.referrals');
});

// ============================================
// PAYMENT ROUTES
// ============================================

// IMPORTANT: GET routes must come before POST routes to avoid conflicts

Route::get('/payment/mpesa/{bookingId}',          [PaymentController::class, 'initiatePayment'])->name('payment.mpesa');
Route::get('/payment/status/{id}',                [PaymentController::class, 'paymentStatus'])->name('payment.status');
Route::get('/payment/simulate/{paymentId}',       [PaymentController::class, 'simulateMpesaPayment'])->name('payment.simulate');
Route::post('/payment/mpesa/{bookingId}/process', [PaymentController::class, 'processMpesaPayment'])->name('payment.mpesa.process');
Route::post('/mpesa/callback',                    [PaymentController::class, 'mpesaCallback'])->name('mpesa.callback');

// ============================================
// API ROUTES (No auth for calendar events)
// ============================================

Route::get('/api/calendar/events/{propertyId}',             [HostCalendarController::class, 'getEvents'])->name('api.calendar.events');
Route::get('/api/calendar/ical/{propertyId}',               [HostCalendarController::class, 'exportIcal'])->name('api.calendar.ical');
Route::get('/api/properties/{propertyId}/upcoming-bookings',[HostCalendarController::class, 'getUpcomingBookings']);

// ============================================
// PRICE ALERT ROUTES
// ============================================

Route::post('/price-alert/store', [PriceAlertController::class, 'store'])->name('price.alert.store');

// ============================================
// DEBUG ROUTES (Remove in production)
// ============================================

Route::get('/debug-spring-boot', function () {
    $api     = app(App\Services\SpringBootApiService::class);
    $results = [];

    try {
        $properties = $api->getProperties();
        $results['get_properties'] = [
            'success' => true,
            'count'   => count($properties),
            'sample'  => count($properties) > 0 ? array_slice($properties, 0, 1) : null,
        ];
    } catch (\Exception $e) {
        $results['get_properties'] = [
            'success' => false,
            'error'   => $e->getMessage(),
        ];
    }

    $results['auth'] = [
        'has_token'     => session()->has('api_token'),
        'has_user'      => session()->has('user'),
        'user'          => session('user'),
        'token_preview' => session('api_token') ? substr(session('api_token'), 0, 20) . '...' : null,
    ];

    $results['config'] = [
        'api_url' => env('SPRING_BOOT_API_URL', 'http://localhost:8080/api'),
        'app_url' => env('APP_URL', 'http://localhost:8000'),
        'env'     => app()->environment(),
    ];

    return response()->json($results);
});

Route::post('/debug-booking-test/{propertyId}', function (Request $request, $propertyId) {
    $api = app(App\Services\SpringBootApiService::class);

    $testData = [
        'propertyId'   => (int) $propertyId,
        'checkInDate'  => $request->input('check_in_date', date('Y-m-d', strtotime('+7 days'))),
        'checkOutDate' => $request->input('check_out_date', date('Y-m-d', strtotime('+10 days'))),
        'guests'       => (int) $request->input('guests', 2),
    ];

    Log::info('Debug booking test', ['test_data' => $testData]);

    $result = $api->createBooking($testData);

    return response()->json([
        'test_data'    => $testData,
        'result'       => $result,
        'session_info' => [
            'has_token' => session()->has('api_token'),
            'user'      => session('user'),
        ],
    ]);
})->middleware('spring.auth');

Route::get('/check-api', function () {
    $apiUrl = env('SPRING_BOOT_API_URL', 'http://localhost:8080/api');

    try {
        $ch = curl_init($apiUrl . '/properties/approved');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return response()->json([
            'api_url'          => $apiUrl,
            'status_code'      => $httpCode,
            'reachable'        => $httpCode > 0,
            'response_preview' => $response ? substr($response, 0, 200) : null,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'api_url'   => $apiUrl,
            'reachable' => false,
            'error'     => $e->getMessage(),
        ]);
    }
});

// ============================================
// ADDITIONAL DEBUG ROUTES FOR AUTHENTICATION
// ============================================

// Debug route to check authentication token status
Route::get('/debug-token', function () {
    $token = session('api_token');
    $user = session('user');

    if (!$token) {
        return response()->json([
            'authenticated' => false,
            'message' => 'No token found in session',
            'user' => $user,
            'session_id' => session()->getId()
        ]);
    }

    try {
        $response = Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->timeout(10)->get('http://localhost:8080/api/owner/properties');

        return response()->json([
            'authenticated' => true,
            'has_token' => !empty($token),
            'token_preview' => substr($token, 0, 30) . '...',
            'token_length' => strlen($token),
            'user' => $user,
            'user_role' => $user['role'] ?? null,
            'session_id' => session()->getId(),
            'test_api_response' => [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'authenticated' => true,
            'has_token' => !empty($token),
            'token_preview' => substr($token, 0, 30) . '...',
            'user' => $user,
            'error' => $e->getMessage()
        ]);
    }
})->middleware('spring.auth');

// Debug route to check user details
Route::get('/debug-user', function () {
    $user = session('user');
    $token = session('api_token');

    return response()->json([
        'user' => $user,
        'user_role' => $user['role'] ?? null,
        'user_id' => $user['id'] ?? null,
        'user_email' => $user['email'] ?? null,
        'has_token' => !empty($token),
        'session_id' => session()->getId(),
        'all_session_data' => session()->all()
    ]);
})->middleware('spring.auth');

// Debug route to test direct API call
Route::get('/debug-api-test', function () {
    $token = session('api_token');

    if (!$token) {
        return response()->json(['error' => 'No token found'], 401);
    }

    try {
        // Test GET request
        $getResponse = Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->timeout(10)->get('http://localhost:8080/api/owner/properties');

        // Test POST request (without file) to check if endpoint exists
        $postResponse = Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->timeout(10)->post('http://localhost:8080/api/owner/properties/17/videos', [
            'test' => 'connection'
        ]);

        return response()->json([
            'token_valid' => true,
            'get_request' => [
                'status' => $getResponse->status(),
                'successful' => $getResponse->successful(),
                'body' => $getResponse->body(),
            ],
            'post_request' => [
                'status' => $postResponse->status(),
                'successful' => $postResponse->successful(),
                'body' => $postResponse->body(),
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'token_valid' => true,
            'error' => $e->getMessage()
        ]);
    }
})->middleware('spring.auth');

// ============================================
// TEST ROUTE - Isolate the property data issue
// ============================================

Route::get('/test-property/{id}', function ($id) {
    $api = app(App\Services\SpringBootApiService::class);
    $property = $api->getProperty($id, true);

    return response()->json([
        'success' => true,
        'type' => gettype($property),
        'is_array' => is_array($property),
        'keys' => is_array($property) ? array_keys($property) : null,
        'has_title' => isset($property['title']),
        'title' => $property['title'] ?? null,
        'has_id' => isset($property['id']),
        'id' => $property['id'] ?? null,
        'property_preview' => $property ? array_slice($property, 0, 10) : null
    ]);
})->middleware('spring.auth');
