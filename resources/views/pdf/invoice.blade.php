<!-- File: resources/views/pdf/invoice.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $booking->id }} | Eserian Homes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9edf2 100%);
            padding: 40px;
            line-height: 1.5;
        }
        
        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Header Section */
        .invoice-header {
            background: linear-gradient(135deg, #00288e 0%, #1e40af 100%);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 80%;
            height: 200%;
            background: rgba(255, 255, 255, 0.05);
            transform: rotate(35deg);
            pointer-events: none;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }
        
        .logo-section {
            flex: 1;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
            color: white;
            font-family: 'Manrope', sans-serif;
            margin-bottom: 8px;
        }
        
        .logo span {
            color: #a8b8ff;
        }
        
        .tagline {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            letter-spacing: 1px;
        }
        
        .invoice-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 12px 24px;
            border-radius: 50px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .invoice-badge .label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .invoice-badge .number {
            color: white;
            font-size: 24px;
            font-weight: 700;
            font-family: monospace;
            margin-top: 4px;
        }
        
        .title-section {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .title-section h1 {
            color: white;
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 8px;
            font-family: 'Manrope', sans-serif;
        }
        
        .title-section p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 15px;
        }
        
        /* Content Section */
        .invoice-content {
            padding: 40px;
        }
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f2f5;
        }
        
        .info-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .info-card h3 {
            color: #00288e;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-card .icon {
            font-size: 18px;
        }
        
        .info-card p {
            color: #1e293b;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .info-card small {
            color: #64748b;
            font-size: 12px;
        }
        
        /* Property Card */
        .property-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 40px;
            border: 1px solid #e2e8f0;
        }
        
        .property-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .property-title {
            flex: 1;
        }
        
        .property-title h2 {
            color: #00288e;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            font-family: 'Manrope', sans-serif;
        }
        
        .property-location {
            color: #64748b;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .property-type {
            background: white;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            color: #00288e;
            border: 1px solid #dde1ff;
        }
        
        .property-details {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            font-size: 14px;
        }
        
        .detail-item strong {
            color: #00288e;
            font-weight: 600;
        }
        
        /* Trip Dates */
        .trip-dates {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .date-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        
        .date-label {
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .date-value {
            color: #00288e;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .date-time {
            color: #94a3b8;
            font-size: 12px;
        }
        
        /* Price Breakdown */
        .price-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .price-title {
            font-size: 18px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed rgba(146, 64, 14, 0.2);
        }
        
        .price-row:last-child {
            border-bottom: none;
        }
        
        .price-row.total {
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #92400e;
            font-size: 20px;
            font-weight: 800;
            color: #92400e;
        }
        
        /* Payment Receipt */
        .receipt-section {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #a7f3d0;
        }
        
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #6ee7b7;
        }
        
        .receipt-header h3 {
            color: #065f46;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .paid-badge {
            background: #10b981;
            color: white;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .receipt-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .receipt-item {
            display: flex;
            flex-direction: column;
        }
        
        .receipt-label {
            font-size: 11px;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .receipt-value {
            font-size: 15px;
            font-weight: 600;
            color: #064e3b;
        }
        
        .receipt-value.mono {
            font-family: monospace;
            font-size: 12px;
        }
        
        /* Footer */
        .invoice-footer {
            background: #f8fafc;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-content {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .footer-content p {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 8px;
        }
        
        .footer-content .contact {
            color: #00288e;
            font-weight: 600;
            margin-top: 12px;
            font-size: 13px;
        }
        
        .thankyou {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #00288e;
            font-weight: 600;
            font-size: 14px;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
            
            .status-badge {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 20px;
            }
            
            .invoice-header {
                padding: 25px;
            }
            
            .header-top {
                flex-direction: column;
                gap: 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .trip-dates {
                grid-template-columns: 1fr;
            }
            
            .receipt-grid {
                grid-template-columns: 1fr;
            }
            
            .invoice-content {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-top">
                <div class="logo-section">
                    <div class="logo">ESERIAN <span>HOMES</span></div>
                    <div class="tagline">Architectural Excellence in Every Stay</div>
                </div>
                <div class="invoice-badge">
                    <div class="label">Invoice Number</div>
                    <div class="number">#INV-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
            
            <div class="title-section">
                <h1>Tax Invoice</h1>
                <p>Official Payment Receipt & Booking Confirmation</p>
                <div class="status-badge">✓ PAID & CONFIRMED</div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="invoice-content">
            <!-- Customer & Booking Info -->
            <div class="info-grid">
                <div class="info-card">
                    <h3>
                        <span class="icon">👤</span>
                        BILLED TO
                    </h3>
                    <p>{{ $booking->customer->name }}</p>
                    <p style="font-size: 14px; font-weight: normal;">{{ $booking->customer->email }}</p>
                    <p style="font-size: 14px; font-weight: normal;">{{ $booking->customer->phone }}</p>
                </div>
                <div class="info-card">
                    <h3>
                        <span class="icon">📄</span>
                        INVOICE DETAILS
                    </h3>
                    <p>Invoice Date: {{ now()->format('F d, Y') }}</p>
                    <p>Booking ID: #{{ $booking->id }}</p>
                    <p>Payment Status: <span style="color: #10b981;">Completed</span></p>
                </div>
            </div>
            
            <!-- Property Details -->
            <div class="property-card">
                <div class="property-header">
                    <div class="property-title">
                        <h2>{{ $booking->property->title }}</h2>
                        <div class="property-location">
                            📍 {{ $booking->property->location }}
                        </div>
                    </div>
                    <div class="property-type">
                        {{ $booking->property->property_type }}
                    </div>
                </div>
                <div class="property-details">
                    <div class="detail-item">
                        <span>🛏️</span>
                        <span><strong>{{ $booking->property->bedrooms }}</strong> Bedrooms</span>
                    </div>
                    <div class="detail-item">
                        <span>🛁</span>
                        <span><strong>{{ $booking->property->bathrooms }}</strong> Bathrooms</span>
                    </div>
                    <div class="detail-item">
                        <span>👥</span>
                        <span><strong>{{ $booking->guests }}</strong> Guests</span>
                    </div>
                    @if($booking->property->amenities)
                        <div class="detail-item">
                            <span>✨</span>
                            <span><strong>{{ count(json_decode($booking->property->amenities, true) ?? []) }}</strong> Amenities</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Trip Dates -->
            <div class="trip-dates">
                <div class="date-card">
                    <div class="date-label">CHECK-IN</div>
                    <div class="date-value">{{ $booking->check_in_date->format('l, F d, Y') }}</div>
                    <div class="date-time">After 3:00 PM</div>
                </div>
                <div class="date-card">
                    <div class="date-label">CHECK-OUT</div>
                    <div class="date-value">{{ $booking->check_out_date->format('l, F d, Y') }}</div>
                    <div class="date-time">Before 11:00 AM</div>
                </div>
            </div>
            
            <!-- Price Breakdown -->
            <div class="price-section">
                <div class="price-title">
                    💰 PRICE BREAKDOWN
                </div>
                <div class="price-row">
                    <span>Nightly Rate ({{ $booking->property->property_type }})</span>
                    <span>KES {{ number_format($booking->property->price_per_night) }}</span>
                </div>
                <div class="price-row">
                    <span>Number of Nights</span>
                    <span>{{ $booking->nights }} nights</span>
                </div>
                <div class="price-row">
                    <span>Subtotal</span>
                    <span>KES {{ number_format($booking->property->price_per_night * $booking->nights) }}</span>
                </div>
                <div class="price-row">
                    <span>Service Fee (0%)</span>
                    <span>KES 0</span>
                </div>
                <div class="price-row">
                    <span>Tax (0%)</span>
                    <span>KES 0</span>
                </div>
                <div class="price-row total">
                    <span>TOTAL AMOUNT</span>
                    <span>KES {{ number_format($booking->total_price) }}</span>
                </div>
            </div>
            
            <!-- Payment Receipt -->
            <div class="receipt-section">
                <div class="receipt-header">
                    <h3>
                        🧾 PAYMENT RECEIPT
                    </h3>
                    <div class="paid-badge">PAID IN FULL</div>
                </div>
                <div class="receipt-grid">
                    <div class="receipt-item">
                        <div class="receipt-label">Receipt Number</div>
                        <div class="receipt-value">RCP-{{ str_pad($booking->payment->id ?? $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="receipt-item">
                        <div class="receipt-label">Payment Date</div>
                        <div class="receipt-value">{{ ($booking->payment->created_at ?? now())->format('F d, Y H:i') }}</div>
                    </div>
                    <div class="receipt-item">
                        <div class="receipt-label">Transaction ID</div>
                        <div class="receipt-value mono">{{ $booking->payment->transaction_id ?? 'MPESA-' . $booking->id }}</div>
                    </div>
                    <div class="receipt-item">
                        <div class="receipt-label">Payment Method</div>
                        <div class="receipt-value">M-Pesa</div>
                    </div>
                    <div class="receipt-item">
                        <div class="receipt-label">Amount Paid</div>
                        <div class="receipt-value" style="font-size: 20px; font-weight: 800;">KES {{ number_format($booking->payment->amount ?? $booking->total_price) }}</div>
                    </div>
                    <div class="receipt-item">
                        <div class="receipt-label">Payment Status</div>
                        <div class="receipt-value" style="color: #10b981;">✓ Completed</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-content">
                <p>Eserian Homes - Architectural Excellence in Every Stay</p>
                <p>Nairobi, Kenya | +254 700 000 000</p>
                <p class="contact">📧 support@eserianhomes.com | 🌐 www.eserianhomes.com</p>
                <div class="thankyou">
                    Thank you for choosing Eserian Homes!
                </div>
                <p style="margin-top: 15px; font-size: 10px;">
                    This is a computer-generated document and requires no signature.
                    For any inquiries, please contact our concierge team 24/7.
                </p>
            </div>
        </div>
    </div>
</body>
</html>