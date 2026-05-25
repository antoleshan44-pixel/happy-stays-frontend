{{-- resources/views/owner/property-bookings.blade.php --}}
@extends('layouts.owner')

@section('title', 'Bookings - ' . $property->title)

@section('content')
<div class="container-custom py-6">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-2">{{ $property->title }}</h1>
        <p class="text-on-surface-variant">{{ $property->location }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-surface-container-lowest rounded-xl p-4 text-center shadow-sm border border-outline-variant">
            <p class="text-2xl font-bold text-secondary">{{ $stats['total'] }}</p>
            <p class="text-xs text-on-surface-variant">Total</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-4 text-center shadow-sm border border-outline-variant">
            <p class="text-2xl font-bold text-success">{{ $stats['confirmed'] }}</p>
            <p class="text-xs text-on-surface-variant">Confirmed</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-4 text-center shadow-sm border border-outline-variant">
            <p class="text-2xl font-bold text-warning">{{ $stats['pending'] }}</p>
            <p class="text-xs text-on-surface-variant">Pending</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-4 text-center shadow-sm border border-outline-variant">
            <p class="text-2xl font-bold text-primary">{{ $stats['completed'] }}</p>
            <p class="text-xs text-on-surface-variant">Completed</p>
        </div>
        <div class="bg-gradient-to-br from-secondary to-secondary/80 rounded-xl p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-white">KES {{ number_format($stats['total_revenue']) }}</p>
            <p class="text-xs text-white/80">Revenue</p>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant">
            <h3 class="font-semibold text-on-surface">All Bookings</h3>
        </div>

        @if($bookings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase">Guest</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase">Check-in</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase">Check-out</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase">Guests</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase">Amount</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase">Booked On</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-surface-container-low transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-on-surface">{{ $booking->customer->name ?? 'Guest' }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $booking->customer->email ?? '' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $booking->guests }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-secondary">KES {{ number_format($booking->total_price) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($booking->status == 'confirmed') bg-success/10 text-success
                                @elseif($booking->status == 'pending') bg-warning/10 text-warning
                                @elseif($booking->status == 'completed') bg-primary/10 text-primary
                                @else bg-error/10 text-error @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-on-surface-variant">{{ $booking->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-3">receipt_long</span>
            <p class="text-on-surface-variant">No bookings yet for this property</p>
        </div>
        @endif
    </div>
</div>
@endsection
