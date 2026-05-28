<?php $__env->startSection('title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-custom">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Side Column -->
        <aside class="w-full lg:w-1/3">
            <div class="bg-surface-card p-6 rounded-xl shadow-sm border border-border-color text-center lg:text-left">
                <div class="w-24 h-24 rounded-full border-4 border-brand-200 overflow-hidden mx-auto lg:mx-0 mb-4 bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center">
                    <span class="text-white text-3xl font-bold">
                        <?php echo e(is_object($profileUser ?? $user) ? substr((($profileUser ?? $user)->name ?? 'U'), 0, 1) : (is_array($profileUser ?? $user) ? substr((($profileUser ?? $user)['name'] ?? 'U'), 0, 1) : 'U')); ?>

                    </span>
                </div>
                <h1 class="text-2xl font-bold text-text-primary mb-1 heading-font">
                    <?php echo e(is_object($profileUser ?? $user) ? (($profileUser ?? $user)->name ?? 'User') : (is_array($profileUser ?? $user) ? (($profileUser ?? $user)['name'] ?? 'User') : 'User')); ?>

                </h1>
                <p class="text-text-muted">Member since <?php echo e(is_object($profileUser ?? $user) ? (date('Y', strtotime(($profileUser ?? $user)->created_at ?? 'now'))) : (is_array($profileUser ?? $user) ? (date('Y', strtotime(($profileUser ?? $user)['created_at'] ?? 'now'))) : date('Y'))); ?></p>

                <div class="mt-6 space-y-3">
                    <a href="<?php echo e(route('customer.profile.management')); ?>" class="block w-full bg-brand-500 text-white font-semibold text-sm py-3 px-6 rounded-xl hover:bg-brand-600 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        Edit Profile
                    </a>
                    <button onclick="shareProfile()" class="block w-full bg-white border border-border-color text-text-primary font-semibold text-sm py-3 px-6 rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">share</span>
                        Share Profile
                    </button>
                </div>
            </div>

            <div class="bg-brand-50 p-4 rounded-xl border border-brand-100 mt-4">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-brand-600">verified</span>
                    <span class="font-semibold text-sm text-brand-700">Identity Verified</span>
                </div>
                <p class="text-sm text-text-secondary">Your account is fully verified, providing you access to premium bookings and host communication.</p>
            </div>

            <div class="bg-surface-card p-4 rounded-xl border border-border-color mt-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-text-muted">Total Reviews</span>
                    <span class="text-2xl font-bold text-text-primary"><?php echo e($totalReviews ?? 0); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-text-muted">Member Since</span>
                    <span class="font-semibold text-text-primary"><?php echo e(is_object($profileUser ?? $user) ? (date('M Y', strtotime(($profileUser ?? $user)->created_at ?? 'now'))) : (is_array($profileUser ?? $user) ? (date('M Y', strtotime(($profileUser ?? $user)['created_at'] ?? 'now'))) : date('M Y'))); ?></span>
                </div>
            </div>
        </aside>

        <!-- Main Column -->
        <div class="w-full lg:w-2/3 space-y-6">
            <!-- Personal Information -->
            <section class="bg-surface-card rounded-xl shadow-sm border border-border-color overflow-hidden">
                <div class="p-4 border-b border-border-color bg-gray-50/50">
                    <h2 class="font-semibold text-lg text-text-primary heading-font">Personal Information</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-text-muted uppercase tracking-wider">Full Name</label>
                        <p class="text-base text-text-primary"><?php echo e(is_object($profileUser ?? $user) ? (($profileUser ?? $user)->name ?? 'Not provided') : (is_array($profileUser ?? $user) ? (($profileUser ?? $user)['name'] ?? 'Not provided') : 'Not provided')); ?></p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-text-muted uppercase tracking-wider">Email Address</label>
                        <p class="text-base text-text-primary"><?php echo e(is_object($profileUser ?? $user) ? (($profileUser ?? $user)->email ?? 'Not provided') : (is_array($profileUser ?? $user) ? (($profileUser ?? $user)['email'] ?? 'Not provided') : 'Not provided')); ?></p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-text-muted uppercase tracking-wider">Phone Number</label>
                        <p class="text-base text-text-primary"><?php echo e(is_object($profileUser ?? $user) ? (($profileUser ?? $user)->phone ?? 'Not provided') : (is_array($profileUser ?? $user) ? (($profileUser ?? $user)['phone'] ?? 'Not provided') : 'Not provided')); ?></p>
                    </div>
                </div>
            </section>

            <!-- Bio -->
            <section class="bg-surface-card rounded-xl shadow-sm border border-border-color overflow-hidden">
                <div class="p-4 border-b border-border-color bg-gray-50/50">
                    <h2 class="font-semibold text-lg text-text-primary heading-font">About Me</h2>
                </div>
                <div class="p-6">
                    <p class="text-text-secondary">
                        <?php echo e(is_object($profileUser ?? $user) ? (($profileUser ?? $user)->bio ?? 'Travel enthusiast exploring the world one stay at a time. ✈️') : (is_array($profileUser ?? $user) ? (($profileUser ?? $user)['bio'] ?? 'Travel enthusiast exploring the world one stay at a time. ✈️') : 'Travel enthusiast exploring the world one stay at a time. ✈️')); ?>

                    </p>
                </div>
            </section>

            <!-- Recent Reviews -->
            <section class="bg-surface-card rounded-xl shadow-sm border border-border-color overflow-hidden">
                <div class="p-4 border-b border-border-color bg-gray-50/50 flex justify-between items-center">
                    <h2 class="font-semibold text-lg text-text-primary heading-font">Recent Reviews</h2>
                    <a href="<?php echo e(route('customer.my.reviews')); ?>" class="text-brand-600 text-sm font-semibold hover:underline">View all</a>
                </div>
                <div class="divide-y divide-border-color">
                    <?php $__empty_1 = true; $__currentLoopData = ($recentReviews ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold text-text-primary"><?php echo e($review['property']['title'] ?? 'Property'); ?></p>
                                <p class="text-xs text-text-muted"><?php echo e(\Carbon\Carbon::parse($review['created_at'] ?? 'now')->format('M d, Y')); ?></p>
                            </div>
                            <div class="flex text-yellow-400">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <span class="material-symbols-outlined text-sm"><?php echo e($i <= ($review['rating'] ?? 5) ? 'star' : 'star_border'); ?></span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="text-sm text-text-secondary"><?php echo e(Str::limit($review['comment'] ?? 'No comment provided.', 150)); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-6 text-center">
                        <p class="text-text-muted">No reviews yet</p>
                    </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Logout -->
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-red-50 text-error font-semibold py-4 rounded-xl shadow-sm border border-red-100 hover:bg-red-100 transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">logout</span>
                    Log out
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function shareProfile() {
        if (navigator.share) {
            navigator.share({
                title: 'Check out my profile on Eserian Homes',
                url: window.location.href,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Profile link copied to clipboard!');
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/customer/profile.blade.php ENDPATH**/ ?>