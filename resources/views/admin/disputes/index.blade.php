@extends('admin.layouts.app')

@section('title', 'Dispute Resolution')
@section('subtitle', 'Manage and resolve customer disputes')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Open Disputes</h3>
    </div>
    <div class="divide-y divide-gray-200">
        <div class="p-6 hover:bg-gray-50 transition">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Open</span>
                        <span class="text-xs text-gray-500">#DSP-001</span>
                    </div>
                    <h4 class="text-sm font-medium text-gray-900 mt-2">Property Damage Claim</h4>
                    <p class="text-sm text-gray-600 mt-1">Guest claims property damage during stay</p>
                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                        <span><i class="fas fa-user"></i> John Doe (Guest)</span>
                        <span><i class="fas fa-building"></i> Luxury Villa #123</span>
                        <span><i class="fas fa-dollar-sign"></i> $500 claim</span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700">Review</button>
                    <button class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700">Resolve</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
