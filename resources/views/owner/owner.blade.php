<!DOCTYPE html>
<html class="light" lang="en">

<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<title>Eserian Homes - Owner Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

<script src="https://cdn.tailwindcss.com"></script>

<style>
body { font-family: Inter, sans-serif; }
h1,h2,h3 { font-family: Manrope, sans-serif; }
</style>

</head>

<body class="bg-slate-50">

<!-- TOP BAR -->
<header class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur shadow flex justify-between items-center px-6 py-4">

<div class="flex items-center gap-3">
    <div class="w-10 h-10 rounded-full bg-gray-200"></div>
    <h1 class="text-xl font-black text-blue-900">Eserian Homes</h1>
</div>

<div class="flex gap-6 text-sm font-semibold text-gray-600">
    <a href="/dashboard" class="text-blue-900">Dashboard</a>
    <a href="/properties">Properties</a>
    <a href="#">Bookings</a>
    <a href="#">Earnings</a>
</div>

</header>

<!-- MAIN -->
<main class="max-w-7xl mx-auto px-6 pt-28 space-y-10">

<!-- WELCOME -->
<section>
    <h1 class="text-4xl font-black text-gray-900">
        Welcome back, {{ auth()->user()->name }}
    </h1>
    <p class="text-gray-500 mt-2">Manage your properties and bookings</p>
</section>

<!-- STATS -->
<section class="grid grid-cols-1 md:grid-cols-4 gap-6">

<div class="bg-white p-6 rounded-xl shadow">
    <p class="text-gray-500 text-sm">Total Properties</p>
    <h2 class="text-3xl font-black">{{ $properties->count() }}</h2>
</div>

<div class="bg-white p-6 rounded-xl shadow">
    <p class="text-gray-500 text-sm">Bookings</p>
    <h2 class="text-3xl font-black">0</h2>
</div>

<div class="bg-blue-900 text-white p-6 rounded-xl shadow">
    <p class="text-sm opacity-70">Monthly Earnings</p>
    <h2 class="text-3xl font-black">KES 0</h2>
</div>

<div class="bg-white p-6 rounded-xl shadow">
    <p class="text-gray-500 text-sm">Occupancy</p>
    <h2 class="text-3xl font-black">0%</h2>
</div>

</section>

<!-- PROPERTIES -->
<section>

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-black">My Properties</h2>

    <a href="/properties/create"
       class="bg-blue-900 text-white px-4 py-2 rounded-xl">
        + Add Property
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

@forelse($properties as $property)

<div class="bg-white rounded-xl shadow overflow-hidden">

    @if($property->image)
        <img src="{{ asset('storage/' . $property->image) }}"
             class="h-48 w-full object-cover">
    @endif

    <div class="p-4">
        <h3 class="font-bold text-lg">{{ $property->title }}</h3>
        <p class="text-gray-500">{{ $property->location }}</p>
        <p class="font-black text-blue-900">KES {{ $property->price_per_night }}</p>

        <div class="flex gap-3 mt-4">
            <a href="/properties/{{ $property->id }}/edit"
               class="text-blue-600 text-sm">Edit</a>

            <form method="POST" action="/properties/{{ $property->id }}">
                @csrf
                @method('DELETE')

                <button class="text-red-600 text-sm"
                        onclick="return confirm('Delete property?')">
                    Delete
                </button>
            </form>
        </div>
    </div>

</div>

@empty
<p>No properties yet. Click "Add Property".</p>
@endforelse

</div>

</section>

</main>

</body>
</html>