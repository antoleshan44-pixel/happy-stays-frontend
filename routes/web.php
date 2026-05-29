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
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// ============================================
// CACHE CLEAR ROUTE (Temporary - Remove after everything works)
// ============================================
Route::get('/clear-cache', function() {
    try {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return "✅ Cache cleared successfully!";
    } catch (\Exception $e) {
        return "❌ Error clearing cache: " . $e->getMessage();
    }
});

// ============================================
// PUBLIC ROUTES (No login required)
// ============================================

Route::get('/', function () {
    return view('welcome');
})->name('home');

// About Us Page
Route::view('/about', 'about')->name('about.us');

// Regular User Auth Routes (Only for displaying forms - POST handled by direct API)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
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

    // SIMPLE UPLOAD TEST ROUTE
    Route::get('/property/{id}/upload-simple', function($id) {
        $api = app(App\Services\SpringBootApiService::class);
        $property = $api->getProperty($id, true);
        return view('owner.upload-simple', compact('property'));
    })->name('owner.upload.simple');

    // Earnings
    Route::get('/earnings',        [OwnerController::class, 'earnings'])->name('owner.earnings');
    Route::get('/earnings/export', [OwnerController::class, 'exportEarnings'])->name('owner.earnings.export');

    // Photo Management
    Route::get('/property/{id}/photos',                           [PhotoController::class, 'index'])->name('owner.property.photos');
    Route::post('/property/{id}/photos/upload',                   [PhotoController::class, 'upload'])->name('owner.property.upload');
    Route::post('/property/{propertyId}/upload-all',              [OwnerController::class, 'uploadAllMedia'])->name('owner.property.upload.all');

    Route::delete('/property/{propertyId}/photos/{photoId}',      [OwnerController::class, 'deletePhoto'])->name('owner.photo.delete');
    Route::put('/property/{propertyId}/photos/{photoId}/primary', [OwnerController::class, 'setPrimaryPhoto'])->name('owner.photo.primary');
    Route::post('/property/{id}/photos/reorder',                  [PhotoController::class, 'reorder'])->name('owner.photos.reorder');

    // Video Management
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

Route::prefix('admin')->middleware([\App\Http\Middleware\AdminAuth::class])->group(function () {
    // Dashboard
    Route::get('/dashboard',                    [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Property Management
    Route::get('/pending-properties',           [AdminController::class, 'pendingProperties'])->name('admin.pending');
    Route::get('/property/review/{id}',         [AdminController::class, 'reviewProperty'])->name('admin.property.review');
    Route::post('/property/{id}/approve',       [AdminController::class, 'approveProperty'])->name('admin.property.approve');
    Route::post('/property/{id}/reject',        [AdminController::class, 'rejectProperty'])->name('admin.property.reject');
    Route::post('/property/{id}/suspend',       [AdminController::class, 'suspendProperty'])->name('admin.property.suspend');
    Route::post('/property/{id}/archive',       [AdminController::class, 'archiveProperty'])->name('admin.property.archive');
    Route::delete('/property/{id}',             [AdminController::class, 'deleteProperty'])->name('admin.property.delete');
    Route::get('/all-properties',               [AdminController::class, 'allProperties'])->name('admin.properties');

    // User Management
    Route::get('/users',                        [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{id}',                   [AdminController::class, 'userDetails'])->name('admin.user.details');
    Route::post('/users/{id}/suspend',          [AdminController::class, 'suspendUser'])->name('admin.user.suspend');
    Route::post('/users/{id}/activate',         [AdminController::class, 'activateUser'])->name('admin.user.activate');

    // Payment Management
    Route::get('/payments',                     [AdminController::class, 'payments'])->name('admin.payments');
    Route::post('/payments/{id}/refund',        [AdminController::class, 'processRefund'])->name('admin.payment.refund');
    Route::get('/payouts',                      [AdminController::class, 'payouts'])->name('admin.payouts');
    Route::get('/revenue',                      [AdminController::class, 'revenueReport'])->name('admin.revenue');

    // Fraud Management
    Route::get('/fraud/alerts',                 [AdminController::class, 'fraudAlerts'])->name('admin.fraud.alerts');
    Route::get('/fraud/case/{id}',              [AdminController::class, 'fraudCase'])->name('admin.fraud.case');
    Route::post('/fraud/case/{id}/resolve',     [AdminController::class, 'resolveFraudCase'])->name('admin.fraud.resolve');

    // Dispute Management
    Route::get('/disputes',                     [AdminController::class, 'disputes'])->name('admin.disputes');
    Route::post('/disputes/{id}/resolve',       [AdminController::class, 'resolveDispute'])->name('admin.dispute.resolve');

    // Reports & Analytics
    Route::get('/reports',                      [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/export/{type}',        [AdminController::class, 'exportReport'])->name('admin.reports.export');

    // Settings
    Route::get('/settings',                     [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings/commissions',        [AdminController::class, 'updateCommissions'])->name('admin.settings.commissions');

    // Referrals
    Route::get('/referrals',                    [ReferralController::class, 'adminIndex'])->name('admin.referrals');
});

// ============================================
// PAYMENT ROUTES
// ============================================

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
