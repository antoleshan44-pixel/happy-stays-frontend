<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
                <a href="<?php echo e(url('/')); ?>" class="text-3xl font-bold text-white tracking-tighter">Eserian Homes</a>
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

    <!-- Right Side - Registration Form (Compact - No Scroll) -->
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

                <!-- Display Validation Errors -->
                <?php if($errors->any()): ?>
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-red-600 text-sm">error</span>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-red-800 mb-1">Please fix the following errors:</p>
                                <ul class="text-xs text-red-700 list-disc list-inside">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Success Message -->
                <?php if(session('success')): ?>
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600 text-sm">check_circle</span>
                            <p class="text-xs text-green-700"><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Registration Form - Compact -->
                <form method="POST" action="<?php echo e(url('/register')); ?>">
                    <?php echo csrf_field(); ?>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">person</span>
                            </span>
                            <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                                   class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Full Name">
                        </div>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">email</span>
                            </span>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                                   class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Email Address">
                        </div>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">call</span>
                            </span>
                            <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" required
                                   class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Phone Number">
                        </div>
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">lock</span>
                            </span>
                            <input type="password" name="password" required
                                   class="w-full pl-10 pr-10 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Password"
                                   id="password">
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg" id="toggleIcon">visibility_off</span>
                            </button>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-xs text-gray-400 mt-0.5">Minimum 6 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <div class="input-group relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">
                                <span class="material-symbols-outlined text-lg">verified</span>
                            </span>
                            <input type="password" name="password_confirmation" required
                                   class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Confirm Password">
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold mb-2 text-on-surface">I want to</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="role-card relative flex cursor-pointer">
                                <input type="radio" name="role" value="customer" <?php echo e(old('role') == 'customer' ? 'checked' : 'checked'); ?> class="peer sr-only">
                                <div class="w-full p-3 text-center rounded-xl border-2 peer-checked:border-primary peer-checked:bg-primary/5 transition-all cursor-pointer border-gray-200 bg-white hover:border-primary/50">
                                    <span class="material-symbols-outlined text-2xl block mx-auto mb-1 text-primary">search</span>
                                    <span class="text-xs font-semibold text-gray-800">Customer</span>
                                    <p class="text-[10px] text-gray-500">Book stays</p>
                                </div>
                            </label>
                            <label class="role-card relative flex cursor-pointer">
                                <input type="radio" name="role" value="owner" <?php echo e(old('role') == 'owner' ? 'checked' : ''); ?> class="peer sr-only">
                                <div class="w-full p-3 text-center rounded-xl border-2 peer-checked:border-primary peer-checked:bg-primary/5 transition-all cursor-pointer border-gray-200 bg-white hover:border-primary/50">
                                    <span class="material-symbols-outlined text-2xl block mx-auto mb-1 text-primary">home_work</span>
                                    <span class="text-xs font-semibold text-gray-800">Owner</span>
                                    <p class="text-[10px] text-gray-500">List properties</p>
                                </div>
                            </label>
                        </div>
                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-primary to-primary-container text-white py-2.5 rounded-lg font-semibold text-sm hover:shadow-lg transition-all transform hover:scale-[1.02] active:scale-95">
                        Create Account
                    </button>

                    <!-- Login Link -->
                    <div class="text-center mt-4">
                        <p class="text-xs text-gray-600">
                            Already have an account?
                            <a href="<?php echo e(url('/login')); ?>" class="text-primary font-semibold hover:underline">Sign In</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/auth/register.blade.php ENDPATH**/ ?>