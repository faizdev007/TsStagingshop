<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\GoogleIndexingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleIndexingController extends Controller
{
    private $googleIndexingService;
    private const BASE_URL = 'https://terezaestates.com';
    
    public function __construct(GoogleIndexingService $googleIndexingService)
    {
        $this->googleIndexingService = $googleIndexingService;
    }
    
    public function requestIndexing(Request $request)
    {
        try {
            $path = $request->url;
            if (empty($path)) {
                throw new \Exception('URL path is required');
            }

            // Log the incoming path for debugging
            Log::info('Received URL path', ['path' => $path]);

            // Remove any leading/trailing slashes and ensure single slashes between segments
            $path = trim($path, '/');
            $path = preg_replace('#/+#', '/', $path);
            
            // Build the complete URL
            $url = self::BASE_URL . '/' . $path;
            
            // Log the constructed URL for debugging
            Log::info('Constructed URL', ['url' => $url]);

            // Validate URL format - allow for any characters in the property name except slashes
            if (!preg_match('#^https://terezaestates\.com/property/[^/]+/[0-9]+$#', $url)) {
                Log::error('URL validation failed', ['url' => $url]);
                throw new \Exception('Invalid property URL format. Expected format: https://terezaestates.com/property/{property-name}/{id}');
            }

            Log::info('Processing indexing request', ['url' => $url]);
            
            $result = $this->googleIndexingService->requestIndexing($url);
            
            return response()->json([
                'success' => true,
                'message' => 'URL submitted for indexing successfully',
                'details' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('Google Indexing Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_url' => $request->url ?? null
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
