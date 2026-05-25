<!-- File: resources/views/customer/dashboard.blade.php -->
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | Eserian Homes</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container-high": "#e6e8ea",
                        "on-error": "#ffffff",
                        "surface-dim": "#d8dadc",
                        "background": "#f7f9fb",
                        "on-tertiary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "error": "#ba1a1a",
                        "inverse-surface": "#2d3133",
                        "primary-fixed": "#dde1ff",
                        "on-surface-variant": "#444653",
                        "surface-container-highest": "#e0e3e5",
                        "on-primary-fixed-variant": "#173bab",
                        "on-error-container": "#93000a",
                        "on-secondary-container": "#646464",
                        "outline": "#757684",
                        "on-primary-container": "#a8b8ff",
                        "on-secondary-fixed": "#1b1b1b",
                        "primary": "#00288e",
                        "surface-bright": "#f7f9fb",
                        "secondary": "#5e5e5e",
                        "surface-variant": "#e0e3e5",
                        "primary-container": "#1e40af",
                        "on-primary-fixed": "#001453",
                        "on-tertiary-fixed-variant": "#802a00",
                        "error-container": "#ffdad6",
                        "surface-tint": "#3755c3",
                        "tertiary-container": "#872d00",
                        "primary-fixed-dim": "#b8c4ff",
                        "outline-variant": "#c4c5d5",
                        "inverse-on-surface": "#eff1f3",
                        "surface-container": "#eceef0",
                        "surface": "#f7f9fb",
                        "on-tertiary-container": "#ffa583",
                        "on-surface": "#191c1e",
                        "tertiary": "#611e00",
                        "inverse-primary": "#b8c4ff",
                        "on-primary": "#ffffff",
                        "tertiary-fixed": "#ffdbce",
                        "tertiary-fixed-dim": "#ffb59a",
                        "secondary-container": "#e2e2e2",
                        "on-background": "#191c1e",
                        "on-secondary": "#ffffff",
                        "on-secondary-fixed-variant": "#474747",
                        "surface-container-low": "#f2f4f6",
                        "on-tertiary-fixed": "#380d00",
                        "secondary-fixed": "#e2e2e2",
                        "secondary-fixed-dim": "#c6c6c6"
                    },
                    borderRadius: {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    fontFamily: {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-nav {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .stat-card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body selection:bg-primary-fixed selection:text-on-primary-fixed">

<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl shadow-sm">
    <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
        <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tighter text-blue-900">Eserian Homes</a>
        
        <div class="hidden md:flex items-center space-x-12">
            <a class="font-manrope tracking-tight font-semibold text-slate-500 hover:text-blue-700 transition-colors duration-300" href="{{ route('customer.browse') }}">Collections</a>
            <a class="font-manrope tracking-tight font-semibold text-slate-500 hover:text-blue-700 transition-colors duration-300" href="{{ route('customer.saved') }}">Saved</a>
            <a class="font-manrope tracking-tight font-semibold text-blue-900 border-b-2 border-blue-900 pb-1" href="{{ route('customer.dashboard') }}">Dashboard</a>
        </div>
        
        <div class="flex items-center space-x-6">
            <span class="text-slate-500 font-manrope font-semibold hidden md:inline">Welcome, {{ Auth::user()->name }}</span>
            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isOwner() ? route('owner.dashboard') : route('customer.dashboard')) }}" 
               class="bg-gradient-to-br from-primary to-primary-container text-on-primary px-6 py-2.5 rounded-md font-manrope font-bold transition-all">
                Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-slate-500 font-manrope font-semibold hover:text-red-600 transition-colors">Logout</button>
            </form>
        </div>
    </div>
</nav>

<main class="pt-32 pb-20 px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Section -->
        <div class="mb-12">
            <h1 class="font-headline text-4xl md:text-5xl font-extrabold text-blue-900 tracking-tighter mb-4">
                Welcome back, {{ Auth::user()->name }}
            </h1>
            <p class="text-on-surface-variant text-lg">Your architectural journey continues here.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-surface-container-lowest rounded-xl p-6 stat-card-hover">
                <div class="flex items-center justify-between mb-4">
                    <span class="material-symbols-outlined text-primary text-3xl">receipt_long</span>
                    <span class="text-3xl font-headline font-extrabold text-blue-900">{{ $totalBookings }}</span>
                </div>
                <h3 class="font-semibold text-on-surface">Total Bookings</h3>
                <p class="text-sm text-on-surface-variant">Lifetime reservations</p>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-6 stat-card-hover">
                <div class="flex items-center justify-between mb-4">
                    <span class="material-symbols-outlined text-primary text-3xl">event_upcoming</span>
                    <span class="text-3xl font-headline font-extrabold text-blue-900">{{ $upcomingBookings }}</span>
                </div>
                <h3 class="font-semibold text-on-surface">Upcoming Stays</h3>
                <p class="text-sm text-on-surface-variant">Next 30 days</p>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-6 stat-card-hover">
                <div class="flex items-center justify-between mb-4">
                    <span class="material-symbols-outlined text-primary text-3xl">payments</span>
                    <span class="text-3xl font-headline font-extrabold text-blue-900">KES {{ number_format($totalSpent, 0) }}</span>
                </div>
                <h3 class="font-semibold text-on-surface">Total Spent</h3>
                <p class="text-sm text-on-surface-variant">Across all stays</p>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-6 stat-card-hover">
                <div class="flex items-center justify-between mb-4">
                    <span class="material-symbols-outlined text-primary text-3xl">favorite</span>
                    <span class="text-3xl font-headline font-extrabold text-blue-900">{{ $favoriteCount }}</span>
                </div>
                <h3 class="font-semibold text-on-surface">Saved Properties</h3>
                <p class="text-sm text-on-surface-variant">Your curated collection</p>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-headline text-2xl font-bold text-blue-900">Recent Bookings</h2>
                <a href="{{ route('customer.bookings') }}" class="text-primary font-semibold flex items-center hover:underline">
                    View all <span class="material-symbols-outlined ml-1 text-sm">arrow_forward</span>
                </a>
            </div>
            
            @if($recentBookings->count() > 0)
                <div class="space-y-4">
                    @foreach($recentBookings as $booking)
                    <div class="bg-surface-container-lowest rounded-xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex items-center gap-4">
                            @if($booking->property->photos->first())
                                <img src="{{ asset('storage/' . $booking->property->photos->first()->photo_path) }}" 
                                     alt="{{ $booking->property->title }}"
                                     class="w-20 h-20 rounded-lg object-cover">
                            @else
                                <div class="w-20 h-20 rounded-lg bg-surface-container-high flex items-center justify-center">
                                    <span class="material-symbols-outlined text-3xl text-outline">home</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-on-surface">{{ $booking->property->title }}</h3>
                                <p class="text-sm text-on-surface-variant">{{ $booking->property->location }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">
                                    {{ $booking->check_in_date->format('M d, Y') }} - {{ $booking->check_out_date->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-headline font-bold text-xl text-blue-900">KES {{ number_format($booking->total_price) }}</p>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mt-2
                                @if($booking->status == 'confirmed') bg-green-100 text-green-700
                                @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($booking->status == 'completed') bg-blue-100 text-blue-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-surface-container-lowest rounded-xl p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-outline mb-4">receipt_long</span>
                    <h3 class="font-headline text-xl font-bold text-on-surface mb-2">No bookings yet</h3>
                    <p class="text-on-surface-variant mb-6">Start your architectural journey with us.</p>
                    <a href="{{ route('customer.browse') }}" class="bg-primary text-white px-6 py-3 rounded-md font-semibold hover:bg-primary-container transition-colors inline-block">
                        Browse Properties
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div>
            <h2 class="font-headline text-2xl font-bold text-blue-900 mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('customer.browse') }}" class="bg-surface-container-lowest rounded-xl p-6 text-center hover:shadow-lg transition-all group">
                    <span class="material-symbols-outlined text-4xl text-primary mb-3 group-hover:scale-110 transition-transform inline-block">search</span>
                    <h3 class="font-semibold text-on-surface mb-1">Discover Properties</h3>
                    <p class="text-sm text-on-surface-variant">Explore our curated collections</p>
                </a>
                
                <a href="{{ route('customer.bookings') }}" class="bg-surface-container-lowest rounded-xl p-6 text-center hover:shadow-lg transition-all group">
                    <span class="material-symbols-outlined text-4xl text-primary mb-3 group-hover:scale-110 transition-transform inline-block">calendar_month</span>
                    <h3 class="font-semibold text-on-surface mb-1">Manage Bookings</h3>
                    <p class="text-sm text-on-surface-variant">View and manage your reservations</p>
                </a>
                
                <a href="{{ route('customer.saved') }}" class="bg-surface-container-lowest rounded-xl p-6 text-center hover:shadow-lg transition-all group">
                    <span class="material-symbols-outlined text-4xl text-primary mb-3 group-hover:scale-110 transition-transform inline-block">favorite</span>
                    <h3 class="font-semibold text-on-surface mb-1">Saved Properties</h3>
                    <p class="text-sm text-on-surface-variant">Your curated wishlist</p>
                </a>
            </div>
        </div>
    </div>
</main>

<footer class="w-full py-16 px-8 bg-slate-50 border-t border-slate-200/20 mt-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
        <div class="col-span-1 md:col-span-1">
            <div class="text-xl font-black text-blue-900 mb-6">Eserian Homes</div>
            <p class="font-inter text-sm leading-relaxed text-slate-500 mb-6">© 2024 Eserian Homes. Architectural Excellence.</p>
        </div>
        
        <div>
            <h4 class="font-manrope font-bold text-blue-900 mb-6">Company</h4>
            <ul class="space-y-4">
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">About Us</a></li>
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Sustainability</a></li>
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Investment</a></li>
            </ul>
        </div>
        
        <div>
            <h4 class="font-manrope font-bold text-blue-900 mb-6">Support</h4>
            <ul class="space-y-4">
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Contact Support</a></li>
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Privacy Policy</a></li>
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Terms of Service</a></li>
            </ul>
        </div>
        
        <div>
            <h4 class="font-manrope font-bold text-blue-900 mb-6">Newsletter</h4>
            <p class="font-inter text-sm text-slate-500 mb-4">Curated architectural insights delivered monthly.</p>
            <div class="flex">
                <input class="bg-surface-container border-none text-sm px-4 py-2 w-full focus:ring-1 focus:ring-primary rounded-l-md" placeholder="Email Address" type="email"/>
                <button class="bg-primary text-white px-4 py-2 rounded-r-md hover:bg-primary-container transition-colors">Join</button>
            </div>
        </div>
    </div>
</footer>
</body>
</html>