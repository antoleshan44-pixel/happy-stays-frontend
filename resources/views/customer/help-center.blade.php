{{-- resources/views/customer/help-center.blade.php --}}
@extends('layouts.app')

@section('title', 'Help Center')

@section('content')
<div class="container-custom max-w-4xl mx-auto text-center">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-text-primary heading-font mb-4">How can we help?</h1>
        <p class="text-text-secondary">Find answers to common questions or contact our support team</p>
    </div>

    <!-- Search Bar -->
    <div class="relative max-w-2xl mx-auto mb-12">
        <div class="flex items-center bg-surface-card rounded-xl shadow-sm border border-border-color p-2">
            <span class="material-symbols-outlined ml-4 text-text-muted">search</span>
            <input class="w-full bg-transparent border-none focus:ring-0 text-text-primary py-3 px-4 placeholder:text-text-muted"
                   placeholder="Search for help topics..." type="text" id="helpSearch">
            <button class="bg-brand-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-brand-600 transition">
                Search
            </button>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="group bg-surface-card p-6 rounded-xl shadow-sm border border-border-color hover:shadow-md transition cursor-pointer hover:border-brand-200">
            <div class="w-12 h-12 rounded-lg bg-brand-100 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-brand-600">event_available</span>
            </div>
            <h3 class="font-semibold text-text-primary mb-2 heading-font">Booking</h3>
            <p class="text-text-muted text-sm mb-4">Cancellations, date changes, and guest requirements.</p>
            <div class="flex items-center text-brand-600 text-sm font-semibold">
                <span>View articles</span>
                <span class="material-symbols-outlined text-sm ml-1">chevron_right</span>
            </div>
        </div>

        <div class="group bg-surface-card p-6 rounded-xl shadow-sm border border-border-color hover:shadow-md transition cursor-pointer hover:border-brand-200">
            <div class="w-12 h-12 rounded-lg bg-brand-100 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-brand-600">payments</span>
            </div>
            <h3 class="font-semibold text-text-primary mb-2 heading-font">Payments</h3>
            <p class="text-text-muted text-sm mb-4">Refunds, pricing details, and billing information.</p>
            <div class="flex items-center text-brand-600 text-sm font-semibold">
                <span>View articles</span>
                <span class="material-symbols-outlined text-sm ml-1">chevron_right</span>
            </div>
        </div>

        <div class="group bg-surface-card p-6 rounded-xl shadow-sm border border-border-color hover:shadow-md transition cursor-pointer hover:border-brand-200">
            <div class="w-12 h-12 rounded-lg bg-brand-100 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-brand-600">home_work</span>
            </div>
            <h3 class="font-semibold text-text-primary mb-2 heading-font">Hosting</h3>
            <p class="text-text-muted text-sm mb-4">Managing listings, reviews, and host tools.</p>
            <div class="flex items-center text-brand-600 text-sm font-semibold">
                <span>View articles</span>
                <span class="material-symbols-outlined text-sm ml-1">chevron_right</span>
            </div>
        </div>

        <div class="group bg-surface-card p-6 rounded-xl shadow-sm border border-border-color hover:shadow-md transition cursor-pointer hover:border-brand-200">
            <div class="w-12 h-12 rounded-lg bg-brand-100 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-brand-600">verified_user</span>
            </div>
            <h3 class="font-semibold text-text-primary mb-2 heading-font">Safety</h3>
            <p class="text-text-muted text-sm mb-4">Account security, emergency help, and trust.</p>
            <div class="flex items-center text-brand-600 text-sm font-semibold">
                <span>View articles</span>
                <span class="material-symbols-outlined text-sm ml-1">chevron_right</span>
            </div>
        </div>
    </div>

    <!-- Contact Support & Report Problem -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Contact Support -->
        <div class="bg-surface-card rounded-xl p-6 shadow-sm border border-border-color text-left">
            <h2 class="font-semibold text-text-primary text-lg mb-4 heading-font">Contact Support</h2>
            <p class="text-text-muted text-sm mb-6">Our dedicated team is here to help you 24/7 with any inquiries or issues you might have.</p>
            <div class="space-y-3">
                <button class="w-full flex items-center justify-between p-4 rounded-xl bg-brand-500 text-white hover:bg-brand-600 transition">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined">chat_bubble</span>
                        <div class="text-left">
                            <span class="block text-sm font-semibold">Live Chat</span>
                            <span class="block text-xs opacity-80">Wait time: ~2 mins</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </button>
                <button class="w-full flex items-center justify-between p-4 rounded-xl bg-gray-50 text-text-primary border border-border-color hover:bg-gray-100 transition">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined">mail</span>
                        <div class="text-left">
                            <span class="block text-sm font-semibold">Email Support</span>
                            <span class="block text-xs text-text-muted">Response in 24 hours</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-text-muted">arrow_forward_ios</span>
                </button>
            </div>
        </div>

        <!-- Report Problem -->
        <div class="bg-surface-card rounded-xl p-6 shadow-sm border border-border-color text-left">
            <h2 class="font-semibold text-text-primary text-lg mb-4 heading-font">Report a Problem</h2>
            <p class="text-text-muted text-sm mb-6">Encountered a bug or a property issue? Let us know so we can improve your experience.</p>
            <form class="space-y-4" id="reportForm">
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Category</label>
                    <select class="w-full bg-gray-50 border border-border-color rounded-lg p-3 text-text-primary focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option>Technical Glitch</option>
                        <option>Property Listing Error</option>
                        <option>Account Access</option>
                        <option>Payment Issue</option>
                        <option>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Description</label>
                    <textarea class="w-full bg-gray-50 border border-border-color rounded-lg p-3 text-text-primary focus:outline-none focus:ring-2 focus:ring-brand-500"
                              placeholder="Tell us what happened..." rows="3" id="reportDescription"></textarea>
                </div>
                <button type="button" onclick="submitReport()" class="w-full bg-brand-500 text-white py-3 rounded-lg font-semibold hover:bg-brand-600 transition">
                    Submit Report
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function submitReport() {
        const description = document.getElementById('reportDescription').value;
        if (!description.trim()) {
            alert('Please describe the problem');
            return;
        }
        alert('Thank you for your report. Our team will review it shortly.');
        document.getElementById('reportForm').reset();
    }
</script>
@endsection
