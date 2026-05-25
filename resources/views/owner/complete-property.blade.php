{{-- resources/views/owner/complete-property.blade.php --}}
@extends('layouts.owner')

@section('title', 'Setup Complete')

@section('content')
<div class="container-custom py-12">

    {{-- Progress Steps --}}
    <div class="mb-10">
        <div class="flex items-center justify-between max-w-md mx-auto">
            <div class="text-center">
                <div class="w-10 h-10 bg-secondary text-white rounded-full flex items-center justify-center mx-auto mb-2 text-sm font-bold">
                    <span class="material-symbols-outlined text-sm">check</span>
                </div>
                <p class="text-sm text-on-surface-variant">Details</p>
            </div>
            <div class="flex-1 h-1 bg-secondary mx-2"></div>
            <div class="text-center">
                <div class="w-10 h-10 bg-secondary text-white rounded-full flex items-center justify-center mx-auto mb-2 text-sm font-bold">
                    <span class="material-symbols-outlined text-sm">check</span>
                </div>
                <p class="text-sm text-on-surface-variant">Photos & Video</p>
            </div>
            <div class="flex-1 h-1 bg-secondary mx-2"></div>
            <div class="text-center">
                <div class="w-10 h-10 bg-secondary text-white rounded-full flex items-center justify-center mx-auto mb-2 text-sm font-bold ring-4 ring-secondary/20">3</div>
                <p class="text-sm font-semibold text-secondary">Complete</p>
            </div>
        </div>
    </div>

    {{-- Success card --}}
    <div class="max-w-lg mx-auto bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant p-10 text-center">

        <div class="w-20 h-20 bg-success/10 rounded-full flex items-center justify-center mx-auto mb-5">
            <span class="material-symbols-outlined text-5xl text-success">check_circle</span>
        </div>

        <h1 class="text-2xl font-bold text-on-surface mb-2">Property Submitted!</h1>
        <p class="text-on-surface-variant mb-2">
            <strong class="text-on-surface">{{ $property['title'] ?? $property->title ?? 'Your property' }}</strong>
            has been submitted for admin review.
        </p>
        <p class="text-sm text-on-surface-variant mb-8">
            Our team will review your listing within 24–48 hours.
            You'll be notified once it's approved and visible to guests.
        </p>

        {{-- Photo / video summary --}}
        @php
            $photoCount = count($property['photos'] ?? []);
            $videoCount = count($property['videos'] ?? []);
        @endphp
        <div class="flex justify-center gap-6 mb-8">
            <div class="text-center">
                <p class="text-2xl font-bold text-secondary">{{ $photoCount }}</p>
                <p class="text-xs text-on-surface-variant">Photo(s)</p>
            </div>
            <div class="w-px bg-outline-variant"></div>
            <div class="text-center">
                <p class="text-2xl font-bold text-secondary">{{ $videoCount }}</p>
                <p class="text-xs text-on-surface-variant">Video(s)</p>
            </div>
            <div class="w-px bg-outline-variant"></div>
            <div class="text-center">
                <p class="text-2xl font-bold text-warning">Pending</p>
                <p class="text-xs text-on-surface-variant">Status</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('owner.properties') }}"
               class="flex-1 bg-secondary text-white py-3 rounded-xl font-semibold
                      hover:bg-secondary/90 transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">home</span>
                View My Properties
            </a>
            <a href="{{ route('owner.property.create') }}"
               class="flex-1 border border-outline-variant text-on-surface py-3 rounded-xl font-semibold
                      text-center hover:bg-surface-container transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">add</span>
                Add Another
            </a>
        </div>

    </div>
</div>
@endsection
