update my current)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Eserian Homes'); ?> | Eserian Homes</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        }
        .container-custom {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        @media (min-width: 768px) {
            .container-custom {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-surface text-text-primary">

    <!-- Navbar -->
    <?php echo $__env->make('layouts.partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <main class="min-h-screen pt-20">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <?php echo $__env->make('layouts.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Mobile Bottom Navigation (only for authenticated users) -->
    <?php echo $__env->make('layouts.partials.mobile-bottom-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            if (menu) menu.classList.toggle('hidden');
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                const menu = document.getElementById('mobileMenu');
                if (menu) menu.classList.add('hidden');
            }
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/layouts/app.blade.php ENDPATH**/ ?>