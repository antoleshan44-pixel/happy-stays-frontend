{{-- resources/views/customer/profile-management.blade.php --}}
@extends('layouts.app')

@section('title', 'Profile Management')

@section('content')
<div class="container-custom">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <aside class="w-full lg:w-1/3">
            <div class="bg-surface-card p-6 rounded-xl shadow-sm border border-border-color text-center lg:text-left">
                <div class="relative w-24 h-24 rounded-full border-4 border-brand-200 overflow-hidden mx-auto lg:mx-0 mb-4 bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center">
                    <span class="text-4xl text-white font-bold">
                        @php
                            $userName = is_object($user) ? ($user->name ?? 'G') : (is_array($user) ? ($user['name'] ?? 'G') : 'G');
                            echo substr($userName, 0, 1);
                        @endphp
                    </span>
                </div>
                <h2 class="text-xl font-bold text-text-primary mb-1 heading-font">
                    {{ is_object($user) ? ($user->name ?? 'Guest') : (is_array($user) ? ($user['name'] ?? 'Guest') : 'Guest') }}
                </h2>
                <p class="text-sm text-text-muted mb-4">
                    Member since
                    @php
                        $createdAt = is_object($user) ? ($user->created_at ?? '2024-01-01') : (is_array($user) ? ($user['created_at'] ?? '2024-01-01') : '2024-01-01');
                        echo \Carbon\Carbon::parse($createdAt)->format('Y');
                    @endphp
                </p>
                <div class="space-y-3">
                    <button onclick="showSection('personal')" class="w-full bg-brand-500 text-white py-2.5 rounded-xl font-semibold hover:bg-brand-600 transition">Edit Profile</button>
                    <button onclick="showSection('security')" class="w-full bg-white border border-border-color text-text-primary py-2.5 rounded-xl font-semibold hover:bg-gray-50 transition">Security Settings</button>
                </div>
            </div>
            <div class="bg-brand-50 p-4 rounded-xl border border-brand-100 mt-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-brand-600">verified</span>
                    <span class="text-sm font-semibold text-brand-700">Identity Verified</span>
                </div>
                <p class="text-sm text-text-secondary">Your account is fully verified, providing you access to premium bookings.</p>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="w-full lg:w-2/3 space-y-6">
            <!-- Personal Info Section -->
            <div id="personal-section" class="bg-surface-card rounded-xl shadow-sm border border-border-color overflow-hidden">
                <div class="p-4 border-b border-border-color bg-gray-50/50">
                    <h3 class="font-semibold text-text-primary heading-font">Personal Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-text-primary mb-1">Full Name</label>
                        <input type="text" id="profileName" value="{{ is_object($user) ? ($user->name ?? '') : (is_array($user) ? ($user['name'] ?? '') : '') }}"
                               class="w-full px-4 py-2 rounded-lg border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-primary mb-1">Email Address</label>
                        <input type="email" id="profileEmail" value="{{ is_object($user) ? ($user->email ?? '') : (is_array($user) ? ($user['email'] ?? '') : '') }}"
                               class="w-full px-4 py-2 rounded-lg border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-primary mb-1">Phone Number</label>
                        <input type="tel" id="profilePhone" value="{{ is_object($user) ? ($user->phone ?? '') : (is_array($user) ? ($user['phone'] ?? '') : '') }}"
                               class="w-full px-4 py-2 rounded-lg border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                    </div>
                    <div id="profileMessage" class="hidden p-3 rounded-lg"></div>
                    <button onclick="updateProfile()" class="bg-brand-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-brand-600 transition">Save Changes</button>
                </div>
            </div>

            <!-- Security Section -->
            <div id="security-section" class="hidden bg-surface-card rounded-xl shadow-sm border border-border-color overflow-hidden">
                <div class="p-4 border-b border-border-color bg-gray-50/50">
                    <h3 class="font-semibold text-text-primary heading-font">Security</h3>
                </div>
                <div class="divide-y divide-border-color">
                    <button onclick="showChangePassword()" class="w-full p-5 flex items-center justify-between hover:bg-gray-50 transition text-left">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-text-muted">lock</span>
                            <div>
                                <p class="font-medium text-text-primary">Change Password</p>
                                <p class="text-xs text-text-muted">Update your password</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-text-muted">chevron_right</span>
                    </button>
                    <button onclick="enable2FA()" class="w-full p-5 flex items-center justify-between hover:bg-gray-50 transition text-left">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-text-muted">vibration</span>
                            <div>
                                <p class="font-medium text-text-primary">Two-Factor Authentication</p>
                                <p class="text-xs text-text-muted">Add an extra layer of security</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-text-muted">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface-card rounded-xl p-6 max-w-md w-full mx-4 shadow-xl border border-border-color">
        <h3 class="text-xl font-bold text-text-primary mb-4 heading-font">Change Password</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-text-primary mb-1">Current Password</label>
                <input type="password" id="currentPassword" class="w-full px-4 py-2 rounded-lg border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-text-primary mb-1">New Password</label>
                <input type="password" id="newPassword" class="w-full px-4 py-2 rounded-lg border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-text-primary mb-1">Confirm Password</label>
                <input type="password" id="confirmPassword" class="w-full px-4 py-2 rounded-lg border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div id="passwordMessage" class="hidden p-3 rounded-lg"></div>
            <div class="flex gap-3">
                <button onclick="changePassword()" class="flex-1 bg-brand-500 text-white py-2 rounded-lg font-semibold hover:bg-brand-600 transition">Update Password</button>
                <button onclick="closePasswordModal()" class="flex-1 border border-border-color text-text-primary py-2 rounded-lg font-semibold hover:bg-gray-50 transition">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showSection(section) {
        if(section === 'personal') {
            document.getElementById('personal-section').classList.remove('hidden');
            document.getElementById('security-section').classList.add('hidden');
        } else {
            document.getElementById('personal-section').classList.add('hidden');
            document.getElementById('security-section').classList.remove('hidden');
        }
    }

    async function updateProfile() {
        let data = {
            name: document.getElementById('profileName').value,
            email: document.getElementById('profileEmail').value,
            phone: document.getElementById('profilePhone').value
        };

        const messageDiv = document.getElementById('profileMessage');

        try {
            const response = await fetch('{{ url("/api/user/profile") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                messageDiv.className = 'p-3 rounded-lg bg-green-50 text-success';
                messageDiv.textContent = 'Profile updated successfully!';
                messageDiv.classList.remove('hidden');
                setTimeout(() => messageDiv.classList.add('hidden'), 3000);
                setTimeout(() => location.reload(), 1500);
            } else {
                messageDiv.className = 'p-3 rounded-lg bg-red-50 text-error';
                messageDiv.textContent = result.message || 'Update failed. Please try again.';
                messageDiv.classList.remove('hidden');
                setTimeout(() => messageDiv.classList.add('hidden'), 3000);
            }
        } catch (error) {
            messageDiv.className = 'p-3 rounded-lg bg-red-50 text-error';
            messageDiv.textContent = 'Network error. Please try again.';
            messageDiv.classList.remove('hidden');
            setTimeout(() => messageDiv.classList.add('hidden'), 3000);
        }
    }

    function showChangePassword() {
        document.getElementById('passwordModal').classList.add('flex');
        document.getElementById('passwordModal').classList.remove('hidden');
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.add('hidden');
        document.getElementById('passwordModal').classList.remove('flex');
    }

    async function changePassword() {
        let current = document.getElementById('currentPassword').value;
        let newPwd = document.getElementById('newPassword').value;
        let confirm = document.getElementById('confirmPassword').value;

        const messageDiv = document.getElementById('passwordMessage');

        if(newPwd !== confirm) {
            messageDiv.className = 'p-3 rounded-lg bg-red-50 text-error';
            messageDiv.textContent = 'Passwords do not match!';
            messageDiv.classList.remove('hidden');
            setTimeout(() => messageDiv.classList.add('hidden'), 3000);
            return;
        }

        if(newPwd.length < 6) {
            messageDiv.className = 'p-3 rounded-lg bg-red-50 text-error';
            messageDiv.textContent = 'Password must be at least 6 characters!';
            messageDiv.classList.remove('hidden');
            setTimeout(() => messageDiv.classList.add('hidden'), 3000);
            return;
        }

        try {
            const response = await fetch('{{ url("/api/user/change-password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    current_password: current,
                    password: newPwd,
                    password_confirmation: confirm
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                messageDiv.className = 'p-3 rounded-lg bg-green-50 text-success';
                messageDiv.textContent = 'Password changed successfully!';
                messageDiv.classList.remove('hidden');
                setTimeout(() => {
                    messageDiv.classList.add('hidden');
                    closePasswordModal();
                    document.getElementById('currentPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                }, 2000);
            } else {
                messageDiv.className = 'p-3 rounded-lg bg-red-50 text-error';
                messageDiv.textContent = result.message || 'Password change failed. Please check your current password.';
                messageDiv.classList.remove('hidden');
                setTimeout(() => messageDiv.classList.add('hidden'), 3000);
            }
        } catch (error) {
            messageDiv.className = 'p-3 rounded-lg bg-red-50 text-error';
            messageDiv.textContent = 'Network error. Please try again.';
            messageDiv.classList.remove('hidden');
            setTimeout(() => messageDiv.classList.add('hidden'), 3000);
        }
    }

    function enable2FA() {
        alert('2FA setup would be configured here');
    }
</script>
@endsection
