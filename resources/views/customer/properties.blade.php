<!-- File: resources/views/customer/properties.blade.php -->
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Browse Properties | Eserian Homes</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <style>.scrollbar-hide::-webkit-scrollbar { display: none; }</style>
</head>
<body class="bg-[#1a1a2e]">

<header class="bg-[#16213e]/90 backdrop-blur-md shadow-sm fixed top-0 w-full z-50 border-b border-gray-700">
    <div class="flex justify-between items-center px-6 py-4 max-w-7xl mx-auto">
        <div class="flex items-center gap-2"><span class="material-symbols-outlined text-red-500 text-3xl">travel_explore</span><a href="{{ route('home.authenticated') }}" class="text-2xl font-bold text-white">Eserian Homes</a></div>
        <div class="hidden md:flex items-center gap-6"><a href="{{ route('customer.browse') }}" class="text-red-400 border-b-2 border-red-400 pb-1">Properties</a><a href="{{ route('customer.calendar') }}" class="text-gray-300 hover:text-red-400 transition">Calendar</a><a href="{{ route('customer.saved') }}" class="text-gray-300 hover:text-red-400 transition">Saved</a><a href="{{ route('customer.dashboard') }}" class="text-gray-300 hover:text-red-400 transition">Dashboard</a></div>
        <div class="flex items-center gap-3"><div class="w-10 h-10 bg-[#1e2a4a] rounded-full flex items-center justify-center"><span class="text-white font-semibold">{{ substr($user['name'] ?? 'G', 0, 1) }}</span></div><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-gray-400 hover:text-red-400 text-sm">Logout</button></form></div>
    </div>
</header>

<main class="pt-20 pb-16 px-6 max-w-7xl mx-auto">
    <!-- Sticky Search Bar -->
    <div class="sticky top-16 z-40 bg-[#1a1a2e]/95 backdrop-blur-sm border-b border-gray-700 py-4 -mx-6 px-6 mb-6"><div class="flex flex-col md:flex-row gap-4 max-w-7xl mx-auto"><div class="flex-1 flex items-center bg-[#0f1420] rounded-full px-6 py-3 border border-gray-700"><div class="flex flex-1 items-center gap-2 border-r border-gray-700 pr-4"><span class="material-symbols-outlined text-gray-400">location_on</span><div class="flex flex-col"><span class="text-[10px] font-bold text-gray-500">WHERE</span><input class="bg-transparent border-none p-0 text-sm focus:ring-0 text-white w-full" placeholder="Santorini, Greece" type="text" id="searchLocation"></div></div><div class="flex flex-1 items-center gap-2 px-4"><span class="material-symbols-outlined text-gray-400">calendar_today</span><div class="flex flex-col"><span class="text-[10px] font-bold text-gray-500">WHEN</span><span class="text-sm text-white" id="dateRange">Select dates</span></div></div><div class="flex items-center gap-2 pl-4"><span class="material-symbols-outlined text-gray-400">group</span><div class="flex flex-col"><span class="text-[10px] font-bold text-gray-500">WHO</span><span class="text-sm text-white" id="guestCount">2 guests</span></div></div><button onclick="applyFilters()" class="ml-4 w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white hover:bg-red-700 transition"><span class="material-symbols-outlined">search</span></button></div><button onclick="openFilters()" class="md:hidden flex items-center justify-center gap-2 px-4 py-2 bg-[#0f1420] rounded-full border border-gray-700"><span class="material-symbols-outlined text-sm">filter_list</span><span class="text-sm">Filters</span></button></div></div>

    <div class="flex justify-between items-center mb-6"><div><h1 class="text-2xl font-bold text-white">{{ $totalCount ?? 0 }} stays found</h1><p class="text-sm text-gray-400">Showing results for your selected criteria</p></div><button class="flex items-center gap-2 border border-gray-700 px-4 py-2 rounded-lg text-sm font-semibold text-gray-300 hover:bg-[#0f1420] transition"><span class="material-symbols-outlined text-sm">filter_list</span>Filters</button></div>

    <!-- Categories -->
    <div class="flex items-center gap-4 overflow-x-auto pb-4 mb-6 scrollbar-hide"><div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-red-400 border-b-2 border-red-400 pb-2"><span class="material-symbols-outlined">pool</span><span class="text-xs font-semibold">Amazing pools</span></div><div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-gray-400 hover:text-white transition pb-2"><span class="material-symbols-outlined">beach_access</span><span class="text-xs">Beachfront</span></div><div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-gray-400 hover:text-white transition pb-2"><span class="material-symbols-outlined">castle</span><span class="text-xs">Castles</span></div><div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-gray-400 hover:text-white transition pb-2"><span class="material-symbols-outlined">landscape</span><span class="text-xs">Amazing views</span></div><div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-gray-400 hover:text-white transition pb-2"><span class="material-symbols-outlined">cabin</span><span class="text-xs">Cabins</span></div></div>

    <!-- Property Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($properties as $property)
        <div class="group cursor-pointer" onclick="window.location.href='{{ route('customer.property.detail', $property->id) }}'"><div class="relative aspect-square rounded-xl overflow-hidden bg-[#0f1420] border border-gray-700"><img class="w-full h-full object-cover group-hover:scale-105 transition duration-500" src="{{ isset($property->photos[0]) ? asset('storage/' . $property->photos[0]->photo_path) : 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=500&h=500&fit=crop' }}" alt="{{ $property->title }}"><button onclick="event.stopPropagation(); saveProperty({{ $property->id }})" class="absolute top-3 right-3 text-white drop-shadow-md hover:scale-110 transition"><span class="material-symbols-outlined">favorite_border</span></button><div class="absolute bottom-3 left-3 px-2 py-1 rounded-md bg-black/70 backdrop-blur-sm text-white flex items-center gap-1 shadow-sm"><span class="material-symbols-outlined text-yellow-400 text-sm">star</span><span class="text-xs font-semibold">{{ number_format($property->averageRating ?? 4.5, 1) }}</span></div></div><div class="flex justify-between items-start pt-2"><div><p class="font-semibold text-white">{{ $property->location }}</p><p class="text-xs text-gray-500">{{ $property->property_type }}</p><p class="text-xs text-gray-500">Oct 12 – 17</p><p class="mt-1"><span class="font-bold text-red-400">KES {{ number_format($property->price_per_night) }}</span> <span class="text-gray-500 text-sm">night</span></p></div><div class="flex items-center gap-1"><span class="material-symbols-outlined text-yellow-400 text-sm">star</span><span class="text-sm font-semibold text-white">{{ number_format($property->averageRating ?? 4.5, 1) }}</span></div></div></div>
        @empty<div class="col-span-4 text-center py-12"><span class="material-symbols-outlined text-5xl text-gray-600 mb-3">search_off</span><h3 class="text-xl font-semibold text-white mb-2">No properties found</h3><p class="text-gray-400">Try adjusting your filters</p></div>@endforelse
    </div>

    <button class="fixed bottom-24 left-1/2 -translate-x-1/2 bg-gray-900 text-white flex items-center gap-2 px-6 py-3 rounded-full font-semibold shadow-lg hover:bg-black transition z-40"><span>Show map</span><span class="material-symbols-outlined text-sm">map</span></button>
</main>

<script>
    function saveProperty(id) { fetch('{{ url("/customer/property") }}/' + id + '/save', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => alert('Property saved!')); }
    function applyFilters() { let location = document.getElementById('searchLocation').value; window.location.href = '{{ route("customer.browse") }}?location=' + encodeURIComponent(location); }
    function openFilters() { alert('Filter panel would open here'); }
</script>

<footer class="bg-black text-gray-400 py-8 mt-12"><div class="max-w-7xl mx-auto px-6 text-center"><p class="text-sm">© 2026 Eserian Homes. All rights reserved.</p></div></footer>
</body>
</html>
