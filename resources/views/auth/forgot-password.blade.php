<!-- File: resources/views/auth/forgot-password.blade.php -->
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password | Eserian Homes</title>
    
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
                    },
                    fontFamily: {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                    }
                },
            },
        }
    </script>
</head>
<body class="bg-background text-on-surface font-body">

<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl shadow-sm">
    <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
        <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tighter text-blue-900">Eserian Homes</a>
    </div>
</nav>

<main class="min-h-screen flex items-center justify-center px-8 pt-32 pb-20">
    <div class="max-w-md w-full">
        <div class="bg-surface-container-lowest rounded-2xl shadow-xl p-8 text-center">
            <span class="material-symbols-outlined text-5xl text-primary mb-4">lock_reset</span>
            <h1 class="font-headline text-2xl font-bold text-blue-900 mb-2">Reset Password</h1>
            <p class="text-on-surface-variant text-sm mb-6">
                Enter your email address and we'll send you instructions to reset your password.
            </p>
            
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-6">
                    <input type="email" name="email" required
                           class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary"
                           placeholder="Enter your email address">
                </div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold hover:bg-primary-container transition-colors">
                    Send Reset Link
                </button>
            </form>
            
            <div class="mt-6">
                <a href="{{ route('login') }}" class="text-primary text-sm hover:underline flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    Back to Sign In
                </a>
            </div>
        </div>
    </div>
</main>
</body>
</html>