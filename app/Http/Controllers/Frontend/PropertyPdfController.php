<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\SendPDFDownloadRequestEmail;
use Illuminate\Http\Request;
use App\Property;
use Mail;
use Illuminate\Support\Str;

class PropertyPdfController extends Controller
{
    public function downloadPdf(Request $request)
    {
        $request->validate([
            'fullname' => 'required',
            'email' => 'required|email',
            'terms' => 'required',
            'property_ref' => 'required'
        ]);

        $property = Property::where('ref', $request->property_ref)->first();
        
        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        if (empty($property->pdf_url)) {
            return response()->json(['error' => 'PDF not available for this property'], 404);
        }
        $formattedPrice = number_format($property->price, 0, '.', ',');
        $data = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'property_ref' => $property->ref,
            'property_title' => $property->name,
            'property_area' => $property->complex_name,
            'property_price' => 'AED ' . $formattedPrice,
            'newsletter' => $request->newsletter
        ];

        SendPDFDownloadRequestEmail::dispatch($data);


        return response()->json([
            'success' => 'Request sent successfully',
            'pdf_url' => url("/property-pdf/view/{$property->id}")
        ]);
    }
}
