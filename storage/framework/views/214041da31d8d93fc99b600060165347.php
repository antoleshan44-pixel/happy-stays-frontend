


<?php $__env->startSection('title', 'Edit Property'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-custom py-6">

    
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-2">Edit Property</h1>
        <p class="text-on-surface-variant">Update your property information</p>
    </div>

    
    <?php if(session('success')): ?>
        <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-lg text-success text-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-lg text-error text-sm">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-lg text-error text-sm">
            <p class="font-semibold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-0.5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">

        
        <div class="bg-gradient-to-r from-secondary to-secondary/80 px-6 py-4">
            <h2 class="text-white font-semibold text-lg">
                <?php echo e($property['title'] ?? 'Property'); ?>

            </h2>
            <p class="text-white/70 text-sm mt-0.5">
                <?php echo e($property['location'] ?? ''); ?>

            </p>
        </div>

        <form action="<?php echo e(route('owner.property.update', $property['id'])); ?>"
              method="POST" class="p-6 space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Property Title <span class="text-error">*</span>
                    </label>
                    <input type="text" name="title"
                           value="<?php echo e(old('title', $property['title'] ?? '')); ?>"
                           required maxlength="255"
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="e.g., Luxury Beachfront Villa">
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-error text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Location <span class="text-error">*</span>
                    </label>
                    <input type="text" name="location"
                           value="<?php echo e(old('location', $property['location'] ?? '')); ?>"
                           required
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="e.g., Diani Beach, Westlands Nairobi">
                    <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-error text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Description <span class="text-error">*</span>
                    </label>
                    <textarea name="description" rows="5" required
                              class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                     focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                     <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              placeholder="Describe your property... What makes it special?"><?php echo e(old('description', $property['description'] ?? '')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-error text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Property Type <span class="text-error">*</span>
                    </label>
                    <?php
                        $currentType = old('property_type', $property['propertyType'] ?? $property['property_type'] ?? '');
                    ?>
                    <select name="property_type" required
                            class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                   focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                   <?php $__errorArgs = ['property_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Select type…</option>
                        <?php $__currentLoopData = $propertyTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e($currentType == $type ? 'selected' : ''); ?>>
                                <?php echo e($type); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['property_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-error text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Price per Night (KES) <span class="text-error">*</span>
                    </label>
                    <?php
                        $currentPrice = old('price_per_night', $property['pricePerNight'] ?? $property['price_per_night'] ?? 0);
                    ?>
                    <input type="number" name="price_per_night"
                           value="<?php echo e($currentPrice); ?>"
                           required min="0" step="100"
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  <?php $__errorArgs = ['price_per_night'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="15000">
                    <?php $__errorArgs = ['price_per_night'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-error text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Bedrooms <span class="text-error">*</span>
                    </label>
                    <?php
                        $currentBedrooms = old('bedrooms', $property['bedrooms'] ?? 1);
                    ?>
                    <input type="number" name="bedrooms"
                           value="<?php echo e($currentBedrooms); ?>"
                           required min="1" max="20"
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  <?php $__errorArgs = ['bedrooms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['bedrooms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-error text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Bathrooms <span class="text-error">*</span>
                    </label>
                    <?php
                        $currentBathrooms = old('bathrooms', $property['bathrooms'] ?? 1);
                    ?>
                    <input type="number" name="bathrooms"
                           value="<?php echo e($currentBathrooms); ?>"
                           required min="1" max="20"
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  <?php $__errorArgs = ['bathrooms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['bathrooms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-error text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

            </div>

            
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Amenities</label>
                <p class="text-xs text-on-surface-variant mb-4">Select everything your property offers guests.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    <?php $__currentLoopData = $amenitiesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isChecked = false;
                            if (isset($currentAmenities) && is_array($currentAmenities)) {
                                $isChecked = in_array($amenity, $currentAmenities);
                            } elseif (isset($property['amenities'])) {
                                $raw = $property['amenities'];
                                if (is_array($raw)) {
                                    $isChecked = in_array($amenity, $raw);
                                } elseif (is_string($raw)) {
                                    $decoded = json_decode($raw, true);
                                    $isChecked = is_array($decoded) && in_array($amenity, $decoded);
                                }
                            }
                        ?>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-surface-container rounded-lg p-2 transition group">
                            <input type="checkbox" name="amenities[]" value="<?php echo e($amenity); ?>"
                                   class="w-4 h-4 text-secondary rounded border-outline-variant focus:ring-secondary flex-shrink-0"
                                   <?php echo e($isChecked ? 'checked' : ''); ?>>
                            <span class="text-sm text-on-surface"><?php echo e($amenity); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="bg-surface-container-low p-4 rounded-lg border border-outline-variant">
                <?php
                    $status = strtolower($property['status'] ?? 'unknown');
                ?>
                <p class="text-sm text-on-surface">
                    Current Status:
                    <span class="font-semibold
                        <?php if($status === 'approved'): ?> text-success
                        <?php elseif($status === 'pending'): ?> text-warning
                        <?php else: ?> text-error
                        <?php endif; ?>">
                        <?php echo e(ucfirst($status)); ?>

                    </span>
                </p>
                <p class="text-xs text-on-surface-variant mt-1">
                    Editing will not change your approval status.
                </p>
            </div>

            
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-secondary text-white py-2.5 rounded-lg font-semibold
                               hover:bg-secondary/90 active:scale-[0.98] transition">
                    Save Changes
                </button>
                <a href="<?php echo e(route('owner.property.photos', $property['id'])); ?>"
                   class="flex-1 border border-outline-variant text-on-surface py-2.5 rounded-lg
                          font-semibold text-center hover:bg-surface-container transition">
                    Manage Photos
                </a>
                <a href="<?php echo e(route('owner.properties')); ?>"
                   class="flex-1 border border-outline-variant text-on-surface-variant py-2.5 rounded-lg
                          font-semibold text-center hover:bg-surface-container transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/owner/edit-property.blade.php ENDPATH**/ ?>