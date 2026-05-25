<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Property;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request, $propertyId)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        $property = Property::findOrFail($propertyId);

        // Calculate nights
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        $nights = $checkOut->diffInDays($checkIn);

        $totalPrice = $nights * $property->price_per_night;

        // Prevent double booking
        $exists = Booking::where('property_id', $propertyId)
            ->where(function ($query) use ($request) {
                $query->whereBetween('check_in', [$request->check_in, $request->check_out])
                      ->orWhereBetween('check_out', [$request->check_in, $request->check_out]);
            })
            ->exists();

        if ($exists) {
            return back()->with('error', 'Property already booked for selected dates.');
        }

        // Create booking
        Booking::create([
            'user_id' => auth()->id(),
            'property_id' => $propertyId,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect('/my-bookings')->with('success', 'Booking successful!');
    }

    public function myBookings()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with('property')
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }
}