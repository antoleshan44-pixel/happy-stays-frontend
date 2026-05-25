<!-- File: resources/views/emails/booking-confirmation.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Eserian Homes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #191c1e;
            background-color: #f7f9fb;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .email-wrapper {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
        }
        
        /* Header Section */
        .header {
            background: linear-gradient(135deg, #00288e 0%, #1e40af 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: white;
            margin-bottom: 15px;
            font-family: 'Manrope', sans-serif;
        }
        
        .logo span {
            color: #a8b8ff;
        }
        
        .success-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .success-icon svg {
            width: 40px;
            height: 40px;
            color: white;
        }
        
        .header h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: 'Manrope', sans-serif;
        }
        
        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
        }
        
        /* Content Section */
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            margin-bottom: 30px;
        }
        
        .greeting h2 {
            color: #00288e;
            font-size: 22px;
            margin-bottom: 10px;
            font-family: 'Manrope', sans-serif;
        }
        
        .greeting p {
            color: #444653;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 25px;
        }
        
        /* Info Cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #f7f9fb;
            padding: 20px;
            border-radius: 15px;
            border: 1px solid #e6e8ea;
        }
        
        .info-card h3 {
            color: #00288e;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }
        
        .info-card p {
            color: #191c1e;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .info-card small {
            color: #757684;
            font-size: 12px;
        }
        
        /* Property Card */
        .property-card {
            background: #f7f9fb;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #e6e8ea;
        }
        
        .property-title {
            font-size: 20px;
            font-weight: 700;
            color: #00288e;
            margin-bottom: 10px;
            font-family: 'Manrope', sans-serif;
        }
        
        .property-location {
            color: #444653;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .property-details {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e3e5;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #444653;
            font-size: 14px;
        }
        
        /* Price Breakdown */
        .price-breakdown {
            background: #f7f9fb;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #e6e8ea;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e3e5;
        }
        
        .price-row.total {
            border-bottom: none;
            padding-top: 15px;
            margin-top: 5px;
            font-size: 18px;
            font-weight: 700;
            color: #00288e;
        }
        
        /* Receipt Section */
        .receipt-section {
            background: linear-gradient(135deg, #f0f4ff 0%, #e8edf8 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #dde1ff;
        }
        
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #b8c4ff;
        }
        
        .receipt-header h3 {
            color: #00288e;
            font-size: 18px;
            font-weight: 700;
        }
        
        .receipt-badge {
            background: #10b981;
            color: white;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .receipt-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .receipt-item {
            display: flex;
            flex-direction: column;
        }
        
        .receipt-label {
            font-size: 12px;
            color: #444653;
            margin-bottom: 5px;
        }
        
        .receipt-value {
            font-size: 16px;
            font-weight: 600;
            color: #191c1e;
        }
        
        .receipt-value.mono {
            font-family: monospace;
            font-size: 13px;
        }
        
        /* Button */
        .button-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .btn-primary {
            display: inline-block;
            background: #00288e;
            color: white;
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 40, 142, 0.3);
        }
        
        .btn-primary:hover {
            background: #1e40af;
            transform: translateY(-2px);
        }
        
        /* Footer */
        .footer {
            background: #f7f9fb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e6e8ea;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .social-links a {
            color: #757684;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: #00288e;
        }
        
        .footer p {
            color: #757684;
            font-size: 12px;
            margin-bottom: 8px;
        }
        
        .footer .contact {
            color: #00288e;
            font-weight: 600;
            margin-top: 10px;
        }
        
        @media (max-width: 500px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .receipt-details {
                grid-template-columns: 1fr;
            }
            
            .content {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <!-- Header -->
            <div class="header">
                <div class="logo">ESERIAN <span>HOMES</span></div>
                <div class="success-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1>Booking Confirmed! 🎉</h1>
                <p>Your architectural journey begins here</p>
            </div>
            
            <!-- Content -->
            <div class="content">
                <div class="greeting">
                    <h2>Dear {{ $booking->customer->name }},</h2>
                    <p>Great news! Your booking has been confirmed and your payment has been successfully processed.</p>
                </div>
                
                <div style="text-align: center;">
                    <div class="status-badge">
                        ✓ CONFIRMED & PAID
                    </div>
                </div>
                
                <!-- Booking & Payment Info Grid -->
                <div class="info-grid">
                    <div class="info-card">
                        <h3>Booking ID</h3>
                        <p>#{{ $booking->id }}</p>
                        <small>Keep this for reference</small>
                    </div>
                    <div class="info-card">
                        <h3>Payment Status</h3>
                        <p>Completed ✓</p>
                        <small>Transaction ID: {{ $payment->transaction_id ?? 'N/A' }}</small>
                    </div>
                </div>
                
                <!-- Property Details -->
                <div class="property-card">
                    <div class="property-title">{{ $booking->property->title }}</div>
                    <div class="property-location">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        {{ $booking->property->location }}
                    </div>
                    <div class="property-details">
                        <span class="detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            </svg>
                            {{ $booking->property->bedrooms }} beds
                        </span>
                        <span class="detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            {{ $booking->guests }} guests
                        </span>
                    </div>
                </div>
                
                <!-- Trip Dates -->
                <div class="info-grid">
                    <div class="info-card">
                        <h3>Check-in Date</h3>
                        <p>{{ $booking->check_in_date->format('l, F d, Y') }}</p>
                        <small>After 3:00 PM</small>
                    </div>
                    <div class="info-card">
                        <h3>Check-out Date</h3>
                        <p>{{ $booking->check_out_date->format('l, F d, Y') }}</p>
                        <small>Before 11:00 AM</small>
                    </div>
                </div>
                
                <!-- Price Breakdown -->
                <div class="price-breakdown">
                    <div class="price-row">
                        <span>Price per night</span>
                        <span>KES {{ number_format($booking->property->price_per_night) }}</span>
                    </div>
                    <div class="price-row">
                        <span>Number of nights</span>
                        <span>{{ $booking->nights }} nights</span>
                    </div>
                    <div class="price-row total">
                        <span>Total Amount Paid</span>
                        <span>KES {{ number_format($booking->total_price) }}</span>
                    </div>
                </div>
                
                <!-- Payment Receipt Section -->
                <div class="receipt-section">
                    <div class="receipt-header">
                        <h3>Payment Receipt</h3>
                        <div class="receipt-badge">PAID</div>
                    </div>
                    <div class="receipt-details">
                        <div class="receipt-item">
                            <span class="receipt-label">Receipt Number</span>
                            <span class="receipt-value">RCP-{{ $payment->id }}</span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-label">Payment Date</span>
                            <span class="receipt-value">{{ $payment->created_at->format('F d, Y H:i') }}</span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-label">Transaction ID</span>
                            <span class="receipt-value mono">{{ $payment->transaction_id ?? 'MPESA-' . $payment->id }}</span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-label">Payment Method</span>
                            <span class="receipt-value">M-Pesa</span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-label">Amount Paid</span>
                            <span class="receipt-value" style="color: #00288e; font-size: 18px;">KES {{ number_format($payment->amount) }}</span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-label">Payment Status</span>
                            <span class="receipt-value" style="color: #10b981;">Completed ✓</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Button -->
                <div class="button-container">
                    <a href="{{ route('customer.booking.details', $booking->id) }}" class="btn-primary">
                        View Full Booking Details
                    </a>
                </div>
                
                <!-- Additional Info -->
                <div style="background: #fef3c7; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div>
                            <p style="color: #92400e; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Important Information</p>
                            <p style="color: #78350f; font-size: 12px;">Please have your booking confirmation ready upon arrival. Check-in time is 3:00 PM and check-out is 11:00 AM.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <div class="social-links">
                    <a href="#">📷 Instagram</a>
                    <a href="#">🐦 Twitter</a>
                    <a href="#">📘 Facebook</a>
                    <a href="#">🔗 LinkedIn</a>
                </div>
                <p>Eserian Homes - Architectural Excellence in Every Stay</p>
                <p>Nairobi, Kenya | +254 700 000 000</p>
                <p class="contact">📧 support@eserianhomes.com | 🌐 www.eserianhomes.com</p>
                <p style="margin-top: 20px;">Need help? Reply to this email or contact our concierge team 24/7</p>
                <p style="margin-top: 15px;">© {{ date('Y') }} Eserian Homes. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>