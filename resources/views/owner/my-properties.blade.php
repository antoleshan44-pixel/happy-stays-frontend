{{-- resources/views/owner/my-properties.blade.php --}}
@extends('layouts.owner')

@section('title', 'My Properties')

@section('styles')
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }
    .property-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .property-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }
    .delete-btn {
        transition: all 0.2s ease;
    }
    .delete-btn:hover {
        transform: scale(1.05);
        background-color: #dc2626 !important;
        color: white !important;
    }
    .video-badge {
        position: absolute;
        bottom: 8px;
        left: 8px;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 4px;
        z-index: 10;
    }
    .video-badge .material-symbols-outlined {
        font-size: 14px;
    }
</style>
@endsection

@section('content')
<div class="container-custom py-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-1">My Properties</h1>
            <p class="text-on-surface-variant">Managing {{ $properties->count() }} properties in your portfolio</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('owner.earnings.export') }}" class="px-4 py-2 border border-outline-variant rounded-lg text-sm font-semibold text-on-surface hover:bg-surface-container transition">
                Download Report
            </a>
            <a href="{{ route('owner.property.create') }}" class="px-4 py-2 bg-secondary text-white rounded-lg text-sm font-semibold hover:bg-secondary/90 transition">
                + Add New Property
            </a>
        </div>
    </div>

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

    @if($properties->count() > 0)
        <div class="space-y-6">
            @foreach($properties as $index => $property)
                <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant property-card">
                    <div class="flex flex-col md:flex-row">
                        <!-- Property Image -->
                        <div class="md:w-64 h-48 md:h-auto relative overflow-hidden bg-surface-container">
                            @php
                                $firstPhoto = null;
                                $photoPath = null;
                                if(isset($property->photos) && is_array($property->photos) && count($property->photos) > 0) {
                                    $firstPhoto = $property->photos[0];
                                    $photoPath = $firstPhoto->photo_path ?? null;
                                }
                            @endphp
                            @if($photoPath)
                                <img src="{{ spring_boot_url($photoPath) }}"
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.src='https://placehold.co/400x400?text=Image+Not+Found'">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-on-surface-variant">home</span>
                                </div>
                            @endif

                            {{-- VIDEO BADGE - Shows if property has video (either legacy video_path or videos array) --}}
                            @php
                                $hasVideo = (isset($property->video_path) && $property->video_path) || (isset($property->videos) && count($property->videos) > 0);
                            @endphp
                            @if($hasVideo)
                                <div class="video-badge">
                                    <span class="material-symbols-outlined">play_circle</span>
                                    <span>Video</span>
                                </div>
                            @endif

                            <div class="absolute top-2 left-2">
                                @if(isset($property->status) && strtolower($property->status) == 'approved')
                                    <span class="bg-success text-white px-2 py-0.5 rounded text-xs font-semibold">Approved</span>
                                @elseif(isset($property->status) && strtolower($property->status) == 'pending')
                                    <span class="bg-warning text-white px-2 py-0.5 rounded text-xs font-semibold">Pending</span>
                                @else
                                    <span class="bg-error text-white px-2 py-0.5 rounded text-xs font-semibold">{{ ucfirst($property->status ?? 'Unknown') }}</span>
                                @endif
                            </div>

                            <button onclick="openGallery({{ $property->id }})" class="absolute bottom-2 right-2 bg-black/50 text-white p-1.5 rounded-full text-xs hover:bg-black/70 transition">
                                <span class="material-symbols-outlined text-sm">image</span>
                            </button>
                        </div>

                        <!-- Property Details -->
                        <div class="flex-1 p-5">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                <div>
                                    <h3 class="text-xl font-bold text-on-surface mb-1">{{ $property->title ?? 'Property' }}</h3>
                                    <p class="text-sm text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">location_on</span>
                                        {{ $property->location ?? 'Unknown location' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-bold text-secondary">KES {{ number_format($property->price_per_night ?? 0) }}</span>
                                    <span class="text-sm text-on-surface-variant">/ night</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-6 mt-4 text-sm text-on-surface-variant">
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">bed</span>
                                    <span>{{ $property->bedrooms ?? 1 }} bedrooms</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">bathtub</span>
                                    <span>{{ $property->bathrooms ?? 1 }} bathrooms</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">family_history</span>
                                    <span>{{ $property->property_type ?? 'Property' }}</span>
                                </div>
                                {{-- Video count badge in details --}}
                                @if(isset($property->videos) && count($property->videos) > 0)
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-secondary">play_circle</span>
                                    <span class="text-secondary">{{ count($property->videos) }} video(s)</span>
                                </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2 mt-5 pt-4 border-t border-outline-variant">
                                <a href="{{ route('owner.property.bookings', $property->id) }}" class="px-4 py-2 bg-secondary text-white rounded-lg text-sm font-semibold hover:bg-secondary/90 transition">
                                    View Bookings
                                </a>
                                <a href="{{ route('owner.property.edit', $property->id) }}" class="px-4 py-2 border border-outline-variant rounded-lg text-sm font-semibold text-on-surface hover:bg-surface-container transition">
                                    Edit
                                </a>
                                <a href="{{ route('owner.property.photos', $property->id) }}" class="px-4 py-2 border border-outline-variant rounded-lg text-sm font-semibold text-on-surface hover:bg-surface-container transition">
                                    Manage Photos
                                </a>
                                <a href="{{ route('owner.videos.manage', $property->id) }}" class="px-4 py-2 border border-outline-variant rounded-lg text-sm font-semibold text-on-surface hover:bg-surface-container transition">
                                    Manage Videos
                                </a>
                                <form method="POST" action="{{ route('owner.property.delete', $property->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete &quot;{{ $property->title ?? 'this property' }}&quot;? This action cannot be undone.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 border border-error text-error rounded-lg text-sm font-semibold hover:bg-error hover:text-white transition delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-surface-container-lowest rounded-xl p-16 text-center border border-outline-variant">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4">real_estate_agent</span>
            <h3 class="text-xl font-bold text-on-surface mb-2">No properties yet</h3>
            <p class="text-on-surface-variant mb-6">Start by listing your first property on Eserian Homes</p>
            <a href="{{ route('owner.property.create') }}" class="inline-block px-6 py-3 bg-secondary text-white rounded-lg font-semibold hover:bg-secondary/90 transition">
                + Add New Property
            </a>
        </div>
    @endif

    <!-- Performance Snapshot -->
    @if($properties->count() > 0)
    @php
        $totalRevenue = 0;
        $totalBookings = 0;
        foreach($properties as $property) {
            if(isset($property->bookings)) {
                foreach($property->bookings as $booking) {
                    if(isset($booking->status) && $booking->status == 'completed') {
                        $totalRevenue += $booking->total_price ?? 0;
                    }
                }
                $totalBookings += $property->bookings->count();
            }
        }
    @endphp
    <div class="mt-10">
        <h2 class="text-xl font-semibold text-on-surface mb-5">Performance Snapshot</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant">
                <p class="text-sm text-on-surface-variant uppercase tracking-wide">Total Revenue</p>
                <p class="text-2xl font-bold text-on-surface mt-1">KES {{ number_format($totalRevenue) }}</p>
            </div>
            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant">
                <p class="text-sm text-on-surface-variant uppercase tracking-wide">Total Bookings</p>
                <p class="text-2xl font-bold text-on-surface mt-1">{{ $totalBookings }}</p>
            </div>
            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant">
                <p class="text-sm text-on-surface-variant uppercase tracking-wide">Active Properties</p>
                <p class="text-2xl font-bold text-on-surface mt-1">{{ $properties->where('status', 'approved')->count() }}</p>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Photo Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-2xl hidden flex-col items-center justify-center p-12">
    <button onclick="closeGallery()" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors z-10">
        <span class="material-symbols-outlined text-4xl">close</span>
    </button>

    <div class="w-full max-w-6xl relative">
        <div id="galleryMainContent" class="aspect-video relative group">
            <img id="galleryMainImage" class="w-full h-full object-contain rounded-2xl shadow-2xl" alt="Gallery Image" style="display: none;">
            <video id="galleryMainVideo" class="w-full h-full object-contain rounded-2xl shadow-2xl" controls style="display: none;">
                <source id="galleryVideoSource" src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <button id="prevButton" onclick="previousMedia()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center backdrop-blur-xl transition-all">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button id="nextButton" onclick="nextMedia()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center backdrop-blur-xl transition-all">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>
        <div class="text-center text-white/70 text-sm mt-4">
            <span id="currentMediaIndex">1</span> / <span id="totalMediaCount">0</span>
        </div>
        <div id="thumbnailContainer" class="mt-6 flex gap-3 overflow-x-auto pb-4 justify-center"></div>
        <div class="text-center mt-6">
            <h3 id="galleryPropertyTitle" class="text-white text-xl font-bold"></h3>
            <p id="galleryPropertyLocation" class="text-white/60 text-sm"></p>
        </div>
    </div>
</div>

<script>
    let galleryData = [];
    let currentPropertyId = null;
    let currentMediaIndex = 0;
    let isVideoMode = false;

    @foreach($properties as $property)
        @php
            // Collect all media (photos + videos) for the gallery
            $mediaItems = [];

            // Add videos first
            if(isset($property->videos) && is_array($property->videos) && count($property->videos) > 0) {
                foreach($property->videos as $video) {
                    $mediaItems[] = [
                        'type' => 'video',
                        'url' => spring_boot_url($video->video_path ?? ''),
                        'thumbnail' => spring_boot_url($video->video_path ?? ''),
                        'title' => $video->title ?? 'Video'
                    ];
                }
            }

            // Then add photos
            if(isset($property->photos) && is_array($property->photos) && count($property->photos) > 0) {
                foreach($property->photos as $photo) {
                    $mediaItems[] = [
                        'type' => 'image',
                        'url' => spring_boot_url($photo->photo_path ?? ''),
                        'is_primary' => $photo->is_primary ?? false
                    ];
                }
            }
        @endphp

        galleryData[{{ $property->id }}] = {
            title: '{{ addslashes($property->title ?? 'Property') }}',
            location: '{{ addslashes($property->location ?? 'Unknown') }}',
            media: @json($mediaItems),
            totalCount: {{ count($mediaItems) }}
        };
    @endforeach

    function openGallery(propertyId) {
        currentPropertyId = propertyId;
        const property = galleryData[propertyId];
        if (!property || property.totalCount === 0) {
            alert('No media available for this property');
            return;
        }
        currentMediaIndex = 0;
        document.getElementById('galleryPropertyTitle').textContent = property.title;
        document.getElementById('galleryPropertyLocation').textContent = property.location;
        document.getElementById('totalMediaCount').textContent = property.totalCount;
        updateMainMedia();

        const thumbnailContainer = document.getElementById('thumbnailContainer');
        thumbnailContainer.innerHTML = '';
        property.media.forEach((media, index) => {
            const thumb = document.createElement('div');
            thumb.className = `w-20 h-16 rounded-lg overflow-hidden cursor-pointer transition-all hover:opacity-80 ${index === currentMediaIndex ? 'ring-2 ring-secondary' : 'opacity-60'}`;
            if (media.type === 'video') {
                thumb.innerHTML = `<video class="w-full h-full object-cover" src="${media.url}"></video>`;
            } else {
                thumb.innerHTML = `<img src="${media.url}" class="w-full h-full object-cover">`;
            }
            thumb.onclick = () => { currentMediaIndex = index; updateMainMedia(); };
            thumbnailContainer.appendChild(thumb);
        });
        document.getElementById('galleryModal').classList.remove('hidden');
        document.getElementById('galleryModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function updateMainMedia() {
        const property = galleryData[currentPropertyId];
        if (property && property.media[currentMediaIndex]) {
            const media = property.media[currentMediaIndex];
            const imageElement = document.getElementById('galleryMainImage');
            const videoElement = document.getElementById('galleryMainVideo');
            if (media.type === 'video') {
                imageElement.style.display = 'none';
                videoElement.style.display = 'block';
                document.getElementById('galleryVideoSource').src = media.url;
                videoElement.load();
                isVideoMode = true;
            } else {
                imageElement.style.display = 'block';
                videoElement.style.display = 'none';
                imageElement.src = media.url;
                isVideoMode = false;
            }
            document.getElementById('currentMediaIndex').textContent = currentMediaIndex + 1;
        }
    }

    function nextMedia() {
        const property = galleryData[currentPropertyId];
        if (property && currentMediaIndex < property.totalCount - 1) {
            currentMediaIndex++;
            updateMainMedia();
        }
    }

    function previousMedia() {
        if (currentMediaIndex > 0) {
            currentMediaIndex--;
            updateMainMedia();
        }
    }

    function closeGallery() {
        const videoElement = document.getElementById('galleryMainVideo');
        if (videoElement) videoElement.pause();
        document.getElementById('galleryModal').classList.add('hidden');
        document.getElementById('galleryModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('galleryModal');
        if (modal.classList.contains('flex')) {
            if (e.key === 'ArrowRight') nextMedia();
            else if (e.key === 'ArrowLeft') previousMedia();
            else if (e.key === 'Escape') closeGallery();
        }
    });
</script>
@endsection
