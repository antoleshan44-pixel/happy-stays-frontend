<?php
// File: app/Mail/BookingConfirmation.php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $payment;

    public function __construct(Booking $booking, Payment $payment = null)
    {
        $this->booking = $booking;
        $this->payment = $payment ?? $booking->payment;
    }

    public function build()
    {
        return $this->subject('Booking Confirmed & Payment Receipt - Eserian Homes #' . $this->booking->id)
                    ->view('emails.booking-confirmation');
    }
}