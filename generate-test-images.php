<?php
// generate-test-images.php
$propertyId = 22;
$photosDir = __DIR__ . "/storage/app/public/properties/{$propertyId}/photos";

if (!is_dir($photosDir)) {
    mkdir($photosDir, 0777, true);
    echo "Created directory: $photosDir\n";
}

// Get the photo records from database
$pdo = new PDO('mysql:host=localhost;dbname=airbnb_clone', 'root', 'newpassword123');
$stmt = $pdo->prepare("SELECT id, photo_path FROM property_photos WHERE property_id = ?");
$stmt->execute([$propertyId]);
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($photos) . " photo records in database\n";

foreach ($photos as $photo) {
    $photoPath = $photosDir . "/" . basename($photo['photo_path']);

    // Create a simple colored placeholder image
    $width = 800;
    $height = 600;
    $image = imagecreatetruecolor($width, $height);

    // Generate random color based on ID
    $color = imagecolorallocate($image, rand(100, 200), rand(100, 200), rand(100, 200));
    imagefill($image, 0, 0, $color);

    // Add text
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $text = "Property {$propertyId}\nPhoto ID: {$photo['id']}";
    imagestring($image, 5, 50, $height/2, $text, $textColor);

    // Save image
    imagejpeg($image, $photoPath, 90);
    imagedestroy($image);

    echo "Created: " . basename($photo['photo_path']) . "\n";
}

echo "Done! Created " . count($photos) . " placeholder images.\n";
