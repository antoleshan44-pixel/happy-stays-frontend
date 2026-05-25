{{-- resources/views/customer/my-reviews.blade.php --}}
@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
<div class="container-custom">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-2">My Reviews</h1>
        <p class="text-text-secondary">Manage your feedback and share your experiences from recent stays</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-8 border-b border-border-color mb-8">
        <button onclick="showTab('towrite')" id="tab-towrite" class="pb-4 text-brand-600 border-b-2 border-brand-600 font-semibold transition">
            To Write ({{ $pendingReviews->count() }})
        </button>
        <button onclick="showTab('past')" id="tab-past" class="pb-4 text-text-muted hover:text-text-primary transition">
            Past Reviews ({{ $pastReviews->count() }})
        </button>
    </div>

    <!-- To Write Tab -->
    <div id="towrite-tab" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($pendingReviews as $booking)
        <div class="bg-surface-card rounded-xl overflow-hidden shadow-sm border border-border-color hover:shadow-md transition flex flex-col">
            <div class="h-48 overflow-hidden relative">
                <img class="w-full h-full object-cover"
                     src="{{ isset($booking['property']['photos'][0]['photo_path']) ? asset('storage/' . $booking['property']['photos'][0]['photo_path']) : 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=500&h=400&fit=crop' }}"
                     alt="Property">
                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full">
                    <span class="text-xs text-brand-600 font-semibold">
                        Stayed {{ \Carbon\Carbon::parse($booking['checkInDate'])->format('M j') }} - {{ \Carbon\Carbon::parse($booking['checkOutDate'])->format('M j, Y') }}
                    </span>
                </div>
            </div>
            <div class="p-5 flex-1 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-text-primary heading-font mb-1">{{ $booking['property']['title'] ?? 'Property' }}</h3>
                    <p class="text-sm text-text-muted">{{ $booking['property']['location'] ?? 'Unknown' }}</p>
                </div>
                <div class="mt-auto">
                    <button onclick="openReviewModal({{ $booking['id'] }}, '{{ addslashes($booking['property']['title']) }}')"
                            class="w-full py-3 bg-brand-500 text-white rounded-lg font-semibold hover:bg-brand-600 transition">
                        Write Review
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <span class="material-symbols-outlined text-5xl text-text-muted mb-3">rate_review</span>
            <h3 class="text-xl font-semibold text-text-primary mb-2">No pending reviews</h3>
            <p class="text-text-muted">Complete a stay to leave a review</p>
        </div>
        @endforelse
    </div>

    <!-- Past Reviews Tab -->
    <div id="past-tab" class="hidden space-y-6">
        @forelse($pastReviews as $review)
        <div class="bg-surface-card rounded-xl p-6 shadow-sm border border-border-color">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-48 h-32 rounded-lg overflow-hidden">
                    <img class="w-full h-full object-cover"
                         src="{{ isset($review['property']['photos'][0]['photo_path']) ? asset('storage/' . $review['property']['photos'][0]['photo_path']) : 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=300&h=200&fit=crop' }}"
                         alt="Property">
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="text-xl font-bold text-text-primary heading-font">{{ $review['property']['title'] ?? 'Property' }}</h4>
                            <p class="text-sm text-text-muted mb-2">
                                {{ $review['property']['location'] ?? 'Unknown' }} • Stayed {{ \Carbon\Carbon::parse($review['created_at'])->format('M Y') }}
                            </p>
                        </div>
                        <div class="flex text-yellow-400">
                            @for($i=1;$i<=5;$i++)
                                <span class="material-symbols-outlined text-sm">{{ $i <= $review['rating'] ? 'star' : 'star_border' }}</span>
                            @endfor
                        </div>
                    </div>
                    <p class="text-text-secondary mb-4">{{ $review['comment'] }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <p class="text-text-muted">No past reviews yet</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface-card rounded-xl p-6 max-w-md w-full mx-4 shadow-xl border border-border-color">
        <h3 class="text-xl font-bold text-text-primary mb-4 heading-font" id="modalPropertyTitle">Write a Review</h3>

        <div class="mb-4">
            <label class="block text-sm font-medium text-text-primary mb-2">Your Rating</label>
            <div class="flex gap-2" id="ratingStars">
                @for($i=1;$i<=5;$i++)
                <button type="button" data-rating="{{ $i }}" class="rating-star text-3xl text-gray-300 hover:text-yellow-400 transition">
                    <span class="material-symbols-outlined">star</span>
                </button>
                @endfor
            </div>
            <input type="hidden" id="reviewRating" value="5">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-text-primary mb-2">Your Comment</label>
            <textarea id="reviewComment" rows="4" class="w-full px-4 py-2 rounded-lg border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                      placeholder="Share your experience..."></textarea>
        </div>

        <div class="flex gap-3">
            <button onclick="submitReview()" class="flex-1 bg-brand-500 text-white py-2 rounded-lg font-semibold hover:bg-brand-600 transition">Submit Review</button>
            <button onclick="closeReviewModal()" class="flex-1 border border-border-color text-text-primary py-2 rounded-lg font-semibold hover:bg-gray-50 transition">Cancel</button>
        </div>
    </div>
</div>

<script>
    let currentBookingId = null;

    function openReviewModal(bookingId, title) {
        currentBookingId = bookingId;
        document.getElementById('modalPropertyTitle').textContent = 'Write a Review for ' + title;
        document.getElementById('reviewModal').classList.add('flex');
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.getElementById('reviewModal').classList.remove('flex');
    }

    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', function() {
            let rating = this.dataset.rating;
            document.getElementById('reviewRating').value = rating;
            document.querySelectorAll('.rating-star').forEach((s, i) => {
                if(i < rating) {
                    s.classList.add('text-yellow-400');
                    s.classList.remove('text-gray-300');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });

    function submitReview() {
        let rating = document.getElementById('reviewRating').value;
        let comment = document.getElementById('reviewComment').value;

        fetch('{{ url("/customer/booking") }}/' + currentBookingId + '/review', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rating: rating, comment: comment })
        }).then(() => {
            location.reload();
        });
    }

    function showTab(tab) {
        if(tab === 'towrite') {
            document.getElementById('towrite-tab').classList.remove('hidden');
            document.getElementById('past-tab').classList.add('hidden');
            document.getElementById('tab-towrite').classList.add('text-brand-600', 'border-brand-600');
            document.getElementById('tab-towrite').classList.remove('text-text-muted', 'border-transparent');
            document.getElementById('tab-past').classList.remove('text-brand-600', 'border-brand-600');
            document.getElementById('tab-past').classList.add('text-text-muted', 'border-transparent');
        } else {
            document.getElementById('towrite-tab').classList.add('hidden');
            document.getElementById('past-tab').classList.remove('hidden');
            document.getElementById('tab-past').classList.add('text-brand-600', 'border-brand-600');
            document.getElementById('tab-past').classList.remove('text-text-muted', 'border-transparent');
            document.getElementById('tab-towrite').classList.remove('text-brand-600', 'border-brand-600');
            document.getElementById('tab-towrite').classList.add('text-text-muted', 'border-transparent');
        }
    }
</script>
@endsection
