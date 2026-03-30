<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstaCardDesignerController extends Controller
{
    // Display the Insta Card Designer page
    public function index()
    {
        // Fetch properties to populate a dropdown or list
        $properties = DB::table('properties')
            ->select('ref', 'name')
            ->orderBy('ref', 'desc')
            ->get();

        return view('backend.insta-card-designer.index', [
            'properties' => $properties,
            'pageTitle' => 'Insta Card Designer'
        ]);
    }

    // Save the design for a specific property
    public function saveDesign(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'ref' => 'required|string',
            'design_data' => 'required|array'
        ]);

        // Prepare the design data for storage
        $designPath = "insta-card-designs/{$validatedData['ref']}.json";
        
        // Store the design as a JSON file
        Storage::put($designPath, json_encode($validatedData['design_data']));

        return response()->json([
            'message' => 'Design saved successfully',
            'path' => $designPath
        ]);
    }

    // Retrieve a saved design for a specific property
    public function getDesign($ref)
    {
        $designPath = "insta-card-designs/{$ref}.json";
        
        // Check if design exists
        if (!Storage::exists($designPath)) {
            return response()->json(['error' => 'Design not found'], 404);
        }

        // Read and return the design data
        $designData = Storage::get($designPath);
        return response()->json(json_decode($designData, true));
    }

    // Get images for a specific property
    public function getPropertyImages($ref)
    {
        $imagePath = "public/properties/{$ref}/photos";
        
        // Check if directory exists
        if (!Storage::exists($imagePath)) {
            return response()->json(['images' => [], 'image_count' => 0]);
        }

        // Get image files
        $imageFiles = Storage::files($imagePath);
        
        // Convert to full URLs
        $imageUrls = array_map(function($file) {
            return Storage::url($file);
        }, $imageFiles);

        return response()->json([
            'images' => $imageUrls,
            'image_count' => count($imageUrls)
        ]);
    }
}