<h1>{{ $property->title }}</h1>

@if($property->image)
    <img src="{{ asset('storage/' . $property->image) }}" width="400">
@endif

<p><strong>Location:</strong> {{ $property->location }}</p>
<p><strong>Description:</strong> {{ $property->description }}</p>
<p><strong>Price:</strong> KES {{ $property->price_per_night }} / night</p>

<hr>

<h2>Book This Property</h2>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

{{-- ERROR MESSAGE --}}
@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<form method="POST" action="/book/{{ $property->id }}">
    @csrf

    <label>Check-in Date:</label><br>
    <input type="date" name="check_in" required>
    <br><br>

    <label>Check-out Date:</label><br>
    <input type="date" name="check_out" required>
    <br><br>

    <label>Guests:</label><br>
    <input type="number" name="guests" value="1" min="1" required>
    <br><br>

    <button type="submit">Book Now</button>
</form>