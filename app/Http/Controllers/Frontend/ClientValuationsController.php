<?php

namespace App\Http\Controllers\Frontend;

use App\Mail\ValuationAccepted;
use App\Models\ClientValuation;
use App\Models\ClientValuationNote;
use App\Models\WhyList;
use App\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendValuationAcceptedEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class ClientValuationsController extends Controller
{
    public function show($id)
    {
        $valuation = ClientValuation::whereUuid($id)->firstOrFail();

        $view_data = array
        (
            'data'          => $valuation,
            'why_items'     => WhyList::orderBy('order', 'ASC')->get(),
            'testimonial'   => Testimonial::all()->random(1)->first()
        );

        $view = 'frontend.demo1.page-templates.valuation-report';

        // Change View Per Template...
        if(view()->exists($view))
        {
            // Shared View in the Templates...
            return view($view,  $view_data);
        }
        else
        {
            // Load Shared View...
            return view('frontend.shared.page-templates.valuation-report', $view_data);
        }
    }

    public function accept(Request $request)
    {
        $valuation_id = $request->input('client_valuation_id');

        $valuation = ClientValuation::find($valuation_id);

        if($valuation->count() > 0)
        {
            // Update The Status & Set an Instructed Date...
            $valuation->client_valuation_status = 'instructed';
            $valuation->client_valuation_instructed_date = Carbon::now()->toDateString();
            $valuation->save();

            // Also Create a note....
            $valuation_note = new ClientValuationNote;
            $valuation_note->client_valuation_note_title = 'Valuation Accepted';
            $valuation_note->client_valuation_text = 'Valuation accepted by client';
            $valuation_note->client_valuation_note_type = 'customer';
            $valuation_note->client_valuation_id = $valuation_id;
            $valuation_note->save();

            SendValuationAcceptedEmail::dispatch($valuation);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'You have accepted this valuation'
                ]
            );
        }
        else
        {
            // Not Found....
            return response()->json(
                [
                    'success' => false
                ]
            );
        }
    }

}
