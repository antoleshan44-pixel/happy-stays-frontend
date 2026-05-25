{{-- resources/views/admin/partials/sidebar.blade.php --}}
<aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-brand-600 to-brand-800 shadow-xl transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50 overflow-y-auto">
    <!-- Logo Area -->
    <div class="flex flex-col items-center justify-center py-6 border-b border-brand-500">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-3 shadow-lg">
            <span class="text-brand-600 font-bold text-2xl">E</span>
        </div>
        <h1 class="text-white font-heading font-bold text-xl">Eserian Homes</h1>
        <p class="text-brand-200 text-xs mt-1">Admin Portal</p>
    </div>

    <!-- Admin Info -->
    <div class="px-4 py-4 border-b border-brand-500">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                <span class="text-brand-600 font-bold text-sm">{{ substr(session('admin_name', 'A'), 0, 1) }}</span>
            </div>
            <div class="flex-1">
                <p class="text-white text-sm font-medium">{{ session('admin_name', 'Administrator') }}</p>
                <p class="text-brand-200 text-xs">{{ session('admin_role', 'admin') }}</p>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-brand-200 hover:text-white transition">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="px-3 py-4">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-brand-100 rounded-lg hover:bg-brand-500 hover:text-white transition group {{ request()->routeIs('admin.dashboard') ? 'bg-brand-500 text-white' : '' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span class="ml-3 text-sm">Dashboard</span>
            </a>

            <!-- Properties -->
            <div>
                <button onclick="toggleSubmenu('propertiesMenu')" class="flex items-center justify-between w-full px-3 py-2.5 text-brand-100 rounded-lg hover:bg-brand-500 hover:text-white transition group">
                    <div class="flex items-center">
                        <i class="fas fa-building w-5"></i>
                        <span class="ml-3 text-sm">Properties</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" id="propertiesMenuIcon"></i>
                </button>
                <div id="propertiesMenu" class="hidden ml-6 mt-1 space-y-1">
                    <a href="{{ route('admin.pending') }}" class="block px-3 py-2 text-sm text-brand-200 rounded-lg hover:bg-brand-500 hover:text-white transition {{ request()->routeIs('admin.pending') ? 'bg-brand-500 text-white' : '' }}">
                        <i class="fas fa-clock w-4 mr-2"></i> Pending Approval
                    </a>
                    <a href="{{ route('admin.properties') }}" class="block px-3 py-2 text-sm text-brand-200 rounded-lg hover:bg-brand-500 hover:text-white transition {{ request()->routeIs('admin.properties') ? 'bg-brand-500 text-white' : '' }}">
                        <i class="fas fa-list w-4 mr-2"></i> All Properties
                    </a>
                </div>
            </div>

            <!-- Users -->
            <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2.5 text-brand-100 rounded-lg hover:bg-brand-500 hover:text-white transition group {{ request()->routeIs('admin.users') ? 'bg-brand-500 text-white' : '' }}">
                <i class="fas fa-users w-5"></i>
                <span class="ml-3 text-sm">Users</span>
            </a>

            <!-- Payments -->
            <div>
                <button onclick="toggleSubmenu('paymentsMenu')" class="flex items-center justify-between w-full px-3 py-2.5 text-brand-100 rounded-lg hover:bg-brand-500 hover:text-white transition group">
                    <div class="flex items-center">
                        <i class="fas fa-credit-card w-5"></i>
                        <span class="ml-3 text-sm">Payments</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" id="paymentsMenuIcon"></i>
                </button>
                <div id="paymentsMenu" class="hidden ml-6 mt-1 space-y-1">
                    <a href="{{ route('admin.payments') }}" class="block px-3 py-2 text-sm text-brand-200 rounded-lg hover:bg-brand-500 hover:text-white transition">
                        <i class="fas fa-history w-4 mr-2"></i> Transactions
                    </a>
                    <a href="{{ route('admin.payouts') }}" class="block px-3 py-2 text-sm text-brand-200 rounded-lg hover:bg-brand-500 hover:text-white transition">
                        <i class="fas fa-money-bill-wave w-4 mr-2"></i> Payouts
                    </a>
                </div>
            </div>

            <!-- Fraud Alerts -->
            <a href="{{ route('admin.fraud.alerts') }}" class="flex items-center px-3 py-2.5 text-brand-100 rounded-lg hover:bg-brand-500 hover:text-white transition group {{ request()->routeIs('admin.fraud.*') ? 'bg-brand-500 text-white' : '' }}">
                <i class="fas fa-shield-alt w-5"></i>
                <span class="ml-3 text-sm">Fraud Alerts</span>
                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">3</span>
            </a>

            <!-- Disputes -->
            <a href="{{ route('admin.disputes') }}" class="flex items-center px-3 py-2.5 text-brand-100 rounded-lg hover:bg-brand-500 hover:text-white transition group {{ request()->routeIs('admin.disputes') ? 'bg-brand-500 text-white' : '' }}">
                <i class="fas fa-gavel w-5"></i>
                <span class="ml-3 text-sm">Disputes</span>
            </a>

            <!-- Reports / Analytics -->
            <a href="{{ route('admin.reports') }}" class="flex items-center px-3 py-2.5 text-brand-100 rounded-lg hover:bg-brand-500 hover:text-white transition group {{ request()->routeIs('admin.reports') ? 'bg-brand-500 text-white' : '' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span class="ml-3 text-sm">Analytics</span>
            </a>

            <!-- Settings -->
            <a href="{{ route('admin.settings') }}" class="flex items-center px-3 py-2.5 text-brand-100 rounded-lg hover:bg-brand-500 hover:text-white transition group {{ request()->routeIs('admin.settings') ? 'bg-brand-500 text-white' : '' }}">
                <i class="fas fa-cog w-5"></i>
                <span class="ml-3 text-sm">Settings</span>
            </a>
        </div>
    </nav>

    <!-- System Status -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-brand-500 bg-brand-700">
        <div class="space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-xs text-brand-200">API Status</span>
                <span class="text-xs text-green-300"><span class="status-dot green mr-1"></span> Online</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-brand-200">Version</span>
                <span class="text-xs text-brand-200">v2.0.0</span>
            </div>
        </div>
    </div>
</aside>

<script>
    function toggleSubmenu(menuId) {
        const menu = document.getElementById(menuId);
        const icon = document.getElementById(menuId + 'Icon');
        if (menu) {
            menu.classList.toggle('hidden');
            if (icon) {
                icon.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        }
    }

    // Keep submenu open if active route
    @if(request()->routeIs('admin.pending') || request()->routeIs('admin.properties'))
        document.getElementById('propertiesMenu')?.classList.remove('hidden');
        document.getElementById('propertiesMenuIcon')?.style.setProperty('transform', 'rotate(180deg)');
    @endif

    @if(request()->routeIs('admin.payments') || request()->routeIs('admin.payouts'))
        document.getElementById('paymentsMenu')?.classList.remove('hidden');
        document.getElementById('paymentsMenuIcon')?.style.setProperty('transform', 'rotate(180deg)');
    @endif
</script>
