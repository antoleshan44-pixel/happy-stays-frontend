<?php
// File: app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Services\SpringBootApiService;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $api;

    public function __construct(SpringBootApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Show M-Pesa payment form
     */
    public function initiatePayment($bookingId)
    {
        // Get booking from Spring Boot API
        $rawBooking = $this->api->getBookingDetails($bookingId);

        if (!$rawBooking) {
            abort(404, 'Booking not found');
        }

        $booking = ApiHelper::toBookingObject($rawBooking);
        $user = $this->api->getCurrentUser();

        // Create a payment record in session (since we don't have local DB)
        $paymentId = 'PAY_' . time() . '_' . $bookingId;
        $payment = (object)[
            'id' => $paymentId,
            'booking_id' => $bookingId,
            'amount' => $booking->total_price,
            'status' => 'pending',
            'payment_method' => 'mpesa'
        ];

        // Store payment in session
        session(["payment_{$paymentId}" => [
            'booking_id' => $bookingId,
            'amount' => $booking->total_price,
            'status' => 'pending',
            'created_at' => now()
        ]]);

        return view('payment.mpesa-payment', compact('booking', 'payment', 'user'));
    }

    /**
     * Process M-Pesa payment
     */
    public function processMpesaPayment(Request $request, $bookingId)
    {
        $request->validate([
            'phone' => 'required|string|min:9|max:13'
        ]);

        // Get booking from Spring Boot API
        $rawBooking = $this->api->getBookingDetails($bookingId);

        if (!$rawBooking) {
            return back()->with('error', 'Booking not found');
        }

        $booking = ApiHelper::toBookingObject($rawBooking);

        // Format phone number to international format
        $phone = $this->formatPhoneNumber($request->phone);

        Log::info('Processing M-Pesa payment', [
            'booking_id' => $bookingId,
            'phone' => $phone,
            'amount' => $booking->total_price
        ]);

        // Create payment record in session
        $paymentId = 'PAY_' . time() . '_' . $bookingId;
        session(["payment_{$paymentId}" => [
            'booking_id' => $bookingId,
            'amount' => $booking->total_price,
            'phone' => $phone,
            'status' => 'pending',
            'created_at' => now()
        ]]);

        // For sandbox/testing - simulate STK push
        // In production with real M-Pesa, you would call the API here

        // Simulate successful STK push for testing
        $simulateSuccess = true; // Set to false to test failure

        if ($simulateSuccess) {
            return redirect()->route('payment.status', $paymentId)
                ->with('success', 'STK Push sent! Please check your phone and enter PIN to complete payment.');
        } else {
            return back()->with('error', 'Payment initiation failed. Please try again.');
        }
    }

    /**
     * Show payment status
     */
    public function paymentStatus($id)
    {
        // Get payment from session
        $paymentData = session("payment_{$id}");

        if (!$paymentData) {
            // For demo, create mock payment data
            $payment = (object)[
                'id' => $id,
                'status' => 'pending',
                'amount' => 0,
                'booking_id' => null
            ];

            // Try to get booking if we have an ID in the payment ID
            if (strpos($id, 'PAY_') !== false) {
                $parts = explode('_', $id);
                $bookingId = end($parts);
                $rawBooking = $this->api->getBookingDetails($bookingId);
                if ($rawBooking) {
                    $payment->booking = ApiHelper::toBookingObject($rawBooking);
                    $payment->amount = $payment->booking->total_price;
                    $payment->booking_id = $bookingId;
                }
            }
        } else {
            $payment = (object)$paymentData;
            $rawBooking = $this->api->getBookingDetails($paymentData['booking_id']);
            if ($rawBooking) {
                $payment->booking = ApiHelper::toBookingObject($rawBooking);
            }
        }

        // For demo purposes, you can auto-complete payment after 3 seconds using JavaScript
        // Or provide a simulate button

        return view('payment.payment-status', compact('payment'));
    }

    /**
     * Handle M-Pesa callback from Safaricom
     */
    public function mpesaCallback(Request $request)
    {
        $data = $request->all();

        Log::info('M-Pesa Callback received', ['data' => $data]);

        if (isset($data['Body']['stkCallback'])) {
            $callback = $data['Body']['stkCallback'];
            $resultCode = $callback['ResultCode'];
            $checkoutRequestID = $callback['CheckoutRequestID'];
            $resultDesc = $callback['ResultDesc'] ?? '';

            // Find payment by transaction ID from session
            $paymentFound = false;
            $allPayments = [];

            // Search through session for payment with this transaction ID
            // This is simplified - in production you'd use a database

            if ($resultCode == 0) {
                Log::info('Payment completed successfully', [
                    'checkoutRequestID' => $checkoutRequestID,
                    'resultDesc' => $resultDesc
                ]);
            } else {
                Log::error('Payment failed', [
                    'checkoutRequestID' => $checkoutRequestID,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc
                ]);
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    /**
     * Simulate successful M-Pesa payment (for testing)
     */
    public function simulateMpesaPayment($paymentId)
    {
        // Update payment status in session
        $paymentData = session("payment_{$paymentId}");

        if ($paymentData) {
            $paymentData['status'] = 'completed';
            session(["payment_{$paymentId}" => $paymentData]);

            Log::info('Payment simulated successfully', ['payment_id' => $paymentId]);

            return redirect()->route('payment.status', $paymentId)
                ->with('success', 'Payment completed successfully! Your booking is now confirmed.');
        }

        return redirect()->route('customer.bookings')
            ->with('error', 'Payment not found');
    }

    /**
     * Format phone number to international format for M-Pesa
     */
    private function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading 0 or +254
        if (substr($phone, 0, 1) == '0') {
            $phone = '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 4) == '254') {
            $phone = $phone;
        } elseif (substr($phone, 0, 5) == '+254') {
            $phone = '254' . substr($phone, 4);
        }

        return $phone;
    }

    /**
     * Get M-Pesa access token from Safaricom
     */
    private function getMpesaAccessToken()
    {
        $consumerKey = env('MPESA_CONSUMER_KEY');
        $consumerSecret = env('MPESA_CONSUMER_SECRET');

        // Skip if not configured (for testing)
        if (!$consumerKey || !$consumerSecret) {
            Log::warning('M-Pesa credentials not configured, using mock token');
            return 'mock_token_for_testing';
        }

        $credentials = base64_encode($consumerKey . ':' . $consumerSecret);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $credentials
            ])->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('Failed to get M-Pesa token', ['response' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('M-Pesa token exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Send STK push request to M-Pesa
     */
    private function stkPushRequest($accessToken, $phone, $amount, $timestamp, $password)
    {
        $shortCode = env('MPESA_SHORTCODE');
        $callBackURL = env('MPESA_CALLBACK_URL', route('mpesa.callback'));

        // Skip if not configured (for testing)
        if (!$shortCode) {
            Log::warning('M-Pesa shortcode not configured, using mock response');
            return ['ResponseCode' => '0', 'CheckoutRequestID' => 'MOCK_' . time()];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $shortCode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => round($amount),
                'PartyA' => $phone,
                'PartyB' => $shortCode,
                'PhoneNumber' => $phone,
                'CallBackURL' => $callBackURL,
                'AccountReference' => 'ESERIAN' . time(),
                'TransactionDesc' => 'Eserian Homes Booking Payment'
            ]);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('STK Push exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
