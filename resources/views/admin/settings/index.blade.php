@extends('admin.layouts.app')

@section('title', 'System Settings')
@section('subtitle', 'Configure platform settings and preferences')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Commission Settings -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Commission Settings</h3>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Platform Commission (%)</label>
                    <input type="number" value="12" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Percentage taken from each booking</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Fee (KES)</label>
                    <input type="number" value="500" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cleaning Fee (KES)</label>
                    <input type="number" value="2500" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">VAT (%)</label>
                    <input type="number" value="16" class="w-full border rounded-lg px-3 py-2">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Save Commission Settings</button>
            </form>
        </div>

        <!-- Tax Settings -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Tax Settings</h3>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Withholding Tax (%)</label>
                    <input type="number" value="5" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tourism Levy (KES)</label>
                    <input type="number" value="200" class="w-full border rounded-lg px-3 py-2">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Save Tax Settings</button>
            </form>
        </div>

        <!-- Payment Settings -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Payment Settings</h3>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Payout Amount (KES)</label>
                    <input type="number" value="1000" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payout Schedule</label>
                    <select class="w-full border rounded-lg px-3 py-2">
                        <option>Weekly</option>
                        <option>Bi-weekly</option>
                        <option>Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Escrow Hold Period (days)</label>
                    <input type="number" value="24" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Days after check-in to release funds</p>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Save Payment Settings</button>
            </form>
        </div>
    </div>

    <!-- Quick Actions Sidebar -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <button class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">Clear System Cache</button>
                <button class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">Run Database Backup</button>
                <button class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">Optimize Database</button>
                <button class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">Generate System Report</button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">System Information</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Version</span>
                    <span class="font-medium">2.0.0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Last Backup</span>
                    <span class="font-medium">2024-01-15 02:00 AM</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Environment</span>
                    <span class="font-medium">Production</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">API Status</span>
                    <span class="text-green-600">Operational</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
