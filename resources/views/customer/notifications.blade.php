{{-- resources/views/customer/notifications.blade.php --}}
@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-custom max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-2">Notifications</h1>
            <p class="text-text-secondary">Stay updated on your bookings and messages</p>
        </div>
        <button onclick="markAllRead()" class="text-brand-600 text-sm font-semibold hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">done_all</span>
            Mark all as read
        </button>
    </div>

    @if(isset($notifications) && count($notifications) > 0)
        <div class="space-y-6">
            @foreach($notifications as $date => $group)
            <div>
                <h3 class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-4">{{ $date }}</h3>
                <div class="space-y-3">
                    @foreach($group as $notif)
                    <div class="bg-surface-card p-4 rounded-xl shadow-sm border {{ $notif['read'] ? 'border-border-color' : 'border-brand-200 bg-brand-50/30' }} hover:shadow-md transition cursor-pointer"
                         onclick="markRead({{ $notif['id'] }})">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full {{ $notif['type'] == 'booking' ? 'bg-green-100' : ($notif['type'] == 'payment' ? 'bg-blue-100' : 'bg-brand-100') }} flex items-center justify-center">
                                <span class="material-symbols-outlined {{ $notif['type'] == 'booking' ? 'text-success' : ($notif['type'] == 'payment' ? 'text-blue-600' : 'text-brand-600') }}">
                                    {{ $notif['icon'] }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-semibold text-text-primary">{{ $notif['title'] }}</h4>
                                    <span class="text-xs text-text-muted">{{ $notif['time_ago'] }}</span>
                                </div>
                                <p class="text-sm text-text-secondary mt-1">{{ $notif['message'] }}</p>
                            </div>
                            @if(!$notif['read'])
                                <div class="w-2 h-2 rounded-full bg-brand-500 mt-2"></div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-surface-card rounded-xl p-16 text-center shadow-sm border border-border-color">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-4xl text-text-muted">notifications_none</span>
            </div>
            <h3 class="text-xl font-semibold text-text-primary mb-2">No notifications yet</h3>
            <p class="text-text-muted">When you have new notifications, they'll appear here.</p>
        </div>
    @endif
</div>

<script>
    function markRead(id) {
        fetch('/api/notifications/' + id + '/read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => location.reload());
    }

    function markAllRead() {
        fetch('/api/notifications/read-all', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => location.reload());
    }
</script>
@endsection
