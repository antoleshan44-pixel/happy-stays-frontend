{{-- resources/views/owner/dashboard.blade.php --}}
@extends('layouts.owner')

@section('title', 'Host Dashboard')

@section('content')
<div class="container-custom py-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-on-surface">Good Morning, {{ $ownerName }}!</h1>
            <p class="text-base text-on-surface-variant">Here is what is happening across your {{ $totalProperties }} properties today.</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('owner.earnings.export') }}" class="bg-surface-container-lowest border border-outline-variant text-on-surface text-sm font-semibold px-6 py-3 rounded-lg hover:shadow-md transition">
                Download Report
            </a>
            <a href="{{ route('owner.property.create') }}" class="bg-secondary text-white text-sm font-semibold px-6 py-3 rounded-lg hover:shadow-md transition">
                Add Property
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="stat-card bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant">
            <div class="flex items-center justify-between mb-2">
                <span class="material-symbols-outlined text-secondary">home_work</span>
                <span class="text-xs font-bold text-secondary">{{ $totalProperties }}</span>
            </div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wide">Total Properties</p>
            <p class="text-2xl font-bold text-on-surface">{{ $totalProperties }}</p>
        </div>
        <div class="stat-card bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant">
            <div class="flex items-center justify-between mb-2">
                <span class="material-symbols-outlined text-success">check_circle</span>
                <span class="text-xs font-bold text-success">{{ $activeProperties }}</span>
            </div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wide">Active Listings</p>
            <p class="text-2xl font-bold text-on-surface">{{ $activeProperties }}</p>
        </div>
        <div class="stat-card bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant">
            <div class="flex items-center justify-between mb-2">
                <span class="material-symbols-outlined text-warning">hourglass_empty</span>
                <span class="text-xs font-bold text-warning">{{ $pendingProperties }}</span>
            </div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wide">Pending Review</p>
            <p class="text-2xl font-bold text-on-surface">{{ $pendingProperties }}</p>
        </div>
        <div class="stat-card bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant">
            <div class="flex items-center justify-between mb-2">
                <span class="material-symbols-outlined text-secondary">calendar_today</span>
                <span class="text-xs font-bold text-secondary">{{ $activeBookings }}</span>
            </div>
            <p class="text-xs text-on-surface-variant uppercase tracking-wide">Active Bookings</p>
            <p class="text-2xl font-bold text-on-surface">{{ $activeBookings }}</p>
        </div>
        <div class="stat-card bg-gradient-to-br from-secondary to-secondary/80 rounded-xl p-4 shadow-sm text-white">
            <div class="flex items-center justify-between mb-2">
                <span class="material-symbols-outlined text-white">payments</span>
                <span class="text-xs font-bold text-white/80">Total Earnings</span>
            </div>
            <p class="text-2xl font-bold">KES {{ number_format($totalEarnings) }}</p>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="xl:col-span-2 bg-surface-container-lowest rounded-xl shadow-sm p-6 border border-outline-variant">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-semibold text-lg text-on-surface">Revenue Growth</h3>
                    <p class="text-sm text-on-surface-variant">Last 6 Months</p>
                </div>
                <div class="flex items-center gap-2 text-secondary font-bold">
                    <span class="material-symbols-outlined">trending_up</span>
                    <span class="text-sm">+{{ $monthlyEarnings[5]['percentage'] ?? 0 }}%</span>
                </div>
            </div>
            <div class="flex items-end justify-between h-64 pt-8 gap-2">
                @foreach($monthlyEarnings as $month)
                <div class="flex-1 flex flex-col items-center gap-3">
                    <div class="w-full bg-secondary/20 rounded-t-lg chart-bar" style="height: {{ max(5, $month['percentage']) }}%;"></div>
                    <span class="text-xs text-on-surface-variant">{{ $month['month'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Operations Feed -->
        <div class="xl:col-span-1 bg-surface-container-lowest rounded-xl shadow-sm p-6 border border-outline-variant">
            <h3 class="font-semibold text-lg text-on-surface mb-4">Operations Feed</h3>
            <div class="flex flex-col gap-3">
                <div class="flex gap-3 p-3 rounded-lg bg-error/10 border-l-4 border-error">
                    <span class="material-symbols-outlined text-error">cleaning_services</span>
                    <div class="flex-1">
                        <h4 class="font-medium text-sm">Cleaning Alert</h4>
                        <p class="text-xs text-on-surface-variant">Property needs inspection before check-in.</p>
                    </div>
                </div>
                <div class="flex gap-3 p-3 rounded-lg bg-secondary/10 border-l-4 border-secondary">
                    <span class="material-symbols-outlined text-secondary">mail</span>
                    <div class="flex-1">
                        <h4 class="font-medium text-sm">Unread Message</h4>
                        <p class="text-xs text-on-surface-variant">Guest asked about late checkout.</p>
                    </div>
                </div>
                @foreach($activeBookingsList as $booking)
                <div class="flex gap-3 p-3 rounded-lg bg-surface-container">
                    <span class="material-symbols-outlined text-on-surface-variant">key</span>
                    <div class="flex-1">
                        <h4 class="font-medium text-sm">Upcoming Check-in</h4>
                        <p class="text-xs text-on-surface-variant">{{ $booking['guest_name'] }} arriving at {{ $booking['property_title'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('owner.properties') }}" class="w-full text-center py-2 text-secondary text-sm font-semibold hover:underline block mt-4">View all tasks →</a>
        </div>
    </div>

    <!-- Active Bookings Section -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-lg text-on-surface">Active Bookings</h3>
            <span class="bg-surface-container px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider text-on-surface-variant">Live Now: {{ $activeBookings }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($activeBookingsList as $booking)
            <div class="booking-card bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="h-32 w-full bg-secondary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-secondary">home</span>
                </div>
                <div class="p-4">
                    <h4 class="font-semibold text-on-surface">{{ $booking['property_title'] }}</h4>
                    <p class="text-xs text-on-surface-variant mt-1">Guest: {{ $booking['guest_name'] }}</p>
                    <div class="flex justify-between items-center mt-3 pt-2 border-t border-outline-variant">
                        <p class="font-bold text-secondary">KES {{ number_format($booking['price_per_night']) }}<span class="text-xs text-on-surface-variant">/night</span></p>
                        <a href="{{ route('owner.property.bookings', $propertyId ?? 1) }}" class="material-symbols-outlined text-on-surface-variant text-sm">chevron_right</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-8 text-on-surface-variant">No active bookings yet.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        const bars = document.querySelectorAll('.chart-bar');
        bars.forEach(bar => {
            const height = bar.style.height;
            bar.style.height = '0';
            setTimeout(() => { bar.style.height = height; }, 100);
        });
    });
</script>
@endsection
