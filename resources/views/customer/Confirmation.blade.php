{{-- resources/views/customer/confirmation.blade.php --}}
@extends('layouts.app')

@section('title', 'Booking Confirmed')

@section('content')
<div class="container-custom max-w-2xl mx-auto text-center">
    <!-- Success Animation -->
    <div class="relative w-full max-w-md aspect-square mx-auto mb-8">
        <div class="absolute inset-0 bg-success/20 rounded-full scale-90 blur-3xl"></div>
        <div class="relative z-10 flex flex-col items-center">
            <div class="w-32 h-32 bg-surface-card rounded-xl shadow-lg flex items-center justify-center overflow-hidden border border-success/30 mx-auto">
                <span class="material-symbols-outlined text-success text-5xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>
            <div class="mt-4 bg-success/10 text-success px-4 py-2 rounded-full flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                <span class="text-sm font-semibold">Booking Confirmed</span>
            </div>
        </div>
    </div>

    <h1 class="text-3xl md:text-4xl font-bold text-text-primary heading-font mb-2">Booking Confirmed ✅</h1>
    <p class="text-text-muted text-lg mb-8">Pack your bags! Your stay is locked in.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <div class="bg-surface-card p-6 rounded-xl shadow-sm border border-border-color text-left">
            <p class="text-xs text-text-muted uppercase tracking-wider mb-1">Booking ID</p>
            <p class="text-2xl font-bold text-brand-600 mb-4 heading-font">#{{ $booking['id'] }}</p>
            <a href="{{ route('customer.booking.details', $booking['id']) }}" class="block w-full bg-brand-500 text-white py-3 rounded-xl font-semibold hover:bg-brand-600 transition text-center">
                View your trip
            </a>
        </div>

        <div class="flex flex-col gap-3">
            <a href="#" class="flex items-center justify-between p-4 bg-surface-card border border-border-color rounded-xl hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-text-primary">directions</span>
                    </div>
                    <span class="font-medium text-text-primary">Get Directions</span>
                </div>
                <span class="material-symbols-outlined text-text-muted">chevron_right</span>
            </a>
            <a href="#" class="flex items-center justify-between p-4 bg-surface-card border border-border-color rounded-xl hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-text-primary">contact_support</span>
                    </div>
                    <span class="font-medium text-text-primary">Contact Host</span>
                </div>
                <span class="material-symbols-outlined text-text-muted">chevron_right</span>
            </a>
            <a href="{{ route('invoice.download', $booking['id']) }}" class="flex items-center justify-between p-4 bg-surface-card border border-border-color rounded-xl hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-text-primary">download</span>
                    </div>
                    <span class="font-medium text-text-primary">Download Receipt</span>
                </div>
                <span class="material-symbols-outlined text-text-muted">chevron_right</span>
            </a>
        </div>
    </div>

    <!-- Next Steps -->
    <div>
        <h3 class="text-xl font-semibold text-text-primary heading-font mb-6">Next Steps</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-brand-600">calendar_today</span>
                </div>
                <p class="font-medium text-text-primary">Add to Calendar</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-brand-600">share</span>
                </div>
                <p class="font-medium text-text-primary">Share with Guests</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-brand-600">explore</span>
                </div>
                <p class="font-medium text-text-primary">Explore Local Guide</p>
            </div>
        </div>
    </div>
</div>
@endsection
