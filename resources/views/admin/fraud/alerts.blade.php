@extends('admin.layouts.app')

@section('title', 'Fraud Alerts')
@section('subtitle', 'Monitor and investigate suspicious activities')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Critical Alert -->
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Critical - Suspicious Payment Pattern</h3>
                <p class="text-sm text-red-700 mt-1">Multiple failed payment attempts from same IP address</p>
                <div class="mt-2 flex space-x-3">
                    <span class="text-xs text-red-600">Risk Score: 95%</span>
                    <span class="text-xs text-red-600">Detected: 5 mins ago</span>
                </div>
                <div class="mt-3">
                    <button class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700">Investigate</button>
                </div>
            </div>
        </div>
    </div>

    <!-- High Alert -->
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-shield-alt text-yellow-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">High - Potential Duplicate Listing</h3>
                <p class="text-sm text-yellow-700 mt-1">Property matches existing listing with different owner</p>
                <div class="mt-2 flex space-x-3">
                    <span class="text-xs text-yellow-600">Risk Score: 78%</span>
                    <span class="text-xs text-yellow-600">Detected: 1 hour ago</span>
                </div>
                <div class="mt-3">
                    <button class="px-3 py-1 bg-yellow-600 text-white text-xs rounded-lg hover:bg-yellow-700">Investigate</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">All Fraud Alerts</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Risk Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Critical</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">Payment Fraud</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Multiple failed attempts</td>
                    <td class="px-6 py-4 text-sm font-medium text-red-600">95%</td>
                    <td class="px-6 py-4"><button class="text-blue-600 hover:text-blue-800">Review</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
