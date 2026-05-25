<!DOCTYPE html>
<html>
<head>
    <title>Properties - Eserian Homes</title>
</head>
<body>

<h1>All Properties</h1>

<a href="/properties/create">Add Property</a>

<hr>

@foreach($properties as $property)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">

        <!-- PROPERTY IMAGE -->
        @if($property->image)
            <img src="{{ asset('storage/' . $property->image) }}" 
                 alt="Property Image"
                 style="width:200px; height:150px; object-fit:cover;">
        @else
            <p><em>No image available</em></p>
        @endif

        <!-- PROPERTY DETAILS -->
        <h3>{{ $property->title }}</h3>
        <p><strong>Location:</strong> {{ $property->location }}</p>
        <p><strong>Price:</strong> KES {{ $property->price_per_night }}</p>

        <!-- OPTIONAL DESCRIPTION PREVIEW -->
        <p>{{ \Illuminate\Support\Str::limit($property->description, 100) }}</p>

    </div>
@endforeach

</body>
</html>