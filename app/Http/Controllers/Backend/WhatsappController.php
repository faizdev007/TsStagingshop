<?php



namespace App\Http\Controllers\Backend;



use Config;

use Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use App\Property;



class WhatsappController extends Controller

{



    

    public function redirectToView(Request $request)

    {

        // Retrieve distinct rows based on the 'ref' column

        $data = DB::table('whatsapp_clicks')

            ->select('ref', DB::raw('MAX(clicked_at) as latest_clicked_at'), DB::raw('COUNT(ref) as ref_count'), 'p_name')

            ->groupBy('ref', 'p_name')

            ->orderBy('latest_clicked_at', 'desc') // Sort by latest_clicked_at in descending order

            ->paginate(10);



            $pageTitle = 'Whatsapp Clicks';

    

        return view('backend.Whatsapp.index', ['datas' => $data, 'pageTitle' => $pageTitle ]);

    }



    



}