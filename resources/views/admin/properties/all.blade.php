@extends('admin.layouts.app')

@section('title', 'All Properties')
@section('subtitle', 'Manage all property listings')

@section('content')
@php use App\Helpers\CurrencyHelper; @endphp
<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b border-gray-100">
        <div class="flex flex-wrap gap-3">
            <input type="text" placeholder="Search properties..." class="px-4 py-2 border rounded-lg text-sm flex-1">
            <select class="px-4 py-2 border rounded-lg text-sm" id="statusFilter">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="suspended">Suspended</option>
                <option value="archived">Archived</option>
            </select>
            <select class="px-4 py-2 border rounded-lg text-sm" id="typeFilter">
                <option value="">All Types</option>
                <option value="villa">Villa</option>
                <option value="apartment">Apartment</option>
                <option value="cabin">Cabin</option>
                <option value="house">House</option>
            </select>
            <select class="px-4 py-2 border rounded-lg text-sm" id="priceFilter">
                <option value="">Price Range</option>
                <option value="0-5000">Below KES 5,000</option>
                <option value="5000-10000">KES 5,000 - 10,000</option>
                <option value="10000-20000">KES 10,000 - 20,000</option>
                <option value="20000+">Above KES 20,000</option>
            </select>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700" onclick="applyFilters()">Apply Filter</button>
            <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200" onclick="resetFilters()">Reset</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="propertiesTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price/Night (KES)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue (KES)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($properties as $property)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">#{{ $property['id'] }}</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($property['title'] ?? 'N/A', 40) }}</p>
                            <p class="text-xs text-gray-500">{{ $property['location'] ?? 'N/A' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $property['owner_name'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ CurrencyHelper::formatKES($property['price_per_night'] ?? $property['pricePerNight'] ?? 0, true) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ ($property['status'] ?? 'pending') == 'approved' ? 'bg-green-100 text-green-700' :
                               (($property['status'] ?? 'pending') == 'pending' ? 'bg-yellow-100 text-yellow-700' :
                               (($property['status'] ?? 'pending') == 'suspended' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ ucfirst($property['status'] ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $property['booking_count'] ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ CurrencyHelper::formatKES($property['total_revenue'] ?? 0, true) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.property.show', $property['id']) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a>
                            <button onclick="suspendProperty({{ $property['id'] }})" class="text-red-600 hover:text-red-800"><i class="fas fa-ban"></i></button>
                            <button onclick="archiveProperty({{ $property['id'] }})" class="text-gray-600 hover:text-gray-800"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">No properties found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#propertiesTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 25
        });
    });

    function applyFilters() {
        const status = $('#statusFilter').val();
        const type = $('#typeFilter').val();
        const price = $('#priceFilter').val();
        window.location.href = `?status=${status}&type=${type}&price=${price}`;
    }

    function resetFilters() {
        window.location.href = window.location.pathname;
    }

    function suspendProperty(id) {
        if (confirm('Are you sure you want to suspend this property?')) {
            $.post(`/admin/property/${id}/suspend`, {
                _token: '{{ csrf_token() }}',
                reason: 'Admin action'
            }).done(function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Failed to suspend property');
                }
            });
        }
    }

    function archiveProperty(id) {
        if (confirm('Archive this property?')) {
            $.post(`/admin/property/${id}/archive`, {
                _token: '{{ csrf_token() }}'
            }).done(function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Failed to archive property');
                }
            });
        }
    }
</script>
@endsection
