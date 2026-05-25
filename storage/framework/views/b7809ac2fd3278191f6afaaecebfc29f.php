<?php $__env->startSection('title', 'Find Your Perfect Stay'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .search-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .search-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    // Get user from session (Spring Boot authentication)
    $user = session('user');
    $userRole = $user ? (is_array($user) ? strtoupper($user['role'] ?? 'CUSTOMER') : 'CUSTOMER') : null;
    $userName = $user ? (is_array($user) ? ($user['name'] ?? 'Guest') : 'Guest') : null;
?>


<?php if(session()->has('api_token') && $user): ?>
<section class="bg-gradient-to-r from-brand-50 to-white border-b border-border-color">
    <div class="container-custom py-8">
        <div class="bg-gradient-to-r from-brand-500/10 to-transparent rounded-2xl p-6 md:p-8">
            <h1 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-2">
                Welcome back, <?php echo e($userName); ?>! 👋
            </h1>
            <p class="text-text-secondary text-base">Discover your next unforgettable stay in Kenya</p>

            
            <?php if($userRole === 'OWNER'): ?>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="<?php echo e(route('owner.dashboard')); ?>" class="inline-flex items-center gap-2 bg-brand-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brand-600 transition">
                    <span class="material-symbols-outlined text-sm">dashboard</span>
                    Go to Owner Dashboard
                </a>
                <a href="<?php echo e(route('owner.property.create')); ?>" class="inline-flex items-center gap-2 bg-white text-brand-600 border border-brand-200 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                    <span class="material-symbols-outlined text-sm">add_business</span>
                    Add New Property
                </a>
            </div>
            <?php elseif($userRole === 'ADMIN'): ?>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="inline-flex items-center gap-2 bg-brand-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brand-600 transition">
                    <span class="material-symbols-outlined text-sm">admin_panel_settings</span>
                    Go to Admin Panel
                </a>
                <a href="<?php echo e(route('admin.pending')); ?>" class="inline-flex items-center gap-2 bg-white text-brand-600 border border-brand-200 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                    <span class="material-symbols-outlined text-sm">pending_actions</span>
                    Review Pending Properties
                </a>
            </div>
            <?php else: ?>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="<?php echo e(route('customer.dashboard')); ?>" class="inline-flex items-center gap-2 bg-brand-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brand-600 transition">
                    <span class="material-symbols-outlined text-sm">dashboard</span>
                    My Dashboard
                </a>
                <a href="<?php echo e(route('customer.bookings')); ?>" class="inline-flex items-center gap-2 bg-white text-brand-600 border border-brand-200 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                    <span class="material-symbols-outlined text-sm">receipt_long</span>
                    My Bookings
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="relative min-h-[600px] flex items-center">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover"
             src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=1600&h=900&fit=crop"
             alt="Luxury villa in Kenya">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
    </div>

    <div class="relative z-10 container-custom py-16">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white heading-font mb-4 leading-tight">
                Find Your Perfect Stay.<br/>
                <span class="text-brand-300">Architectural Excellence.</span>
            </h1>
            <p class="text-lg text-white/80 mb-8">
                Discover Kenya's finest curated collection of luxury homes and vacation rentals.
            </p>

            
            <form action="<?php echo e(route('customer.browse')); ?>" method="GET" class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <div class="flex-1 p-4 border-b md:border-b-0 md:border-r border-border-color">
                        <label class="block text-[11px] font-semibold uppercase tracking-wider text-text-muted mb-1">📍 Location</label>
                        <input type="text" name="location"
                               class="w-full border-0 p-0 focus:ring-0 text-text-primary placeholder:text-text-muted text-base"
                               placeholder="City, region, or property name">
                    </div>

                    <div class="flex-1 p-4 border-b md:border-b-0 md:border-r border-border-color">
                        <label class="block text-[11px] font-semibold uppercase tracking-wider text-text-muted mb-1">🏠 Property Type</label>
                        <select name="property_type" class="w-full border-0 p-0 focus:ring-0 text-text-primary bg-transparent">
                            <option value="">All Types</option>
                            <option value="Villa">Villa</option>
                            <option value="Apartment">Apartment</option>
                            <option value="Cottage">Cottage</option>
                            <option value="House">House</option>
                        </select>
                    </div>

                    <div class="flex-1 p-4">
                        <label class="block text-[11px] font-semibold uppercase tracking-wider text-text-muted mb-1">💰 Max Price (KES)</label>
                        <input type="number" name="max_price"
                               class="w-full border-0 p-0 focus:ring-0 text-text-primary placeholder:text-text-muted text-base"
                               placeholder="Any">
                    </div>

                    <button type="submit" class="md:w-auto bg-brand-500 hover:bg-brand-600 text-white px-8 py-4 font-semibold transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">search</span>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>


<section class="py-16 bg-surface">
    <div class="container-custom">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-text-primary heading-font">Featured Properties</h2>
                <p class="text-text-muted mt-2">Curated architectural masterpieces available now.</p>
            </div>
            <a href="<?php echo e(route('customer.browse')); ?>" class="mt-4 md:mt-0 text-brand-600 font-semibold text-sm flex items-center gap-2 hover:gap-3 transition group">
                View All Properties
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
                $featuredProperties = \App\Helpers\ApiHelper::toPropertyCollection($properties ?? [])->take(6);
            ?>

            <?php $__empty_1 = true; $__currentLoopData = $featuredProperties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="group bg-surface-card rounded-xl overflow-hidden shadow-sm border border-border-color hover:shadow-lg transition-all hover:-translate-y-1">
                <a href="<?php echo e(route('customer.property.detail', $property->id)); ?>" class="block">
                    <div class="relative overflow-hidden h-64">
                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             src="<?php echo e(isset($property->photos[0]) ? asset('storage/' . $property->photos[0]->photo_path) : 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=800&h=600&fit=crop'); ?>"
                             alt="<?php echo e($property->title); ?>">

                        <div class="absolute top-3 left-3 bg-black/70 backdrop-blur px-2 py-1 rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-yellow-400 text-sm">star</span>
                            <span class="text-xs font-semibold text-white"><?php echo e(number_format($property->averageRating ?? 4.5, 1)); ?></span>
                        </div>

                        <div class="absolute top-3 right-3 bg-brand-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            <?php echo e($property->property_type); ?>

                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="font-bold text-lg text-text-primary heading-font"><?php echo e(Str::limit($property->title, 30)); ?></h3>
                        <p class="text-sm text-text-muted flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            <?php echo e(Str::limit($property->location, 35)); ?>

                        </p>

                        <div class="flex justify-between items-center mt-3 pt-2 border-t border-border-color">
                            <div>
                                <span class="text-xl font-bold text-brand-600">KES <?php echo e(number_format($property->price_per_night)); ?></span>
                                <span class="text-xs text-text-muted">/ night</span>
                            </div>
                            <div class="flex gap-2 text-xs text-text-muted">
                                <span class="flex items-center gap-1">🛏️ <?php echo e($property->bedrooms ?? 2); ?></span>
                                <span class="flex items-center gap-1">🛁 <?php echo e($property->bathrooms ?? 2); ?></span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 text-center py-12 bg-surface-card rounded-xl border border-border-color">
                <span class="material-symbols-outlined text-5xl text-text-muted mb-3">home</span>
                <h3 class="text-xl font-semibold text-text-primary mb-2">No properties available yet</h3>
                <p class="text-text-muted">Check back soon for new listings!</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="py-16 bg-white border-t border-border-color">
    <div class="container-custom text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-4">How It Works</h2>
        <p class="text-text-muted mb-12 max-w-2xl mx-auto">Three simple steps to your perfect stay</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
            <div class="text-center">
                <div class="w-20 h-20 bg-brand-50 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <span class="material-symbols-outlined text-brand-500 text-3xl">search</span>
                </div>
                <h3 class="text-xl font-bold text-text-primary heading-font mb-2">1. Discover</h3>
                <p class="text-text-muted">Browse our curated collection of luxury properties</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-brand-50 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <span class="material-symbols-outlined text-brand-500 text-3xl">book_online</span>
                </div>
                <h3 class="text-xl font-bold text-text-primary heading-font mb-2">2. Book</h3>
                <p class="text-text-muted">Secure your stay with easy online booking</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-brand-50 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <span class="material-symbols-outlined text-brand-500 text-3xl">celebration</span>
                </div>
                <h3 class="text-xl font-bold text-text-primary heading-font mb-2">3. Enjoy</h3>
                <p class="text-text-muted">Experience Kenyan hospitality at its finest</p>
            </div>
        </div>
    </div>
</section>


<section class="py-16 bg-brand-500">
    <div class="container-custom">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-3xl md:text-4xl font-bold text-white heading-font">500+</div>
                <p class="text-brand-100 text-sm mt-1">Happy Guests</p>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-white heading-font">50+</div>
                <p class="text-brand-100 text-sm mt-1">Luxury Properties</p>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-white heading-font">15+</div>
                <p class="text-brand-100 text-sm mt-1">Locations</p>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-white heading-font">4.9</div>
                <p class="text-brand-100 text-sm mt-1">Average Rating</p>
            </div>
        </div>
    </div>
</section>


<section class="py-16 bg-surface">
    <div class="container-custom">
        <div class="bg-gradient-to-r from-brand-500 to-brand-600 rounded-2xl p-8 md:p-12 text-center text-white">
            <h2 class="text-2xl md:text-3xl font-bold heading-font mb-3">Ready to experience luxury?</h2>
            <p class="text-brand-100 mb-6 max-w-2xl mx-auto">Join thousands of happy guests who have found their perfect stay with Eserian Homes.</p>
            <a href="<?php echo e(route('customer.browse')); ?>" class="inline-flex items-center gap-2 bg-white text-brand-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition shadow-lg">
                Browse Properties
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/home.blade.php ENDPATH**/ ?>