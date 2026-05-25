@extends('admin.layouts.app')

@section('title', 'Revenue Report')
@section('subtitle', 'View detailed revenue analytics')

@section('content')
@php use App\Helpers\CurrencyHelper; @endphp
<div class="space-y-6">
    <!-- Date Range Filter -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" id="startDate" class="border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" id="endDate" class="border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                <select id="reportType" class="border rounded-lg px-3 py-2">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" onclick="generateReport()">Generate Report</button>
            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700" onclick="exportExcel()">Export Excel</button>
            <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700" onclick="exportPDF()">Export PDF</button>
        </div>
    </div>

    <!-- Revenue Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-gray-500 text-sm">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-800">{{ CurrencyHelper::formatKES($totalRevenue ?? 0, true) }}</p>
            <p class="text-green-600 text-xs mt-2"><i class="fas fa-arrow-up"></i> {{ $revenueGrowth ?? 15 }}% vs last period</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-gray-500 text-sm">Platform Commission</p>
            <p class="text-2xl font-bold text-gray-800">{{ CurrencyHelper::formatKES($totalCommission ?? 0, true) }}</p>
            <p class="text-green-600 text-xs mt-2">{{ $avgCommission ?? 12 }}% average commission</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-gray-500 text-sm">Owner Payouts</p>
            <p class="text-2xl font-bold text-gray-800">{{ CurrencyHelper::formatKES($totalPayouts ?? 0, true) }}</p>
            <p class="text-green-600 text-xs mt-2"><i class="fas fa-arrow-up"></i> {{ $payoutGrowth ?? 12 }}% increase</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-gray-500 text-sm">Pending Payouts</p>
            <p class="text-2xl font-bold text-yellow-600">{{ CurrencyHelper::formatKES($pendingPayouts ?? 0, true) }}</p>
            <p class="text-gray-500 text-xs mt-2">Awaiting approval</p>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Revenue Trend</h3>
        <canvas id="revenueTrendChart" height="300"></canvas>
    </div>

    <!-- Revenue by Property Type -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Revenue by Property Type</h3>
            <canvas id="revenueByTypeChart" height="250"></canvas>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Revenue by Location</h3>
            <canvas id="revenueByLocationChart" height="250"></canvas>
        </div>
    </div>

    <!-- Top Performing Properties -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Top Performing Properties</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="topPropertiesTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue (KES)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commission (KES)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($topProperties as $property)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $property['title'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $property['owner_name'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $property['booking_count'] ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ CurrencyHelper::formatKES($property['revenue'] ?? 0, true) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ CurrencyHelper::formatKES(CurrencyHelper::calculateCommission($property['revenue'] ?? 0, $property['property_type'] ?? null), true) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Trend Chart
    const revenueData = @json($revenueTrendData ?? array_fill(0, 12, 0));
    const revenueLabels = @json($revenueLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);

    const ctx1 = document.getElementById('revenueTrendChart').getContext('2d');
    let revenueChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue (KES)',
                data: revenueData,
                borderColor: '#00288e',
                backgroundColor: 'rgba(0, 40, 142, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Revenue by Property Type
    const typeData = @json($revenueByTypeData ?? [3500000, 2000000, 1000000, 750000]);
    const typeLabels = @json($revenueByTypeLabels ?? ['Villas', 'Apartments', 'Cabins', 'Hotels']);
    const typeColors = @json($revenueByTypeColors ?? ['#667eea', '#28a745', '#fd7e14', '#17a2b8']);

    const ctx2 = document.getElementById('revenueByTypeChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: typeLabels,
            datasets: [{
                data: typeData,
                backgroundColor: typeColors
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': KES ' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Revenue by Location
    const locationData = @json($revenueByLocationData ?? [2500000, 2000000, 1500000, 500000, 750000]);
    const locationLabels = @json($revenueByLocationLabels ?? ['Nairobi', 'Diani', 'Mombasa', 'Nakuru', 'Naivasha']);

    const ctx3 = document.getElementById('revenueByLocationChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: locationLabels,
            datasets: [{
                label: 'Revenue (KES)',
                data: locationData,
                backgroundColor: '#667eea'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    function generateReport() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const reportType = document.getElementById('reportType').value;

        window.location.href = `?start_date=${startDate}&end_date=${endDate}&type=${reportType}`;
    }

    function exportExcel() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = `/admin/reports/export/revenue?format=excel&${params.toString()}`;
    }

    function exportPDF() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = `/admin/reports/export/revenue?format=pdf&${params.toString()}`;
    }
</script>
@endpush
@endsection
