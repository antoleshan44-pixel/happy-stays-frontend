{{-- resources/views/customer/wishlist.blade.php --}}
@extends('layouts.app')

@section('title', 'My Wishlist')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --brand: #00288e;
        --brand-pale: #eef1fa;
        --love: #e11d48;
        --love-pale: #fff1f3;
        --text: #111827;
        --text-mid: #4b5563;
        --muted: #9ca3af;
        --border: #e5e7eb;
        --surface: #f7f9fb;
        --card: #ffffff;
    }

    .wl-page {
        font-family: 'Outfit', sans-serif;
        padding: 2.5rem 0 5rem;
        min-height: 100vh;
    }

    /* ── Header ── */
    .wl-header {
        margin-bottom: 3rem;
        position: relative;
    }
    .wl-header__top {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .wl-header__eyebrow {
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--love);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .wl-header__eyebrow::before {
        content: '';
        display: inline-block;
        width: 18px;
        height: 2px;
        background: var(--love);
        border-radius: 2px;
    }
    .wl-header__title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2.4rem, 5vw, 3.5rem);
        font-weight: 600;
        color: var(--text);
        line-height: 1.05;
        margin: 0 0 0.5rem;
    }
    .wl-header__title em {
        font-style: italic;
        color: var(--love);
    }
    .wl-header__sub {
        font-size: 0.9rem;
        color: var(--text-mid);
        font-weight: 300;
    }
    .wl-header__count {
        font-family: 'Cormorant Garamond', serif;
        font-size: 3.5rem;
        font-weight: 600;
        color: var(--brand-pale);
        line-height: 1;
        color: transparent;
        -webkit-text-stroke: 2px #d1d5db;
        user-select: none;
        flex-shrink: 0;
    }

    /* ── Grid ── */
    .wl-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 2rem;
    }
    @media (min-width: 640px) {
        .wl-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (min-width: 1024px) {
        .wl-grid { grid-template-columns: repeat(3, 1fr); }
    }

    /* ── Card ── */
    .wl-card {
        cursor: pointer;
        animation: fadeUp 0.5s ease both;
    }
    .wl-card:nth-child(1) { animation-delay: 0.05s; }
    .wl-card:nth-child(2) { animation-delay: 0.1s; }
    .wl-card:nth-child(3) { animation-delay: 0.15s; }
    .wl-card:nth-child(4) { animation-delay: 0.2s; }
    .wl-card:nth-child(5) { animation-delay: 0.25s; }
    .wl-card:nth-child(6) { animation-delay: 0.3s; }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .wl-card__img-wrap {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 1rem;
        overflow: hidden;
        background: #e5e7eb;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        transition: box-shadow 0.3s ease;
    }
    .wl-card:hover .wl-card__img-wrap {
        box-shadow: 0 12px 40px rgba(0,0,0,0.14);
    }
    .wl-card__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .wl-card:hover .wl-card__img {
        transform: scale(1.07);
    }
    .wl-card__overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.1) 45%, transparent 70%);
        pointer-events: none;
    }

    /* rating chip */
    .wl-card__rating {
        position: absolute;
        bottom: 0.85rem;
        left: 0.85rem;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.25);
        color: white;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.3rem 0.65rem;
        border-radius: 99px;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* type chip */
    .wl-card__type {
        position: absolute;
        top: 0.85rem;
        left: 0.85rem;
        background: rgba(0,40,142,0.75);
        backdrop-filter: blur(6px);
        color: white;
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.25rem 0.6rem;
        border-radius: 99px;
    }

    /* remove button */
    .wl-card__remove {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 50%;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(6px);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--love);
        transition: transform 0.2s ease, background 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .wl-card__remove:hover {
        transform: scale(1.15);
        background: var(--love);
        color: white;
    }

    /* card body */
    .wl-card__body {
        padding: 0.9rem 0.25rem 0;
    }
    .wl-card__name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text);
        line-height: 1.25;
        margin-bottom: 0.3rem;
        transition: color 0.2s;
    }
    .wl-card:hover .wl-card__name {
        color: var(--brand);
    }
    .wl-card__location {
        font-size: 0.78rem;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-bottom: 0.6rem;
    }
    .wl-card__price-row {
        display: flex;
        align-items: baseline;
        gap: 0.3rem;
    }
    .wl-card__price {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--brand);
    }
    .wl-card__per {
        font-size: 0.78rem;
        color: var(--muted);
        font-weight: 400;
    }

    /* ── Divider ── */
    .wl-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, var(--border) 20%, var(--border) 80%, transparent);
        margin: 3rem 0;
    }

    /* ── Empty state ── */
    .wl-empty {
        text-align: center;
        padding: 5rem 2rem;
        max-width: 420px;
        margin: 0 auto;
    }
    .wl-empty__heart {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        display: block;
        animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    .wl-empty__title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 0.75rem;
    }
    .wl-empty__sub {
        font-size: 0.9rem;
        color: var(--text-mid);
        font-weight: 300;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    .wl-empty__cta {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--brand);
        color: white;
        padding: 0.8rem 1.75rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background 0.2s, transform 0.2s;
    }
    .wl-empty__cta:hover {
        background: #002072;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="wl-page">
<div class="container-custom">

    {{-- Header --}}
    <div class="wl-header">
        <div class="wl-header__top">
            <div>
                <p class="wl-header__eyebrow">My Collection</p>
                <h1 class="wl-header__title">Places I <em>love</em></h1>
                <p class="wl-header__sub">Your handpicked escapes, saved and ready when you are.</p>
            </div>
            @if($properties->count() > 0)
            <div class="wl-header__count">{{ $properties->count() }}</div>
            @endif
        </div>
    </div>

    @if($properties->count() > 0)

    <div class="wl-grid">
        @foreach($properties as $property)
        @php
            $photoPath = null;
            if (isset($property->photos) && is_array($property->photos) && count($property->photos) > 0) {
                $photoPath = $property->photos[0]->photo_path ?? null;
            }
        @endphp
        <div class="wl-card" onclick="window.location.href='{{ route('customer.property.detail', $property->id) }}'">
            <div class="wl-card__img-wrap">
                <img class="wl-card__img"
                     src="{{ $photoPath ? asset('storage/' . $photoPath) : 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=600&h=450&fit=crop' }}"
                     alt="{{ $property->title }}"
                     loading="lazy">
                <div class="wl-card__overlay"></div>

                <span class="wl-card__type">{{ $property->property_type ?? 'Property' }}</span>

                <div class="wl-card__rating">
                    ⭐ {{ number_format($property->averageRating ?? 4.5, 1) }}
                </div>

                <button class="wl-card__remove"
                        onclick="event.stopPropagation(); removeFromFavorites({{ $property->id }})"
                        title="Remove from wishlist">
                    <span class="material-symbols-outlined" style="font-size:18px; font-variation-settings:'FILL' 1;">favorite</span>
                </button>
            </div>

            <div class="wl-card__body">
                <div class="wl-card__name">{{ $property->title }}</div>
                <div class="wl-card__location">
                    <span class="material-symbols-outlined" style="font-size:14px;">location_on</span>
                    {{ $property->location }}
                </div>
                <div class="wl-card__price-row">
                    <span class="wl-card__price">KES {{ number_format($property->price_per_night) }}</span>
                    <span class="wl-card__per">/ night</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @else

    {{-- Empty state --}}
    <div class="wl-empty">
        <span class="wl-empty__heart">🤍</span>
        <h2 class="wl-empty__title">Nothing saved yet</h2>
        <p class="wl-empty__sub">
            Start exploring Kenya's finest properties and tap the heart to save the ones that speak to you.
        </p>
        <a href="{{ route('customer.browse') }}" class="wl-empty__cta">
            <span class="material-symbols-outlined" style="font-size:18px;">travel_explore</span>
            Explore Properties
        </a>
    </div>

    @endif

</div>
</div>
@endsection

@push('scripts')
<script>
function removeFromFavorites(id) {
    if (!confirm('Remove this property from your wishlist?')) return;

    fetch('{{ url("/customer/property") }}/' + id + '/remove', {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => {
        if (res.ok) {
            // Animate card out before reload
            const cards = document.querySelectorAll('.wl-card');
            cards.forEach(card => {
                if (card.querySelector(`[onclick*="${id}"]`)) {
                    card.style.transition = 'opacity 0.3s, transform 0.3s';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => location.reload(), 300);
                }
            });
        }
    });
}
</script>
@endpush
