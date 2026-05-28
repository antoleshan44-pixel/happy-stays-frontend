


<?php $__env->startSection('title', 'Map View'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-container {
        width: 100%;
        height: 500px;
        margin-top: 1rem;
        border-radius: 1rem;
        overflow: hidden;
        position: relative;
        background: #f0f0f0;
    }

    #map {
        width: 100%;
        height: 100%;
    }

    .info-card {
        background: white;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        margin-top: 1rem;
        text-align: center;
    }

    .info-card h3 {
        font-weight: 700;
        margin-bottom: 4px;
        color: #00288e;
    }

    .info-card p {
        font-size: 13px;
        color: #666;
        margin: 5px 0;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-custom py-6">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary heading-font">Map View</h1>
            <p class="text-text-muted text-sm">
                Explore properties across Kenya
            </p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('customer.browse')); ?>" class="px-4 py-2 border border-border-color rounded-lg text-sm font-semibold text-text-primary hover:bg-gray-50 transition">
                List View
            </a>
            <a href="<?php echo e(route('customer.map')); ?>" class="px-4 py-2 bg-brand-500 text-white rounded-lg text-sm font-semibold hover:bg-brand-600 transition">
                Map View
            </a>
        </div>
    </div>

    <div id="map-container">
        <div id="map"></div>
    </div>

    <div class="info-card">
        <h3>🏠 Eserian Homes</h3>
        <p>Discover luxury stays across Kenya</p>
        <p style="font-size: 11px; color: #888;">📍 Add properties to see them on the map</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM ready, initializing map...');

        var mapElement = document.getElementById('map');
        if (!mapElement) {
            console.error('Map element not found!');
            return;
        }

        // Create the map centered on Kenya
        var map = L.map('map').setView([-1.286389, 36.817223], 6.5);

        // Add the tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Get properties from the backend
        var properties = <?php echo json_encode($properties ?? [], 15, 512) ?>;

        console.log('Properties to display:', properties.length);

        // Only add markers if there are properties
        if (properties && Array.isArray(properties) && properties.length > 0) {
            // Custom property icon
            var propertyIcon = L.divIcon({
                html: '<div style="background-color: #00288e; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.3); border: 2px solid white;"><span style="color: white; font-size: 18px;">🏠</span></div>',
                iconSize: [35, 35],
                popupAnchor: [0, -17]
            });

            // Kenyan locations mapping
            var locationCoords = {
                'Diani Beach': [-4.3093, 39.5792],
                'Westlands, Nairobi': [-1.2636, 36.8003],
                'Nairobi': [-1.286389, 36.817223],
                'Mombasa': [-4.0435, 39.6682],
                'Kisumu': [-0.1022, 34.7617],
                'Nakuru': [-0.3031, 36.0800],
                'Malindi': [-3.2187, 40.1169],
                'Watamu': [-3.3386, 40.0267],
                'Aberdare': [-0.4353, 36.7462],
                'Naivasha': [-0.7167, 36.4333],
                'Eldoret': [0.5143, 35.2698],
                'Thika': [-1.0389, 37.0833],
                'Kitengela': [-1.5167, 36.9667],
                'Kilifi': [-3.6305, 39.8500],
                'Lamu': [-2.2685, 40.9020],
                'Nanyuki': [0.0167, 37.0667]
            };

            var bounds = [];

            properties.forEach(function(property) {
                if (!property.location) return;

                // Get coordinates for the location
                var coords = locationCoords[property.location];

                // If exact location not found, try partial match
                if (!coords) {
                    for (var key in locationCoords) {
                        if (property.location.toLowerCase().includes(key.toLowerCase()) ||
                            key.toLowerCase().includes(property.location.toLowerCase())) {
                            coords = locationCoords[key];
                            break;
                        }
                    }
                }

                // Default to Nairobi if location not found
                if (!coords) {
                    coords = [-1.286389, 36.817223];
                }

                bounds.push(coords);

                // Get photo URL
                var photoUrl = '';
                if (property.photos && property.photos.length > 0 && property.photos[0].photo_path) {
                    photoUrl = '/storage/' + property.photos[0].photo_path;
                } else {
                    photoUrl = 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=300&h=200&fit=crop';
                }

                var price = property.price_per_night || 0;
                var rating = property.averageRating || 4.5;

                var popupContent = `
                    <div style="min-width: 240px; max-width: 260px; font-family: sans-serif;">
                        <img src="${photoUrl}" alt="${property.title}" style="width: 100%; height: 140px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;">
                        <div style="padding: 0 8px 8px 8px;">
                            <h3 style="font-weight: 700; font-size: 16px; margin: 0 0 4px 0; color: #191c1e;">${property.title}</h3>
                            <p style="color: #6b7280; font-size: 12px; margin: 0 0 8px 0;">📍 ${property.location}</p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                                <span style="color: #00288e; font-weight: 700;">KES ${price.toLocaleString()}<span style="font-size: 11px; font-weight: normal;">/night</span></span>
                                <span style="background-color: #d1fae5; color: #065f46; font-size: 11px; padding: 2px 8px; border-radius: 9999px;">★ ${rating}</span>
                            </div>
                            <a href="/customer/property/${property.id}" style="display: block; margin-top: 12px; background-color: #00288e; color: white; text-align: center; padding: 8px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                View Details
                            </a>
                        </div>
                    </div>
                `;

                var marker = L.marker(coords, { icon: propertyIcon }).addTo(map);
                marker.bindPopup(popupContent);
            });

            // Fit map to show all property markers
            if (bounds.length > 1) {
                var boundsLatLng = L.latLngBounds(bounds);
                map.fitBounds(boundsLatLng.pad(0.2));
            } else if (bounds.length === 1) {
                map.setView(bounds[0], 12);
            }
        } else {
            // No properties message
            var noPropertiesIcon = L.divIcon({
                html: '<div style="background-color: #9ca3af; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.3); border: 2px solid white;"><span style="color: white; font-size: 20px;">📍</span></div>',
                iconSize: [40, 40],
                popupAnchor: [0, -20]
            });

            L.marker([-1.286389, 36.817223], { icon: noPropertiesIcon })
                .addTo(map)
                .bindPopup(`
                    <div style="text-align: center; padding: 10px;">
                        <strong style="color: #00288e;">No properties yet</strong><br>
                        <span style="font-size: 12px;">Browse and add properties to see them here</span><br>
                        <a href="/customer/browse" style="display: inline-block; margin-top: 8px; color: #00288e; text-decoration: underline;">Browse Properties →</a>
                    </div>
                `)
                .openPopup();
        }

        // Add scale control
        L.control.scale().addTo(map);

        // Force map to resize correctly
        setTimeout(function() {
            map.invalidateSize();
        }, 200);

        console.log('Map initialized successfully');
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eserian-homes\resources\views/customer/map-view.blade.php ENDPATH**/ ?>