<?php
// File: app/Http/Controllers/PhotoController.php

namespace App\Http\Controllers;

use App\Services\SpringBootApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PhotoController extends Controller
{
    protected $api;

    public function __construct(SpringBootApiService $api)
    {
        $this->api = $api;
    }

    // ============================================
    // SHOW PHOTO UPLOAD PAGE
    // GET /owner/property/{id}/photos
    // ============================================

    public function index($propertyId)
    {
        // MUST use owner context so photos are included in API response
        $property = $this->api->getProperty($propertyId, true);

        if (!$property) {
            return redirect()->route('owner.properties')
                ->with('error', 'Property not found.');
        }

        // Ownership check
        $user = session('user');
        if (!$user || ($property['ownerId'] ?? $property['owner_id'] ?? null) != $user['id']) {
            return redirect()->route('owner.properties')
                ->with('error', 'You do not have permission to manage this property.');
        }

        // Normalise photos — Spring Boot returns camelCase keys
        $existingPhotos = collect($property['photos'] ?? [])->map(function ($photo) {
            return [
                'id'         => $photo['id'] ?? null,
                'photo_path' => $photo['photoPath'] ?? $photo['photo_path'] ?? null,
                'is_primary' => $photo['isPrimary'] ?? $photo['is_primary'] ?? false,
            ];
        })->toArray();

        $remainingSlots = 15 - count($existingPhotos);

        return view('owner.upload-photos', compact('property', 'existingPhotos', 'remainingSlots'));
    }

    // ============================================
    // UPLOAD PHOTOS
    // POST /owner/property/{id}/photos/upload
    // ============================================

    public function upload(Request $request, $propertyId)
    {
        $request->validate([
            'photos'   => 'required|array|min:1|max:15',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $property = $this->api->getProperty($propertyId, true);

        if (!$property) {
            return redirect()->route('owner.property.photos', $propertyId)
                ->with('error', 'Property not found.');
        }

        // Ownership check
        $user = session('user');
        if (!$user || ($property['ownerId'] ?? $property['owner_id'] ?? null) != $user['id']) {
            return redirect()->route('owner.properties')
                ->with('error', 'You do not have permission to upload photos for this property.');
        }

        $existingCount = count($property['photos'] ?? []);
        $maxUpload     = 15 - $existingCount;
        $incoming      = $request->file('photos');

        if (count($incoming) > $maxUpload) {
            return redirect()->route('owner.property.photos', $propertyId)
                ->with('error', "You can only upload up to {$maxUpload} more photo(s). You currently have {$existingCount}/15.");
        }

        Log::info('PhotoController::upload', [
            'propertyId'    => $propertyId,
            'incomingCount' => count($incoming),
            'existingCount' => $existingCount,
        ]);

        $uploaded = $this->api->uploadPhotos($propertyId, $incoming);

        if (count($uploaded) > 0) {
            return redirect()->route('owner.property.photos', $propertyId)
                ->with('success', count($uploaded) . ' photo(s) uploaded successfully!');
        }

        return redirect()->route('owner.property.photos', $propertyId)
            ->with('error', 'Failed to upload photos. Please check your connection and try again.');
    }

    // ============================================
    // DELETE A PHOTO
    // DELETE /owner/property/{propertyId}/photos/{photoId}
    // ============================================

    public function destroy($propertyId, $photoId)
    {
        $property = $this->api->getProperty($propertyId, true);

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        // Ownership check
        $user = session('user');
        if (!$user || ($property['ownerId'] ?? $property['owner_id'] ?? null) != $user['id']) {
            return back()->with('error', 'You do not have permission to delete this photo.');
        }

        Log::info('PhotoController::destroy', [
            'propertyId' => $propertyId,
            'photoId'    => $photoId,
        ]);

        $result = $this->api->deletePhoto($propertyId, $photoId);

        if ($result) {
            return back()->with('success', 'Photo deleted successfully.');
        }

        return back()->with('error', 'Failed to delete photo. Please try again.');
    }

    // ============================================
    // SET PRIMARY / MAIN PHOTO
    // PUT /owner/property/{propertyId}/photos/{photoId}/primary
    // ============================================

    public function setPrimary($propertyId, $photoId)
    {
        $property = $this->api->getProperty($propertyId, true);

        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        // Ownership check
        $user = session('user');
        if (!$user || ($property['ownerId'] ?? $property['owner_id'] ?? null) != $user['id']) {
            return back()->with('error', 'You do not have permission to update this property.');
        }

        Log::info('PhotoController::setPrimary', [
            'propertyId' => $propertyId,
            'photoId'    => $photoId,
        ]);

        $result = $this->api->setPrimaryPhoto($propertyId, $photoId);

        if ($result) {
            return back()->with('success', 'Main photo updated successfully.');
        }

        return back()->with('error', 'Failed to update main photo. Please try again.');
    }

    // ============================================
    // REORDER PHOTOS (AJAX)
    // POST /owner/property/{id}/photos/reorder
    // ============================================

    public function reorder(Request $request, $propertyId)
    {
        $request->validate([
            'photo_ids'   => 'required|array',
            'photo_ids.*' => 'integer',
        ]);

        $property = $this->api->getProperty($propertyId, true);

        if (!$property) {
            return response()->json(['success' => false, 'message' => 'Property not found.'], 404);
        }

        // Ownership check
        $user = session('user');
        if (!$user || ($property['ownerId'] ?? $property['owner_id'] ?? null) != $user['id']) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        Log::info('PhotoController::reorder', [
            'propertyId' => $propertyId,
            'photoIds'   => $request->photo_ids,
        ]);

        $result = $this->api->reorderPhotos($propertyId, $request->photo_ids);

        if ($result) {
            return response()->json(['success' => true, 'message' => 'Photos reordered successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to reorder photos.'], 500);
    }
}
