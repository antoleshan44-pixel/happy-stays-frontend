{{-- resources/views/customer/my-bookings.blade.php --}}
@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container-custom">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-2">My Bookings</h1>
        <p class="text-text-secondary">Manage your reservations and stay history</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-8">
        <div class="bg-surface-card rounded-xl p-3 text-center shadow-sm border border-border-color">
            <p class="text-xl font-bold text-text-primary">{{ $stats['total'] ?? 0 }}</p>
            <p class="text-xs text-text-muted">Total</p>
        </div>
        <div class="bg-surface-card rounded-xl p-3 text-center shadow-sm border border-green-100">
            <p class="text-xl font-bold text-success">{{ $stats['confirmed'] ?? 0 }}</p>
            <p class="text-xs text-text-muted">Confirmed</p>
        </div>
        <div class="bg-surface-card rounded-xl p-3 text-center shadow-sm border border-yellow-100">
            <p class="text-xl font-bold text-warning">{{ $stats['pending'] ?? 0 }}</p>
            <p class="text-xs text-text-muted">Pending</p>
        </div>
        <div class="bg-surface-card rounded-xl p-3 text-center shadow-sm border border-blue-100">
            <p class="text-xl font-bold text-blue-600">{{ $stats['completed'] ?? 0 }}</p>
            <p class="text-xs text-text-muted">Completed</p>
        </div>
        <div class="bg-surface-card rounded-xl p-3 text-center shadow-sm border border-red-100">
            <p class="text-xl font-bold text-error">{{ $stats['cancelled'] ?? 0 }}</p>
            <p class="text-xs text-text-muted">Cancelled</p>
        </div>
    </div>

    @if($bookings->count() > 0)
        <div class="space-y-3">
            @foreach($bookings as $booking)
            <div class="bg-surface-card rounded-xl overflow-hidden shadow-sm border border-border-color hover-lift">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-40 h-32 md:h-auto overflow-hidden bg-gray-100">
                        @php
                            $firstPhoto = null;
                            if (isset($booking->property->photos) && is_array($booking->property->photos) && count($booking->property->photos) > 0) {
                                $firstPhoto = $booking->property->photos[0]->photo_path ?? null;
                            }
                        @endphp
                        @if($firstPhoto)
                            <img src="{{ asset('storage/' . $firstPhoto) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-text-muted text-3xl">home</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 p-4">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-3">
                            <div>
                                <h3 class="font-bold text-text-primary">{{ $booking->property->title ?? 'Property' }}</h3>
                                <p class="text-sm text-text-muted flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-sm">location_on</span>
                                    {{ $booking->property->location ?? 'Unknown' }}
                                </p>
                            </div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mt-2 md:mt-0
                                {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' :
                                   ($booking->status == 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                   ($booking->status == 'completed' ? 'bg-blue-100 text-blue-700' :
                                   'bg-red-100 text-red-700')) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3 text-sm">
                            <div>
                                <p class="text-xs text-text-muted">Check-in</p>
                                <p class="font-medium text-text-primary">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-text-muted">Check-out</p>
                                <p class="font-medium text-text-primary">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-text-muted">Guests</p>
                                <p class="font-medium text-text-primary">{{ $booking->guests }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-text-muted">Total</p>
                                <p class="font-medium text-brand-600">KES {{ number_format($booking->total_price) }}</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('customer.booking.details', $booking->id) }}" class="bg-brand-500 text-white px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-brand-600 transition">
                                View Details
                            </a>
                            @if($booking->status == 'pending')
                                <form method="POST" action="{{ route('customer.cancel.booking', $booking->id) }}" onsubmit="return confirm('Cancel this booking?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border border-error text-error px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-red-50 transition">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="bg-surface-card rounded-xl p-12 text-center shadow-sm border border-border-color">
            <span class="material-symbols-outlined text-5xl text-text-muted mb-3">receipt_long</span>
            <h3 class="text-xl font-semibold text-text-primary mb-2">No bookings yet</h3>
            <p class="text-text-muted mb-6">Start exploring our curated collections and book your next stay.</p>
            <a href="{{ route('customer.browse') }}" class="bg-brand-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-brand-600 transition inline-block">
                Browse Properties
            </a>
        </div>
    @endif
</div>
@endsection
