{{-- resources/views/layouts/partials/mobile-bottom-nav.blade.php --}}
@php
    $isAuthenticated = Auth::check() || session()->has('api_token');
@endphp

@if($isAuthenticated)
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-border-color md:hidden z-40">
    <div class="flex justify-around items-center py-2">
        <a href="{{ route('home.authenticated') }}" class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors {{ request()->routeIs('home.authenticated') ? 'text-brand-600' : 'text-text-muted' }}">
            <span class="material-symbols-outlined text-xl">home</span>
            <span class="text-[10px] mt-0.5">Home</span>
        </a>
        <a href="{{ route('customer.browse') }}" class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors {{ request()->routeIs('customer.browse') ? 'text-brand-600' : 'text-text-muted' }}">
            <span class="material-symbols-outlined text-xl">search</span>
            <span class="text-[10px] mt-0.5">Explore</span>
        </a>
        <a href="{{ route('customer.calendar') }}" class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors {{ request()->routeIs('customer.calendar') ? 'text-brand-600' : 'text-text-muted' }}">
            <span class="material-symbols-outlined text-xl">calendar_month</span>
            <span class="text-[10px] mt-0.5">Calendar</span>
        </a>
        <a href="{{ route('customer.bookings') }}" class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors {{ request()->routeIs('customer.bookings') ? 'text-brand-600' : 'text-text-muted' }}">
            <span class="material-symbols-outlined text-xl">receipt_long</span>
            <span class="text-[10px] mt-0.5">Trips</span>
        </a>
        <a href="{{ route('customer.saved') }}" class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors {{ request()->routeIs('customer.saved') ? 'text-brand-600' : 'text-text-muted' }}">
            <span class="material-symbols-outlined text-xl">favorite</span>
            <span class="text-[10px] mt-0.5">Wishlist</span>
        </a>
        <a href="{{ route('customer.profile') }}" class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors {{ request()->routeIs('customer.profile') ? 'text-brand-600' : 'text-text-muted' }}">
            <span class="material-symbols-outlined text-xl">person</span>
            <span class="text-[10px] mt-0.5">Profile</span>
        </a>
    </div>
</nav>
@endif
