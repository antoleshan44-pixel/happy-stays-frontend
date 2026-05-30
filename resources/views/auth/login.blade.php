<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In | Eserian Homes</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#00288e",
                        "primary-container": "#1e40af",
                        "surface": "#f7f9fb",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#444653",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                    },
                    fontFamily: {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                    }
                },
            },
        }
    </script>

    <style>
        .input-group {
            transition: all 0.3s ease;
        }
        .input-group:focus-within {
            transform: translateX(4px);
        }
        .hero-section {
            background-image: linear-gradient(135deg, rgba(0,40,142,0.95) 0%, rgba(30,64,175,0.85) 100%),
                              url('https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=1600&h=1200&fit=crop');
            background-size: cover;
            background-position: center;
        }
        body {
            overflow: hidden;
            height: 100vh;
        }
    </style>
</head>
<body class="font-body">

<div class="flex h-screen">
    <!-- Left Side - Hero Section -->
    <div class="hidden lg:flex lg:w-1/2 hero-section relative">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 flex flex-col justify-between p-12 w-full h-full">
            <div>
                <a href="{{ url('/') }}" class="text-3xl font-bold text-white tracking-tighter">Eserian Homes</a>
                <p class="text-white/70 text-sm mt-2">Architectural Excellence</p>
            </div>

            <div class="my-auto">
                <h2 class="text-5xl font-bold text-white leading-tight mb-6">
                    Welcome Back<br>
                    to Luxury Living
                </h2>
                <div class="space-y-4 text-white/80">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-2xl">key</span>
                        <span>Access your bookings</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-2xl">favorite</span>
                        <span>Manage saved properties</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-2xl">support_agent</span>
                        <span>24/7 Concierge support</span>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center gap-2 text-white/60 text-sm">
                    <span>© 2026 Eserian Homes</span>
                    <span>•</span>
                    <span>Luxury Redefined</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 bg-gradient-to-br from-gray-50 to-white flex items-center justify-center px-6 py-6 overflow-y-auto">
        <div class="max-w-md w-full">
            <div class="text-center mb-6 lg:hidden">
                <h1 class="text-3xl font-bold text-primary">Eserian Homes</h1>
                <p class="text-gray-500 text-sm mt-1">Welcome back</p>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6">
                <div class="text-center mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary to-primary-container rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                        <span class="material-symbols-outlined text-white text-2xl">login</span>
                    </div>
                    <h1 class="font-headline text-xl font-bold text-primary">Welcome Back</h1>
                    <p class="text-on-surface-variant text-xs mt-1">Sign in to continue</p>
                </div>

                <!-- Error Container -->
                <div id="errorContainer" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-red-600 text-sm">error</span>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-red-800 mb-1">Unable to sign in</p>
                            <p id="errorMessage" class="text-xs text-red-700"></p>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="successContainer" class="hidden mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600 text-sm">check_circle</span>
                        <p id="successMessage" class="text-sm text-green-700"></p>
                    </div>
                </div>

                <!-- Login Form -->
                <form id="loginForm" method="POST">
                    <div class="mb-4">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">email</span>
                            </span>
                            <input type="email" name="email" id="email" required
                                   class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Email Address" autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">lock</span>
                            </span>
                            <input type="password" name="password" id="password" required
                                   class="w-full pl-10 pr-10 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Password">
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg" id="toggleIcon">visibility_off</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" id="remember" class="w-3 h-3 text-primary rounded">
                            <span class="ml-2 text-xs text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-xs text-primary hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-primary to-primary-container text-white py-2.5 rounded-lg font-semibold text-sm hover:shadow-lg transition-all transform hover:scale-[1.02] active:scale-95">
                        Sign In
                    </button>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-4 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <button type="button" class="flex items-center justify-center gap-2 px-3 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="text-xs">Google</span>
                        </button>
                        <button type="button" class="flex items-center justify-center gap-2 px-3 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="#1877F2" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/>
                            </svg>
                            <span class="text-xs">Facebook</span>
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-xs text-gray-600">
                            Don't have an account?
                            <a href="{{ url('/register') }}" class="text-primary font-semibold hover:underline">Create Account</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 flex flex-col items-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mb-3"></div>
        <p class="text-gray-700">Signing in...</p>
    </div>
</div>

<script>
    // FIXED: Correct backend URL with -1
    const BACKEND_API_URL = 'https://happy-stays-backend-1.onrender.com';

    // Get URL parameters for success message (declared ONCE)
    const urlParams = new URLSearchParams(window.location.search);

    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.textContent = 'visibility';
        } else {
            passwordInput.type = 'password';
            toggleIcon.textContent = 'visibility_off';
        }
    }

    function showError(message) {
        const errorContainer = document.getElementById('errorContainer');
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = message;
        errorContainer.classList.remove('hidden');
        setTimeout(() => {
            errorContainer.classList.add('hidden');
        }, 5000);
    }

    function showSuccess(message) {
        const successContainer = document.getElementById('successContainer');
        const successMessage = document.getElementById('successMessage');
        successMessage.textContent = message;
        successContainer.classList.remove('hidden');
    }

    // Handle form submission
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        document.getElementById('errorContainer').classList.add('hidden');

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const remember = document.getElementById('remember').checked;

        if (!email || !password) {
            showError('Please enter both email and password');
            return;
        }

        document.getElementById('loadingSpinner').classList.remove('hidden');
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').textContent = 'Signing in...';

        try {
            const response = await fetch(`${BACKEND_API_URL}/api/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            });

            const result = await response.json();

            if (response.ok && result.token) {
                if (remember) {
                    localStorage.setItem('api_token', result.token);
                    localStorage.setItem('user', JSON.stringify(result.user || result));
                } else {
                    sessionStorage.setItem('api_token', result.token);
                    sessionStorage.setItem('user', JSON.stringify(result.user || result));
                }

                showSuccess('Login successful! Redirecting...');

                setTimeout(() => {
                    const userRole = ((result.user?.role || result.role || '').toUpperCase());
                    if (userRole === 'OWNER') {
                        window.location.href = '/owner/dashboard';
                    } else if (userRole === 'ADMIN') {
                        window.location.href = '/admin/dashboard';
                    } else {
                        window.location.href = '/';
                    }
                }, 1500);
            } else {
                const errorMessage = result.message || result.error || 'Invalid email or password';
                showError(errorMessage);
            }
        } catch (error) {
            console.error('Login error:', error);
            showError('Connection error. Please try again.');
        } finally {
            document.getElementById('loadingSpinner').classList.add('hidden');
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('submitBtn').textContent = 'Sign In';
        }
    });

    // Add focus effect to inputs
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-primary/20');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-primary/20');
        });
    });

    // Show success message if coming from registration page
    if (urlParams.get('registered') === 'success') {
        showSuccess('Account created successfully! Please sign in.');
    }
</script>

</body>
</html>
