<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Your Reviews | Eserian Homes</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
</head>
<body class="bg-gray-50">

<header class="bg-white/90 backdrop-blur-md shadow-sm fixed top-0 w-full z-50 border-b border-gray-200">
    <div class="flex justify-between items-center px-6 py-4 max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-700 text-3xl">travel_explore</span>
            <span class="text-2xl font-bold text-blue-900">Eserian Homes</span>
        </div>
        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB2PyHi3PK4qqg3uHQ578CV4-3fBH3MoHkBLhacYYhYF_wMp-kDkRBSQ-sP7Jl66iAQhd4iCEcm_KmGw0QwsMUdc6xj66MVAVu1IwOQ0aprRJR41PUtug5UC6yqm4Vs1AQvOCcg8SVoTUZSqbul2uGxVHWk9PXo8HFOTR6pKs_msSxfEHInLTVgdE3ZYcFYLcfRGL-YpGlbdXev7miOWMUTyNRwSzwMh0iRCQJItY4fctM8ZJ-qvtp5cORX4Fbqvl5JvhOUzBp-y1M" alt="Profile" class="w-full h-full object-cover rounded-full">
        </div>
    </div>
</header>

<main class="pt-24 pb-16 px-6 max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Your Reviews</h2>
        <p class="text-gray-500">Manage your feedback and share your experiences from recent stays</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-8 border-b border-gray-200 mb-8">
        <button class="pb-4 text-blue-700 border-b-2 border-blue-700 font-semibold">To Write (2)</button>
        <button class="pb-4 text-gray-500 hover:text-gray-700 transition">Past Reviews (14)</button>
    </div>

    <!-- Reviews Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition group flex flex-col">
            <div class="h-48 overflow-hidden relative">
                <img class="w-full h-full object-cover group-hover:scale-105 transition duration-500" src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=500&h=400&fit=crop" alt="Property">
                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full">
                    <span class="text-xs text-blue-600 font-semibold">Stayed Oct 12 - 15</span>
                </div>
            </div>
            <div class="p-5 flex-1 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Azure Bay Sanctuary</h3>
                    <p class="text-sm text-gray-500">Malibu, California</p>
                </div>
                <div class="mt-auto">
                    <button class="w-full py-3 bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-800 transition">Write Review</button>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition group flex flex-col">
            <div class="h-48 overflow-hidden relative">
                <img class="w-full h-full object-cover group-hover:scale-105 transition duration-500" src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500&h=400&fit=crop" alt="Property">
                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full">
                    <span class="text-xs text-blue-600 font-semibold">Stayed Sep 28 - Oct 2</span>
                </div>
            </div>
            <div class="p-5 flex-1 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Hidden Peak Chalet</h3>
                    <p class="text-sm text-gray-500">Aspen, Colorado</p>
                </div>
                <div class="mt-auto">
                    <button class="w-full py-3 bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-800 transition">Write Review</button>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl p-6 flex flex-col justify-center items-center text-center border border-blue-100">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-blue-700 text-3xl">redeem</span>
            </div>
            <h3 class="text-xl font-bold text-blue-800 mb-2">Share your story</h3>
            <p class="text-gray-600 mb-6">Your reviews help the community find the best spots. Complete 5 reviews to earn a 'Top Traveler' badge.</p>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-full w-[60%] rounded-full"></div>
            </div>
            <p class="mt-2 text-sm text-blue-600">3/5 reviews completed</p>
        </div>
    </div>

    <!-- Past Reviews Section -->
    <div class="mt-12">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Past Reviews</h3>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Sort by: Newest</span>
                <span class="material-symbols-outlined text-sm">expand_more</span>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-48 h-32 rounded-lg overflow-hidden">
                        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=300&h=200&fit=crop" alt="Property">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">The Artist's Loft</h4>
                                <p class="text-sm text-gray-500 mb-2">Le Marais, Paris • Stayed July 2025</p>
                            </div>
                            <div class="flex text-yellow-500">
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">Absolutely breathtaking views and incredible attention to detail. The check-in process was seamless and the host provided a wonderful guide to the local neighborhood.</p>
                        <div class="flex gap-2">
                            <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=100&h=100&fit=crop" alt="Review">
                            </div>
                            <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=100&h=100&fit=crop" alt="Review">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-48 h-32 rounded-lg overflow-hidden">
                        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&h=200&fit=crop" alt="Property">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">Mirror Lake Retreat</h4>
                                <p class="text-sm text-gray-500 mb-2">Lake Tahoe, Nevada • Stayed May 2025</p>
                            </div>
                            <div class="flex text-yellow-500">
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="material-symbols-outlined text-sm text-gray-300">star</span>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">The location is unbeatable. Having your own private dock for morning coffee was the highlight of the trip. The interior is very modern and comfortable.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="bg-black text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-sm">© 2026 Eserian Homes. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
