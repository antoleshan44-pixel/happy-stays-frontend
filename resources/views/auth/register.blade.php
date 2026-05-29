<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account | Eserian Homes</title>

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
        .register-card {
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .input-group {
            transition: all 0.3s ease;
        }
        .input-group:focus-within {
            transform: translateX(4px);
        }
        .role-card {
            transition: all 0.3s ease;
        }
        .role-card:hover {
            transform: translateY(-2px);
        }
        .hero-section {
            background-image: linear-gradient(135deg, rgba(0,40,142,0.95) 0%, rgba(30,64,175,0.85) 100%),
                              url('https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=1600&h=1200&fit=crop');
            background-size: cover;
            background-position: center;
        }
        /* Prevent scrolling on body */
        body {
            overflow: hidden;
            height: 100vh;
        }
    </style>
</head>
<body class="font-body">

<!-- Split Screen Layout - No Scroll -->
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
                    Find Your<br>
                    Perfect Stay
                </h2>
                <div class="space-y-4 text-white/80">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-2xl">check_circle</span>
                        <span>Curated luxury properties</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-2xl">check_circle</span>
                        <span>Seamless booking experience</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-2xl">check_circle</span>
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

    <!-- Right Side - Registration Form -->
    <div class="w-full lg:w-1/2 bg-gradient-to-br from-gray-50 to-white flex items-center justify-center px-6 py-6 overflow-y-auto">
        <div class="max-w-md w-full">
            <div class="text-center mb-6 lg:hidden">
                <h1 class="text-3xl font-bold text-primary">Eserian Homes</h1>
                <p class="text-gray-500 text-sm mt-1">Create your account</p>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6 register-card">
                <!-- Decorative Header -->
                <div class="text-center mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary to-primary-container rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                        <span class="material-symbols-outlined text-white text-2xl">person_add</span>
                    </div>
                    <h1 class="font-headline text-xl font-bold text-primary">Create Account</h1>
                    <p class="text-on-surface-variant text-xs mt-1">Join the architectural community</p>
                </div>

                <!-- Error Container -->
                <div id="errorContainer" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-red-600 text-sm">error</span>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-red-800 mb-1">Please fix the following errors:</p>
                            <ul id="errorList" class="text-xs text-red-700 list-disc list-inside"></ul>
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

                <!-- Registration Form -->
                <form id="registerForm" method="POST">
                    <!-- Full Name -->
                    <div class="mb-3">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">person</span>
                            </span>
                            <input type="text" name="name" id="name" required
                                   class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Full Name">
                        </div>
                        <p id="nameError" class="text-xs text-red-500 mt-1 hidden"></p>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">email</span>
                            </span>
                            <input type="email" name="email" id="email" required
                                   class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Email Address">
                        </div>
                        <p id="emailError" class="text-xs text-red-500 mt-1 hidden"></p>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">call</span>
                            </span>
                            <input type="tel" name="phone" id="phone" required
                                   class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Phone Number">
                        </div>
                        <p id="phoneError" class="text-xs text-red-500 mt-1 hidden"></p>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
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
                        <p id="passwordError" class="text-xs text-red-500 mt-1 hidden"></p>
                        <p class="text-xs text-gray-400 mt-0.5">Minimum 6 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">verified</span>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Confirm Password">
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold mb-2 text-on-surface">I want to</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="role-card relative flex cursor-pointer">
                                <input type="radio" name="role" value="CUSTOMER" checked class="peer sr-only" id="role_customer">
                                <div class="w-full p-3 text-center rounded-xl border-2 peer-checked:border-primary peer-checked:bg-primary/5 transition-all cursor-pointer border-gray-200 bg-white hover:border-primary/50">
                                    <span class="material-symbols-outlined text-2xl block mx-auto mb-1 text-primary">search</span>
                                    <span class="text-xs font-semibold text-gray-800">Customer</span>
                                    <p class="text-[10px] text-gray-500">Book stays</p>
                                </div>
                            </label>
                            <label class="role-card relative flex cursor-pointer">
                                <input type="radio" name="role" value="OWNER" class="peer sr-only" id="role_owner">
                                <div class="w-full p-3 text-center rounded-xl border-2 peer-checked:border-primary peer-checked:bg-primary/5 transition-all cursor-pointer border-gray-200 bg-white hover:border-primary/50">
                                    <span class="material-symbols-outlined text-2xl block mx-auto mb-1 text-primary">home_work</span>
                                    <span class="text-xs font-semibold text-gray-800">Owner</span>
                                    <p class="text-[10px] text-gray-500">List properties</p>
                                </div>
                            </label>
                        </div>
                        <p id="roleError" class="text-xs text-red-500 mt-1 hidden"></p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-primary to-primary-container text-white py-2.5 rounded-lg font-semibold text-sm hover:shadow-lg transition-all transform hover:scale-[1.02] active:scale-95">
                        Create Account
                    </button>

                    <!-- Login Link -->
                    <div class="text-center mt-4">
                        <p class="text-xs text-gray-600">
                            Already have an account?
                            <a href="{{ url('/login') }}" class="text-primary font-semibold hover:underline">Sign In</a>
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
        <p class="text-gray-700">Creating account...</p>
    </div>
</div>

<script>
    // FIXED: Correct backend URL
    const BACKEND_API_URL = 'https://happy-stays-backend-1.onrender.com';

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
        const errorList = document.getElementById('errorList');
        errorList.innerHTML = `<li>${message}</li>`;
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
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        // Clear previous errors
        document.getElementById('errorContainer').classList.add('hidden');
        document.querySelectorAll('[id$="Error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });

        // Get form data
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        // Validate passwords match
        if (password !== confirmPassword) {
            const passwordError = document.getElementById('passwordError');
            passwordError.textContent = 'Passwords do not match';
            passwordError.classList.remove('hidden');
            return;
        }

        // FIXED: Send confirmPassword (camelCase) not password_confirmation
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            password: password,
            confirmPassword: confirmPassword,  // KEY FIX - backend expects this exact field name
            role: document.querySelector('input[name="role"]:checked').value
        };

        // Validate password length
        if (formData.password.length < 6) {
            const passwordError = document.getElementById('passwordError');
            passwordError.textContent = 'Password must be at least 6 characters';
            passwordError.classList.remove('hidden');
            return;
        }

        // Validate email
        if (!formData.email.includes('@')) {
            const emailError = document.getElementById('emailError');
            emailError.textContent = 'Please enter a valid email address';
            emailError.classList.remove('hidden');
            return;
        }

        // Show loading spinner
        document.getElementById('loadingSpinner').classList.remove('hidden');
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').textContent = 'Creating Account...';

        try {
            const response = await fetch(`${BACKEND_API_URL}/api/auth/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok && result.token) {
                // Store token
                localStorage.setItem('api_token', result.token);
                localStorage.setItem('user', JSON.stringify(result.user || result));
                sessionStorage.setItem('api_token', result.token);

                showSuccess('Account created successfully! Redirecting...');

                setTimeout(() => {
                    if (formData.role === 'OWNER') {
                        window.location.href = '/owner/dashboard';
                    } else {
                        window.location.href = '/';
                    }
                }, 2000);
            } else {
                const errorMessage = result.message || result.error || 'Registration failed. Please try again.';

                if (result.errors) {
                    if (result.errors.email) {
                        document.getElementById('emailError').textContent = result.errors.email[0];
                        document.getElementById('emailError').classList.remove('hidden');
                    }
                    if (result.errors.password) {
                        document.getElementById('passwordError').textContent = result.errors.password[0];
                        document.getElementById('passwordError').classList.remove('hidden');
                    }
                    if (result.errors.name) {
                        document.getElementById('nameError').textContent = result.errors.name[0];
                        document.getElementById('nameError').classList.remove('hidden');
                    }
                } else {
                    showError(errorMessage);
                }
            }
        } catch (error) {
            console.error('Registration error:', error);
            showError('Connection error. Please try again.');
        } finally {
            document.getElementById('loadingSpinner').classList.add('hidden');
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('submitBtn').textContent = 'Create Account';
        }
    });

    // Add focus effect
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-primary/20');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-primary/20');
        });
    });
</script>

</body>
</html>
