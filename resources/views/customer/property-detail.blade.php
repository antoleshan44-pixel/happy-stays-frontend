{{-- resources/views/customer/property-detail.blade.php --}}
@extends('layouts.app')

@section('title', is_object($property) ? ($property->title ?? 'Property Details') : ($property['title'] ?? 'Property Details'))

@section('styles')
<style>
    .gallery-image {
        transition: transform 0.3s ease;
    }
    .gallery-image:hover {
        transform: scale(1.02);
    }
    .thumbnail-gallery {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    .thumbnail-item {
        aspect-ratio: 1/1;
        overflow: hidden;
        border-radius: 0.5rem;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
    }
    .thumbnail-item.active {
        border-color: var(--brand);
    }
    .thumbnail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .main-image-container {
        width: 100%;
        aspect-ratio: 16/9;
        overflow: hidden;
        border-radius: 1rem;
        background: #f3f4f6;
        position: relative;
    }
    .main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .video-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0,0,0,0.7);
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
    }
    .video-icon:hover {
        background: rgba(0,0,0,0.9);
        transform: translate(-50%, -50%) scale(1.1);
    }
    .video-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.95);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .video-modal video {
        max-width: 90%;
        max-height: 90%;
        cursor: default;
    }
    .close-video {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
@php
    // ============================================
    // NORMALIZE PROPERTY DATA - Handle object & array formats
    // ============================================

    // Basic property info
    $propId = is_object($property) ? ($property->id ?? 0) : ($property['id'] ?? 0);
    $propTitle = is_object($property) ? ($property->title ?? 'Untitled') : ($property['title'] ?? 'Untitled');
    $propLocation = is_object($property) ? ($property->location ?? '') : ($property['location'] ?? '');
    $propRating = is_object($property) ? ($property->averageRating ?? 4.5) : ($property['averageRating'] ?? 4.5);
    $propOwner = is_object($property) ? ($property->owner_name ?? $property->ownerName ?? 'Eserian Host') : ($property['owner_name'] ?? $property['ownerName'] ?? 'Eserian Host');
    $propPrice = is_object($property) ? ($property->price_per_night ?? $property->pricePerNight ?? 0) : ($property['price_per_night'] ?? $property['pricePerNight'] ?? 0);
    $propDesc = is_object($property) ? ($property->description ?? '') : ($property['description'] ?? '');
    $propType = is_object($property) ? ($property->property_type ?? $property->propertyType ?? 'Property') : ($property['property_type'] ?? $property['propertyType'] ?? 'Property');

    // ============================================
    // HANDLE PHOTOS
    // ============================================

    $photos = [];
    if (is_object($property) && isset($property->photos) && is_array($property->photos)) {
        $photos = $property->photos;
    } elseif (is_array($property) && isset($property['photos']) && is_array($property['photos'])) {
        $photos = $property['photos'];
    }

    $getPhotoPath = function($photo) {
        if (is_object($photo)) {
            return $photo->photo_path ?? $photo->photoPath ?? '';
        }
        return $photo['photo_path'] ?? $photo['photoPath'] ?? '';
    };

    $getIsPrimary = function($photo) {
        if (is_object($photo)) {
            return $photo->is_primary ?? $photo->isPrimary ?? false;
        }
        return $photo['is_primary'] ?? $photo['isPrimary'] ?? false;
    };

    // Sort photos - primary first
    usort($photos, function($a, $b) use ($getIsPrimary) {
        $aPrimary = $getIsPrimary($a) ? 0 : 1;
        $bPrimary = $getIsPrimary($b) ? 0 : 1;
        return $aPrimary - $bPrimary;
    });

    $photoCount = count($photos);
    $mainPhoto = $photoCount > 0 ? $getPhotoPath($photos[0]) : null;

    // ============================================
    // HANDLE VIDEOS
    // ============================================

    $videos = [];
    if (is_object($property) && isset($property->videos) && is_array($property->videos)) {
        $videos = $property->videos;
    } elseif (is_array($property) && isset($property['videos']) && is_array($property['videos'])) {
        $videos = $property['videos'];
    }

    $getVideoPath = function($video) {
        if (is_object($video)) {
            return $video->video_path ?? $video->videoPath ?? '';
        }
        return $video['video_path'] ?? $video['videoPath'] ?? '';
    };

    $videoCount = count($videos);
    $featuredVideo = $videoCount > 0 ? $getVideoPath($videos[0]) : null;

    // ============================================
    // HANDLE AMENITIES
    // ============================================

    $amenitiesList = [];
    if (is_object($property) && isset($property->amenities)) {
        $amenities = $property->amenities;
        if (is_array($amenities)) {
            $amenitiesList = $amenities;
        } elseif (is_string($amenities)) {
            $amenitiesList = json_decode($amenities, true) ?? [];
            if (empty($amenitiesList)) {
                $amenitiesList = array_map('trim', explode(',', $amenities));
            }
        }
    } elseif (is_array($property) && isset($property['amenities'])) {
        $amenities = $property['amenities'];
        if (is_array($amenities)) {
            $amenitiesList = $amenities;
        } elseif (is_string($amenities)) {
            $amenitiesList = json_decode($amenities, true) ?? [];
            if (empty($amenitiesList)) {
                $amenitiesList = array_map('trim', explode(',', $amenities));
            }
        }
    }

    if (empty($amenitiesList)) {
        $amenitiesList = ['Fast WiFi', 'Kitchen', 'Free Parking', 'Air Conditioning', 'TV', 'Washer'];
    }
@endphp

<div class="pb-24 md:pb-12">
    <!-- Gallery Section -->
    <section class="mb-8">
        <div class="container-custom">
            @if($photoCount > 0)
                <div class="main-image-container">
                    <img id="mainDisplayImage"
                         class="main-image gallery-image"
                         src="{{ $mainPhoto ? (str_starts_with($mainPhoto, 'http') ? $mainPhoto : asset('storage/' . $mainPhoto)) : '' }}"
                         alt="{{ $propTitle }}"
                         onerror="this.src='https://placehold.co/1200x800/f3f4f6/9ca3af?text=No+Image'">
                    @if($videoCount > 0)
                        <div class="video-icon" onclick="playVideo()">
                            <span class="material-symbols-outlined" style="font-size: 40px; color: white;">play_circle</span>
                        </div>
                    @endif
                </div>

                @if($photoCount > 1)
                    <div class="thumbnail-gallery">
                        @foreach($photos as $index => $photo)
                            @php
                                $photoPath = '';
                                if (is_object($photo)) {
                                    $photoPath = $photo->photo_path ?? $photo->photoPath ?? '';
                                } else {
                                    $photoPath = $photo['photo_path'] ?? $photo['photoPath'] ?? '';
                                }
                            @endphp
                            @if($photoPath)
                                <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}"
                                     onclick="changeMainImage('{{ str_starts_with($photoPath, 'http') ? $photoPath : asset('storage/' . $photoPath) }}', this)">
                                    <img class="thumbnail-image"
                                         src="{{ str_starts_with($photoPath, 'http') ? $photoPath : asset('storage/' . $photoPath) }}"
                                         alt="Thumbnail {{ $index + 1 }}"
                                         onerror="this.src='https://placehold.co/100x100/f3f4f6/9ca3af?text=?'">
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            @else
                <div class="main-image-container bg-gray-100 flex items-center justify-center">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-5xl text-gray-300">photo_camera</span>
                        <p class="text-gray-400 mt-2">No photos available</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <div class="container-custom">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column -->
            <div class="flex-1">
                <!-- Title & Info -->
                <div class="border-b border-border-color pb-5 mb-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-2">{{ $propTitle }}</h1>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                                <span class="font-bold text-text-primary">{{ number_format($propRating, 1) }}</span>
                                <span class="text-text-muted">·</span>
                                <span class="text-brand-600 underline cursor-pointer">0 reviews</span>
                                <span class="text-text-muted">·</span>
                                <span class="text-text-secondary">{{ $propLocation }}</span>
                                @if($videoCount > 0)
                                    <span class="text-text-muted">·</span>
                                    <span class="flex items-center gap-1 text-text-muted">
                                        <span class="material-symbols-outlined text-sm">smart_display</span>
                                        {{ $videoCount }} video(s)
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="saveProperty({{ $propId }})" class="p-2 rounded-full hover:bg-gray-100 transition">
                                <span class="material-symbols-outlined text-text-muted">favorite_border</span>
                            </button>
                            <button onclick="shareProperty()" class="p-2 rounded-full hover:bg-gray-100 transition">
                                <span class="material-symbols-outlined text-text-muted">share</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Host Info -->
                <div class="py-5 border-b border-border-color">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="w-14 h-14 rounded-full bg-brand-500 flex items-center justify-center text-white text-xl font-semibold">
                                    {{ substr($propOwner, 0, 1) }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 bg-brand-500 text-white p-0.5 rounded-full border-2 border-white">
                                    <span class="material-symbols-outlined text-sm">verified</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-text-primary">Hosted by {{ $propOwner }}</h3>
                                <p class="text-sm text-text-muted">Superhost · 5 years hosting</p>
                            </div>
                        </div>
                        <button onclick="contactHost()" class="px-4 py-2 border border-border-color rounded-lg text-sm font-semibold text-text-secondary hover:bg-gray-50 transition">
                            Contact Host
                        </button>
                    </div>
                </div>

                <!-- Description -->
                <div class="py-5 border-b border-border-color">
                    <p class="text-text-secondary leading-relaxed">{{ $propDesc ?: 'No description provided.' }}</p>
                </div>

                <!-- Amenities -->
                <div class="py-5 border-b border-border-color">
                    <h3 class="font-bold text-text-primary text-lg mb-4 heading-font">What this place offers</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach(array_slice($amenitiesList, 0, 8) as $amenity)
                            <div class="flex items-center gap-3 text-text-secondary">
                                <span class="material-symbols-outlined text-brand-500 text-sm">check_circle</span>
                                {{ is_string($amenity) ? $amenity : ($amenity['name'] ?? 'Amenity') }}
                            </div>
                        @endforeach
                    </div>
                    @if(count($amenitiesList) > 8)
                        <button onclick="showAllAmenities()" class="mt-4 text-brand-600 text-sm font-semibold">
                            Show all {{ count($amenitiesList) }} amenities →
                        </button>
                    @endif
                </div>

                <!-- Reviews -->
                <div class="py-5">
                    <div class="flex items-center gap-4 mb-5">
                        <div>
                            <div class="text-3xl font-bold text-text-primary">{{ number_format($propRating, 1) }}</div>
                            <div class="flex text-yellow-400 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-sm">{{ $i <= $propRating ? 'star' : 'star_border' }}</span>
                                @endfor
                            </div>
                            <div class="text-sm text-text-muted mt-1">0 reviews</div>
                        </div>
                    </div>
                    <a href="{{ route('customer.property.reviews', $propId) }}" class="inline-flex items-center gap-2 text-brand-600 font-semibold text-sm">
                        Read all reviews
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Right Column - Booking Card -->
            <aside class="lg:w-[380px]">
                <div class="sticky top-24 p-5 bg-white rounded-xl shadow-lg border border-border-color">
                    <div class="flex justify-between items-baseline mb-5">
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold text-brand-600">KES {{ number_format($propPrice) }}</span>
                            <span class="text-text-muted">/ night</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                            <span class="font-bold text-text-primary">{{ number_format($propRating, 1) }}</span>
                        </div>
                    </div>

                    <div id="bookingError" class="hidden bg-red-50 border border-red-200 rounded-lg p-3 mb-4 text-error text-sm"></div>

                    <form method="POST" action="{{ route('customer.create.booking', $propId) }}" id="bookingForm">
                        @csrf
                        <div class="border border-border-color rounded-lg overflow-hidden mb-4">
                            <div class="grid grid-cols-2">
                                <div class="p-3 border-r border-border-color">
                                    <label class="block text-[10px] font-bold uppercase text-text-muted">Check-in</label>
                                    <input type="date" name="check_in_date" id="checkIn" class="w-full bg-transparent border-none p-0 text-sm text-text-primary focus:ring-0" required>
                                </div>
                                <div class="p-3">
                                    <label class="block text-[10px] font-bold uppercase text-text-muted">Checkout</label>
                                    <input type="date" name="check_out_date" id="checkOut" class="w-full bg-transparent border-none p-0 text-sm text-text-primary focus:ring-0" required>
                                </div>
                            </div>
                            <div class="p-3 border-t border-border-color">
                                <label class="block text-[10px] font-bold uppercase text-text-muted">Guests</label>
                                <input type="number" name="guests" id="guests" class="w-full bg-transparent border-none p-0 text-sm text-text-primary focus:ring-0" min="1" max="20" value="2" required>
                            </div>
                        </div>

                        <div id="priceBreakdown" class="space-y-2 text-sm text-text-secondary mb-4">
                            <div class="flex justify-between">
                                <span>KES {{ number_format($propPrice) }} x <span id="nightCount">0</span> nights</span>
                                <span id="subtotal">KES 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Cleaning fee</span>
                                <span>KES 2,500</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Service fee</span>
                                <span>KES 4,200</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Taxes</span>
                                <span>KES 1,800</span>
                            </div>
                            <div class="pt-3 mt-3 border-t border-border-color flex justify-between items-center">
                                <span class="font-bold text-text-primary">Total</span>
                                <span class="font-bold text-brand-600 text-lg" id="totalAmount">KES 0</span>
                            </div>
                        </div>

                        <button type="submit" id="reserveButton" class="w-full py-3 bg-brand-500 text-white rounded-lg font-semibold hover:bg-brand-600 transition mb-3">
                            Reserve
                        </button>
                        <p class="text-center text-text-muted text-xs">You won't be charged yet</p>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div id="videoModal" class="video-modal" style="display: none;" onclick="closeVideo()">
    <span class="close-video" onclick="closeVideo()">&times;</span>
    <video id="videoPlayer" controls onclick="event.stopPropagation()">
        <source id="videoSource" src="" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

<script>
    const pricePerNight = {{ $propPrice }};
    const propertyId = {{ $propId }};
    @if($featuredVideo)
        const videoUrl = '{{ str_starts_with($featuredVideo, 'http') ? $featuredVideo : asset('storage/' . $featuredVideo) }}';
    @else
        const videoUrl = '';
    @endif

    const today = new Date().toISOString().split('T')[0];
    const checkInInput = document.getElementById('checkIn');
    const checkOutInput = document.getElementById('checkOut');

    if (checkInInput) checkInInput.min = today;
    if (checkOutInput) checkOutInput.min = today;

    function calculateTotal() {
        let checkIn = document.getElementById('checkIn')?.value;
        let checkOut = document.getElementById('checkOut')?.value;

        if (checkIn && checkOut) {
            let start = new Date(checkIn);
            let end = new Date(checkOut);
            let nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

            if (nights > 0) {
                let subtotal = nights * pricePerNight;
                let total = subtotal + 2500 + 4200 + 1800;
                document.getElementById('nightCount').innerText = nights;
                document.getElementById('subtotal').innerText = 'KES ' + subtotal.toLocaleString();
                document.getElementById('totalAmount').innerHTML = 'KES ' + total.toLocaleString();
            }
        }
    }

    function changeMainImage(imageUrl, element) {
        const mainImage = document.getElementById('mainDisplayImage');
        if (mainImage) {
            mainImage.src = imageUrl;
        }
        document.querySelectorAll('.thumbnail-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');
    }

    function playVideo() {
        if (videoUrl) {
            const modal = document.getElementById('videoModal');
            const videoPlayer = document.getElementById('videoPlayer');
            const videoSource = document.getElementById('videoSource');

            videoSource.src = videoUrl;
            videoPlayer.load();
            modal.style.display = 'flex';
            videoPlayer.play();
        } else {
            showToast('No video available for this property', true);
        }
    }

    function closeVideo() {
        const modal = document.getElementById('videoModal');
        const videoPlayer = document.getElementById('videoPlayer');
        if (videoPlayer) videoPlayer.pause();
        if (modal) modal.style.display = 'none';
    }

    document.getElementById('checkIn')?.addEventListener('change', calculateTotal);
    document.getElementById('checkOut')?.addEventListener('change', calculateTotal);
    document.getElementById('guests')?.addEventListener('change', calculateTotal);

    function saveProperty(id) {
        fetch('{{ url("/customer/property") }}/' + id + '/save', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'saved') {
                showToast('❤️ Property saved to wishlist!');
            } else if (data.status === 'already_saved') {
                showToast('Property already in your wishlist');
            } else {
                showToast('Could not save property', true);
            }
        })
        .catch(() => showToast('Connection error', true));
    }

    function shareProperty() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $propTitle }}',
                text: 'Check out this amazing property on Eserian Homes!',
                url: window.location.href
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href);
            showToast('Link copied to clipboard!');
        }
    }

    function contactHost() {
        window.location.href = '{{ route("customer.messages") }}?host_id={{ $propId }}';
    }

    function showAllAmenities() {
        const amenitiesList = @json($amenitiesList);
        let modalHtml = `
            <div id="amenitiesModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl max-w-md w-full max-h-[80vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white p-4 border-b border-border-color flex justify-between items-center">
                        <h3 class="font-bold text-lg">All Amenities</h3>
                        <button onclick="closeAmenitiesModal()" class="p-1 rounded-full hover:bg-gray-100">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-3">
        `;
        amenitiesList.forEach(amenity => {
            modalHtml += `
                <div class="flex items-center gap-2 text-text-secondary">
                    <span class="material-symbols-outlined text-brand-500 text-sm">check_circle</span>
                    <span>${typeof amenity === 'string' ? amenity : (amenity.name || 'Amenity')}</span>
                </div>
            `;
        });
        modalHtml += `
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        document.body.style.overflow = 'hidden';
    }

    function closeAmenitiesModal() {
        const modal = document.getElementById('amenitiesModal');
        if (modal) {
            modal.remove();
            document.body.style.overflow = '';
        }
    }

    function showToast(message, isError = false) {
        let toast = document.getElementById('customToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'customToast';
            toast.style.cssText = `
                position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%);
                background: ${isError ? '#be123c' : '#111827'}; color: white;
                padding: 0.75rem 1.5rem; border-radius: 9999px; font-size: 0.875rem;
                font-weight: 500; z-index: 9999; opacity: 0; transition: opacity 0.3s;
                pointer-events: none; white-space: nowrap;
            `;
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.style.backgroundColor = isError ? '#be123c' : '#111827';
        toast.style.opacity = '1';
        setTimeout(() => {
            toast.style.opacity = '0';
        }, 3000);
    }

    function showError(message) {
        const errorDiv = document.getElementById('bookingError');
        if (errorDiv) {
            errorDiv.innerText = message;
            errorDiv.classList.remove('hidden');
            setTimeout(() => errorDiv.classList.add('hidden'), 5000);
        }
    }

    function validateForm() {
        const checkIn = document.getElementById('checkIn')?.value;
        const checkOut = document.getElementById('checkOut')?.value;
        const guests = document.getElementById('guests')?.value;

        if (!checkIn) { showError('Please select a check-in date'); return false; }
        if (!checkOut) { showError('Please select a check-out date'); return false; }

        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        const todayDate = new Date();
        todayDate.setHours(0, 0, 0, 0);

        if (checkInDate < todayDate) { showError('Check-in date cannot be in the past'); return false; }
        if (checkOutDate <= checkInDate) { showError('Check-out date must be after check-in date'); return false; }
        if (!guests || guests < 1) { showError('Please enter number of guests'); return false; }
        if (guests > 20) { showError('Maximum 20 guests allowed'); return false; }

        return true;
    }

    async function submitBookingForm() {
        if (!validateForm()) return;

        const form = document.getElementById('bookingForm');
        const submitButton = document.getElementById('reserveButton');
        const originalText = submitButton.innerHTML;

        submitButton.innerHTML = '<div class="flex items-center justify-center gap-2"><div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Processing...</div>';
        submitButton.disabled = true;

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            if (response.redirected) {
                window.location.href = response.url;
            } else if (response.ok) {
                const data = await response.json();
                if (data.redirect) window.location.href = data.redirect;
                else if (data.booking_id || data.id) window.location.href = '{{ url("/payment/mpesa") }}/' + (data.booking_id || data.id);
                else if (data.url) window.location.href = data.url;
                else window.location.href = '{{ route("customer.bookings") }}';
            } else {
                const errorData = await response.json().catch(() => ({}));
                showError(errorData.message || errorData.error || 'Failed to create booking. Please try again.');
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        } catch (error) {
            showError('Network error. Please check your connection and try again.');
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    }

    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            await submitBookingForm();
        });
    }
</script>
@endsection
