<?php

namespace App\Http\Controllers\Backend;

use Config;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class PropertyviewController extends Controller
{

    
    public function redirectToView(Request $request)
    {


         // Retrieve distinct rows based on the 'ref' column
         $dataa = DB::table('property_stats')
         ->select('property_id', DB::raw('MAX(created_at) as latest_clicked_at'), DB::raw('COUNT(property_id) as property_id_count'), 'p_name')
         ->where('type', '=', 'property_view') // Filter by type column
         ->whereNotNull('p_name') // Only include rows where 'p_name' is not empty
         ->groupBy('property_id', 'p_name')
         ->orderBy('latest_clicked_at', 'desc') // Sort by latest_clicked_at in descending order
         ->get();

        $pageTitle = 'Property Views';

        return view('backend.Property_views.index', ['dataa' => $dataa, 'pageTitle' => $pageTitle ]);
    }

    

}