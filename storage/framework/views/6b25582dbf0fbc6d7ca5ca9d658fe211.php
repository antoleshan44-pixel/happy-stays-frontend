


<?php $__env->startSection('title', 'Upload Media'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-custom py-6">

    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">

        <div class="bg-gradient-to-r from-secondary to-secondary/80 px-6 py-4">
            <h1 class="text-white font-semibold text-lg">Upload Property Media</h1>
            <p class="text-white/80 text-sm mt-0.5">
                <?php echo e($property['title'] ?? $property['name'] ?? 'Property'); ?>

            </p>
        </div>

        <div class="p-6">

            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-100 border border-green-400 rounded-lg text-green-700">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-red-100 border border-red-400 rounded-lg text-red-700">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php
                $propertyId = $property['id'] ?? null;
                $photoCount = is_array($existingPhotos) ? count($existingPhotos) : 0;
            ?>

            
            <form method="POST" action="<?php echo e(route('owner.property.upload.all', $propertyId)); ?>" enctype="multipart/form-data" id="uploadForm">
                <?php echo csrf_field(); ?>

                <div class="mb-6">
                    <label class="block font-bold mb-2">Video (Optional)</label>
                    <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm,video/x-matroska" class="w-full border p-2 rounded">
                    <p class="text-xs text-gray-500 mt-1">Max 100MB</p>
                </div>

                <div class="mb-6">
                    <label class="block font-bold mb-2">Photos (<?php echo e($photoCount); ?>/15)</label>
                    <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="w-full border p-2 rounded">
                    <p class="text-xs text-gray-500 mt-1">Max 5MB each. Minimum 5 photos required.</p>
                </div>

                <?php if($photoCount < 5): ?>
                    <div class="mb-6 p-3 bg-yellow-100 border border-yellow-400 rounded">
                        <p class="text-yellow-700">⚠️ You need <?php echo e(5 - $photoCount); ?> more photo(s). Minimum 5 required.</p>
                    </div>
                <?php endif; ?>

                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700">
                    UPLOAD & CONTINUE
                </button>
            </form>

            
            <?php if($photoCount > 0): ?>
                <div class="mt-8">
                    <h3 class="font-bold mb-3">Existing Photos (<?php echo e($photoCount); ?>)</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <?php $__currentLoopData = $existingPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($photo['photo_path']): ?>
                            <div class="relative border rounded overflow-hidden aspect-square group" id="photo-<?php echo e($photo['id']); ?>">
                                <img src="<?php echo e(spring_boot_url($photo['photo_path'])); ?>"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='https://placehold.co/200x200/f3f4f6/9ca3af?text=Error'">

                                <?php if($photo['is_primary']): ?>
                                    <span class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-1 rounded">Main</span>
                                <?php endif; ?>

                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                    <?php if($photo['id']): ?>
                                        <button type="button"
                                                onclick="deletePhoto(<?php echo e($propertyId); ?>, <?php echo e($photo['id']); ?>)"
                                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition"
                                                title="Delete Photo">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>

                                        <?php if(!$photo['is_primary']): ?>
                                            <button type="button"
                                                    onclick="setPrimaryPhoto(<?php echo e($propertyId); ?>, <?php echo e($photo['id']); ?>)"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition"
                                                    title="Set as Main Photo">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
const CSRF_TOKEN = '<?php echo e(csrf_token()); ?>';

function deletePhoto(propertyId, photoId) {
    if (!confirm('Are you sure you want to delete this photo? This action cannot be undone.')) {
        return;
    }

    const formData = new FormData();
    formData.append('_method', 'DELETE');
    formData.append('_token', CSRF_TOKEN);

    fetch(`/owner/property/${propertyId}/photos/${photoId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the photo element from DOM
            const photoElement = document.getElementById(`photo-${photoId}`);
            if (photoElement) {
                photoElement.remove();
            }
            showMessage('Photo deleted successfully!', 'success');

            // Update photo count
            const remainingPhotos = document.querySelectorAll('[id^="photo-"]').length;
            const photoCountElement = document.querySelector('.font-bold.mb-3');
            if (photoCountElement) {
                photoCountElement.textContent = `Existing Photos (${remainingPhotos})`;
            }

            // Reload page after short delay to refresh counts
            setTimeout(() => location.reload(), 1500);
        } else {
            showMessage(data.message || 'Failed to delete photo', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Network error. Please try again.', 'error');
    });
}

function setPrimaryPhoto(propertyId, photoId) {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('_token', CSRF_TOKEN);

    fetch(`/owner/property/${propertyId}/photos/${photoId}/primary`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Primary photo updated!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage(data.message || 'Failed to set primary photo', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Network error. Please try again.', 'error');
    });
}

function showMessage(message, type = 'success') {
    // Remove existing toast if any
    const existingToast = document.getElementById('customToast');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.id = 'customToast';
    toast.style.cssText = `
        position: fixed; bottom: 2rem; right: 2rem;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white; padding: 0.75rem 1.5rem;
        border-radius: 0.5rem; font-size: 0.875rem;
        font-weight: 500; z-index: 9999;
        opacity: 0; transition: opacity 0.3s;
        pointer-events: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    `;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => { toast.style.opacity = '1'; }, 10);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/owner/upload-photos.blade.php ENDPATH**/ ?>