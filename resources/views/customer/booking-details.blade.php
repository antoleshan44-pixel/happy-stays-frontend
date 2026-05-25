{{-- resources/views/customer/booking-details.blade.php --}}
@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="container-custom max-w-4xl mx-auto">
    <!-- Status Banner -->
    <div class="mb-6 p-4 rounded-xl {{ $booking->status == 'confirmed' ? 'bg-green-50 border border-green-200' : ($booking->status == 'pending' ? 'bg-yellow-50 border border-yellow-200' : 'bg-red-50 border border-red-200') }}">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined {{ $booking->status == 'confirmed' ? 'text-success' : ($booking->status == 'pending' ? 'text-warning' : 'text-error') }}">
                {{ $booking->status == 'confirmed' ? 'check_circle' : ($booking->status == 'pending' ? 'schedule' : 'cancel') }}
            </span>
            <div>
                <h3 class="font-bold text-text-primary">Booking {{ ucfirst($booking->status) }}</h3>
                <p class="text-sm text-text-muted">Booking ID: #{{ $booking->id }}</p>
            </div>
        </div>
    </div>

    <!-- Property Info -->
    <div class="bg-surface-card rounded-xl overflow-hidden border border-border-color mb-6">
        <div class="md:flex">
            <div class="md:w-1/3 h-48 md:h-auto">
                <img class="w-full h-full object-cover"
                     src="{{ isset($booking->property->photos[0]) ? asset('storage/' . $booking->property->photos[0]->photo_path) : 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400&h=300&fit=crop' }}"
                     alt="{{ $booking->property->title ?? 'Property' }}">
            </div>
            <div class="p-6 flex-1">
                <h2 class="text-2xl font-bold text-text-primary mb-2 heading-font">{{ $booking->property->title ?? 'Property' }}</h2>
                <p class="text-text-muted flex items-center gap-1 mb-4">
                    <span class="material-symbols-outlined text-sm">location_on</span>
                    {{ $booking->property->location ?? 'Unknown location' }}
                </p>
                <a href="{{ route('customer.property.detail', $booking->property->id) }}" class="text-brand-600 text-sm font-semibold hover:underline">
                    View Property Details →
                </a>
            </div>
        </div>
    </div>

    <!-- Trip Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-surface-card rounded-xl p-6 border border-border-color">
            <h3 class="font-semibold text-text-primary mb-4 flex items-center gap-2 heading-font">
                <span class="material-symbols-outlined text-brand-500">calendar_today</span>
                Trip Dates
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-text-muted">Check-in:</span>
                    <span class="text-text-primary font-semibold">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('l, F j, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-text-muted">Check-out:</span>
                    <span class="text-text-primary font-semibold">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('l, F j, Y') }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-border-color">
                    <span class="text-text-muted">Total nights:</span>
                    <span class="text-text-primary font-semibold">{{ $booking->nights }} nights</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-card rounded-xl p-6 border border-border-color">
            <h3 class="font-semibold text-text-primary mb-4 flex items-center gap-2 heading-font">
                <span class="material-symbols-outlined text-brand-500">group</span>
                Guest Details
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-text-muted">Guests:</span>
                    <span class="text-text-primary font-semibold">{{ $booking->guests }} guests</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-text-muted">Booking date:</span>
                    <span class="text-text-primary">{{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Price Breakdown -->
    <div class="bg-surface-card rounded-xl p-6 border border-border-color mb-6">
        <h3 class="font-semibold text-text-primary mb-4 flex items-center gap-2 heading-font">
            <span class="material-symbols-outlined text-brand-500">receipt</span>
            Price Details
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between text-text-secondary">
                <span>KES {{ number_format($booking->price_per_night ?? 0) }} x {{ $booking->nights }} nights</span>
                <span>KES {{ number_format(($booking->price_per_night ?? 0) * $booking->nights) }}</span>
            </div>
            <div class="flex justify-between text-text-secondary">
                <span>Cleaning fee</span>
                <span>KES {{ number_format($booking->cleaning_fee ?? 2500) }}</span>
            </div>
            <div class="flex justify-between text-text-secondary">
                <span>Service fee</span>
                <span>KES {{ number_format($booking->service_fee ?? 4200) }}</span>
            </div>
            <div class="flex justify-between text-text-secondary">
                <span>Taxes</span>
                <span>KES {{ number_format($booking->taxes ?? 1800) }}</span>
            </div>
            <div class="pt-3 mt-3 border-t border-border-color flex justify-between items-center">
                <span class="font-bold text-text-primary">Total paid</span>
                <span class="text-2xl font-bold text-brand-600">KES {{ number_format($booking->total_price) }}</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4">
        <a href="{{ route('invoice.download', $booking->id) }}" class="flex-1 bg-brand-500 text-white py-3 rounded-xl font-semibold text-center hover:bg-brand-600 transition">
            Download Invoice
        </a>
        @if($booking->status == 'pending')
            <form method="POST" action="{{ route('customer.cancel.booking', $booking->id) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full border border-error text-error py-3 rounded-xl font-semibold hover:bg-red-50 transition">
                    Cancel Booking
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
