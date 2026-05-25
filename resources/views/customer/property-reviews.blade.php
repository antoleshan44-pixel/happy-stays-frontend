{{-- resources/views/customer/property-reviews.blade.php --}}
@extends('layouts.app')

@section('title', 'Reviews for ' . $property->title)

@section('content')
<div class="container-custom max-w-4xl mx-auto">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('customer.property.detail', $property->id) }}" class="flex items-center gap-2 text-brand-600 hover:text-brand-700 transition">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to property
        </a>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-2">{{ $property->title }}</h1>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-yellow-400 text-lg">star</span>
                <span class="text-2xl font-bold text-text-primary">{{ number_format($averageRating, 1) }}</span>
                <span class="text-text-muted">· {{ $property->reviews->count() }} reviews</span>
            </div>
        </div>
        <button onclick="openWriteReview()" class="bg-brand-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-brand-600 transition">
            Write a Review
        </button>
    </div>

    <!-- Rating Categories -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="md:col-span-2 bg-surface-card p-6 rounded-xl shadow-sm border border-border-color">
            <h3 class="text-sm font-semibold text-text-muted uppercase tracking-wider mb-6">Rating Categories</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @php $categories = ['Cleanliness' => 4.9, 'Communication' => 4.8, 'Accuracy' => 4.7, 'Location' => 4.9, 'Check-in' => 4.8, 'Value' => 4.6]; @endphp
                @foreach($categories as $cat => $rating)
                <div>
                    <div class="flex justify-between text-sm text-text-secondary mb-2">
                        <span>{{ $cat }}</span>
                        <span class="text-brand-600">{{ $rating }}</span>
                    </div>
                    <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-500 rounded-full" style="width: {{ $rating * 20 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-gradient-to-br from-brand-50 to-brand-100 p-6 rounded-xl">
            <span class="material-symbols-outlined text-3xl text-brand-600 mb-3">recommend</span>
            <p class="text-xl font-bold text-text-primary mb-2">98% of guests recommend</p>
            <p class="text-sm text-text-secondary">Based on recent guest feedback from the last 12 months.</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="flex flex-wrap gap-4 mb-8">
        <div class="relative flex-grow max-w-md">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-text-muted">search</span>
            <input id="searchReviews" class="w-full pl-12 pr-4 py-3 bg-surface-card border border-border-color rounded-xl text-text-primary focus:outline-none focus:ring-2 focus:ring-brand-500"
                   placeholder="Search reviews" type="text">
        </div>
        <button class="flex items-center gap-2 px-6 py-3 bg-surface-card border border-border-color rounded-xl text-text-primary hover:bg-gray-50 transition">
            <span class="material-symbols-outlined text-sm">tune</span>
            Filters
        </button>
        <button class="flex items-center gap-2 px-6 py-3 bg-surface-card border border-border-color rounded-xl text-text-primary hover:bg-gray-50 transition">
            Newest <span class="material-symbols-outlined text-sm">expand_more</span>
        </button>
    </div>

    <!-- Reviews List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="reviewsList">
        @foreach($property->reviews as $review)
        <div class="bg-surface-card p-6 rounded-xl shadow-sm border border-border-color hover:shadow-md transition">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-semibold">
                    {{ substr($review->user->name, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-semibold text-text-primary">{{ $review->user->name }}</h4>
                    <p class="text-text-muted text-sm">
                        {{ \Carbon\Carbon::parse($review->created_at)->format('F Y') }} • Stayed {{ \Carbon\Carbon::parse($review->created_at)->diffInDays($property->created_at ?? now()) }} nights
                    </p>
                </div>
            </div>
            <div class="flex gap-1 mb-3">
                @for($i=1;$i<=5;$i++)
                    <span class="material-symbols-outlined text-yellow-400 text-sm">{{ $i <= $review->rating ? 'star' : 'star_border' }}</span>
                @endfor
            </div>
            <p class="text-text-secondary mb-4">{{ $review->comment }}</p>
            <div class="flex items-center gap-4">
                <button class="flex items-center gap-1 text-text-muted hover:text-brand-600 transition text-sm">
                    <span class="material-symbols-outlined text-sm">thumb_up</span>
                    Helpful
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8 flex justify-center gap-2">
        {{ $property->reviews->links() }}
    </div>
</div>

<!-- Write Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface-card rounded-xl p-6 max-w-md w-full mx-4 shadow-xl border border-border-color">
        <h3 class="text-xl font-bold text-text-primary mb-4 heading-font">Write a Review for {{ $property->title }}</h3>

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
            <textarea id="reviewComment" rows="4" class="w-full px-4 py-2 rounded-lg border border-border-color focus:outline-none focus:ring-2 focus:ring-brand-500"
                      placeholder="Share your experience..."></textarea>
        </div>

        <div class="flex gap-3">
            <button onclick="submitReview()" class="flex-1 bg-brand-500 text-white py-2 rounded-lg font-semibold hover:bg-brand-600 transition">Submit Review</button>
            <button onclick="closeReviewModal()" class="flex-1 border border-border-color text-text-primary py-2 rounded-lg font-semibold hover:bg-gray-50 transition">Cancel</button>
        </div>
    </div>
</div>

<script>
    let currentPropertyId = {{ $property->id }};

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

    function openWriteReview() {
        document.getElementById('reviewModal').classList.add('flex');
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.getElementById('reviewModal').classList.remove('flex');
    }

    function submitReview() {
        let rating = document.getElementById('reviewRating').value;
        let comment = document.getElementById('reviewComment').value;

        fetch('{{ url("/customer/property") }}/' + currentPropertyId + '/review', {
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

    document.getElementById('searchReviews')?.addEventListener('input', function(e) {
        let search = e.target.value.toLowerCase();
        document.querySelectorAll('#reviewsList > div').forEach(card => {
            let text = card.innerText.toLowerCase();
            card.style.display = text.includes(search) ? 'block' : 'none';
        });
    });
</script>
@endsection
