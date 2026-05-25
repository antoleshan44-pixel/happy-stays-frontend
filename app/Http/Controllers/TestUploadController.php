<?php
// File: app/Http/Controllers/TestUploadController.php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TestUploadController extends Controller
{
    public function showForm($propertyId)
    {
        $property = Property::findOrFail($propertyId);
        return view('test-upload', compact('property'));
    }
    
    public function upload(Request $request, $propertyId)
    {
        // Log the request
        \Log::info('Test upload started', [
            'property_id' => $propertyId,
            'user_id' => Auth::id(),
            'has_files' => $request->hasFile('photos')
        ]);
        
        if (!$request->hasFile('photos')) {
            return back()->with('error', 'No files selected');
        }
        
        $property = Property::findOrFail($propertyId);
        $uploadedCount = 0;
        
        foreach ($request->file('photos') as $photo) {
            // Get original filename
            $originalName = $photo->getClientOriginalName();
            $extension = $photo->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            
            // Store the file
            $path = $photo->storeAs('properties', $filename, 'public');
            
            // Create database record
            PropertyPhoto::create([
                'property_id' => $propertyId,
                'photo_path' => $path,
                'is_primary' => $uploadedCount === 0 && $property->photos->count() === 0
            ]);
            
            $uploadedCount++;
        }
        
        return redirect()->route('test.upload.form', $propertyId)
            ->with('success', $uploadedCount . ' photos uploaded!');
    }
}