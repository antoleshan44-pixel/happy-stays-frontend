{{-- resources/views/owner/earnings.blade.php --}}
@extends('layouts.owner')

@section('title', 'Earnings & Analytics')

@section('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
</style>
@endsection

@section('content')
<div class="container-custom py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-on-surface">Earnings &amp; Analytics</h1>
            <p class="text-on-surface-variant">Real-time overview of your property performance and payout status.</p>
        </div>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 bg-surface-container-lowest border border-outline-variant px-4 py-2 rounded-lg text-sm text-on-surface-variant hover:shadow-sm transition">
                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                Last 12 Months
            </button>
            <button onclick="exportReport()" class="flex items-center gap-2 bg-secondary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-secondary/90 transition shadow-md">
                <span class="material-symbols-outlined text-[20px]">download</span>
                Export Report
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-sm stat-card">
            <div class="flex justify-between items-start mb-2">
                <span class="material-symbols-outlined text-secondary bg-secondary/10 p-2 rounded-lg">payments</span>
                <span class="text-success text-sm flex items-center">+12% <span class="material-symbols-outlined text-[14px]">trending_up</span></span>
            </div>
            <p class="text-on-surface-variant text-sm uppercase tracking-tight">Total Lifetime Earnings</p>
            <h3 class="text-2xl font-bold text-on-surface">KES {{ number_format($totalRevenue ?? 0) }}</h3>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-sm stat-card">
            <div class="flex justify-between items-start mb-2">
                <span class="material-symbols-outlined text-warning bg-warning/10 p-2 rounded-lg">event_available</span>
                <span class="text-success text-sm flex items-center">+4.2% <span class="material-symbols-outlined text-[14px]">trending_up</span></span>
            </div>
            <p class="text-on-surface-variant text-sm uppercase tracking-tight">Total Completed Bookings</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $totalCompletedBookings ?? 0 }}</h3>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-sm stat-card">
            <div class="flex justify-between items-start mb-2">
                <span class="material-symbols-outlined text-on-surface bg-surface-container-high p-2 rounded-lg">nightlight</span>
                <span class="text-on-surface-variant text-sm">Stable</span>
            </div>
            <p class="text-on-surface-variant text-sm uppercase tracking-tight">Active Properties</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $activeProperties ?? 0 }}</h3>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Chart -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-on-surface">Revenue Trends</h2>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-secondary"></span>
                        <span class="text-sm text-on-surface-variant">Earnings</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Properties Table -->
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-on-surface">Earnings by Property</h2>
                    <span class="text-secondary text-sm">{{ count($propertyEarnings ?? []) }} Properties</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface-variant uppercase text-xs tracking-wider">
                                <th class="px-6 py-4 font-semibold">Property</th>
                                <th class="px-6 py-4 font-semibold">Location</th>
                                <th class="px-6 py-4 font-semibold">Completed Bookings</th>
                                <th class="px-6 py-4 font-semibold">Total Earnings</th>
                                <th class="px-6 py-4 font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse($propertyEarnings ?? [] as $item)
                            <tr class="hover:bg-surface-container-low transition cursor-pointer" onclick="window.location.href='{{ route('owner.property.bookings', $item['property']->id) }}'">
                                <td class="px-6 py-4 font-medium text-on-surface">{{ $item['property']->title }}</td>
                                <td class="px-6 py-4 text-on-surface-variant">{{ $item['property']->location }}</td>
                                <td class="px-6 py-4 text-on-surface-variant">{{ $item['bookings_count'] }}</td>
                                <td class="px-6 py-4 font-semibold text-on-surface">KES {{ number_format($item['earnings']) }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-secondary text-sm hover:underline">View Details</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">No earnings data available yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <aside class="lg:col-span-4 space-y-6">
            <!-- Earnings Distribution -->
            <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant shadow-sm">
                <h4 class="text-sm text-on-surface-variant uppercase mb-4">Earnings Distribution</h4>
                <div class="space-y-4">
                    @php
                        $total = array_sum(array_column($propertyEarnings ?? [], 'earnings'));
                        $topProperties = array_slice($propertyEarnings ?? [], 0, 5);
                    @endphp
                    @foreach($topProperties as $item)
                    @php $percentage = $total > 0 ? round(($item['earnings'] / $total) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="truncate">{{ Str::limit($item['property']->title, 20) }}</span>
                            <span class="font-bold">KES {{ number_format($item['earnings']) }}</span>
                        </div>
                        <div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-secondary rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-xs text-on-surface-variant mt-1">{{ $percentage }}% of total</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tip Card -->
            <div class="bg-secondary p-6 rounded-xl text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10">
                    <span class="material-symbols-outlined mb-2 text-3xl">lightbulb</span>
                    <h4 class="text-xl font-semibold mb-2">Earnings Insight</h4>
                    <p class="opacity-90 mb-4">Properties with professional photography earn up to 40% more. Update your listing photos today.</p>
                    <a href="{{ route('owner.properties') }}" class="inline-block bg-white text-secondary px-6 py-2 rounded-full text-sm font-semibold hover:bg-gray-50 transition">
                        Manage Properties
                    </a>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition"></div>
            </div>

            <!-- Monthly Summary -->
            <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant shadow-sm">
                <h4 class="text-sm text-on-surface-variant uppercase mb-4">Monthly Summary</h4>
                <div class="space-y-3">
                    @php $bestMonth = collect($monthlyEarnings ?? [])->sortByDesc('earnings')->first(); @endphp
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Best Month</span>
                        <span class="font-semibold text-on-surface">{{ $bestMonth['month'] ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Highest Earnings</span>
                        <span class="font-semibold text-secondary">KES {{ number_format($bestMonth['earnings'] ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Average Monthly</span>
                        <span class="font-semibold text-on-surface">KES {{ number_format(($totalRevenue ?? 0) / max(1, count($monthlyEarnings ?? []))) }}</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const months = @json(array_column($monthlyEarnings ?? [], 'month'));
        const earnings = @json(array_column($monthlyEarnings ?? [], 'earnings'));
        const last12Months = months.slice(-12);
        const last12Earnings = earnings.slice(-12);

        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: last12Months,
                datasets: [{
                    label: 'Earnings',
                    data: last12Earnings,
                    borderColor: '#00288e',
                    backgroundColor: 'rgba(0, 40, 142, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#00288e',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => 'KES ' + ctx.raw.toLocaleString() } } },
                scales: { y: { beginAtZero: true, ticks: { callback: (v) => 'KES ' + v.toLocaleString() } } }
            }
        });
    });

    function exportReport() { window.location.href = '{{ route("owner.earnings.export") }}'; }
</script>
@endsection
