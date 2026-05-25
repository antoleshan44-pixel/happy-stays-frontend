<h1>Available Properties</h1>

@foreach($properties as $property)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">

        @if($property->image)
            <img src="{{ asset('storage/' . $property->image) }}" width="200">
        @endif

        <h2>{{ $property->title }}</h2>
        <p>{{ $property->location }}</p>
        <p>KES {{ $property->price_per_night }} / night</p>

        <a href="/properties/{{ $property->id }}">View Details</a>

    </div>
@endforeach