<!-- File: resources/views/payment/payment-status.blade.php -->
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Payment Status | Eserian Homes</title>
    
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
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body">

<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl shadow-sm">
    <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
        <a href="<?php echo e(route('home')); ?>" class="text-2xl font-bold tracking-tighter text-blue-900">Eserian Homes</a>
    </div>
</nav>

<main class="min-h-screen flex items-center justify-center px-8">
    <div class="max-w-2xl w-full">
        <?php if($payment->status == 'completed'): ?>
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-green-600 text-4xl">check_circle</span>
                </div>
                <h1 class="font-headline text-3xl font-bold text-green-600 mb-2">Payment Successful!</h1>
                <p class="text-on-surface-variant mb-6">Your booking has been confirmed.</p>
                
                <div class="bg-gray-50 rounded-xl p-6 mb-6 text-left">
                    <h3 class="font-semibold mb-3">Booking Details</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Booking ID:</span>
                            <span class="font-semibold">#<?php echo e($payment->booking->id); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Property:</span>
                            <span class="font-semibold"><?php echo e($payment->booking->property->title); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Amount Paid:</span>
                            <span class="font-semibold">KES <?php echo e(number_format($payment->amount)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Transaction ID:</span>
                            <span class="font-semibold text-xs"><?php echo e($payment->transaction_id ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <a href="<?php echo e(route('customer.booking.details', $payment->booking->id)); ?>" 
                       class="flex-1 bg-primary text-white py-3 rounded-md font-semibold hover:bg-primary-container transition-colors">
                        View Booking
                    </a>
                    <a href="<?php echo e(route('customer.browse')); ?>" 
                       class="flex-1 border border-primary text-primary py-3 rounded-md font-semibold hover:bg-primary/5 transition-colors">
                        Browse More
                    </a>
                </div>
            </div>
        <?php elseif($payment->status == 'pending'): ?>
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse-slow">
                    <span class="material-symbols-outlined text-yellow-600 text-4xl">hourglass_empty</span>
                </div>
                <h1 class="font-headline text-3xl font-bold text-yellow-600 mb-2">Payment Pending</h1>
                <p class="text-on-surface-variant mb-4">Please check your phone and enter your M-Pesa PIN to complete the payment.</p>
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">Waiting for payment confirmation...</p>
                </div>
                <div class="flex gap-4">
                    <a href="<?php echo e(route('payment.mpesa', $payment->booking->id)); ?>" 
                       class="flex-1 bg-primary text-white py-3 rounded-md font-semibold hover:bg-primary-container transition-colors">
                        Try Again
                    </a>
                    <a href="<?php echo e(route('customer.bookings')); ?>" 
                       class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-md font-semibold hover:bg-gray-50 transition-colors">
                        My Bookings
                    </a>
                </div>
                <p class="text-xs text-on-surface-variant mt-4">
                    <span class="material-symbols-outlined text-xs align-middle">help</span>
                    Didn't receive the prompt? Make sure your phone is nearby and has network coverage.
                </p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-red-600 text-4xl">error</span>
                </div>
                <h1 class="font-headline text-3xl font-bold text-red-600 mb-2">Payment Failed</h1>
                <p class="text-on-surface-variant mb-6">We couldn't process your payment. Please try again.</p>
                <div class="flex gap-4">
                    <a href="<?php echo e(route('payment.mpesa', $payment->booking->id)); ?>" 
                       class="flex-1 bg-primary text-white py-3 rounded-md font-semibold hover:bg-primary-container transition-colors">
                        Retry Payment
                    </a>
                    <a href="<?php echo e(route('customer.bookings')); ?>" 
                       class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-md font-semibold hover:bg-gray-50 transition-colors">
                        View Bookings
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/payment/payment-status.blade.php ENDPATH**/ ?>