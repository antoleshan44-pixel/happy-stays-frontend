<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title>Eserian Homes | Luxury Properties & Architectural Excellence</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#00288e",
                        "primary-container": "#1e40af",
                        "surface": "#f7f9fb",
                        "surface-container": "#eceef0",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#444653",
                        "outline": "#757684",
                        "outline-variant": "#c4c5d5",
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .glass-nav {
            backdrop-filter: blur(20px);
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,40,142,0.3) 100%);
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface">

<!-- TopNavBar - Guest Version -->
<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-lg shadow-[0_8px_32px_0_rgba(25,28,30,0.04)]">
<div class="flex justify-between items-center px-12 py-5 w-full max-w-[1440px] mx-auto">
    <a href="<?php echo e(route('home')); ?>" class="text-2xl font-bold tracking-tighter text-slate-900 font-manrope">Eserian Homes</a>
    <div class="hidden md:flex items-center space-x-8">
        <a href="<?php echo e(route('public.browse')); ?>" class="text-blue-800 font-bold border-b-2 border-blue-800 pb-1 font-manrope font-medium tracking-tight text-sm">Properties</a>
        <a href="<?php echo e(route('public.browse')); ?>" class="text-slate-500 font-medium font-manrope tracking-tight text-sm hover:text-blue-700 transition-all duration-300 ease-out">Collections</a>
        <a href="<?php echo e(route('register')); ?>?role=owner" class="text-slate-500 font-medium font-manrope tracking-tight text-sm hover:text-blue-700 transition-all duration-300 ease-out">List Property</a>
        <a href="<?php echo e(route('public.browse')); ?>" class="text-slate-500 font-medium font-manrope tracking-tight text-sm hover:text-blue-700 transition-all duration-300 ease-out">Concierge</a>
    </div>
    <div class="flex items-center space-x-6">
        <a href="<?php echo e(route('login')); ?>" class="text-slate-500 font-medium text-sm hover:text-blue-700 transition-colors">Sign In</a>
        <a href="<?php echo e(route('register')); ?>" class="text-slate-500 font-medium text-sm hover:text-blue-700 transition-colors">Register</a>
        <a href="<?php echo e(route('register')); ?>?role=owner" class="bg-gradient-to-br from-primary to-primary-container text-white px-6 py-2.5 rounded-md font-manrope font-semibold text-sm scale-95 active:scale-100 transition-transform">
            List Property
        </a>
    </div>
</div>
</nav>

<main>
<!-- Hero Section -->
<section class="relative h-[921px] min-h-[700px] flex items-center pt-20">
    <div class="absolute inset-0 z-0">
        <img class="w-full h-full object-cover" alt="Luxury architectural villa in Kenya" src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=1600&h=900&fit=crop">
        <div class="hero-gradient absolute inset-0"></div>
    </div>
    <div class="relative z-10 w-full max-w-[1440px] mx-auto px-12">
        <div class="max-w-2xl mb-12">
            <h1 class="text-6xl font-extrabold text-white font-headline leading-[1.1] tracking-tighter mb-6">
                Find Your Perfect Stay.<br/>Architectural Excellence.
            </h1>
            <p class="text-lg text-white/90 font-light tracking-wide">
                Discover Kenya's finest curated collection of luxury homes and vacation rentals.
            </p>
        </div>

        <!-- Search Form -->
        <form action="<?php echo e(route('public.browse')); ?>" method="GET" class="bg-surface-container-lowest/95 backdrop-blur-xl p-4 rounded-full shadow-2xl flex flex-wrap md:flex-nowrap items-center gap-4 max-w-4xl">
            <div class="flex-1 flex items-center px-6 gap-3 border-r border-outline-variant/20">
                <span class="material-symbols-outlined text-primary">location_on</span>
                <div class="flex flex-col w-full">
                    <span class="text-[10px] uppercase tracking-widest font-bold text-outline">Location</span>
                    <input name="location" class="bg-transparent border-none p-0 focus:ring-0 text-on-surface font-semibold placeholder:text-outline/50 w-full" placeholder="City, region, or property name" type="text">
                </div>
            </div>
            <div class="flex-1 flex items-center px-6 gap-3 border-r border-outline-variant/20">
                <span class="material-symbols-outlined text-primary">home_work</span>
                <div class="flex flex-col w-full">
                    <span class="text-[10px] uppercase tracking-widest font-bold text-outline">Property Type</span>
                    <select name="property_type" class="bg-transparent border-none p-0 focus:ring-0 text-on-surface font-semibold w-full">
                        <option value="">All Types</option>
                        <option value="Villa">Villa</option>
                        <option value="Apartment">Apartment</option>
                        <option value="Cottage">Cottage</option>
                        <option value="House">House</option>
                    </select>
                </div>
            </div>
            <div class="flex-1 flex items-center px-6 gap-3">
                <span class="material-symbols-outlined text-primary">payments</span>
                <div class="flex flex-col w-full">
                    <span class="text-[10px] uppercase tracking-widest font-bold text-outline">Max Price (KES)</span>
                    <input name="max_price" class="bg-transparent border-none p-0 focus:ring-0 text-on-surface font-semibold placeholder:text-outline/50 w-full" placeholder="Any" type="number">
                </div>
            </div>
            <button type="submit" class="bg-primary hover:bg-primary-container text-white h-14 w-14 rounded-full flex items-center justify-center transition-all duration-300">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
    </div>
</section>

<!-- Featured Properties Section -->
<section class="py-24 px-12 max-w-[1440px] mx-auto">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="text-3xl font-extrabold font-headline tracking-tighter text-on-surface">Featured Properties</h2>
            <p class="text-on-surface-variant mt-2">Curated architectural masterpieces available now.</p>
        </div>
        <a href="<?php echo e(route('public.browse')); ?>" class="text-primary font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
            View All Properties <span class="material-symbols-outlined">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <?php
            // Get properties from Spring Boot API
            try {
                $api = app(App\Services\SpringBootApiService::class);
                $allProperties = $api->getProperties();

                // Filter approved properties and take first 6
                $featuredProperties = collect($allProperties)
                    ->filter(function($p) {
                        return isset($p['status']) && strtoupper($p['status']) === 'APPROVED';
                    })
                    ->take(6);
            } catch (\Exception $e) {
                $featuredProperties = collect();
            }
        ?>

        <?php $__empty_1 = true; $__currentLoopData = $featuredProperties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="group">
            <a href="<?php echo e(route('public.property.detail', $property['id'])); ?>" class="block">
                <div class="relative overflow-hidden rounded-xl aspect-[4/5] mb-4">
                    <?php if(isset($property['photos'][0]['photoPath'])): ?>
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                             src="<?php echo e(asset('storage/' . $property['photos'][0]['photoPath'])); ?>"
                             alt="<?php echo e($property['title'] ?? 'Property'); ?>">
                    <?php else: ?>
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                             src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=800&h=1000&fit=crop"
                             alt="<?php echo e($property['title'] ?? 'Property'); ?>">
                    <?php endif; ?>

                    <!-- Rating Badge -->
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full flex items-center gap-1">
                        <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="text-xs font-bold font-manrope">
                            <?php echo e(number_format($property['averageRating'] ?? 4.5, 1)); ?>

                        </span>
                    </div>

                    <!-- Property Type Badge -->
                    <div class="absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full text-xs font-bold uppercase">
                        <?php echo e($property['propertyType'] ?? 'Villa'); ?>

                    </div>
                </div>

                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold font-headline leading-tight"><?php echo e($property['title'] ?? 'Property'); ?></h3>
                        <p class="text-sm text-on-surface-variant flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                            <?php echo e($property['location'] ?? 'Unknown Location'); ?>

                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-extrabold font-headline text-primary">KES <?php echo e(number_format($property['pricePerNight'] ?? 0)); ?></p>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-outline">per night</p>
                    </div>
                </div>

                <div class="mt-4 flex gap-4">
                    <?php if(isset($property['bedrooms'])): ?>
                    <span class="text-xs font-semibold text-on-surface-variant bg-surface-container-high px-3 py-1 rounded-full">
                        <?php echo e($property['bedrooms']); ?> <?php echo e($property['bedrooms'] == 1 ? 'Bed' : 'Beds'); ?>

                    </span>
                    <?php endif; ?>
                    <?php if(isset($property['bathrooms'])): ?>
                    <span class="text-xs font-semibold text-on-surface-variant bg-surface-container-high px-3 py-1 rounded-full">
                        <?php echo e($property['bathrooms']); ?> <?php echo e($property['bathrooms'] == 1 ? 'Bath' : 'Baths'); ?>

                    </span>
                    <?php endif; ?>
                </div>
            </a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-3 text-center py-12">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">home</span>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No properties available yet</h3>
            <p class="text-gray-500">Check back soon for new listings! Make sure Spring Boot is running on port 8080.</p>
            <?php if(!isset($allProperties)): ?>
            <p class="text-sm text-blue-600 mt-2">Tip: Start your Spring Boot backend to see properties.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- How It Works -->
<section class="py-24 bg-surface-container">
    <div class="max-w-[1440px] mx-auto px-12">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold font-headline tracking-tighter text-on-surface">How Eserian Homes Works</h2>
            <p class="text-on-surface-variant mt-2">Simple steps to find or list your dream property</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
            <div class="flex flex-col items-center text-center">
                <div class="bg-primary-container/10 p-4 rounded-xl mb-6">
                    <span class="material-symbols-outlined text-primary text-3xl">search_spark</span>
                </div>
                <h3 class="text-2xl font-bold font-headline mb-4 tracking-tight text-on-surface">Discover</h3>
                <p class="text-on-surface-variant font-light leading-relaxed">Browse through our meticulously curated portfolio of architectural gems across Kenya.</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="bg-primary-container/10 p-4 rounded-xl mb-6">
                    <span class="material-symbols-outlined text-primary text-3xl">event_available</span>
                </div>
                <h3 class="text-2xl font-bold font-headline mb-4 tracking-tight text-on-surface">Book</h3>
                <p class="text-on-surface-variant font-light leading-relaxed">Secure your stay with our seamless booking experience and M-Pesa payment.</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="bg-primary-container/10 p-4 rounded-xl mb-6">
                    <span class="material-symbols-outlined text-primary text-3xl">diversity_3</span>
                </div>
                <h3 class="text-2xl font-bold font-headline mb-4 tracking-tight text-on-surface">Stay</h3>
                <p class="text-on-surface-variant font-light leading-relaxed">Enjoy your stay and experience Kenyan hospitality at its finest.</p>
            </div>
        </div>
    </div>
</section>

<!-- List Your Property CTA -->
<section class="mx-12 my-24 overflow-hidden rounded-3xl relative">
    <div class="bg-gradient-to-br from-primary to-primary-container py-24 px-16 relative z-10">
        <div class="max-w-3xl">
            <h2 class="text-5xl font-extrabold font-headline text-white leading-tight tracking-tighter mb-8">
                Own a Property?<br/>List It With Us.
            </h2>
            <p class="text-xl text-blue-200 font-light mb-10 max-w-xl">
                Join an exclusive community of homeowners who value design, integrity, and premium service.
            </p>
            <a href="<?php echo e(route('register')); ?>?role=owner" class="inline-block bg-white text-primary px-10 py-4 rounded-md font-bold font-manrope hover:bg-gray-100 transition-all shadow-xl">
                List Your Property
            </a>
        </div>
    </div>
    <div class="absolute top-0 right-0 h-full w-1/3 hidden lg:block overflow-hidden">
        <img class="w-full h-full object-cover opacity-50" alt="Elegant interior" src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=800&h=1200&fit=crop">
    </div>
</section>
</main>

<!-- Footer -->
<footer class="bg-slate-50 w-full py-16 px-12 mt-20">
    <div class="flex flex-col md:flex-row justify-between items-end max-w-[1440px] mx-auto space-y-8 md:space-y-0">
        <div class="flex flex-col items-start space-y-6">
            <div class="font-manrope font-black text-slate-900 text-lg">Eserian Homes</div>
            <div class="flex flex-wrap gap-8">
                <a href="#" class="font-inter text-[11px] uppercase tracking-[0.1em] font-semibold text-slate-500 hover:text-blue-800 transition-colors">Privacy Policy</a>
                <a href="#" class="font-inter text-[11px] uppercase tracking-[0.1em] font-semibold text-slate-500 hover:text-blue-800 transition-colors">Terms of Service</a>
                <a href="#" class="font-inter text-[11px] uppercase tracking-[0.1em] font-semibold text-slate-500 hover:text-blue-800 transition-colors">About Us</a>
                <a href="#" class="font-inter text-[11px] uppercase tracking-[0.1em] font-semibold text-slate-500 hover:text-blue-800 transition-colors">Contact</a>
            </div>
        </div>
        <div class="text-right">
            <p class="font-inter text-[11px] uppercase tracking-[0.1em] font-semibold text-slate-500">© 2026 Eserian Homes. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/welcome.blade.php ENDPATH**/ ?>