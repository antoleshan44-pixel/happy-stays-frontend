{{-- resources/views/about.blade.php --}}
@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container-custom">
    <!-- Hero Section -->
    <section class="py-12 md:py-16 text-center">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-text-primary heading-font mb-4">
            About <span class="text-brand-600">Eserian Homes</span>
        </h1>
        <p class="text-text-secondary text-lg max-w-3xl mx-auto">
            Redefining luxury hospitality in Kenya through architectural excellence and unforgettable experiences.
        </p>
    </section>

    <!-- Our Story -->
    <section class="py-12 border-t border-border-color">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-4">Our Story</h2>
                <p class="text-text-secondary mb-4 leading-relaxed">
                    Founded in 2020, Eserian Homes was born from a passion for exceptional architecture and a vision to showcase Kenya's most stunning properties to the world.
                </p>
                <p class="text-text-secondary mb-4 leading-relaxed">
                    What started as a small collection of premium villas has grown into Kenya's premier luxury stay platform, curating over 50 of the most architecturally significant homes across the country.
                </p>
                <p class="text-text-secondary leading-relaxed">
                    Today, we're proud to connect discerning travelers with extraordinary spaces that blend modern luxury with authentic Kenyan hospitality.
                </p>
            </div>
            <div class="rounded-2xl overflow-hidden shadow-lg">
                <img src="https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=800&h=600&fit=crop"
                     alt="Luxury villa" class="w-full h-full object-cover">
            </div>
        </div>
    </section>

    <!-- Our Mission -->
    <section class="py-12 bg-brand-50 rounded-2xl my-8">
        <div class="max-w-4xl mx-auto text-center px-6">
            <h2 class="text-2xl md:text-3xl font-bold text-text-primary heading-font mb-4">Our Mission</h2>
            <p class="text-text-secondary text-lg leading-relaxed">
                To provide unparalleled stay experiences by curating exceptional properties and delivering world-class hospitality, while showcasing the beauty and diversity of Kenya.
            </p>
        </div>
    </section>

    <!-- Our Values -->
    <section class="py-12">
        <h2 class="text-2xl md:text-3xl font-bold text-text-primary heading-font text-center mb-12">Our Values</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6 bg-surface-card rounded-xl shadow-sm border border-border-color">
                <div class="w-16 h-16 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-brand-600 text-2xl">verified</span>
                </div>
                <h3 class="text-xl font-semibold text-text-primary mb-2 heading-font">Quality</h3>
                <p class="text-text-muted">Every property in our collection meets rigorous standards for design, comfort, and amenities.</p>
            </div>
            <div class="text-center p-6 bg-surface-card rounded-xl shadow-sm border border-border-color">
                <div class="w-16 h-16 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-brand-600 text-2xl">handshake</span>
                </div>
                <h3 class="text-xl font-semibold text-text-primary mb-2 heading-font">Trust</h3>
                <p class="text-text-muted">We build lasting relationships with guests and property owners through transparency and reliability.</p>
            </div>
            <div class="text-center p-6 bg-surface-card rounded-xl shadow-sm border border-border-color">
                <div class="w-16 h-16 bg-brand-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-brand-600 text-2xl">diversity_3</span>
                </div>
                <h3 class="text-xl font-semibold text-text-primary mb-2 heading-font">Community</h3>
                <p class="text-text-muted">Supporting local communities and showcasing Kenyan culture and hospitality to the world.</p>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="py-12 bg-brand-600 rounded-2xl my-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-white">
            <div>
                <div class="text-3xl md:text-4xl font-bold heading-font">500+</div>
                <p class="text-brand-100 text-sm mt-1">Happy Guests</p>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold heading-font">50+</div>
                <p class="text-brand-100 text-sm mt-1">Luxury Properties</p>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold heading-font">15+</div>
                <p class="text-brand-100 text-sm mt-1">Locations</p>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold heading-font">4.9</div>
                <p class="text-brand-100 text-sm mt-1">Average Rating</p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-12">
        <h2 class="text-2xl md:text-3xl font-bold text-text-primary heading-font text-center mb-12">Meet Our Team</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center mx-auto mb-4 text-white text-3xl font-bold shadow-lg">
                    JD
                </div>
                <h3 class="text-xl font-semibold text-text-primary heading-font">Jane Doe</h3>
                <p class="text-brand-600 text-sm mb-2">Founder & CEO</p>
                <p class="text-text-muted text-sm">Passionate about architecture and creating unforgettable experiences.</p>
            </div>
            <div class="text-center">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center mx-auto mb-4 text-white text-3xl font-bold shadow-lg">
                    JS
                </div>
                <h3 class="text-xl font-semibold text-text-primary heading-font">John Smith</h3>
                <p class="text-brand-600 text-sm mb-2">Head of Hospitality</p>
                <p class="text-text-muted text-sm">Ensuring every guest receives exceptional service.</p>
            </div>
            <div class="text-center">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center mx-auto mb-4 text-white text-3xl font-bold shadow-lg">
                    MW
                </div>
                <h3 class="text-xl font-semibold text-text-primary heading-font">Mary Wanjiku</h3>
                <p class="text-brand-600 text-sm mb-2">Property Curator</p>
                <p class="text-text-muted text-sm">Discovering Kenya's most extraordinary homes.</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-12 mb-12">
        <div class="bg-gradient-to-r from-brand-500 to-brand-600 rounded-2xl p-8 md:p-12 text-center text-white">
            <h2 class="text-2xl md:text-3xl font-bold heading-font mb-3">Ready to experience Eserian Homes?</h2>
            <p class="text-brand-100 mb-6 max-w-2xl mx-auto">Browse our curated collection of luxury properties and book your perfect stay today.</p>
            <a href="{{ route('customer.browse') }}" class="inline-flex items-center gap-2 bg-white text-brand-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition shadow-lg">
                Browse Properties
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    </section>
</div>
@endsection
