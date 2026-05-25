{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal - @yield('title') | Eserian Homes</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand': {
                            50: '#e6e9f5',
                            100: '#cdd3eb',
                            200: '#9ba7d6',
                            300: '#697bc2',
                            400: '#374fad',
                            500: '#00288e',
                            600: '#002072',
                            700: '#001855',
                            800: '#001039',
                            900: '#00081c',
                        },
                        'surface': '#f7f9fb',
                        'surface-card': '#ffffff',
                        'text-primary': '#191c1e',
                        'text-secondary': '#444653',
                        'text-muted': '#757684',
                        'border-color': '#e2e8f0',
                        'success': '#10b981',
                        'warning': '#f59e0b',
                        'error': '#ef4444',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'heading': ['Manrope', 'Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        'xl': '0.75rem',
                        '2xl': '1rem',
                    }
                }
            }
        }
    </script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f7f9fb;
        }

        .heading-font {
            font-family: 'Manrope', sans-serif;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Alert animations */
        .alert {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading spinner */
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #00288e;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Status dots */
        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        .status-dot.green { background: #10b981; box-shadow: 0 0 4px #10b981; }
        .status-dot.yellow { background: #f59e0b; box-shadow: 0 0 4px #f59e0b; }
        .status-dot.red { background: #ef4444; box-shadow: 0 0 4px #ef4444; }

        /* Table styles */
        .data-table th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        /* Card hover effect */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-surface text-text-primary">

    <!-- Admin Sidebar (includes everything - logo, nav, user menu) -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="ml-0 md:ml-64 transition-all duration-300 min-h-screen">
        <!-- Top Bar (minimal - only search and notifications) -->
        <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="px-4 md:px-6 py-3">
                <div class="flex items-center justify-between">
                    <!-- Mobile menu button -->
                    <button class="md:hidden text-gray-600 hover:text-brand-500 transition" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Page Title -->
                    <h1 class="text-lg font-semibold text-gray-800 heading-font md:hidden">@yield('title', 'Dashboard')</h1>

                    <!-- Right section -->
                    <div class="flex items-center space-x-4 ml-auto">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="globalSearch" placeholder="Search..."
                                   class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg w-64 focus:outline-none focus:border-brand-400 focus:ring-1 focus:ring-brand-400 text-sm">
                        </div>

                        <!-- Notifications -->
                        <div class="relative">
                            <button class="text-gray-600 hover:text-brand-500 transition" id="notificationBtn">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border z-50">
                                <div class="p-3 border-b bg-gray-50 rounded-t-lg">
                                    <h3 class="font-semibold text-gray-800">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <div class="p-3 hover:bg-gray-50 cursor-pointer transition border-b">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-building text-green-500 text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">New property awaiting approval</p>
                                                <p class="text-xs text-gray-400 mt-1">5 minutes ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 hover:bg-gray-50 cursor-pointer transition border-b">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-exclamation-triangle text-yellow-500 text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">Suspicious activity detected</p>
                                                <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 hover:bg-gray-50 cursor-pointer transition">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-credit-card text-blue-500 text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">New payout request</p>
                                                <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 border-t text-center bg-gray-50 rounded-b-lg">
                                    <a href="#" class="text-xs text-brand-500">View all</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-4 md:p-6">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div class="flex-1">{{ session('success') }}</div>
                        <button onclick="this.closest('.alert').remove()" class="text-green-500"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <div class="flex-1">{{ session('error') }}</div>
                        <button onclick="this.closest('.alert').remove()" class="text-red-500"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Admin Footer -->
        @include('admin.partials.footer')
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 flex flex-col items-center">
            <div class="spinner"></div>
            <p class="mt-4 text-gray-600">Loading...</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        }, 100);

        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
        }

        // Notification dropdown
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');

        if (notificationBtn) {
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('hidden');
            });
        }

        document.addEventListener('click', function() {
            if (notificationDropdown) notificationDropdown.classList.add('hidden');
        });

        // Global search
        const globalSearch = document.getElementById('globalSearch');
        if (globalSearch) {
            let timeout;
            globalSearch.addEventListener('keyup', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    if (this.value.length > 2) {
                        console.log('Searching:', this.value);
                    }
                }, 500);
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
