<?php $__env->startSection('title', 'Pending Properties'); ?>
<?php $__env->startSection('subtitle', 'Review and approve property listings'); ?>

<?php $__env->startSection('content'); ?>
<?php use App\Helpers\CurrencyHelper; ?>

<div class="space-y-5">

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Properties Awaiting Approval</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                <?php echo e(count($properties)); ?> <?php echo e(Str::plural('property', count($properties))); ?> pending review
            </p>
        </div>
        <div class="flex gap-2">
            <button onclick="location.reload()"
                    class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-200 rounded-lg
                           text-sm text-gray-600 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <?php if(count($properties) > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full" id="pendingTable">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 text-left w-8">
                            <input type="checkbox" id="selectAll"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-14">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Price/Night</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Risk</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Submitted</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $riskScore  = $property['risk_score'] ?? 0;
                        $riskClass  = $riskScore >= 70 ? 'bg-red-50'
                                   : ($riskScore >= 50 ? 'bg-yellow-50' : '');
                        $propType   = $property['propertyType'] ?? $property['property_type'] ?? 'N/A';
                        $price      = $property['pricePerNight'] ?? $property['price_per_night'] ?? 0;
                        $ownerName  = $property['owner_name'] ?? $property['ownerName'] ?? 'N/A';
                        $createdAt  = $property['createdAt'] ?? $property['created_at'] ?? null;
                        $photoCount = $property['photoCount'] ?? count($property['photos'] ?? []);
                        $firstPic   = !empty($property['photos'])
                                    ? ($property['photos'][0]['photoPath'] ?? $property['photos'][0]['photo_path'] ?? null)
                                    : null;
                    ?>
                    <tr class="hover:bg-gray-50 transition <?php echo e($riskClass); ?>" data-id="<?php echo e($property['id']); ?>">
                        <td class="px-4 py-4">
                            <input type="checkbox" class="propertyCheckbox rounded border-gray-300
                                   text-blue-600 focus:ring-blue-500" value="<?php echo e($property['id']); ?>">
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-sm font-mono text-gray-500">#<?php echo e($property['id']); ?></span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-200">
                                    <?php if($firstPic): ?>
                                        <img src="<?php echo e(Str::startsWith($firstPic,'http') ? $firstPic : asset('storage/'.$firstPic)); ?>"
                                             alt="<?php echo e($property['title'] ?? ''); ?>"
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://placehold.co/48x48/f3f4f6/9ca3af?text=?'">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate max-w-[200px]">
                                        <?php echo e(Str::limit($property['title'] ?? 'Untitled', 45)); ?>

                                    </p>
                                    <p class="text-xs text-gray-500 truncate max-w-[200px] mt-0.5">
                                        <?php echo e($property['location'] ?? 'No location'); ?>

                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] text-gray-400"><?php echo e($photoCount); ?> photo(s)</span>
                                        <?php if($photoCount < 5): ?>
                                            <span class="text-[10px] text-amber-600 font-medium">⚠ Needs more photos</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-blue-600">
                                        <?php echo e(strtoupper(substr($ownerName, 0, 1))); ?>

                                    </span>
                                </div>
                                <span class="text-sm text-gray-700"><?php echo e($ownerName); ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                         bg-blue-50 text-blue-700 border border-blue-100">
                                <?php echo e(ucfirst($propType)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-sm font-semibold text-gray-900">
                                KES <?php echo e(number_format($price)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5 w-16">
                                    <div class="h-1.5 rounded-full transition-all
                                        <?php echo e($riskScore >= 70 ? 'bg-red-500' : ($riskScore >= 50 ? 'bg-yellow-500' : 'bg-green-500')); ?>"
                                         style="width: <?php echo e(min($riskScore, 100)); ?>%"></div>
                                </div>
                                <span class="text-xs font-medium
                                    <?php echo e($riskScore >= 70 ? 'text-red-600' : ($riskScore >= 50 ? 'text-yellow-600' : 'text-green-600')); ?>">
                                    <?php echo e($riskScore); ?>%
                                </span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-0.5">
                                <?php echo e($riskScore >= 70 ? 'High risk' : ($riskScore >= 50 ? 'Medium' : 'Low risk')); ?>

                            </p>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-sm text-gray-600">
                                <?php echo e($createdAt ? \Carbon\Carbon::parse($createdAt)->diffForHumans() : 'Unknown'); ?>

                            </p>
                            <?php if($createdAt): ?>
                                <p class="text-[10px] text-gray-400 mt-0.5">
                                    <?php echo e(\Carbon\Carbon::parse($createdAt)->format('d M Y')); ?>

                                </p>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <button onclick="approveProperty(<?php echo e($property['id']); ?>)"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-green-600 text-white
                                               text-xs font-semibold rounded-lg hover:bg-green-700 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Approve
                                </button>
                                <button onclick="showRejectModal(<?php echo e($property['id']); ?>, '<?php echo e(addslashes($property['title'] ?? '')); ?>')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-600 text-white
                                               text-xs font-semibold rounded-lg hover:bg-red-700 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reject
                                </button>
                                <button onclick="viewDetails(<?php echo e($property['id']); ?>)"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-gray-100 text-gray-700
                                               text-xs font-medium rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div id="bulkBar" class="hidden px-4 py-3 bg-blue-50 border-t border-blue-100 flex items-center gap-3">
            <span id="selectedCount" class="text-sm font-medium text-blue-700">0 selected</span>
            <button onclick="bulkApprove()"
                    class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition">
                Approve All Selected
            </button>
            <button onclick="bulkReject()"
                    class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition">
                Reject All Selected
            </button>
        </div>

        <?php else: ?>
        
        <div class="py-20 text-center">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">All caught up!</h3>
            <p class="text-sm text-gray-500">No pending properties to review.</p>
            <p class="text-xs text-gray-400 mt-1">New submissions will appear here automatically.</p>
        </div>
        <?php endif; ?>

    </div>
</div>


<div id="rejectModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl">
        <div class="p-6">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Reject Property</h3>
                    <p id="rejectPropertyTitle" class="text-sm text-gray-500 mt-0.5 truncate max-w-xs"></p>
                </div>
                <button onclick="closeRejectModal()"
                        class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="rejectForm" method="POST" onsubmit="submitReject(event)">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <select name="reason" id="rejectReason" required
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm
                                       focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                            <option value="">Select reason…</option>
                            <option value="fake_property">Fake / Fraudulent Property</option>
                            <option value="poor_quality">Poor Quality Images</option>
                            <option value="invalid_ownership">Invalid Ownership Documents</option>
                            <option value="policy_violation">Policy Violation</option>
                            <option value="incorrect_pricing">Incorrect / Unrealistic Pricing</option>
                            <option value="missing_info">Missing Required Information</option>
                            <option value="duplicate">Duplicate Listing</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Feedback for Owner <span class="text-red-500">*</span>
                        </label>
                        <textarea name="details" id="rejectDetails" rows="3" required
                                  placeholder="Explain what needs to be fixed so the owner can resubmit…"
                                  class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm
                                         focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none
                                         transition resize-none"></textarea>
                    </div>

                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="allow_resubmission" value="1"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">Allow owner to resubmit after corrections</span>
                    </label>

                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeRejectModal()"
                            class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-gray-700
                                   text-sm font-medium hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl text-sm
                                   font-semibold hover:bg-red-700 transition">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="detailDrawer"
     class="fixed inset-y-0 right-0 w-full max-w-lg bg-white shadow-2xl z-50 transform translate-x-full
            transition-transform duration-300 overflow-y-auto">
    <div class="p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-800">Property Details</h3>
            <button onclick="closeDrawer()"
                    class="text-gray-400 hover:text-gray-600 transition p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="drawerContent">
            <div class="flex items-center justify-center py-12">
                <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
    </div>
</div>
<div id="drawerOverlay" class="fixed inset-0 bg-black/30 z-40 hidden" onclick="closeDrawer()"></div>

<?php $__env->startPush('scripts'); ?>
<script>
// ── CSRF token for all AJAX ───────────────────────────────────
const CSRF = '<?php echo e(csrf_token()); ?>';
let rejectPropertyId = null;

// ── DataTables init ───────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined'
        && document.getElementById('pendingTable')) {
        if ($.fn.DataTable.isDataTable('#pendingTable')) {
            $('#pendingTable').DataTable().destroy();
        }
        $('#pendingTable').DataTable({
            order:      [[7, 'desc']],
            pageLength: 25,
            columnDefs: [
                { orderable: false, targets: [0, 8] },
                { searchable: false, targets: [0, 8] }
            ],
            language: {
                emptyTable: 'No pending properties found',
                search:     'Search properties:',
            }
        });
    }

    // Checkbox select-all
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.propertyCheckbox').forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkBar();
        });
        document.querySelectorAll('.propertyCheckbox').forEach(cb => {
            cb.addEventListener('change', updateBulkBar);
        });
    }
});

function updateBulkBar() {
    const checked = document.querySelectorAll('.propertyCheckbox:checked').length;
    const bar = document.getElementById('bulkBar');
    const cnt = document.getElementById('selectedCount');
    if (checked > 0) {
        bar.classList.remove('hidden');
        bar.classList.add('flex');
        cnt.textContent = checked + ' selected';
    } else {
        bar.classList.add('hidden');
        bar.classList.remove('flex');
    }
}

// ── Approve ───────────────────────────────────────────────────
function approveProperty(id) {
    if (!confirm('Approve this property? It will become visible to guests immediately.')) return;

    fetch('/admin/property/' + id + '/approve', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ moderator_notes: '' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector('tr[data-id="' + id + '"]');
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => { row.remove(); updateCount(); }, 300);
            }
            showToast('Property approved successfully!', 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast(data.message || 'Failed to approve property', 'error');
        }
    })
    .catch(() => showToast('Network error — please try again', 'error'));
}

// ── Reject modal ──────────────────────────────────────────────
function showRejectModal(id, title) {
    rejectPropertyId = id;
    document.getElementById('rejectPropertyTitle').textContent = title;
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectDetails').value = '';
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    rejectPropertyId = null;
}

function submitReject(e) {
    e.preventDefault();
    if (!rejectPropertyId) return;

    const formData = new FormData(document.getElementById('rejectForm'));

    fetch('/admin/property/' + rejectPropertyId + '/reject', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeRejectModal();
            const row = document.querySelector('tr[data-id="' + rejectPropertyId + '"]');
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => { row.remove(); updateCount(); }, 300);
            }
            showToast('Property rejected. Owner has been notified.', 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast(data.message || 'Failed to reject property', 'error');
        }
    })
    .catch(() => showToast('Network error — please try again', 'error'));
}

// ── View details drawer ───────────────────────────────────────
function viewDetails(id) {
    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawerOverlay');
    const content = document.getElementById('drawerContent');

    content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div></div>';
    drawer.classList.remove('translate-x-full');
    overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    const props = <?php echo json_encode($properties, 15, 512) ?>;
    const prop = props.find(p => p.id == id);

    if (prop) {
        const photoCount = prop.photoCount ?? (prop.photos ? prop.photos.length : 0);
        const videoCount = prop.videos ? prop.videos.length : 0;
        const price = prop.pricePerNight ?? prop.price_per_night ?? 0;
        const type = prop.propertyType ?? prop.property_type ?? 'N/A';
        const owner = prop.owner_name ?? prop.ownerName ?? 'N/A';
        const risk = prop.risk_score ?? 0;
        const photos = prop.photos ?? [];

        let galleryHtml = '';
        if (photos.length > 0) {
            galleryHtml = '<div class="grid grid-cols-3 gap-2 mt-3">';
            photos.slice(0, 6).forEach(photo => {
                const photoPath = photo.photoPath ?? photo.photo_path ?? '';
                galleryHtml += `<img src="<?php echo e(asset('storage/')); ?>/${photoPath}" class="w-full h-20 object-cover rounded-lg" onerror="this.src='https://placehold.co/80x80/f3f4f6/9ca3af?text=?'">`;
            });
            galleryHtml += '</div>';
        }

        content.innerHTML = `
            <div class="space-y-5">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-base mb-1">${prop.title ?? 'Untitled'}</h4>
                    <p class="text-sm text-gray-500">${prop.location ?? 'No location'}</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <p class="text-xs text-blue-500 font-medium uppercase tracking-wide">Price / Night</p>
                        <p class="text-lg font-bold text-blue-700 mt-0.5">KES ${price.toLocaleString()}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-xl">
                        <p class="text-xs text-purple-500 font-medium uppercase tracking-wide">Type</p>
                        <p class="text-base font-bold text-purple-700 mt-0.5">${type}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-xl">
                        <p class="text-xs text-green-500 font-medium uppercase tracking-wide">Photos</p>
                        <p class="text-lg font-bold text-green-700 mt-0.5">${photoCount}</p>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl">
                        <p class="text-xs text-amber-500 font-medium uppercase tracking-wide">Risk Score</p>
                        <p class="text-lg font-bold mt-0.5 ${risk >= 70 ? 'text-red-600' : risk >= 50 ? 'text-amber-600' : 'text-green-600'}">${risk}%</p>
                    </div>
                </div>
                ${galleryHtml}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Owner</p>
                    <p class="text-sm text-gray-700">${owner}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Description</p>
                    <p class="text-sm text-gray-600 leading-relaxed">${(prop.description ?? 'No description').substring(0, 300)}${(prop.description ?? '').length > 300 ? '…' : ''}</p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button onclick="approveProperty(${id}); closeDrawer();"
                            class="flex-1 py-2.5 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                        Approve
                    </button>
                    <button onclick="closeDrawer(); setTimeout(() => showRejectModal(${id}, '${(prop.title ?? '').replace(/'/g, "\\'")}'), 200);"
                            class="flex-1 py-2.5 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition">
                        Reject
                    </button>
                </div>
            </div>`;
    } else {
        content.innerHTML = '<p class="text-sm text-gray-500 text-center py-8">Property details not available.</p>';
    }
}

function closeDrawer() {
    document.getElementById('detailDrawer').classList.add('translate-x-full');
    document.getElementById('drawerOverlay').classList.add('hidden');
    document.body.style.overflow = '';
}

// ── Bulk actions ──────────────────────────────────────────────
function bulkApprove() {
    const ids = [...document.querySelectorAll('.propertyCheckbox:checked')].map(cb => cb.value);
    if (!ids.length) return;
    if (!confirm('Approve ' + ids.length + ' selected properties?')) return;
    ids.forEach(id => approveProperty(parseInt(id)));
}

function bulkReject() {
    const ids = [...document.querySelectorAll('.propertyCheckbox:checked')].map(cb => cb.value);
    if (!ids.length) return;
    if (!confirm('Reject ' + ids.length + ' selected properties?')) return;
    ids.forEach(id => {
        fetch('/admin/property/' + id + '/reject', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ reason: 'policy_violation', details: 'Bulk rejection by admin' })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                const row = document.querySelector('tr[data-id="' + id + '"]');
                if (row) { row.style.opacity = '0'; setTimeout(() => { row.remove(); updateCount(); }, 300); }
            }
        });
    });
    showToast(ids.length + ' properties rejected.', 'success');
    setTimeout(() => location.reload(), 1000);
}

function updateCount() {
    const remaining = document.querySelectorAll('tbody tr[data-id]').length;
    const countEl = document.querySelector('.text-gray-500.mt-0\\.5');
    if (countEl) countEl.textContent = remaining + ' propert' + (remaining === 1 ? 'y' : 'ies') + ' pending review';
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = [
        'fixed bottom-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-sm font-medium',
        'transform translate-y-2 opacity-0 transition-all duration-300',
        type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
    ].join(' ');
    toast.textContent = message;
    document.body.appendChild(toast);
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
    });
    setTimeout(() => {
        toast.classList.add('translate-y-2', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeRejectModal();
        closeDrawer();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/admin/properties/pending.blade.php ENDPATH**/ ?>