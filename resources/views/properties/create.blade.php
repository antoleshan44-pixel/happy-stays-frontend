<!DOCTYPE html>
<html>
<head>
    <title>Add Property</title>
</head>
<body>

<h1>Add Property</h1>
<form method="POST" action="/owner/properties" enctype="multipart/form-data">
    @csrf

    <input type="text" name="title" placeholder="Title"><br><br>

    <textarea name="description" placeholder="Description"></textarea><br><br>

    <input type="text" name="location" placeholder="Location"><br><br>

    <input type="number" name="price_per_night" placeholder="Price per night"><br><br>

    <input type="file" name="image"><br><br>

    <button type="submit">Save Property</button>
</form>

</body>
</html>