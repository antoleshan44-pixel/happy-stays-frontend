{{-- resources/views/owner/edit-property.blade.php --}}
@extends('layouts.owner')

@section('title', 'Edit Property')

@section('content')
<div class="container-custom py-6">

    {{-- Page header --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-2">Edit Property</h1>
        <p class="text-on-surface-variant">Update your property information</p>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-lg text-success text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-lg text-error text-sm">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-lg text-error text-sm">
            <p class="font-semibold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">

        {{-- Card header --}}
        <div class="bg-gradient-to-r from-secondary to-secondary/80 px-6 py-4">
            <h2 class="text-white font-semibold text-lg">
                {{ $property['title'] ?? 'Property' }}
            </h2>
            <p class="text-white/70 text-sm mt-0.5">
                {{ $property['location'] ?? '' }}
            </p>
        </div>

        <form action="{{ route('owner.property.update', $property['id']) }}"
              method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Property Title --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Property Title <span class="text-error">*</span>
                    </label>
                    <input type="text" name="title"
                           value="{{ old('title', $property['title'] ?? '') }}"
                           required maxlength="255"
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  @error('title') border-error @enderror"
                           placeholder="e.g., Luxury Beachfront Villa">
                    @error('title')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Location <span class="text-error">*</span>
                    </label>
                    <input type="text" name="location"
                           value="{{ old('location', $property['location'] ?? '') }}"
                           required
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  @error('location') border-error @enderror"
                           placeholder="e.g., Diani Beach, Westlands Nairobi">
                    @error('location')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Description <span class="text-error">*</span>
                    </label>
                    <textarea name="description" rows="5" required
                              class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                     focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                     @error('description') border-error @enderror"
                              placeholder="Describe your property... What makes it special?">{{ old('description', $property['description'] ?? '') }}</textarea>
                    @error('description')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Property Type --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Property Type <span class="text-error">*</span>
                    </label>
                    @php
                        $currentType = old('property_type', $property['propertyType'] ?? $property['property_type'] ?? '');
                    @endphp
                    <select name="property_type" required
                            class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                   focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                   @error('property_type') border-error @enderror">
                        <option value="">Select type…</option>
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type }}" {{ $currentType == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_type')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price per Night --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Price per Night (KES) <span class="text-error">*</span>
                    </label>
                    @php
                        $currentPrice = old('price_per_night', $property['pricePerNight'] ?? $property['price_per_night'] ?? 0);
                    @endphp
                    <input type="number" name="price_per_night"
                           value="{{ $currentPrice }}"
                           required min="0" step="100"
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  @error('price_per_night') border-error @enderror"
                           placeholder="15000">
                    @error('price_per_night')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bedrooms --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Bedrooms <span class="text-error">*</span>
                    </label>
                    @php
                        $currentBedrooms = old('bedrooms', $property['bedrooms'] ?? 1);
                    @endphp
                    <input type="number" name="bedrooms"
                           value="{{ $currentBedrooms }}"
                           required min="1" max="20"
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  @error('bedrooms') border-error @enderror">
                    @error('bedrooms')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bathrooms --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Bathrooms <span class="text-error">*</span>
                    </label>
                    @php
                        $currentBathrooms = old('bathrooms', $property['bathrooms'] ?? 1);
                    @endphp
                    <input type="number" name="bathrooms"
                           value="{{ $currentBathrooms }}"
                           required min="1" max="20"
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  @error('bathrooms') border-error @enderror">
                    @error('bathrooms')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>{{-- end grid --}}

            {{-- Amenities --}}
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Amenities</label>
                <p class="text-xs text-on-surface-variant mb-4">Select everything your property offers guests.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($amenitiesList as $amenity)
                        @php
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
                        @endphp
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-surface-container rounded-lg p-2 transition group">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                                   class="w-4 h-4 text-secondary rounded border-outline-variant focus:ring-secondary flex-shrink-0"
                                   {{ $isChecked ? 'checked' : '' }}>
                            <span class="text-sm text-on-surface">{{ $amenity }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Current Status (read-only) --}}
            <div class="bg-surface-container-low p-4 rounded-lg border border-outline-variant">
                @php
                    $status = strtolower($property['status'] ?? 'unknown');
                @endphp
                <p class="text-sm text-on-surface">
                    Current Status:
                    <span class="font-semibold
                        @if($status === 'approved') text-success
                        @elseif($status === 'pending') text-warning
                        @else text-error
                        @endif">
                        {{ ucfirst($status) }}
                    </span>
                </p>
                <p class="text-xs text-on-surface-variant mt-1">
                    Editing will not change your approval status.
                </p>
            </div>

            {{-- Action buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-secondary text-white py-2.5 rounded-lg font-semibold
                               hover:bg-secondary/90 active:scale-[0.98] transition">
                    Save Changes
                </button>
                <a href="{{ route('owner.property.photos', $property['id']) }}"
                   class="flex-1 border border-outline-variant text-on-surface py-2.5 rounded-lg
                          font-semibold text-center hover:bg-surface-container transition">
                    Manage Photos
                </a>
                <a href="{{ route('owner.properties') }}"
                   class="flex-1 border border-outline-variant text-on-surface-variant py-2.5 rounded-lg
                          font-semibold text-center hover:bg-surface-container transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
