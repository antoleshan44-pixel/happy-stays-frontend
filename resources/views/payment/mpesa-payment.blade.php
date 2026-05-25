<!-- File: resources/views/payment/mpesa-payment.blade.php -->
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Payment | Eserian Homes</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#00288e",
                        "primary-container": "#1e40af",
                        "surface": "#f7f9fb",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#444653",
                    },
                    fontFamily: {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                    }
                },
            },
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .payment-card {
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body">

<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl shadow-sm">
    <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
        <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tighter text-blue-900">Eserian Homes</a>

        <div class="hidden md:flex items-center space-x-12">
            <a class="font-manrope tracking-tight font-semibold text-slate-500 hover:text-blue-700 transition-colors" href="{{ route('customer.browse') }}">Collections</a>
            <a class="font-manrope tracking-tight font-semibold text-slate-500 hover:text-blue-700 transition-colors" href="{{ route('register') }}?role=owner">List Property</a>
            <a class="font-manrope tracking-tight font-semibold text-slate-500 hover:text-blue-700 transition-colors" href="{{ route('customer.browse') }}">Concierge</a>
        </div>

        <div class="flex items-center space-x-6">
            @guest
                <a href="{{ route('login') }}" class="text-slate-500 font-manrope font-semibold hover:text-blue-700 transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="bg-gradient-to-br from-primary to-primary-container text-on-primary px-6 py-2.5 rounded-md font-manrope font-bold transition-all">Inquire</a>
            @else
                <span class="text-slate-500 font-manrope font-semibold hidden md:inline">Welcome, {{ Auth::user()->name }}</span>
                <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isOwner() ? route('owner.dashboard') : route('customer.dashboard')) }}"
                   class="bg-gradient-to-br from-primary to-primary-container text-on-primary px-6 py-2.5 rounded-md font-manrope font-bold transition-all">
                    Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-slate-500 font-manrope font-semibold hover:text-red-600 transition-colors">Logout</button>
                </form>
            @endguest
        </div>
    </div>
</nav>

<main class="pt-32 pb-20 px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Payment Header -->
        <div class="text-center mb-8">
            <span class="material-symbols-outlined text-5xl text-primary mb-2">payments</span>
            <h1 class="font-headline text-3xl md:text-4xl font-extrabold text-blue-900 tracking-tighter">
                Complete Your Payment
            </h1>
            <p class="text-on-surface-variant mt-2">Secure payment via M-Pesa</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Booking Summary -->
            <div class="payment-card">
                <div class="bg-surface-container-lowest rounded-xl p-6">
                    <h2 class="font-headline text-xl font-bold text-blue-900 mb-4 flex items-center">
                        <span class="material-symbols-outlined mr-2">receipt</span>
                        Booking Summary
                    </h2>

                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-outline-variant">
                            <span class="text-on-surface-variant">Property</span>
                            <span class="font-semibold">{{ $booking->property->title ?? $booking->property['title'] ?? 'Property' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-outline-variant">
                            <span class="text-on-surface-variant">Check-in</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-outline-variant">
                            <span class="text-on-surface-variant">Check-out</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-outline-variant">
                            <span class="text-on-surface-variant">Number of nights</span>
                            <span class="font-semibold">{{ $booking->nights ?? \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date)) }} nights</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-outline-variant">
                            <span class="text-on-surface-variant">Guests</span>
                            <span class="font-semibold">{{ $booking->guests }} guests</span>
                        </div>
                        <div class="flex justify-between py-3 mt-2 bg-primary/5 rounded-lg px-3">
                            <span class="font-bold text-lg">Total Amount</span>
                            <span class="font-bold text-2xl text-primary">KES {{ number_format($booking->total_price ?? $payment->amount ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- M-Pesa Payment Form -->
            <div class="payment-card">
                <div class="bg-surface-container-lowest rounded-xl p-6">
                    <h2 class="font-headline text-xl font-bold text-blue-900 mb-4 flex items-center">
                        <span class="material-symbols-outlined mr-2">phone_android</span>
                        M-Pesa Payment
                    </h2>

                    <div class="mb-6 p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-600">info</span>
                            <p class="text-sm text-green-800">You will receive an STK Push on your phone. Enter your M-Pesa PIN to complete payment.</p>
                        </div>
                    </div>

                    <form action="{{ route('payment.mpesa.process', $booking->id ?? $payment->booking_id) }}" method="POST" id="paymentForm">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-semibold mb-2">M-Pesa Phone Number</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">+254</span>
                                <input type="tel" name="phone" id="phone" required
                                       placeholder="712345678"
                                       value="708374149"
                                       class="w-full pl-14 pr-4 py-3 rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">Enter the phone number registered with M-Pesa</p>
                        </div>

                        <div class="mb-6">
                            <div class="bg-surface-container-high rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm">Amount to pay:</span>
                                    <span class="font-bold text-xl text-primary">KES {{ number_format($booking->total_price ?? $payment->amount ?? 0) }}</span>
                                </div>
                                <div class="text-xs text-on-surface-variant">
                                    <p>✓ No additional fees</p>
                                    <p>✓ Secure SSL encryption</p>
                                    <p>✓ Instant confirmation</p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="payButton"
                                class="w-full bg-primary text-white py-3 rounded-md font-bold hover:bg-primary-container transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">lock</span>
                            Pay with M-Pesa
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <a href="{{ route('customer.booking.details', $booking->id ?? $payment->booking_id) }}" class="text-primary hover:underline text-sm">
                            Cancel and return to booking
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Info -->
        <div class="mt-8 text-center">
            <div class="flex items-center justify-center gap-6 text-xs text-on-surface-variant">
                <span class="flex items-center"><span class="material-symbols-outlined text-sm mr-1">verified</span> Secure Payment</span>
                <span class="flex items-center"><span class="material-symbols-outlined text-sm mr-1">schedule</span> Instant Confirmation</span>
                <span class="flex items-center"><span class="material-symbols-outlined text-sm mr-1">support_agent</span> 24/7 Support</span>
            </div>
        </div>
    </div>
</main>

<footer class="w-full py-16 px-8 bg-slate-50 border-t border-slate-200/20 mt-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
        <div class="col-span-1 md:col-span-1">
            <div class="text-xl font-black text-blue-900 mb-6">Eserian Homes</div>
            <p class="font-inter text-sm leading-relaxed text-slate-500 mb-6">© 2024 Eserian Homes. Architectural Excellence.</p>
        </div>
        <div>
            <h4 class="font-manrope font-bold text-blue-900 mb-6">Company</h4>
            <ul class="space-y-4">
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">About Us</a></li>
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Sustainability</a></li>
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Investment</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-manrope font-bold text-blue-900 mb-6">Support</h4>
            <ul class="space-y-4">
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Contact Support</a></li>
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Privacy Policy</a></li>
                <li><a class="font-inter text-sm text-slate-500 hover:text-blue-600 transition-colors" href="#">Terms of Service</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-manrope font-bold text-blue-900 mb-6">Newsletter</h4>
            <p class="font-inter text-sm text-slate-500 mb-4">Curated architectural insights delivered monthly.</p>
            <div class="flex">
                <input class="bg-surface-container border-none text-sm px-4 py-2 w-full focus:ring-1 focus:ring-primary rounded-l-md" placeholder="Email Address" type="email"/>
                <button class="bg-primary text-white px-4 py-2 rounded-r-md hover:bg-primary-container transition-colors">Join</button>
            </div>
        </div>
    </div>
</footer>

<script>
document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
    const payButton = document.getElementById('payButton');
    payButton.disabled = true;
    payButton.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Processing...';

    // Phone number validation
    const phone = document.getElementById('phone').value;
    const phoneRegex = /^[0-9]{9}$/;
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert('Please enter a valid 9-digit phone number (e.g., 712345678)');
        payButton.disabled = false;
        payButton.innerHTML = '<span class="material-symbols-outlined">lock</span> Pay with M-Pesa';
    }
});
</script>
</body>
</html>
