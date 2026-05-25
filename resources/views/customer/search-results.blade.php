{{-- resources/views/customer/search-results.blade.php --}}
@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="container-custom">
    <!-- Search Bar -->
    <div class="mb-8">
        <div class="flex items-center bg-surface-card rounded-xl shadow-sm border border-border-color p-2">
            <span class="material-symbols-outlined ml-4 text-text-muted">location_on</span>
            <input type="text" id="searchInput" class="w-full bg-transparent border-none text-text-primary placeholder:text-text-muted focus:ring-0 px-4 py-3"
                   placeholder="Where are you going?" value="{{ $searchTerm }}">
            <button onclick="performSearch()" class="ml-2 w-12 h-12 rounded-lg bg-brand-500 flex items-center justify-center text-white hover:bg-brand-600 transition">
                <span class="material-symbols-outlined">search</span>
            </button>
        </div>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-1">{{ $totalCount ?? 0 }} properties found</h1>
            <p class="text-text-muted">{{ $searchTerm ? "Search results for '" . $searchTerm . "'" : "Showing all properties" }}</p>
        </div>
        <button class="flex items-center gap-2 border border-border-color px-4 py-2 rounded-lg text-sm font-semibold text-text-primary hover:bg-gray-50 transition">
            <span class="material-symbols-outlined text-sm">tune</span>
            Filters
        </button>
    </div>

    <!-- Categories -->
    <div class="flex items-center gap-4 overflow-x-auto pb-4 mb-6 scrollbar-hide">
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-brand-600 border-b-2 border-brand-600 pb-2">
            <span class="material-symbols-outlined">pool</span>
            <span class="text-xs font-semibold">Amazing pools</span>
        </div>
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-text-muted hover:text-text-primary transition pb-2">
            <span class="material-symbols-outlined">beach_access</span>
            <span class="text-xs">Beachfront</span>
        </div>
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-text-muted hover:text-text-primary transition pb-2">
            <span class="material-symbols-outlined">castle</span>
            <span class="text-xs">Castles</span>
        </div>
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-text-muted hover:text-text-primary transition pb-2">
            <span class="material-symbols-outlined">landscape</span>
            <span class="text-xs">Amazing views</span>
        </div>
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-text-muted hover:text-text-primary transition pb-2">
            <span class="material-symbols-outlined">cabin</span>
            <span class="text-xs">Cabins</span>
        </div>
    </div>

    <!-- Results Grid -->
    @if($properties->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($properties as $property)
            <div class="group cursor-pointer property-card" onclick="window.location.href='{{ route('customer.property.detail', $property->id) }}'">
                <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100 shadow-sm">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                         src="{{ isset($property->photos[0]) ? asset('storage/' . $property->photos[0]->photo_path) : 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=500&h=500&fit=crop' }}"
                         alt="{{ $property->title }}">
                    <button onclick="event.stopPropagation(); saveProperty({{ $property->id }})" class="absolute top-3 right-3 text-white drop-shadow-md hover:scale-110 transition">
                        <span class="material-symbols-outlined">favorite_border</span>
                    </button>
                    <div class="absolute bottom-3 left-3 px-2 py-1 rounded-md bg-black/60 backdrop-blur-sm text-white flex items-center gap-1">
                        <span class="material-symbols-outlined text-yellow-400 text-sm">star</span>
                        <span class="text-xs font-semibold">{{ number_format($property->averageRating ?? 4.5, 1) }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-start pt-2">
                    <div>
                        <p class="font-semibold text-text-primary">{{ $property->location }}</p>
                        <p class="text-xs text-text-muted">{{ $property->property_type }}</p>
                        <p class="text-xs text-text-muted">Oct 12 – 17</p>
                        <p class="mt-1">
                            <span class="font-bold text-brand-600">KES {{ number_format($property->price_per_night) }}</span>
                            <span class="text-text-muted text-sm"> / night</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-yellow-400 text-sm">star</span>
                        <span class="text-sm font-semibold text-text-primary">{{ number_format($property->averageRating ?? 4.5, 1) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $properties->links() }}
        </div>
    @else
        <div class="bg-surface-card rounded-xl p-16 text-center shadow-sm border border-border-color">
            <span class="material-symbols-outlined text-5xl text-text-muted mb-3">search_off</span>
            <h3 class="text-xl font-semibold text-text-primary mb-2">No properties found</h3>
            <p class="text-text-muted">Try adjusting your search or filters</p>
            <a href="{{ route('customer.browse') }}" class="inline-block mt-6 text-brand-600 font-semibold hover:underline">
                Clear all filters →
            </a>
        </div>
    @endif
</div>

<script>
    function performSearch() {
        let term = document.getElementById('searchInput').value;
        window.location.href = '{{ route("customer.browse") }}?search=' + encodeURIComponent(term);
    }

    function saveProperty(id) {
        fetch('{{ url("/customer/property") }}/' + id + '/save', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            alert('Property saved to wishlist! ❤️');
        });
    }
</script>
@endsection
