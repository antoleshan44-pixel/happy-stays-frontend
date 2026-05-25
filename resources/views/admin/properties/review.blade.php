@extends('admin.layouts.app')

@section('title', 'Review Property')
@section('subtitle', 'Review property details before approval')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.pending') }}" class="text-gray-600 hover:text-gray-800 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Pending Properties</span>
        </a>
        <div class="flex space-x-2">
            <button onclick="approveProperty()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2">
                <i class="fas fa-check"></i>
                <span>Approve Property</span>
            </button>
            <button onclick="showRejectModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center space-x-2">
                <i class="fas fa-times"></i>
                <span>Reject Property</span>
            </button>
            <button onclick="flagForFraud()" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center space-x-2">
                <i class="fas fa-flag"></i>
                <span>Flag for Fraud</span>
            </button>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Property Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Property Images -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Property Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="relative group">
                        <img src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400&h=300&fit=crop"
                             alt="Property image" class="w-full h-40 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition rounded-lg flex items-center justify-center">
                            <button class="text-white text-sm"><i class="fas fa-search-plus"></i> Zoom</button>
                        </div>
                    </div>
                    <div class="relative group">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=300&fit=crop"
                             alt="Property image" class="w-full h-40 object-cover rounded-lg">
                    </div>
                    <div class="relative group">
                        <img src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400&h=300&fit=crop"
                             alt="Property image" class="w-full h-40 object-cover rounded-lg">
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <p class="text-sm text-gray-500">3 photos • 0 videos</p>
                </div>
            </div>

            <!-- Property Details -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Property Details</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Property Title</label>
                            <p class="text-gray-800 font-medium">Luxury Beach Villa</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Property Type</label>
                            <p class="text-gray-800">Villa</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Location</label>
                            <p class="text-gray-800">Diani Beach, Kenya</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Price per Night</label>
                            <p class="text-gray-800 font-semibold text-lg">$15,000</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Bedrooms</label>
                            <p class="text-gray-800">4</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Bathrooms</label>
                            <p class="text-gray-800">3</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500 uppercase">Description</label>
                        <p class="text-gray-600 text-sm mt-1">Beautiful beachfront villa with ocean views, private pool, and direct beach access. Perfect for family vacations and romantic getaways.</p>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500 uppercase">Amenities</label>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">WiFi</span>
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Swimming Pool</span>
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Air Conditioning</span>
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Free Parking</span>
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Beach Access</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Owner Information -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Owner Information</h3>
                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Owner Name</label>
                                <p class="text-gray-800 font-medium">Sarah Johnson</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Email</label>
                                <p class="text-gray-800">sarah@example.com</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Phone</label>
                                <p class="text-gray-800">+254 712 345 678</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase">KYC Status</label>
                                <p class="text-green-600"><i class="fas fa-check-circle"></i> Verified</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Member Since</label>
                                <p class="text-gray-800">January 2023</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Listings</label>
                                <p class="text-gray-800">3 properties</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Risk Assessment & Actions -->
        <div class="space-y-6">
            <!-- Risk Score Card -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Risk Assessment</h3>
                <div class="text-center mb-4">
                    <div class="relative inline-flex items-center justify-center">
                        <svg class="w-32 h-32">
                            <circle class="text-gray-200" stroke-width="8" stroke="currentColor" fill="transparent" r="58" cx="64" cy="64"/>
                            <circle class="text-green-500" stroke-width="8" stroke="currentColor" fill="transparent" r="58" cx="64" cy="64"
                                    stroke-dasharray="364.4" stroke-dashoffset="273.3" stroke-linecap="round"/>
                        </svg>
                        <div class="absolute text-center">
                            <div class="text-3xl font-bold text-gray-800">25%</div>
                            <div class="text-xs text-gray-500">Risk Score</div>
                        </div>
                    </div>
                    <p class="text-sm text-green-600 mt-2">Low Risk - Recommended for approval</p>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Owner Reputation</span>
                        <span class="font-medium">85/100</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Property Completeness</span>
                        <span class="font-medium">90/100</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Photo Quality</span>
                        <span class="font-medium">88/100</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Price Consistency</span>
                        <span class="font-medium">72/100</span>
                    </div>
                </div>
            </div>

            <!-- Similar Properties -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Similar Properties</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Ocean Paradise Villa</p>
                            <p class="text-xs text-gray-500">$12,000/night • 4.8★</p>
                        </div>
                        <span class="text-xs text-gray-500">45 bookings</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Beachfront Resort</p>
                            <p class="text-xs text-gray-500">$18,000/night • 4.9★</p>
                        </div>
                        <span class="text-xs text-gray-500">67 bookings</span>
                    </div>
                </div>
            </div>

            <!-- Moderation Notes -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Moderation Notes</h3>
                <textarea rows="4" class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:outline-none focus:border-blue-400"
                          placeholder="Add internal moderation notes..."></textarea>
                <div class="mt-3">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm text-gray-600">Mark as featured listing</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Reject Property</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                    <select id="rejectReason" class="w-full border border-gray-300 rounded-lg p-2">
                        <option value="">Select reason...</option>
                        <option value="fake_property">Fake Property</option>
                        <option value="poor_quality">Poor Quality Images</option>
                        <option value="invalid_ownership">Invalid Ownership</option>
                        <option value="policy_violation">Policy Violation</option>
                        <option value="incorrect_pricing">Incorrect Pricing</option>
                        <option value="missing_info">Missing Information</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Details</label>
                    <textarea id="rejectDetails" rows="3" class="w-full border border-gray-300 rounded-lg p-2"
                              placeholder="Provide specific feedback for the owner..."></textarea>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="allowResubmission" class="rounded border-gray-300">
                    <label class="text-sm text-gray-600">Allow resubmission after corrections</label>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button onclick="confirmReject()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirm Rejection</button>
            </div>
        </div>
    </div>
</div>

<script>
    function approveProperty() {
        if (confirm('Are you sure you want to approve this property? It will become visible to customers immediately.')) {
            // Show loading state
            showLoading();

            // Make API call to approve
            fetch('{{ route("admin.property.approve", $property['id'] ?? 1) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ moderators_notes: document.querySelector('textarea').value })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    window.location.href = '{{ route("admin.pending") }}';
                } else {
                    alert('Failed to approve property: ' + data.message);
                }
            })
            .catch(error => {
                hideLoading();
                alert('Error approving property');
            });
        }
    }

    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }

    function confirmReject() {
        const reason = document.getElementById('rejectReason').value;
        const details = document.getElementById('rejectDetails').value;

        if (!reason) {
            alert('Please select a rejection reason');
            return;
        }

        if (!details) {
            alert('Please provide additional details for the owner');
            return;
        }

        showLoading();

        fetch('{{ route("admin.property.reject", $property['id'] ?? 1) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                reason: reason,
                details: details,
                allow_resubmission: document.getElementById('allowResubmission').checked
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                window.location.href = '{{ route("admin.pending") }}';
            } else {
                alert('Failed to reject property: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            alert('Error rejecting property');
        });
    }

    function flagForFraud() {
        if (confirm('Flag this property for fraud investigation? This will escalate to the fraud team.')) {
            showLoading();

            fetch('{{ route("admin.property.suspend", $property['id'] ?? 1) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reason: 'fraud_investigation' })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    alert('Property flagged for fraud investigation');
                    window.location.href = '{{ route("admin.pending") }}';
                } else {
                    alert('Failed to flag property');
                }
            })
            .catch(error => {
                hideLoading();
                alert('Error flagging property');
            });
        }
    }

    function showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        }
    }

    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }
    }
</script>
@endsection
