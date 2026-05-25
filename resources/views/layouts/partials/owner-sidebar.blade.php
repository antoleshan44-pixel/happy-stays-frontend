{{-- resources/views/layouts/partials/owner-sidebar.blade.php --}}
@php
    $user        = session('user');
    $userName    = is_array($user) ? ($user['name']  ?? 'Owner') : 'Owner';
    $userEmail   = is_array($user) ? ($user['email'] ?? '')      : '';
    $userInitial = strtoupper(substr(is_array($user) ? ($user['name'] ?? 'O') : 'O', 0, 1));

    // Get first & last initial for avatar
    $nameParts = explode(' ', trim($userName));
    $initials  = strtoupper(substr($nameParts[0] ?? 'O', 0, 1));
    if (count($nameParts) > 1) {
        $initials .= strtoupper(substr(end($nameParts), 0, 1));
    }

    // Active route helpers
    $isDashboard  = request()->routeIs('owner.dashboard');
    $isProperties = request()->routeIs('owner.properties') || request()->routeIs('owner.property.*');
    $isCalendar   = request()->routeIs('owner.calendar');
    $isEarnings   = request()->routeIs('owner.earnings');
    $isMessages   = request()->routeIs('owner.messages') || request()->routeIs('owner.conversation');
    $isProfile    = request()->routeIs('customer.profile*');
@endphp

<aside id="sidebar">

    {{-- ── Logo ── --}}
    <div class="px-5 pt-6 pb-4">
        <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2.5 group">
            <div class="w-8 h-8 rounded-xl bg-secondary flex items-center justify-center shadow-sm group-hover:shadow-md transition">
                <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings:'FILL' 1">travel_explore</span>
            </div>
            <div>
                <p class="font-display font-semibold text-secondary leading-none tracking-tight" style="font-size:1.05rem">
                    Eserian <em class="not-italic text-on-surface">Homes</em>
                </p>
                <p class="text-[10px] font-medium text-on-surface-variant tracking-widest uppercase leading-none mt-0.5">Owner Portal</p>
            </div>
        </a>
    </div>

    {{-- ── User card ── --}}
    <div class="mx-4 mb-4 p-3 rounded-xl bg-surface-container border border-outline-variant/60">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm"
                 style="background: linear-gradient(135deg, #00288e 0%, #1e51d4 100%)">
                {{ $initials }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-on-surface truncate leading-tight">{{ $userName }}</p>
                <p class="text-xs text-on-surface-variant truncate leading-tight mt-0.5">{{ $userEmail }}</p>
            </div>
            <a href="{{ route('customer.profile') }}"
               class="flex-shrink-0 p-1 rounded-lg text-on-surface-variant hover:text-secondary hover:bg-secondary-light transition"
               title="Edit profile">
                <span class="material-symbols-outlined text-base">edit</span>
            </a>
        </div>

        {{-- Owner badge --}}
        <div class="mt-2.5 flex items-center gap-1.5">
            <span class="flex items-center gap-1 text-[10px] font-semibold tracking-wide text-success uppercase bg-success/10 px-2 py-0.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-success status-dot inline-block"></span>
                Active Host
            </span>
        </div>
    </div>

    {{-- ── Quick action ── --}}
    <div class="px-4 mb-3">
        <a href="{{ route('owner.property.create') }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 bg-secondary text-white text-sm font-semibold rounded-xl
                  hover:bg-secondary/90 active:scale-[0.98] transition shadow-sm hover:shadow-md">
            <span class="material-symbols-outlined text-base">add</span>
            List New Property
        </a>
    </div>

    <div class="px-4 mb-1">
        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-[0.12em]">Main Menu</p>
    </div>

    {{-- ── Main Nav ── --}}
    <nav class="px-3 flex flex-col gap-0.5 mb-4">

        <a href="{{ route('owner.dashboard') }}"
           class="nav-link {{ $isDashboard ? 'active' : '' }}">
            <span class="material-symbols-outlined">dashboard</span>
            Dashboard
        </a>

        <a href="{{ route('owner.properties') }}"
           class="nav-link {{ $isProperties ? 'active' : '' }}">
            <span class="material-symbols-outlined">home_work</span>
            My Properties
        </a>

        {{-- Calendar — links to first property if available, else dashboard --}}
        <a href="{{ route('owner.calendar', ['id' => request()->route('id') ?? 1]) }}"
           class="nav-link {{ $isCalendar ? 'active' : '' }}">
            <span class="material-symbols-outlined">calendar_month</span>
            Calendar
        </a>

        <a href="{{ route('owner.earnings') }}"
           class="nav-link {{ $isEarnings ? 'active' : '' }}">
            <span class="material-symbols-outlined">payments</span>
            Earnings
        </a>

        <a href="{{ route('owner.messages') }}"
           class="nav-link {{ $isMessages ? 'active' : '' }}">
            <span class="material-symbols-outlined">chat_bubble</span>
            Messages
            {{-- Unread badge placeholder --}}
            {{-- @if($unreadCount ?? 0 > 0)
            <span class="ml-auto bg-error text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                {{ $unreadCount }}
            </span>
            @endif --}}
        </a>

    </nav>

    <div class="px-4 mb-1">
        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-[0.12em]">Account</p>
    </div>

    {{-- ── Account Nav ── --}}
    <nav class="px-3 flex flex-col gap-0.5 mb-4">

        <a href="{{ route('customer.profile') }}"
           class="nav-link {{ $isProfile ? 'active' : '' }}">
            <span class="material-symbols-outlined">person</span>
            Profile
        </a>

        <a href="{{ route('customer.profile.management') }}"
           class="nav-link {{ request()->routeIs('customer.profile.management') ? 'active' : '' }}">
            <span class="material-symbols-outlined">settings</span>
            Settings
        </a>

        <a href="{{ route('customer.browse') }}" class="nav-link">
            <span class="material-symbols-outlined">search</span>
            Browse Listings
        </a>

        <a href="{{ route('customer.help.center') }}" class="nav-link">
            <span class="material-symbols-outlined">help</span>
            Help Center
        </a>

    </nav>

    {{-- ── Spacer ── --}}
    <div class="flex-1"></div>

    {{-- ── Sidebar footer ── --}}
    <div class="mt-auto px-4 py-4 border-t border-outline-variant">

        {{-- System status --}}
        <div class="flex items-center gap-2 mb-3 px-1">
            <span class="w-2 h-2 rounded-full bg-success status-dot flex-shrink-0"></span>
            <span class="text-xs text-on-surface-variant">All systems operational</span>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                           text-error hover:bg-error/8 transition group">
                <span class="material-symbols-outlined text-base group-hover:translate-x-[-2px] transition-transform">logout</span>
                Sign Out
            </button>
        </form>

        <p class="text-[10px] text-on-surface-variant/50 text-center mt-3">
            Eserian Homes v2.0 · {{ date('Y') }}
        </p>
    </div>

</aside>
