<h1>My Bookings</h1>

@foreach($bookings as $booking)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">

        <h3>{{ $booking->property->title }}</h3>

        <p>Check-in: {{ $booking->check_in }}</p>
        <p>Check-out: {{ $booking->check_out }}</p>
        <p>Guests: {{ $booking->guests }}</p>
        <p>Total: KES {{ $booking->total_price }}</p>
        <p>Status: {{ $booking->status }}</p>

    </div>
@endforeach