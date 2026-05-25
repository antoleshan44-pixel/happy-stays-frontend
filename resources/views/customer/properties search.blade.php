<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Properties | Eserian Homes</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-50">

<header class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-200">
    <nav class="flex justify-between items-center px-6 h-16 max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-700 text-3xl">travel_explore</span>
            <span class="text-2xl font-bold text-blue-900">Eserian Homes</span>
        </div>
        <div class="flex items-center gap-4">
            <button class="material-symbols-outlined text-gray-600 hover:bg-gray-100 p-2 rounded-full transition">notifications</button>
            <div class="h-8 w-8 rounded-full overflow-hidden border border-gray-200">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCZ5L6nXEDleGLDQWLmQYuUAlqbxJz4bdK2Bz6OgpqvDyWn42z9dU7s4c_6OTnk2pIwEPBYVvaM2hrnJeiU6YP6OGqiOFsswqCUUOMQeO6AMSpzX0VMOG2gMSDCASXY3F2UwZN-PQfgwJQ_h9Tj_fVUjoQmVVqP1-blI6WY8Ct4KWdS_vx46kmFh48N1eBN1SP-wYV-n8KhQNLiLDBMwRu5xehN9bLbUqyOsubQVWP-WdOIpSPrL5PQCSwt-0gEw4gpNkeHyL518vY" alt="Profile" class="w-full h-full object-cover">
            </div>
        </div>
    </nav>
</header>

<main class="pt-20 pb-16 px-6 max-w-7xl mx-auto">
    <!-- Search Bar -->
    <div class="sticky top-16 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-200 py-4 -mx-6 px-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4 max-w-7xl mx-auto">
            <div class="flex-1 flex items-center bg-gray-100 rounded-full px-6 py-3">
                <div class="flex flex-1 items-center gap-2 border-r border-gray-300 pr-4">
                    <span class="material-symbols-outlined text-gray-500">location_on</span>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-500">WHERE</span>
                        <input class="bg-transparent border-none p-0 text-sm focus:ring-0 w-full" placeholder="Santorini, Greece" type="text">
                    </div>
                </div>
                <div class="flex flex-1 items-center gap-2 px-4">
                    <span class="material-symbols-outlined text-gray-500">calendar_today</span>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-500">WHEN</span>
                        <span class="text-sm">Oct 12 - 18</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 pl-4">
                    <span class="material-symbols-outlined text-gray-500">group</span>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-500">WHO</span>
                        <span class="text-sm">2 guests</span>
                    </div>
                </div>
                <button class="ml-4 w-10 h-10 rounded-full bg-blue-700 flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">312 stays in Santorini</h1>
            <p class="text-sm text-gray-500">Showing results for 2 guests • Oct 12-18</p>
        </div>
        <button class="flex items-center gap-2 border border-gray-300 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
            <span class="material-symbols-outlined text-sm">filter_list</span>
            Filters
        </button>
    </div>

    <!-- Categories -->
    <div class="flex items-center gap-4 overflow-x-auto pb-4 mb-6 scrollbar-hide">
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-blue-700 border-b-2 border-blue-700 pb-2">
            <span class="material-symbols-outlined">pool</span>
            <span class="text-xs font-semibold">Amazing pools</span>
        </div>
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-gray-500 hover:text-gray-700 pb-2">
            <span class="material-symbols-outlined">beach_access</span>
            <span class="text-xs">Beachfront</span>
        </div>
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-gray-500 hover:text-gray-700 pb-2">
            <span class="material-symbols-outlined">castle</span>
            <span class="text-xs">Castles</span>
        </div>
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-gray-500 hover:text-gray-700 pb-2">
            <span class="material-symbols-outlined">landscape</span>
            <span class="text-xs">Amazing views</span>
        </div>
        <div class="flex flex-col items-center gap-1 min-w-[72px] cursor-pointer text-gray-500 hover:text-gray-700 pb-2">
            <span class="material-symbols-outlined">cabin</span>
            <span class="text-xs">Cabins</span>
        </div>
    </div>

    <!-- Property Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div class="group cursor-pointer">
            <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100">
                <img class="w-full h-full object-cover group-hover:scale-105 transition duration-500" src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=500&h=500&fit=crop" alt="Property">
                <button class="absolute top-3 right-3 text-white drop-shadow-md hover:scale-110 transition">
                    <span class="material-symbols-outlined">favorite_border</span>
                </button>
            </div>
            <div class="flex justify-between items-start pt-2">
                <div>
                    <p class="font-semibold">Oia, Greece</p>
                    <p class="text-xs text-gray-500">842 miles away</p>
                    <p class="text-xs text-gray-500">Jul 12 – 17</p>
                    <p class="mt-1"><span class="font-bold text-blue-700">$420</span> <span class="text-gray-500 text-sm">night</span></p>
                </div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                    <span class="text-sm font-semibold">4.92</span>
                </div>
            </div>
        </div>
        <div class="group cursor-pointer">
            <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100">
                <img class="w-full h-full object-cover group-hover:scale-105 transition duration-500" src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500&h=500&fit=crop" alt="Property">
                <button class="absolute top-3 right-3 text-white drop-shadow-md hover:scale-110 transition">
                    <span class="material-symbols-outlined">favorite_border</span>
                </button>
            </div>
            <div class="flex justify-between items-start pt-2">
                <div>
                    <p class="font-semibold">Imerovigli, Greece</p>
                    <p class="text-xs text-gray-500">842 miles away</p>
                    <p class="text-xs text-gray-500">Aug 2 – 7</p>
                    <p class="mt-1"><span class="font-bold text-blue-700">$385</span> <span class="text-gray-500 text-sm">night</span></p>
                </div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                    <span class="text-sm font-semibold">4.88</span>
                </div>
            </div>
        </div>
        <div class="group cursor-pointer">
            <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100">
                <img class="w-full h-full object-cover group-hover:scale-105 transition duration-500" src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=500&h=500&fit=crop" alt="Property">
                <button class="absolute top-3 right-3 text-white drop-shadow-md hover:scale-110 transition">
                    <span class="material-symbols-outlined">favorite_border</span>
                </button>
            </div>
            <div class="flex justify-between items-start pt-2">
                <div>
                    <p class="font-semibold">Akrotiri, Greece</p>
                    <p class="text-xs text-gray-500">845 miles away</p>
                    <p class="text-xs text-gray-500">Sep 15 – 20</p>
                    <p class="mt-1"><span class="font-bold text-blue-700">$290</span> <span class="text-gray-500 text-sm">night</span></p>
                </div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                    <span class="text-sm font-semibold">4.95</span>
                </div>
            </div>
        </div>
        <div class="group cursor-pointer">
            <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100">
                <img class="w-full h-full object-cover group-hover:scale-105 transition duration-500" src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500&h=500&fit=crop" alt="Property">
                <button class="absolute top-3 right-3 text-white drop-shadow-md hover:scale-110 transition">
                    <span class="material-symbols-outlined">favorite_border</span>
                </button>
            </div>
            <div class="flex justify-between items-start pt-2">
                <div>
                    <p class="font-semibold">Pyrgos, Greece</p>
                    <p class="text-xs text-gray-500">841 miles away</p>
                    <p class="text-xs text-gray-500">Oct 5 – 10</p>
                    <p class="mt-1"><span class="font-bold text-blue-700">$215</span> <span class="text-gray-500 text-sm">night</span></p>
                </div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                    <span class="text-sm font-semibold">4.75</span>
                </div>
            </div>
        </div>
    </div>

    <button class="fixed bottom-24 left-1/2 -translate-x-1/2 bg-gray-900 text-white flex items-center gap-2 px-6 py-3 rounded-full font-semibold shadow-lg hover:bg-black transition z-40">
        <span>Show map</span>
        <span class="material-symbols-outlined text-sm">map</span>
    </button>
</main>

<footer class="bg-black text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-sm">© 2026 Eserian Homes. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
