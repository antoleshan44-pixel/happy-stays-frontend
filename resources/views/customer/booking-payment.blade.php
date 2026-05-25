{{-- resources/views/customer/booking-payment.blade.php --}}
@extends('layouts.app')

@section('title', 'Review & Pay')

@section('content')
<div class="container-custom">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Back Link -->
            <div>
                <a href="{{ route('customer.property.detail', $property['id']) }}" class="flex items-center gap-2 text-brand-600 font-semibold mb-4 hover:text-brand-700 transition">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Back to details
                </a>
                <h2 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-2">Review and pay</h2>
                <p class="text-text-muted">Confirm your booking details and complete payment</p>
            </div>

            <!-- Trip Summary -->
            <div class="bg-surface-card rounded-xl p-6 shadow-sm border border-border-color">
                <h3 class="font-semibold text-text-primary text-lg mb-4 heading-font">Your trip</h3>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-text-muted">Dates</p>
                        <p class="font-medium text-text-primary">{{ $checkIn }} – {{ $checkOut }}</p>
                    </div>
                    <a href="{{ route('customer.property.detail', $property['id']) }}" class="text-brand-600 text-sm font-semibold hover:text-brand-700">Edit</a>
                </div>
                <div class="flex justify-between items-start mt-4">
                    <div>
                        <p class="text-sm text-text-muted">Guests</p>
                        <p class="font-medium text-text-primary">{{ $guests }} guests</p>
                    </div>
                    <a href="{{ route('customer.property.detail', $property['id']) }}" class="text-brand-600 text-sm font-semibold hover:text-brand-700">Edit</a>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-surface-card rounded-xl p-6 shadow-sm border border-border-color">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-text-primary text-lg heading-font">Payment Method</h3>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-success text-sm">lock</span>
                        <span class="text-xs text-success uppercase font-bold">Secure</span>
                    </div>
                </div>

                <label class="relative flex items-center p-4 border-2 border-brand-500 bg-brand-50 rounded-xl cursor-pointer mb-4">
                    <input checked type="radio" name="payment" class="hidden">
                    <div class="flex-1 flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-lg shadow-sm flex items-center justify-center font-bold text-success">M</div>
                        <div>
                            <p class="font-semibold text-text-primary">M-Pesa</p>
                            <p class="text-sm text-text-muted">Pay via mobile money</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-brand-600">check_circle</span>
                </label>

                <div class="grid grid-cols-2 gap-4">
                    <label class="flex flex-col items-center p-4 border border-border-color rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <span class="material-symbols-outlined text-text-muted text-2xl mb-2">credit_card</span>
                        <p class="text-sm text-text-primary">Card</p>
                    </label>
                    <label class="flex flex-col items-center p-4 border border-border-color rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <span class="material-symbols-outlined text-text-muted text-2xl mb-2">account_balance_wallet</span>
                        <p class="text-sm text-text-primary">PayPal</p>
                    </label>
                </div>
            </div>

            <!-- Billing Details -->
            <div class="bg-surface-card rounded-xl p-6 shadow-sm border border-border-color">
                <h3 class="font-semibold text-text-primary text-lg mb-4 heading-font">Billing details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-text-primary mb-1">Full Name</label>
                        <input type="text" class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500"
                               placeholder="{{ $user['name'] ?? 'John Doe' }}">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-1">Phone Number</label>
                            <input type="tel" class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500"
                                   placeholder="+254 700 000 000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-1">Email Address</label>
                            <input type="email" class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500"
                                   placeholder="{{ $user['email'] ?? 'john@example.com' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 py-4 border-t border-border-color">
                <span class="material-symbols-outlined text-text-muted">security</span>
                <p class="text-sm text-text-muted">Your data is protected by industry-standard encryption and our secure payment gateway.</p>
            </div>
        </div>

        <!-- Right Column - Price Breakdown -->
        <div class="lg:col-span-5">
            <div class="sticky top-24 space-y-6">
                <div class="bg-surface-card rounded-xl overflow-hidden shadow-lg border border-border-color">
                    <div class="p-6 flex gap-4 border-b border-border-color">
                        <img class="w-24 h-24 rounded-lg object-cover"
                             src="{{ isset($property['photos'][0]['photoPath']) ? asset('storage/' . $property['photos'][0]['photoPath']) : 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=150&h=150&fit=crop' }}"
                             alt="Property">
                        <div>
                            <p class="text-sm text-text-muted mb-1">{{ $property['propertyType'] ?? 'Premium Suite' }}</p>
                            <h4 class="font-bold text-text-primary">{{ $property['title'] }}</h4>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-yellow-400 text-sm">star</span>
                                <span class="text-sm font-semibold text-text-primary">{{ number_format($property['averageRating'] ?? 4.5, 1) }}</span>
                                <span class="text-sm text-text-muted">({{ $property['reviews_count'] ?? 0 }} reviews)</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <h5 class="font-semibold text-text-muted uppercase text-sm tracking-wider">Price details</h5>
                        <div class="flex justify-between text-text-secondary">
                            <span>KES {{ number_format($property['pricePerNight']) }} x {{ $nights }} nights</span>
                            <span>KES {{ number_format($property['pricePerNight'] * $nights) }}</span>
                        </div>
                        <div class="flex justify-between text-text-secondary">
                            <span>Cleaning fee</span>
                            <span>KES {{ number_format($cleaningFee ?? 2500) }}</span>
                        </div>
                        <div class="flex justify-between text-text-secondary">
                            <span>Service fee</span>
                            <span>KES {{ number_format($serviceFee ?? 4200) }}</span>
                        </div>
                        <div class="flex justify-between text-text-secondary">
                            <span>Taxes</span>
                            <span>KES {{ number_format($taxes ?? 1800) }}</span>
                        </div>
                        <div class="pt-4 mt-2 border-t border-border-color flex justify-between items-center">
                            <span class="font-bold text-text-primary">Total (KES)</span>
                            <span class="text-2xl font-bold text-brand-600">KES {{ number_format($totalPrice) }}</span>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50 border-t border-border-color">
                        <form method="POST" action="{{ route('payment.mpesa', $bookingId ?? $property['id']) }}">
                            @csrf
                            <button type="submit" class="w-full bg-brand-500 text-white py-4 rounded-xl font-bold hover:bg-brand-600 transition shadow-sm">
                                Confirm and Pay
                            </button>
                        </form>
                        <p class="text-xs text-center text-text-muted mt-3 px-4">
                            By selecting the button above, you agree to the Property Rules and Cancellation Policy.
                        </p>
                    </div>
                </div>

                <div class="bg-brand-50 p-4 rounded-xl flex items-start gap-3 border border-brand-100">
                    <span class="material-symbols-outlined text-brand-600">calendar_today</span>
                    <div>
                        <p class="font-semibold text-sm text-text-primary">Free cancellation for 48 hours</p>
                        <p class="text-xs text-text-muted">Full refund if you change your mind by {{ $cancelDeadline ?? 'tomorrow' }}.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
