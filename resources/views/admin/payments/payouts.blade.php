@extends('admin.layouts.app')

@section('title', 'Payout Management')
@section('subtitle', 'Manage owner payouts')

@section('content')
@php use App\Helpers\CurrencyHelper; @endphp
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-gray-500 text-sm">Pending Payouts</p>
            <p class="text-2xl font-bold text-yellow-600">{{ CurrencyHelper::formatKES($pendingPayoutsTotal ?? 125000, true) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $pendingPayoutsCount ?? 12 }} requests awaiting approval</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-gray-500 text-sm">Processing Payouts</p>
            <p class="text-2xl font-bold text-blue-600">{{ CurrencyHelper::formatKES($processingPayoutsTotal ?? 45000, true) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $processingPayoutsCount ?? 5 }} in progress</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-gray-500 text-sm">Total Paid (Month)</p>
            <p class="text-2xl font-bold text-green-600">{{ CurrencyHelper::formatKES($monthlyPaidTotal ?? 680000, true) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $paidOwnersCount ?? 48 }} owners paid</p>
        </div>
    </div>

    <!-- Pending Payouts Table -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Pending Payout Requests</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="pendingPayoutsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount (KES)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pendingPayouts as $payout)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">#{{ $payout['id'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $payout['owner_name'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $payout['property_name'] }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ CurrencyHelper::formatKES($payout['amount'], true) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($payout['created_at'])->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700" onclick="approvePayout('{{ $payout['id'] }}')">Approve</button>
                                <button class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700" onclick="rejectPayout('{{ $payout['id'] }}')">Reject</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No pending payouts</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payout History -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Payout History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="payoutHistoryTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount (KES)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payoutHistory as $history)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($history['created_at'])->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $history['owner_name'] }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ CurrencyHelper::formatKES($history['amount'], true) }}</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">{{ ucfirst($history['status']) }}</span></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $history['transaction_id'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No payout history</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#pendingPayoutsTable').DataTable();
        $('#payoutHistoryTable').DataTable();
    });

    function approvePayout(id) {
        if (confirm('Approve payout ' + id + '?')) {
            showLoading();
            $.post(`/admin/payouts/${id}/approve`, {
                _token: '{{ csrf_token() }}'
            }).done(function(response) {
                hideLoading();
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Failed to approve payout');
                }
            }).fail(function() {
                hideLoading();
                alert('Error approving payout');
            });
        }
    }

    function rejectPayout(id) {
        const reason = prompt('Please provide rejection reason:');
        if (reason) {
            if (confirm('Reject payout ' + id + '?')) {
                showLoading();
                $.post(`/admin/payouts/${id}/reject`, {
                    _token: '{{ csrf_token() }}',
                    reason: reason
                }).done(function(response) {
                    hideLoading();
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Failed to reject payout');
                    }
                }).fail(function() {
                    hideLoading();
                    alert('Error rejecting payout');
                });
            }
        }
    }

    function showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        }
    }

    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }
    }
</script>
@endsection
