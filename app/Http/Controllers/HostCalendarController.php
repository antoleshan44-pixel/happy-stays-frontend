<?php
// app/Http/Controllers/HostCalendarController.php

namespace App\Http\Controllers;

use App\Services\SpringBootApiService;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HostCalendarController extends Controller
{
    protected $api;

    public function __construct(SpringBootApiService $api)
    {
        $this->api = $api;
    }

    public function index($propertyId = null)
    {
        try {
            // Get the property
            $property = $this->api->getProperty($propertyId);

            if (!$property) {
                $rawProperties = $this->api->getMyProperties();
                $allProperties = ApiHelper::toPropertyCollection($rawProperties);
                if ($allProperties->count() > 0) {
                    $property = $allProperties->first();
                    $propertyId = $property->id;
                }
            }

            // Get bookings
            $rawBookings = $this->api->getMyBookings();
            $bookings = collect($rawBookings);

            // Filter for this property
            $propertyBookings = $bookings->filter(function($b) use ($propertyId) {
                return ($b['property']['id'] ?? null) == $propertyId;
            });

            $confirmedBookings = [];
            $pendingBookingsList = [];
            $upcomingBookings = [];

            foreach ($propertyBookings as $booking) {
                $checkIn = $booking['checkInDate'];
                $checkOut = $booking['checkOutDate'];
                $dates = $this->getDateRange($checkIn, $checkOut);

                if ($booking['status'] === 'CONFIRMED') {
                    $confirmedBookings = array_merge($confirmedBookings, $dates);
                    if ($checkIn >= date('Y-m-d')) {
                        $upcomingBookings[] = $booking;
                    }
                } elseif ($booking['status'] === 'PENDING') {
                    $pendingBookingsList = array_merge($pendingBookingsList, $dates);
                }
            }

            $blockedDatesList = session('blocked_dates_' . $propertyId, []);

            return view('owner.calendar', compact(
                'property',
                'propertyId',
                'confirmedBookings',
                'pendingBookingsList',
                'blockedDatesList',
                'upcomingBookings'
            ));

        } catch (\Exception $e) {
            Log::error('Calendar error: ' . $e->getMessage());

            return view('owner.calendar', [
                'property' => null,
                'propertyId' => $propertyId,
                'confirmedBookings' => [],
                'pendingBookingsList' => [],
                'blockedDatesList' => [],
                'upcomingBookings' => []
            ]);
        }
    }

    public function blockDate(Request $request, $propertyId)
    {
        try {
            $date = $request->input('date');
            $blockedDates = session('blocked_dates_' . $propertyId, []);

            if (!in_array($date, $blockedDates)) {
                $blockedDates[] = $date;
                session(['blocked_dates_' . $propertyId => $blockedDates]);
            }

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Date blocked successfully');

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function unblockDate(Request $request, $propertyId)
    {
        try {
            $date = $request->input('date');
            $blockedDates = session('blocked_dates_' . $propertyId, []);

            $key = array_search($date, $blockedDates);
            if ($key !== false) {
                unset($blockedDates[$key]);
                session(['blocked_dates_' . $propertyId => array_values($blockedDates)]);
            }

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Date unblocked successfully');

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function getDateRange($startDate, $endDate)
    {
        $dates = [];
        $current = strtotime($startDate);
        $end = strtotime($endDate);

        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }
}
