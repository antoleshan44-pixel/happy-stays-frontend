<?php
// File: app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function downloadInvoice($bookingId)
    {
        $booking = Booking::with(['property', 'customer', 'payment'])
            ->where('customer_id', Auth::id())
            ->findOrFail($bookingId);
        
        $pdf = Pdf::loadView('pdf.invoice', compact('booking'));
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('Eserian_Homes_Invoice_' . $booking->id . '.pdf');
    }
}