{{-- resources/views/owner/create-property.blade.php --}}
@extends('layouts.owner')

@section('title', 'Add New Property')

@section('content')
<div class="container-custom py-6">

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-on-surface-variant mb-1">
            <a href="{{ route('owner.properties') }}" class="hover:text-secondary transition">My Properties</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span>Add New</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-1">List a New Property</h1>
        <p class="text-on-surface-variant text-sm">Fill in the details below — you'll add photos and videos in the next step.</p>
    </div>

    {{-- Progress Steps --}}
    <div class="mb-8">
        <div class="flex items-center justify-between max-w-md mx-auto">
            <div class="text-center">
                <div class="w-10 h-10 bg-secondary text-white rounded-full flex items-center justify-center mx-auto mb-2 text-sm font-bold ring-4 ring-secondary/20">1</div>
                <p class="text-sm font-semibold text-secondary">Details</p>
            </div>
            <div class="flex-1 h-1 bg-outline-variant mx-2"></div>
            <div class="text-center">
                <div class="w-10 h-10 bg-surface-container-high text-on-surface-variant rounded-full flex items-center justify-center mx-auto mb-2 text-sm font-bold">2</div>
                <p class="text-sm text-on-surface-variant">Photos & Video</p>
            </div>
            <div class="flex-1 h-1 bg-outline-variant mx-2"></div>
            <div class="text-center">
                <div class="w-10 h-10 bg-surface-container-high text-on-surface-variant rounded-full flex items-center justify-center mx-auto mb-2 text-sm font-bold">3</div>
                <p class="text-sm text-on-surface-variant">Complete</p>
            </div>
        </div>
    </div>

    {{-- Flash / Validation errors --}}
    @if(session('error'))
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-lg text-error text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-base">error</span>{{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-lg text-error text-sm">
            <p class="font-semibold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
        <div class="bg-gradient-to-r from-secondary to-secondary/80 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <span class="material-symbols-outlined">home_work</span>
                Property Information
            </h2>
        </div>

        <form action="{{ route('owner.property.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- ── Basic Info ── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Property Title <span class="text-error">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required maxlength="255"
                           class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                  @error('title') border-error @enderror"
                           placeholder="e.g., Luxury Beachfront Villa, Modern City Apartment">
                    @error('title')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Location --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Location <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg pointer-events-none">location_on</span>
                        <input type="text" name="location" value="{{ old('location') }}" required
                               class="w-full pl-9 pr-4 py-2.5 border border-outline-variant rounded-lg
                                      focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                      @error('location') border-error @enderror"
                               placeholder="e.g., Diani Beach, Westlands Nairobi, Kilifi">
                    </div>
                    @error('location')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-on-surface">
                            Description <span class="text-error">*</span>
                        </label>
                        <span id="descCount" class="text-xs text-on-surface-variant">0 / 2000</span>
                    </div>
                    <textarea name="description" rows="5" required maxlength="2000"
                              oninput="document.getElementById('descCount').textContent = this.value.length + ' / 2000'"
                              class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                     focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                     @error('description') border-error @enderror"
                              placeholder="Describe your property… What makes it special? Mention views, surroundings, and unique features.">{{ old('description') }}</textarea>
                    @error('description')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Property Type --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Property Type <span class="text-error">*</span>
                    </label>
                    <select name="property_type" required
                            class="w-full px-4 py-2.5 border border-outline-variant rounded-lg
                                   focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                   @error('property_type') border-error @enderror">
                        <option value="">Select type…</option>
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type }}" {{ old('property_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_type')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Price per Night --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Price per Night (KES) <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-on-surface-variant pointer-events-none">KES</span>
                        <input type="number" name="price_per_night" value="{{ old('price_per_night') }}"
                               required min="0" step="100"
                               class="w-full pl-11 pr-4 py-2.5 border border-outline-variant rounded-lg
                                      focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                      @error('price_per_night') border-error @enderror"
                               placeholder="15000">
                    </div>
                    @error('price_per_night')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Bedrooms --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Bedrooms <span class="text-error">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="stepInput('bedrooms',-1)"
                                class="w-10 h-10 rounded-lg border border-outline-variant flex items-center justify-center
                                       hover:bg-surface-container transition font-bold text-lg text-on-surface-variant flex-shrink-0">−</button>
                        <input type="number" id="bedrooms" name="bedrooms"
                               value="{{ old('bedrooms', 1) }}" required min="1" max="20"
                               class="w-full px-4 py-2.5 border border-outline-variant rounded-lg text-center
                                      focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                      @error('bedrooms') border-error @enderror">
                        <button type="button" onclick="stepInput('bedrooms',1)"
                                class="w-10 h-10 rounded-lg border border-outline-variant flex items-center justify-center
                                       hover:bg-surface-container transition font-bold text-lg text-on-surface-variant flex-shrink-0">+</button>
                    </div>
                    @error('bedrooms')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Bathrooms --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2">
                        Bathrooms <span class="text-error">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="stepInput('bathrooms',-1)"
                                class="w-10 h-10 rounded-lg border border-outline-variant flex items-center justify-center
                                       hover:bg-surface-container transition font-bold text-lg text-on-surface-variant flex-shrink-0">−</button>
                        <input type="number" id="bathrooms" name="bathrooms"
                               value="{{ old('bathrooms', 1) }}" required min="1" max="20"
                               class="w-full px-4 py-2.5 border border-outline-variant rounded-lg text-center
                                      focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition
                                      @error('bathrooms') border-error @enderror">
                        <button type="button" onclick="stepInput('bathrooms',1)"
                                class="w-10 h-10 rounded-lg border border-outline-variant flex items-center justify-center
                                       hover:bg-surface-container transition font-bold text-lg text-on-surface-variant flex-shrink-0">+</button>
                    </div>
                    @error('bathrooms')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

            </div>

            {{-- ── Amenities ── --}}
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Amenities</label>
                <p class="text-xs text-on-surface-variant mb-4">Select everything your property offers guests.</p>

                @php
                $amenityGroups = [
                    'Security & Safety'  => ['24/7 Security Guard','CCTV Cameras','Secure Parking','Backup Generator'],
                    'Entertainment'      => ['Free High-Speed WiFi','55" Smart TV / Netflix','Sound System'],
                    'Kitchen'            => ['Fully Equipped Kitchen','Microwave & Oven','Coffee/Tea Maker','Dishwasher'],
                    'Views & Outdoors'   => ['Stunning City View','Ocean/Mountain View','Private Balcony','Garden/Patio'],
                    'Fitness & Wellness' => ['Fully Equipped Gym','Swimming Pool','Spa/Hot Tub'],
                    'Laundry'            => ['Washing Machine','Dryer','Free Housekeeping Service'],
                    'Bathroom'           => ['Rejuvenating Hot Shower','Fresh Clean Water','Hair Dryer','Bath Towels & Toiletries'],
                    'Extras'             => ['Welcome Drinks','Fresh Flowers in Room','Airport Pickup (Extra)','Tour Desk / Concierge'],
                ];
                @endphp

                <div class="space-y-4">
                    @foreach($amenityGroups as $groupName => $groupItems)
                        @php $visible = array_intersect($groupItems, $amenitiesList); @endphp
                        @if(count($visible))
                        <div>
                            <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest mb-2">{{ $groupName }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                                @foreach($visible as $amenity)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                                           {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-secondary rounded border-outline-variant focus:ring-secondary flex-shrink-0">
                                    <span class="text-sm text-on-surface group-hover:text-secondary transition">{{ $amenity }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- ── Submit ── --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-secondary text-white py-3 rounded-lg font-semibold
                               hover:bg-secondary/90 active:scale-[0.98] transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                    Continue to Photo Upload
                </button>
                <a href="{{ route('owner.properties') }}"
                   class="flex-1 border border-outline-variant text-on-surface-variant py-3 rounded-lg
                          font-semibold text-center hover:bg-surface-container transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<script>
function stepInput(id, delta) {
    const el  = document.getElementById(id);
    const min = parseInt(el.min) || 1;
    const max = parseInt(el.max) || 99;
    el.value  = Math.min(max, Math.max(min, (parseInt(el.value) || 1) + delta));
}
</script>
@endsection
