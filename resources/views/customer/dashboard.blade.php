{{-- resources/views/customer/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-custom">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-text-primary heading-font mb-2">
            Welcome back, {{ is_object($user) ? ($user->name ?? 'Guest') : (is_array($user) ? ($user['name'] ?? 'Guest') : 'Guest') }}! 👋
        </h1>
        <p class="text-text-secondary">Your architectural journey continues here.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-10">
        <div class="bg-surface-card rounded-xl p-4 md:p-5 shadow-sm border border-border-color hover-lift">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-brand-500 text-2xl md:text-3xl">receipt_long</span>
                <span class="text-2xl md:text-3xl font-bold text-text-primary">{{ $totalBookings ?? 0 }}</span>
            </div>
            <h3 class="font-semibold text-text-primary text-sm">Total Bookings</h3>
            <p class="text-xs text-text-muted">Lifetime reservations</p>
        </div>

        <div class="bg-surface-card rounded-xl p-4 md:p-5 shadow-sm border border-border-color hover-lift">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-brand-500 text-2xl md:text-3xl">event_upcoming</span>
                <span class="text-2xl md:text-3xl font-bold text-text-primary">{{ $upcomingBookings ?? 0 }}</span>
            </div>
            <h3 class="font-semibold text-text-primary text-sm">Upcoming Stays</h3>
            <p class="text-xs text-text-muted">Next 30 days</p>
        </div>

        <div class="bg-surface-card rounded-xl p-4 md:p-5 shadow-sm border border-border-color hover-lift">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-brand-500 text-2xl md:text-3xl">payments</span>
                <span class="text-2xl md:text-3xl font-bold text-text-primary">KES {{ number_format($totalSpent ?? 0, 0) }}</span>
            </div>
            <h3 class="font-semibold text-text-primary text-sm">Total Spent</h3>
            <p class="text-xs text-text-muted">Across all stays</p>
        </div>

        <div class="bg-surface-card rounded-xl p-4 md:p-5 shadow-sm border border-border-color hover-lift">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-brand-500 text-2xl md:text-3xl">favorite</span>
                <span class="text-2xl md:text-3xl font-bold text-text-primary">{{ $favoriteCount ?? 0 }}</span>
            </div>
            <h3 class="font-semibold text-text-primary text-sm">Saved Properties</h3>
            <p class="text-xs text-text-muted">Your curated collection</p>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="mb-10">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-xl md:text-2xl font-bold text-text-primary heading-font">Recent Bookings</h2>
            <a href="{{ route('customer.bookings') }}" class="text-brand-600 text-sm font-semibold hover:underline flex items-center gap-1">
                View all
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        @if(isset($recentBookings) && $recentBookings->count() > 0)
            <div class="space-y-3">
                @foreach($recentBookings as $booking)
                <div class="bg-surface-card rounded-xl p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm border border-border-color hover-lift">
                    <div class="flex items-center gap-4">
                        @php
                            $photoPath = null;
                            if (isset($booking->property->photos) && is_array($booking->property->photos) && count($booking->property->photos) > 0) {
                                $photoPath = $booking->property->photos[0]->photo_path ?? null;
                            }
                        @endphp
                        @if($photoPath)
                            <img src="{{ asset('storage/' . $photoPath) }}" alt="{{ $booking->property->title ?? 'Property' }}" class="w-16 h-16 rounded-lg object-cover">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl text-text-muted">home</span>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-text-primary">{{ $booking->property->title ?? 'Property' }}</h3>
                            <p class="text-sm text-text-muted">{{ $booking->property->location ?? 'Unknown' }}</p>
                            <p class="text-xs text-text-muted mt-1">
                                {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="font-bold text-text-primary">KES {{ number_format($booking->total_price ?? 0) }}</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mt-1
                            @if($booking->status == 'confirmed') bg-green-100 text-green-700
                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($booking->status == 'completed') bg-blue-100 text-blue-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($booking->status ?? 'unknown') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-surface-card rounded-xl p-12 text-center shadow-sm border border-border-color">
                <span class="material-symbols-outlined text-5xl text-text-muted mb-3">receipt_long</span>
                <h3 class="text-xl font-semibold text-text-primary mb-2">No bookings yet</h3>
                <p class="text-text-muted mb-6">Start your architectural journey with us.</p>
                <a href="{{ route('customer.browse') }}" class="bg-brand-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-brand-600 transition inline-block">
                    Browse Properties
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div>
        <h2 class="text-xl md:text-2xl font-bold text-text-primary heading-font mb-5">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
            <a href="{{ route('customer.browse') }}" class="bg-surface-card rounded-xl p-6 text-center shadow-sm border border-border-color hover-lift group">
                <span class="material-symbols-outlined text-4xl text-brand-500 mb-3 group-hover:scale-110 transition-transform inline-block">search</span>
                <h3 class="font-semibold text-text-primary mb-1">Discover Properties</h3>
                <p class="text-sm text-text-muted">Explore our curated collections</p>
            </a>

            <a href="{{ route('customer.bookings') }}" class="bg-surface-card rounded-xl p-6 text-center shadow-sm border border-border-color hover-lift group">
                <span class="material-symbols-outlined text-4xl text-brand-500 mb-3 group-hover:scale-110 transition-transform inline-block">calendar_month</span>
                <h3 class="font-semibold text-text-primary mb-1">Manage Bookings</h3>
                <p class="text-sm text-text-muted">View and manage your reservations</p>
            </a>

            <a href="{{ route('customer.saved') }}" class="bg-surface-card rounded-xl p-6 text-center shadow-sm border border-border-color hover-lift group">
                <span class="material-symbols-outlined text-4xl text-brand-500 mb-3 group-hover:scale-110 transition-transform inline-block">favorite</span>
                <h3 class="font-semibold text-text-primary mb-1">Saved Properties</h3>
                <p class="text-sm text-text-muted">Your curated wishlist</p>
            </a>
        </div>
    </div>
</div>
@endsection
