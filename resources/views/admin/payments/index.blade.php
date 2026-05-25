@extends('admin.layouts.app')

@section('title', 'Payment Transactions')
@section('subtitle', 'Track and manage all financial transactions')

@section('content')
@php use App\Helpers\CurrencyHelper; @endphp
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-gray-500 text-sm">Total Revenue</p>
        <p class="text-2xl font-bold text-gray-800">{{ CurrencyHelper::formatKES($totalRevenue ?? 0, true) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-gray-500 text-sm">Platform Fees</p>
        <p class="text-2xl font-bold text-gray-800">{{ CurrencyHelper::formatKES($platformFees ?? 0, true) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-gray-500 text-sm">Pending Payouts</p>
        <p class="text-2xl font-bold text-yellow-600">{{ CurrencyHelper::formatKES($pendingPayouts ?? 0, true) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-gray-500 text-sm">Refunds Processed</p>
        <p class="text-2xl font-bold text-red-600">{{ CurrencyHelper::formatKES($refundsTotal ?? 0, true) }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Transaction History</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full" id="paymentsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">#{{ $payment['id'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $payment['user_name'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ CurrencyHelper::formatKES($payment['amount'] ?? 0, true) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ CurrencyHelper::formatKES($payment['commission'] ?? 0, true) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $payment['status'] == 'completed' ? 'bg-green-100 text-green-700' :
                               ($payment['status'] == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($payment['status'] ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $payment['payment_method'] ?? 'M-Pesa' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($payment['created_at'] ?? now())->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <button onclick="refundPayment({{ $payment['id'] }})"
                                class="text-red-600 hover:text-red-800"
                                {{ $payment['status'] != 'completed' ? 'disabled' : '' }}>
                            <i class="fas fa-undo-alt"></i> Refund
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">No transactions found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#paymentsTable').DataTable({
            order: [[6, 'desc']],
            pageLength: 25
        });
    });

    function refundPayment(id) {
        let amount = prompt('Enter refund amount (KES):');
        if (amount && confirm('Process refund for this payment?')) {
            $.post(`/admin/payments/${id}/refund`, {
                _token: '{{ csrf_token() }}',
                amount: amount,
                reason: 'Manual refund'
            }).done(function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Failed to process refund');
                }
            }).fail(function() {
                alert('Error processing refund');
            });
        }
    }
</script>
@endpush
@endsection
