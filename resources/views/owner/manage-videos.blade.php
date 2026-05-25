{{-- resources/views/owner/manage-videos.blade.php --}}
@extends('layouts.owner')

@section('title', 'Manage Videos')

@section('styles')
<style>
    .video-card { transition: all 0.2s ease; }
    .video-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .drop-zone { border: 2px dashed #c4c7c8; transition: all 0.3s ease; }
    .drop-zone.drag-over { border-color: #00288e; background-color: rgba(0,40,142,0.05); }
</style>
@endsection

@section('content')
<div class="container-custom py-6">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-on-surface-variant mb-1">
                <a href="{{ route('owner.properties') }}" class="hover:text-secondary transition">My Properties</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span>Videos</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-1">Manage Videos</h1>
            <p class="text-on-surface-variant text-sm">
                {{ $property['title'] ?? 'Property' }}
            </p>
            <p class="text-sm text-secondary mt-1">
                <span class="material-symbols-outlined text-sm align-middle">videocam</span>
                {{ count($videos) }}/5 videos uploaded
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('owner.property.photos', $property['id']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 border border-outline-variant rounded-lg
                      text-sm font-medium text-on-surface hover:bg-surface-container transition">
                <span class="material-symbols-outlined text-sm">collections</span>
                Photos
            </a>
            <a href="{{ route('owner.property.edit', $property['id']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 border border-outline-variant rounded-lg
                      text-sm font-medium text-on-surface hover:bg-surface-container transition">
                <span class="material-symbols-outlined text-sm">edit</span>
                Edit Details
            </a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-5 p-4 bg-success/10 border border-success/20 rounded-lg text-success text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-base">check_circle</span>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 p-4 bg-error/10 border border-error/20 rounded-lg text-error text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-base">error</span>{{ session('error') }}
        </div>
    @endif

    @php $propertyId = $property['id']; @endphp

    {{-- ── Upload New Video ── --}}
    @if(count($videos) < 5)
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-secondary to-secondary/80 px-6 py-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-white">upload</span>
            <h2 class="text-white font-semibold text-lg">Upload New Video</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('owner.video.upload', $propertyId) }}"
                  method="POST" enctype="multipart/form-data" id="videoUploadForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <input type="text" name="title" placeholder="Video Title (Optional)"
                           class="px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition text-sm">
                    <input type="text" name="description" placeholder="Short Description (Optional)"
                           class="px-4 py-2.5 border border-outline-variant rounded-lg
                                  focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition text-sm">
                </div>

                <div class="drop-zone rounded-xl p-8 text-center"
                     id="videoDropZone"
                     ondragover="handleDragOver(event)"
                     ondragleave="handleDragLeave(event)"
                     ondrop="handleVideoDrop(event)">

                    <input type="file" name="video" id="videoInput"
                           accept="video/mp4,video/quicktime,video/x-msvideo,video/webm,video/x-matroska"
                           class="hidden" onchange="previewVideo(this)">

                    <div id="videoUploadArea" class="space-y-3">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant">video_library</span>
                        <p class="text-on-surface-variant">Click or drag & drop a video here</p>
                        <p class="text-xs text-on-surface-variant">MP4, MOV, AVI, WEBM, MKV — max 100MB</p>
                        <button type="button" onclick="document.getElementById('videoInput').click()"
                                class="bg-secondary text-white px-5 py-2 rounded-lg text-sm font-semibold
                                       hover:bg-secondary/90 transition">
                            Select Video
                        </button>
                    </div>

                    <div id="videoPreview" class="hidden mt-4">
                        <video id="videoPreviewEl" class="w-full max-h-60 rounded-lg object-contain" controls></video>
                        <p id="videoFileName" class="text-sm text-on-surface-variant mt-2"></p>
                        <div class="flex gap-3 justify-center mt-4">
                            <button type="submit"
                                    class="bg-success text-white px-6 py-2.5 rounded-lg font-semibold
                                           hover:bg-success/90 transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">upload</span>
                                Upload Video
                            </button>
                            <button type="button" onclick="resetVideo()"
                                    class="border border-outline-variant text-on-surface px-4 py-2.5 rounded-lg
                                           text-sm font-medium hover:bg-surface-container transition">
                                Choose Different
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ── Existing Videos ── --}}
    @if(count($videos) > 0)
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">video_library</span>
            <h2 class="font-semibold text-on-surface">Your Videos ({{ count($videos) }}/5)</h2>
            <span class="ml-auto text-xs text-on-surface-variant">First video is shown as Featured</span>
        </div>

        <div class="divide-y divide-outline-variant">
            @foreach($videos as $index => $video)
            @php
                $vidId     = $video['id'] ?? $index;
                $vidPath   = $video['video_path'] ?? null;
                $vidTitle  = $video['title'] ?: 'Untitled Video';
                $vidDesc   = $video['description'] ?: 'No description';
                $isFeatured = $video['is_featured'] ?? ($index === 0);
            @endphp
            <div class="video-card p-5">
                <div class="flex gap-4 flex-wrap md:flex-nowrap">

                    {{-- Video thumbnail --}}
                    <div class="w-full md:w-52 flex-shrink-0">
                        @if($vidPath)
                        <video class="w-full h-32 object-cover rounded-lg bg-black" controls preload="metadata">
                            <source src="{{ spring_boot_url($vidPath) }}" type="video/mp4">
                        </video>
                        @else
                        <div class="w-full h-32 rounded-lg bg-surface-container flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl text-on-surface-variant">videocam_off</span>
                        </div>
                        @endif
                    </div>

                    {{-- Video info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <h3 class="font-semibold text-on-surface truncate">{{ $vidTitle }}</h3>
                            @if($isFeatured)
                                <span class="bg-secondary text-white text-xs px-2 py-0.5 rounded-full flex-shrink-0">Featured</span>
                            @endif
                        </div>
                        <p class="text-sm text-on-surface-variant mb-3 line-clamp-2">{{ $vidDesc }}</p>
                        <div class="flex items-center gap-4 text-xs text-on-surface-variant">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">sort</span>
                                Position {{ $index + 1 }}
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex md:flex-col gap-2 items-start flex-shrink-0">
                        {{-- Set Featured --}}
                        @if(!$isFeatured)
                        <form method="POST"
                              action="{{ route('owner.video.featured', [$propertyId, $vidId]) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    title="Set as Featured"
                                    class="p-2 bg-secondary/10 text-secondary rounded-lg hover:bg-secondary/20 transition
                                           flex items-center gap-1 text-xs font-medium">
                                <span class="material-symbols-outlined text-sm">star</span>
                                Feature
                            </button>
                        </form>
                        @endif

                        {{-- Delete --}}
                        <form method="POST"
                              action="{{ route('owner.video.delete', [$propertyId, $vidId]) }}"
                              onsubmit="return confirm('Delete this video? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="p-2 bg-error/10 text-error rounded-lg hover:bg-error hover:text-white transition
                                           flex items-center gap-1 text-xs font-medium">
                                <span class="material-symbols-outlined text-sm">delete</span>
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    {{-- Empty state --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-14 text-center">
        <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-3">video_library</span>
        <h3 class="text-xl font-semibold text-on-surface mb-1">No videos yet</h3>
        <p class="text-on-surface-variant text-sm">Upload a video walkthrough to help guests experience your property.</p>
    </div>
    @endif

    {{-- Back button --}}
    <div class="mt-6">
        <a href="{{ route('owner.property.photos', $propertyId) }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-on-surface hover:text-secondary transition">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Back to Photos
        </a>
    </div>

</div>

<script>
function handleDragOver(e) {
    e.preventDefault();
    document.getElementById('videoDropZone').classList.add('drag-over');
}
function handleDragLeave(e) {
    document.getElementById('videoDropZone').classList.remove('drag-over');
}
function handleVideoDrop(e) {
    e.preventDefault();
    document.getElementById('videoDropZone').classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('video/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('videoInput').files = dt.files;
        previewVideo(document.getElementById('videoInput'));
    }
}
function previewVideo(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 100 * 1024 * 1024) {
        alert('Video is too large. Maximum size is 100MB.');
        input.value = '';
        return;
    }
    document.getElementById('videoPreviewEl').src   = URL.createObjectURL(file);
    document.getElementById('videoFileName').textContent =
        file.name + ' (' + (file.size / (1024*1024)).toFixed(1) + ' MB)';
    document.getElementById('videoUploadArea').classList.add('hidden');
    document.getElementById('videoPreview').classList.remove('hidden');
}
function resetVideo() {
    document.getElementById('videoInput').value = '';
    document.getElementById('videoPreviewEl').src = '';
    document.getElementById('videoUploadArea').classList.remove('hidden');
    document.getElementById('videoPreview').classList.add('hidden');
}
</script>
@endsection
