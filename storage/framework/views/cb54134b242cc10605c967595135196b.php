
<?php
    // Check if user is authenticated via Laravel Auth or Spring Boot session
    $isAuthenticated = Auth::check() || session()->has('api_token');
    $user = $isAuthenticated ? (Auth::user() ?? session('user')) : null;

    // Get user role from session (Spring Boot returns role in uppercase)
    $userRole = null;
    if ($user) {
        if (is_array($user)) {
            $userRole = strtoupper($user['role'] ?? 'CUSTOMER');
        } elseif (is_object($user)) {
            $userRole = strtoupper($user->role ?? 'CUSTOMER');
        }
    }
?>

<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-border-color">
    <div class="container-custom">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo -->
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 group">
                <span class="material-symbols-outlined text-brand-500 text-3xl group-hover:scale-110 transition-transform">travel_explore</span>
                <span class="text-xl md:text-2xl font-bold text-brand-600 heading-font">Eserian Homes</span>
            </a>

            <!-- Desktop Navigation - Changes based on role -->
            <?php if($isAuthenticated): ?>
                <?php if($userRole === 'OWNER'): ?>
                    <!-- OWNER Navigation -->
                    <div class="hidden md:flex items-center gap-6 lg:gap-8">
                        <a href="<?php echo e(route('home.authenticated')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('home.authenticated') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Home
                        </a>
                        <a href="<?php echo e(route('owner.dashboard')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('owner.dashboard') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Dashboard
                        </a>
                        <a href="<?php echo e(route('owner.properties')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('owner.properties') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            My Properties
                        </a>
                        <a href="<?php echo e(route('owner.earnings')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('owner.earnings') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Earnings
                        </a>
                        <a href="<?php echo e(route('customer.browse')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors">
                            Explore
                        </a>
                    </div>
                <?php elseif($userRole === 'ADMIN'): ?>
                    <!-- ADMIN Navigation -->
                    <div class="hidden md:flex items-center gap-6 lg:gap-8">
                        <a href="<?php echo e(route('home.authenticated')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('home.authenticated') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Home
                        </a>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('admin.dashboard') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Admin Panel
                        </a>
                        <a href="<?php echo e(route('admin.pending')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors">
                            Pending Properties
                        </a>
                        <a href="<?php echo e(route('admin.users')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors">
                            Users
                        </a>
                        <a href="<?php echo e(route('customer.browse')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors">
                            Explore
                        </a>
                    </div>
                <?php else: ?>
                    <!-- CUSTOMER Navigation -->
                    <div class="hidden md:flex items-center gap-6 lg:gap-8">
                        <a href="<?php echo e(route('home.authenticated')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('home.authenticated') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Home
                        </a>
                        <a href="<?php echo e(route('customer.browse')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('customer.browse') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Explore
                        </a>
                        <a href="<?php echo e(route('customer.calendar')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('customer.calendar') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Calendar
                        </a>
                        <a href="<?php echo e(route('customer.saved')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('customer.saved') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Wishlist
                        </a>
                        <a href="<?php echo e(route('customer.dashboard')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('customer.dashboard') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            Dashboard
                        </a>
                        <a href="<?php echo e(route('customer.bookings')); ?>"
                           class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('customer.bookings') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                            My Trips
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- PUBLIC Navigation (Not logged in) -->
                <div class="hidden md:flex items-center gap-6 lg:gap-8">
                    <a href="<?php echo e(route('home')); ?>"
                       class="text-text-secondary hover:text-brand-600 font-medium transition-colors <?php echo e(request()->routeIs('home') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : ''); ?>">
                        Home
                    </a>
                    <a href="<?php echo e(route('public.browse')); ?>"
                       class="text-text-secondary hover:text-brand-600 font-medium transition-colors">
                        Explore
                    </a>
                    <a href="<?php echo e(route('about.us')); ?>"
                       class="text-text-secondary hover:text-brand-600 font-medium transition-colors">
                        About Us
                    </a>
                </div>
            <?php endif; ?>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-2 md:gap-4">
                <?php if($isAuthenticated): ?>
                    <?php if($userRole === 'OWNER'): ?>
                        <!-- Owner specific actions -->
                        <a href="<?php echo e(route('owner.property.create')); ?>" class="hidden md:flex items-center gap-1 bg-brand-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brand-600 transition">
                            <span class="material-symbols-outlined text-sm">add</span>
                            List Property
                        </a>
                    <?php elseif($userRole === 'ADMIN'): ?>
                        <!-- Admin specific actions (if any) -->
                    <?php else: ?>
                        <!-- Customer specific actions -->
                        <a href="<?php echo e(route('customer.help.center')); ?>" class="hidden md:flex items-center gap-1 text-text-secondary hover:text-brand-600 transition text-sm">
                            <span class="material-symbols-outlined text-sm">help_outline</span>
                            Help
                        </a>

                        <!-- Notifications - FIXED: Safe handling for any column name -->
                        <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('customer.notifications')); ?>" class="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <span class="material-symbols-outlined text-text-secondary">notifications</span>
                            <?php
                                $unreadCount = 0;
                                try {
                                    if (class_exists(\App\Models\Notification::class)) {
                                        $query = \App\Models\Notification::where('user_id', Auth::id());

                                        // Check which column exists and use it
                                        if (\Schema::hasColumn('notifications', 'is_read')) {
                                            $unreadCount = $query->where('is_read', false)->count();
                                        } elseif (\Schema::hasColumn('notifications', 'read')) {
                                            $unreadCount = $query->where('read', false)->count();
                                        } elseif (\Schema::hasColumn('notifications', 'read_at')) {
                                            $unreadCount = $query->whereNull('read_at')->count();
                                        } else {
                                            // No read status column, just count all notifications
                                            $unreadCount = $query->count();
                                        }
                                    }
                                } catch (\Exception $e) {
                                    $unreadCount = 0;
                                }
                            ?>
                            <?php if($unreadCount > 0): ?>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full"></span>
                            <?php endif; ?>
                        </a>
                        <?php endif; ?>

                        <!-- Messages -->
                        <a href="<?php echo e(route('customer.messages')); ?>" class="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <span class="material-symbols-outlined text-text-secondary">chat</span>
                        </a>
                    <?php endif; ?>

                    <!-- User Dropdown (Common for all roles) -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-semibold">
                                <?php echo e(is_array($user) ? substr(($user['name'] ?? 'U'), 0, 1) : (is_object($user) ? substr($user->name ?? 'U', 0, 1) : 'U')); ?>

                            </div>
                            <span class="material-symbols-outlined text-text-secondary text-sm hidden lg:block">expand_more</span>
                        </button>

                        <div class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-border-color overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <!-- User Info -->
                            <div class="p-4 border-b border-border-color bg-gray-50">
                                <p class="font-semibold text-text-primary">
                                    <?php echo e(is_array($user) ? ($user['name'] ?? 'User') : (is_object($user) ? ($user->name ?? 'User') : 'User')); ?>

                                </p>
                                <p class="text-xs text-text-muted">
                                    <?php echo e(is_array($user) ? ($user['email'] ?? '') : (is_object($user) ? ($user->email ?? '') : '')); ?>

                                </p>
                                <?php if($userRole === 'OWNER'): ?>
                                    <span class="inline-block mt-1 text-xs text-brand-600 font-semibold">Property Owner</span>
                                <?php elseif($userRole === 'ADMIN'): ?>
                                    <span class="inline-block mt-1 text-xs text-brand-600 font-semibold">Administrator</span>
                                <?php else: ?>
                                    <span class="inline-block mt-1 text-xs text-brand-600 font-semibold">Customer</span>
                                <?php endif; ?>
                            </div>

                            <!-- Menu Items - Conditional based on role -->
                            <div class="py-2">
                                <?php if($userRole === 'OWNER'): ?>
                                    <a href="<?php echo e(route('owner.dashboard')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">dashboard</span>
                                        Dashboard
                                    </a>
                                    <a href="<?php echo e(route('owner.properties')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">home_work</span>
                                        My Properties
                                    </a>
                                    <a href="<?php echo e(route('owner.earnings')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">payments</span>
                                        Earnings
                                    </a>
                                    <a href="<?php echo e(route('owner.property.create')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">add_business</span>
                                        Add New Property
                                    </a>
                                <?php elseif($userRole === 'ADMIN'): ?>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">dashboard</span>
                                        Admin Dashboard
                                    </a>
                                    <a href="<?php echo e(route('admin.pending')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">pending_actions</span>
                                        Pending Properties
                                    </a>
                                    <a href="<?php echo e(route('admin.users')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">people</span>
                                        Manage Users
                                    </a>
                                    <a href="<?php echo e(route('admin.payments')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">payments</span>
                                        Payments
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('customer.profile')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">person</span>
                                        My Profile
                                    </a>
                                    <a href="<?php echo e(route('customer.profile.management')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">settings</span>
                                        Account Settings
                                    </a>
                                    <a href="<?php echo e(route('customer.bookings')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">receipt_long</span>
                                        My Bookings
                                    </a>
                                    <a href="<?php echo e(route('customer.saved')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">favorite</span>
                                        Saved Properties
                                    </a>
                                    <a href="<?php echo e(route('customer.my.reviews')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">rate_review</span>
                                        My Reviews
                                    </a>
                                    <a href="<?php echo e(route('customer.messages')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                        <span class="material-symbols-outlined text-text-muted text-sm">chat</span>
                                        Messages
                                    </a>
                                <?php endif; ?>

                                <hr class="my-1 border-border-color">
                                <a href="<?php echo e(route('about.us')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                    <span class="material-symbols-outlined text-text-muted text-sm">info</span>
                                    About Us
                                </a>
                                <a href="<?php echo e(route('customer.help.center')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-text-primary hover:bg-gray-50 transition">
                                    <span class="material-symbols-outlined text-text-muted text-sm">help_center</span>
                                    Help Center
                                </a>
                                <hr class="my-1 border-border-color">
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-error hover:bg-red-50 transition">
                                        <span class="material-symbols-outlined text-sm">logout</span>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Non-authenticated user actions -->
                    <a href="<?php echo e(route('login')); ?>" class="text-text-secondary hover:text-brand-600 font-medium">Sign In</a>
                    <a href="<?php echo e(route('register')); ?>" class="bg-brand-500 text-white px-5 py-2 rounded-lg font-semibold hover:bg-brand-600 transition">
                        Sign Up
                    </a>
                <?php endif; ?>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined text-text-secondary">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-border-color py-4 max-h-[calc(100vh-64px)] overflow-y-auto">
            <?php if($isAuthenticated): ?>
                <?php if($userRole === 'OWNER'): ?>
                    <div class="flex flex-col space-y-1">
                        <a href="<?php echo e(route('home.authenticated')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Home</a>
                        <a href="<?php echo e(route('owner.dashboard')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Dashboard</a>
                        <a href="<?php echo e(route('owner.properties')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">My Properties</a>
                        <a href="<?php echo e(route('owner.earnings')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Earnings</a>
                        <a href="<?php echo e(route('owner.property.create')); ?>" class="px-4 py-2.5 bg-brand-500 text-white rounded-lg font-semibold text-center">+ List Property</a>
                        <hr class="my-2 border-border-color">
                        <a href="<?php echo e(route('customer.browse')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Explore Properties</a>
                        <a href="<?php echo e(route('about.us')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">About Us</a>
                        <hr class="my-2 border-border-color">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-error hover:bg-red-50 rounded-lg flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">logout</span>
                                Logout
                            </button>
                        </form>
                    </div>
                <?php elseif($userRole === 'ADMIN'): ?>
                    <div class="flex flex-col space-y-1">
                        <a href="<?php echo e(route('home.authenticated')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Home</a>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Admin Panel</a>
                        <a href="<?php echo e(route('admin.pending')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Pending Properties</a>
                        <a href="<?php echo e(route('admin.users')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Users</a>
                        <a href="<?php echo e(route('admin.payments')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Payments</a>
                        <hr class="my-2 border-border-color">
                        <a href="<?php echo e(route('customer.browse')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Explore Properties</a>
                        <a href="<?php echo e(route('about.us')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">About Us</a>
                        <hr class="my-2 border-border-color">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-error hover:bg-red-50 rounded-lg flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">logout</span>
                                Logout
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="flex flex-col space-y-1">
                        <a href="<?php echo e(route('home.authenticated')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Home</a>
                        <a href="<?php echo e(route('customer.browse')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Explore</a>
                        <a href="<?php echo e(route('customer.calendar')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Calendar</a>
                        <a href="<?php echo e(route('customer.saved')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Wishlist</a>
                        <a href="<?php echo e(route('customer.dashboard')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Dashboard</a>
                        <a href="<?php echo e(route('customer.bookings')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">My Trips</a>
                        <a href="<?php echo e(route('customer.messages')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Messages</a>
                        <a href="<?php echo e(route('customer.profile')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Profile</a>
                        <a href="<?php echo e(route('customer.profile.management')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Settings</a>
                        <a href="<?php echo e(route('customer.my.reviews')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">My Reviews</a>
                        <hr class="my-2 border-border-color">
                        <a href="<?php echo e(route('about.us')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">About Us</a>
                        <a href="<?php echo e(route('customer.help.center')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Help Center</a>
                        <hr class="my-2 border-border-color">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-error hover:bg-red-50 rounded-lg flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">logout</span>
                                Logout
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="flex flex-col space-y-1">
                    <a href="<?php echo e(route('home')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Home</a>
                    <a href="<?php echo e(route('public.browse')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">Explore</a>
                    <a href="<?php echo e(route('about.us')); ?>" class="px-4 py-2.5 text-text-primary hover:bg-gray-50 rounded-lg">About Us</a>
                    <hr class="my-2 border-border-color">
                    <a href="<?php echo e(route('login')); ?>" class="px-4 py-2.5 text-brand-600 hover:bg-brand-50 rounded-lg font-semibold">Sign In</a>
                    <a href="<?php echo e(route('register')); ?>" class="px-4 py-2.5 bg-brand-500 text-white hover:bg-brand-600 rounded-lg font-semibold text-center">Sign Up</a>
                </div>
            <?php endif; ?>
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
<?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/layouts/partials/navbar.blade.php ENDPATH**/ ?>