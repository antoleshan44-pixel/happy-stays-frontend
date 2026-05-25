{{-- resources/views/layouts/partials/owner-navbar.blade.php --}}
@php
    $user = session('user');
    $isAuthenticated = session()->has('api_token');
    $userName = is_array($user) ? ($user['name'] ?? 'Owner') : 'Owner';
    $userEmail = is_array($user) ? ($user['email'] ?? '') : '';
    $userInitial = is_array($user) ? substr(($user['name'] ?? 'O'), 0, 1) : 'O';
@endphp

<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-border-color">
    <div class="container-custom">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo - Updated to point to owner dashboard -->
            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2 group">
                <span class="material-symbols-outlined text-brand-500 text-3xl group-hover:scale-110 transition-transform">travel_explore</span>
                <span class="text-xl md:text-2xl font-bold text-brand-600 heading-font">Eserian Homes</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-6 lg:gap-8">
                <a href="{{ route('owner.dashboard') }}"
                   class="text-text-secondary hover:text-brand-600 font-medium transition-colors {{ request()->routeIs('owner.dashboard') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('owner.properties') }}"
                   class="text-text-secondary hover:text-brand-600 font-medium transition-colors {{ request()->routeIs('owner.properties') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : '' }}">
                    Properties
                </a>
                <a href="{{ route('owner.earnings') }}"
                   class="text-text-secondary hover:text-brand-600 font-medium transition-colors {{ request()->routeIs('owner.earnings') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : '' }}">
                    Earnings
                </a>
                <a href="{{ route('owner.calendar', ['id' => 1]) }}"
                   class="text-text-secondary hover:text-brand-600 font-medium transition-colors {{ request()->routeIs('owner.calendar') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : '' }}">
                    Calendar
                </a>
                <a href="{{ route('customer.browse') }}"
                   class="text-text-secondary hover:text-brand-600 font-medium transition-colors">
                    Explore
                </a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-2 md:gap-4">
                <!-- List Property Button (Owner specific) -->
                <a href="{{ route('owner.property.create') }}" class="hidden md:flex items-center gap-1 bg-brand-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brand-600 transition">
                    <span class="material-symbols-outlined text-sm">add</span>
                    List Property
                </a>

                <!-- Help/Support Link -->
                <a href="{{ route('customer.help.center') }}" class="hidden md:flex items-center gap-1 text-text-secondary hover:text-brand-600 transition text-sm">
                    <span class="material-symbols-outlined text-sm">help_outline</span>
                    Help
                </a>

                <!-- Notifications -->
                <a href="{{ route('customer.notifications') }}" class="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined text-text-secondary">notifications</span>
                </a>

                <!-- Messages -->
                <a href="{{ route('customer.messages') }}" class="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined text-text-secondary">chat</span>
                </a>

                <!-- User Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-2 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-semibold">
                            {{ $userInitial }}
                        </div>
                        <span class="material-symbols-outlined text-text-secondary text-sm hidden lg:block">expand_more</span>
                    </button>

                    <div class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-border-color overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <!-- User Info -->
                        <div class="p-4 border-b border-border-color bg-gray-50">
                            <p class="font-semibold text-text-primary">{{ $userName }}</p>
                            <p class="text-xs text-text-muted">{{ $userEmail }}</p>
                            <span class="inline-block mt-1 text-xs text-brand-600 font-semibold">Property Owner</span>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">dashboard</span>
                                Dashboard
                            </a>
                            <a href="{{ route('owner.properties') }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">home_work</span>
                                My Properties
                            </a>
                            <a href="{{ route('owner.earnings') }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">payments</span>
                                Earnings
                            </a>
                            <a href="{{ route('owner.calendar', ['id' => 1]) }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">calendar_month</span>
                                Availability Calendar
                            </a>
                            <a href="{{ route('owner.property.create') }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">add_business</span>
                                Add New Property
                            </a>

                            <hr class="my-1 border-border-color">

                            <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">person</span>
                                My Profile
                            </a>
                            <a href="{{ route('customer.profile.management') }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">settings</span>
                                Account Settings
                            </a>

                            <hr class="my-1 border-border-color">

                            <a href="{{ route('customer.browse') }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">search</span>
                                Browse Properties
                            </a>
                            <a href="{{ route('about.us') }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">info</span>
                                About Us
                            </a>
                            <a href="{{ route('customer.help.center') }}" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                <span class="material-symbols-outlined text-text-muted text-sm">help_center</span>
                                Help Center
                            </a>

                            <hr class="my-1 border-border-color">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-error hover:bg-red-50 transition">
                                    <span class="material-symbols-outlined text-sm">logout</span>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined text-text-secondary">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-border-color py-4 max-h-[calc(100vh-64px)] overflow-y-auto">
            <div class="flex flex-col space-y-1">
                <a href="{{ route('owner.dashboard') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Dashboard</a>
                <a href="{{ route('owner.properties') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Properties</a>
                <a href="{{ route('owner.earnings') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Earnings</a>
                <a href="{{ route('owner.calendar', ['id' => 1]) }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Calendar</a>
                <a href="{{ route('owner.property.create') }}" class="px-4 py-2.5 bg-brand-500 text-white rounded-lg font-semibold text-center">+ List Property</a>

                <hr class="my-2 border-border-color">

                <a href="{{ route('customer.browse') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Explore Properties</a>
                <a href="{{ route('customer.calendar') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Trip Calendar</a>
                <a href="{{ route('customer.saved') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Saved</a>
                <a href="{{ route('customer.bookings') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">My Bookings</a>
                <a href="{{ route('customer.messages') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Messages</a>
                <a href="{{ route('customer.profile') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Profile</a>

                <hr class="my-2 border-border-color">

                <a href="{{ route('about.us') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">About Us</a>
                <a href="{{ route('customer.help.center') }}" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Help Center</a>

                <hr class="my-2 border-border-color">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-error hover:bg-red-50 rounded-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">logout</span>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            const menu = document.getElementById('mobileMenu');
            if (menu) menu.classList.add('hidden');
        }
    });
</script>
