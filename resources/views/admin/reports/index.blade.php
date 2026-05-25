@extends('admin.layouts.app')

@section('title', 'Analytics & Reports')
@section('subtitle', 'View platform performance metrics')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <a href="#" class="bg-white rounded-xl shadow-sm p-6 hover-lift block">
        <i class="fas fa-chart-line text-3xl text-blue-600"></i>
        <h3 class="font-semibold text-gray-800 mt-3">Revenue Report</h3>
        <p class="text-sm text-gray-500 mt-1">View revenue trends and forecasts</p>
    </a>
    <a href="#" class="bg-white rounded-xl shadow-sm p-6 hover-lift block">
        <i class="fas fa-calendar-alt text-3xl text-green-600"></i>
        <h3 class="font-semibold text-gray-800 mt-3">Booking Analytics</h3>
        <p class="text-sm text-gray-500 mt-1">Occupancy and booking trends</p>
    </a>
    <a href="#" class="bg-white rounded-xl shadow-sm p-6 hover-lift block">
        <i class="fas fa-users text-3xl text-purple-600"></i>
        <h3 class="font-semibold text-gray-800 mt-3">User Growth</h3>
        <p class="text-sm text-gray-500 mt-1">User acquisition and retention</p>
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-semibold text-gray-800 mb-4">Export Reports</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200">PDF Export</button>
        <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200">Excel Export</button>
        <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200">CSV Export</button>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Schedule Report</button>
    </div>
</div>
@endsection
