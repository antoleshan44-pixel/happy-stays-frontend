


<?php $__env->startSection('title', 'Browse Properties'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;1,600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --brand: #00288e;
        --brand-pale: #eef1fa;
        --love: #e11d48;
        --border: #e5e7eb;
        --text: #111827;
        --text-mid: #4b5563;
        --muted: #9ca3af;
        --card: #ffffff;
        --surface: #f7f9fb;
    }

    .bp-page { padding: 2rem 0 5rem; font-family: 'Outfit', sans-serif; }

    /* Toast */
    .bp-toast {
        position: fixed;
        bottom: 5.5rem; left: 50%;
        transform: translateX(-50%) translateY(16px);
        background: #111827; color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 99px;
        font-size: 0.82rem; font-weight: 500;
        opacity: 0;
        transition: opacity 0.25s ease, transform 0.25s ease;
        z-index: 9999; pointer-events: none;
        display: flex; align-items: center; gap: 0.5rem;
        white-space: nowrap;
        box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    }
    .bp-toast.show  { opacity: 1; transform: translateX(-50%) translateY(0); }
    .bp-toast.error { background: #be123c; }

    /* Filter sidebar */
    .filter-sidebar { position: sticky; top: 90px; }
    .filter-label {
        display: block; font-size: 0.75rem; font-weight: 600;
        letter-spacing: 0.06em; text-transform: uppercase;
        color: var(--text-mid); margin-bottom: 0.5rem;
    }
    .filter-input {
        width: 100%; padding: 0.6rem 0.9rem;
        border-radius: 0.6rem; border: 1px solid var(--border);
        font-size: 0.875rem; font-family: 'Outfit', sans-serif;
        background: var(--surface); outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .filter-input:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(0,40,142,0.1);
        background: white;
    }
    .filter-btn {
        width: 100%; background: var(--brand); color: white;
        padding: 0.7rem; border-radius: 0.65rem;
        font-weight: 600; font-size: 0.875rem;
        font-family: 'Outfit', sans-serif;
        border: none; cursor: pointer;
        transition: background 0.2s, transform 0.15s;
    }
    .filter-btn:hover { background: #002072; transform: translateY(-1px); }

    /* Property cards */
    .prop-card {
        background: var(--card); border: 1px solid var(--border);
        border-radius: 1rem; overflow: hidden; cursor: pointer;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .prop-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.1);
    }
    .prop-card__img-wrap { aspect-ratio: 4/3; overflow: hidden; position: relative; }
    .prop-card__img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.5s ease;
    }
    .prop-card:hover .prop-card__img { transform: scale(1.07); }
    .prop-card__overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.45) 0%, transparent 55%);
        pointer-events: none;
    }

    /* Heart button */
    .heart-btn {
        position: absolute; top: 0.75rem; right: 0.75rem;
        width: 2.2rem; height: 2.2rem; border-radius: 50%;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(4px);
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--muted);
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        transition: transform 0.2s, color 0.2s;
        z-index: 2;
    }
    .heart-btn:hover { transform: scale(1.15); color: var(--love); }
    .heart-btn.saved { color: var(--love); }
    .heart-btn.saved .heart-icon { font-variation-settings: 'FILL' 1 !important; }
    @keyframes heartPop {
        0%   { transform: scale(1); }
        40%  { transform: scale(1.4); }
        70%  { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    .heart-btn.pop { animation: heartPop 0.35s ease forwards; }

    /* Rating chip */
    .prop-card__rating {
        position: absolute; bottom: 0.75rem; left: 0.75rem;
        background: rgba(0,0,0,0.55); backdrop-filter: blur(4px);
        color: white; font-size: 0.72rem; font-weight: 600;
        padding: 0.25rem 0.6rem; border-radius: 99px;
        display: flex; align-items: center; gap: 0.25rem;
    }
    .prop-card__type {
        display: inline-block; background: var(--brand-pale); color: var(--brand);
        font-size: 0.68rem; font-weight: 600;
        letter-spacing: 0.06em; text-transform: uppercase;
        padding: 0.2rem 0.55rem; border-radius: 99px;
    }
    .prop-card__body { padding: 1rem; }
    .prop-card__title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem; font-weight: 600; color: var(--text);
        line-height: 1.2; margin-bottom: 0.3rem;
        transition: color 0.2s;
    }
    .prop-card:hover .prop-card__title { color: var(--brand); }
    .prop-card__location {
        font-size: 0.78rem; color: var(--muted);
        display: flex; align-items: center; gap: 0.2rem;
        margin-bottom: 0.75rem;
    }
    .prop-card__footer {
        display: flex; align-items: center; justify-content: space-between;
        padding-top: 0.75rem; border-top: 1px solid var(--border);
    }
    .prop-card__price { font-weight: 600; font-size: 1rem; color: var(--brand); }
    .prop-card__per   { font-size: 0.75rem; color: var(--muted); font-weight: 400; }
    .prop-card__link  {
        font-size: 0.8rem; font-weight: 600; color: var(--brand);
        text-decoration: none;
    }
    .prop-card__link:hover { text-decoration: underline; }

    /* Empty state */
    .bp-empty {
        text-align: center; padding: 4rem 2rem;
        background: var(--card); border: 1px solid var(--border);
        border-radius: 1rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
$savedIds = session('wishlist_ids', []);
$propertiesCount = is_countable($properties) ? count($properties) : 0;
?>

<div class="bp-page">
<div class="container-custom">

    
    <div class="mb-8">
        <p style="font-size:0.68rem;font-weight:600;letter-spacing:0.16em;text-transform:uppercase;color:var(--brand);margin-bottom:0.4rem;">
            Eserian Homes · Kenya
        </p>
        <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,4vw,3rem);font-weight:600;color:#111827;margin:0 0 0.4rem;">
            Curated <em style="font-style:italic;color:var(--brand);">Collections</em>
        </h1>
        <p style="font-size:0.9rem;color:#4b5563;font-weight:300;">
            Discover architecturally significant homes across Kenya
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">

        
        <div class="lg:w-72 filter-sidebar">
            <div class="bg-white rounded-xl p-5 border border-border-color shadow-sm">
                <h3 style="font-weight:700;font-size:1rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;">
                    <span class="material-symbols-outlined" style="font-size:18px;color:var(--brand);">tune</span>
                    Filters
                </h3>
                <form method="GET" action="<?php echo e(route('customer.browse')); ?>">
                    <div class="mb-4">
                        <label class="filter-label">Location</label>
                        <input type="text" name="location" value="<?php echo e(request('location')); ?>"
                               class="filter-input" placeholder="e.g. Nairobi, Diani…">
                    </div>
                    <div class="mb-4">
                        <label class="filter-label">Price Range (KES)</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="<?php echo e(request('min_price')); ?>"
                                   placeholder="Min" class="filter-input">
                            <input type="number" name="max_price" value="<?php echo e(request('max_price')); ?>"
                                   placeholder="Max" class="filter-input">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="filter-label">Property Type</label>
                        <select name="property_type" class="filter-input">
                            <option value="">All Types</option>
                            <?php $__currentLoopData = ['Villa','Apartment','Cottage','House','Cabin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e(request('property_type') == $type ? 'selected' : ''); ?>>
                                <?php echo e($type); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="filter-label">Sort By</label>
                        <select name="sort" class="filter-input">
                            <option value="newest"     <?php echo e(request('sort') == 'newest'     ? 'selected' : ''); ?>>Newest First</option>
                            <option value="price_low"  <?php echo e(request('sort') == 'price_low'  ? 'selected' : ''); ?>>Price: Low → High</option>
                            <option value="price_high" <?php echo e(request('sort') == 'price_high' ? 'selected' : ''); ?>>Price: High → Low</option>
                        </select>
                    </div>
                    <button type="submit" class="filter-btn">Apply Filters</button>
                    <?php if(request()->anyFilled(['location','min_price','max_price','property_type','sort'])): ?>
                    <a href="<?php echo e(route('customer.browse')); ?>"
                       style="display:block;text-align:center;margin-top:0.75rem;font-size:0.8rem;color:var(--brand);">
                        Clear all filters
                    </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        
        <div class="flex-1">
            <div class="flex justify-between items-center mb-5">
                <p style="font-size:0.82rem;color:var(--muted);">
                    <?php echo e($propertiesCount); ?> properties found
                </p>
                <a href="<?php echo e(route('customer.map')); ?>"
                   style="font-size:0.82rem;font-weight:600;color:var(--brand);display:flex;align-items:center;gap:0.25rem;text-decoration:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;">map</span>
                    Map view
                </a>
            </div>

            <?php if($propertiesCount > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Handle both object and array access
                    $propId = is_object($property) ? ($property->id ?? 0) : ($property['id'] ?? 0);
                    $propTitle = is_object($property) ? ($property->title ?? 'Untitled') : ($property['title'] ?? 'Untitled');
                    $propLocation = is_object($property) ? ($property->location ?? '') : ($property['location'] ?? '');
                    $propType = is_object($property) ? ($property->propertyType ?? $property->property_type ?? 'Property') : ($property['propertyType'] ?? $property['property_type'] ?? 'Property');
                    $propPrice = is_object($property) ? ($property->pricePerNight ?? $property->price_per_night ?? 0) : ($property['pricePerNight'] ?? $property['price_per_night'] ?? 0);
                    $propRating = is_object($property) ? ($property->averageRating ?? 4.5) : ($property['averageRating'] ?? 4.5);

                    // Handle photos
                    $photos = [];
                    if (is_object($property) && isset($property->photos)) {
                        $photos = $property->photos;
                    } elseif (is_array($property) && isset($property['photos'])) {
                        $photos = $property['photos'];
                    }

                    $firstPhoto = null;
                    if (!empty($photos) && is_array($photos)) {
                        $firstPhotoData = $photos[0];
                        if (is_object($firstPhotoData)) {
                            $path = $firstPhotoData->photoPath ?? $firstPhotoData->photo_path ?? null;
                        } else {
                            $path = $firstPhotoData['photoPath'] ?? $firstPhotoData['photo_path'] ?? null;
                        }
                        if ($path) {
                            $firstPhoto = str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
                        }
                    }

                    $isSaved = in_array((int)$propId, array_map('intval', $savedIds));
                ?>

                <div class="prop-card"
                     onclick="window.location.href='<?php echo e(route('customer.property.detail', $propId)); ?>'">

                    <div class="prop-card__img-wrap">
                        <?php if($firstPhoto): ?>
                            <img class="prop-card__img" src="<?php echo e($firstPhoto); ?>"
                                 alt="<?php echo e($propTitle); ?>" loading="lazy">
                        <?php else: ?>
                            <div style="width:100%;height:100%;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                                <span class="material-symbols-outlined" style="font-size:3rem;color:#d1d5db;">home</span>
                            </div>
                        <?php endif; ?>
                        <div class="prop-card__overlay"></div>
                        <div class="prop-card__rating">
                            ⭐ <?php echo e(number_format($propRating, 1)); ?>

                        </div>
                        <button class="heart-btn <?php echo e($isSaved ? 'saved' : ''); ?>"
                                onclick="event.stopPropagation(); toggleWishlist(this, <?php echo e($propId); ?>)">
                            <span class="material-symbols-outlined heart-icon" style="font-size:18px;">favorite</span>
                        </button>
                    </div>

                    <div class="prop-card__body">
                        <div style="margin-bottom:0.5rem;">
                            <span class="prop-card__type">
                                <?php echo e($propType); ?>

                            </span>
                        </div>
                        <div class="prop-card__title">
                            <?php echo e($propTitle); ?>

                        </div>
                        <div class="prop-card__location">
                            <span class="material-symbols-outlined" style="font-size:13px;">location_on</span>
                            <?php echo e($propLocation); ?>

                        </div>
                        <div class="prop-card__footer">
                            <div>
                                <span class="prop-card__price">
                                    KES <?php echo e(number_format($propPrice)); ?>

                                </span>
                                <span class="prop-card__per">/ night</span>
                            </div>
                            <a href="<?php echo e(route('customer.property.detail', $propId)); ?>"
                               class="prop-card__link" onclick="event.stopPropagation()">
                                Details →
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php else: ?>
            <div class="bp-empty">
                <span class="material-symbols-outlined"
                      style="font-size:3rem;color:#d1d5db;display:block;margin-bottom:1rem;">search_off</span>
                <h3 style="font-size:1.1rem;font-weight:600;margin-bottom:0.5rem;">No properties found</h3>
                <p style="font-size:0.875rem;color:var(--muted);">Try adjusting your filters or check back later.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>


<div class="bp-toast" id="bp-toast"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const SAVE_URL   = '<?php echo e(url("/customer/property")); ?>';
const CSRF_TOKEN = '<?php echo e(csrf_token()); ?>';

function showToast(msg, isError = false) {
    const t = document.getElementById('bp-toast');
    t.textContent = msg;
    t.className = 'bp-toast' + (isError ? ' error' : '');
    void t.offsetWidth;
    t.classList.add('show');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => t.classList.remove('show'), 3000);
}

function toggleWishlist(btn, id) {
    const isSaved = btn.classList.contains('saved');

    if (isSaved) {
        fetch(`${SAVE_URL}/${id}/remove`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'removed') {
                btn.classList.remove('saved');
                btn.querySelector('.heart-icon').style.fontVariationSettings = "'FILL' 0";
                showToast('💔 Removed from wishlist');
            } else {
                showToast(data.message || 'Something went wrong', true);
            }
        })
        .catch(() => showToast('Connection error — please try again', true));

    } else {
        btn.classList.add('pop');
        btn.addEventListener('animationend', () => btn.classList.remove('pop'), { once: true });

        fetch(`${SAVE_URL}/${id}/save`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'saved' || data.status === 'already_saved') {
                btn.classList.add('saved');
                btn.querySelector('.heart-icon').style.fontVariationSettings = "'FILL' 1";
                showToast(data.status === 'already_saved' ? 'Already in your wishlist' : '❤️ Saved to wishlist!');
            } else {
                btn.classList.remove('saved');
                btn.querySelector('.heart-icon').style.fontVariationSettings = "'FILL' 0";
                showToast(data.message || 'Could not save — please try again', true);
            }
        })
        .catch(() => {
            btn.classList.remove('saved');
            showToast('Connection error — please try again', true);
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.heart-btn.saved .heart-icon').forEach(icon => {
        icon.style.fontVariationSettings = "'FILL' 1";
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/customer/browse-properties.blade.php ENDPATH**/ ?>