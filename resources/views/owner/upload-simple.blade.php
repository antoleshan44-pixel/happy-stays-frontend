@extends('layouts.owner')

@section('title', 'Upload Media - Simple Test')

@section('content')
<div class="container-custom py-6">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Upload Media for {{ $property['title'] ?? 'Property' }}</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('owner.property.upload.all', $property['id']) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block font-bold mb-2">Video (Optional)</label>
                <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm,video/x-matroska" class="w-full border p-2 rounded">
                <p class="text-xs text-gray-500 mt-1">Max 100MB</p>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Photos (Required - at least 5)</label>
                <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="w-full border p-2 rounded">
                <p class="text-xs text-gray-500 mt-1">Max 5MB each. You can select multiple files at once.</p>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700">
                    UPLOAD & CONTINUE
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('owner.properties') }}" class="text-blue-600 hover:underline">← Back to Properties</a>
        </div>
    </div>
</div>
@endsection
