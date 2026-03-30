<?php

namespace App\Http\Controllers\Backend;

use App\Mail\ValuationMessage;
use App\Models\Client;
use App\Models\ClientValuation;
use App\Models\ClientValuationNote;
use App\Models\WhyList;
use App\Property;
use App\PropertyType;
use App\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendValuationEmail;
use App\Traits\UKPostcodeTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ClientValuationsController extends Controller
{
    use UKPostcodeTrait;

    private $is_set;

    public function __construct()
    {
        $this->is_set = settings('market_valuation');

        // Prevent Access if Turned Of....
        if($this->is_set == '0')
        {
            return redirect('/admin')->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valuations = ClientValuation::orderBy('created_at', 'desc')
            ->whereHas('client', function ($query) use ($request)
            {
                $query->where('client_name', 'like', '%'.$request->input('q').'%');
            })
            ->orWhere('client_valuation_street', 'like', '%'.$request->input('q').'%')
            ->orWhere('client_valuation_town', 'like', '%'.$request->input('q').'%')
            ->orWhere('client_valuation_city', 'like', '%'.$request->input('q').'%')
            ->paginate(20);

        return view('backend.client_valuations.index',
            [
                'valuations'    => $valuations,
                'pageTitle'     => 'Market Valuations'
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.client_valuations.create',
            [
                'pageTitle'     => 'Create new Valuation',
                'property_types' => PropertyType::orderBy('name')->get(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $rules = array
        (
            'client_name'                               => 'required',
            'client_email'                              => 'required',
            'client_valuation_beds'                     => 'required',
            'client_valuation_baths'                    => 'required',
            'client_valuation_postcode'                 => 'required',
            'client_valuation_map'                      => 'required',
            'client_valuation_street'                   => 'required',
            'client_valuation_date'                     => 'required',
            'client_valuation_property_description'     => 'required',
            'client_valuation_location_info'            => 'required',
            'property_type_id'                          => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            // See if Client Exists...
            $client = Client::where('client_name', $request->input('client_name'))
                ->where('client_email', $request->input('client_email'))
                ->first();

            if($client)
            {
                $client_id = $client->client_id;
            }
            else
            {
                $new_client = new Client;
                $new_client->client_name = $request->input('client_name');
                $new_client->client_email = $request->input('client_email');
                $new_client->save();

                $client_id = $new_client->client_id;
            }

            // Create Client Valuation Record....
            $client_valuation = new ClientValuation;
            $client_valuation->client_id = $client_id;
            $client_valuation->client_valuation_street = $request->input('client_valuation_street');
            $client_valuation->client_valuation_town = $request->input('client_valuation_town');
            $client_valuation->client_valuation_city = $request->input('client_valuation_city');
            $client_valuation->client_valuation_region = $request->input('client_valuation_region');
            $client_valuation->client_valuation_postcode = $request->input('client_valuation_postcode');
            $client_valuation->client_valuation_date = $request->input('client_valuation_date');
            $client_valuation->property_type_id = $request->input('property_type_id');
            $client_valuation->client_valuation_beds = $request->input('client_valuation_beds');
            $client_valuation->client_valuation_baths = $request->input('client_valuation_baths');
            $client_valuation->client_valuation_map = $request->input('client_valuation_map');
            $client_valuation->client_valuation_latitude = $request->input('latitude');
            $client_valuation->client_valuation_longitude = $request->input('longitude');
            $client_valuation->client_valuation_price = $request->input('client_valuation_price');
            $client_valuation->client_valuation_price_advice = $request->input('client_valuation_price_advice');
            $client_valuation->client_valuation_property_description = $request->input('client_valuation_property_description');
            $client_valuation->client_valuation_location_info = $request->input('client_valuation_location_info');
            $client_valuation->save();

            // Saved Now Return Back...
            $request->session()->flash('message_success', 'Successfully created market valuation!');
            return redirect('admin/market-valuation/'. $client_valuation->client_valuation_id.'/edit');
        }

    }

    public function store_property(Request $request, $property_id)
    {
        $property = Property::find($property_id);

        // Check If Valuation ID is Present (Don't Create Another)....
        if($property->client_valuation_id)
        {
            $request->session()->flash('message_warning', 'Property already has an Valuation assigned');
            return redirect('admin/properties/'.$property_id.'/edit');
        }
        else
        {
            // See if Client Exists...

            // Create Basic Client Details (Under Admin - Admin can Switch Later)...
            $client_name = Auth::user()->name;
            $client_email = Auth::user()->email;

            $client = Client::where('client_name', $client_name)
                ->where('client_email', $client_email)
                ->first();

            if($client)
            {
                $client_id = $client->client_id;
            }
            else
            {
                $new_client = new Client;
                $new_client->client_name = $client_name;
                $new_client->client_email = $client_email;
                $new_client->save();

                $client_id = $new_client->client_id;
            }


            // No Valuation Already - Create a new one...
            $client_valuation = new ClientValuation;
            $client_valuation->client_id = $client_id;
            $client_valuation->client_valuation_street = $property->street;
            $client_valuation->client_valuation_town = $property->town;
            $client_valuation->client_valuation_city = $property->city;
            $client_valuation->client_valuation_region = $property->region;
            $client_valuation->client_valuation_postcode = $property->postcode;
            $client_valuation->client_valuation_date = Carbon::now();
            $client_valuation->property_type_id = $property->property_type_id;
            $client_valuation->client_valuation_beds = $property->beds;
            $client_valuation->client_valuation_baths = $property->baths;
            $client_valuation->client_valuation_map = 'y'; // Show Map By Default
            $client_valuation->client_valuation_latitude = $property->latitude;
            $client_valuation->client_valuation_longitude = $property->longitude;
            $client_valuation->client_valuation_price = $property->price;
            $client_valuation->client_valuation_price_advice = 'We would recommend this price'; // Default Text
            $client_valuation->client_valuation_property_description = $property->description;
            //$client_valuation->client_valuation_location_info = $request->input('client_valuation_location_info');
            $client_valuation->save();

            // Update The Property...
            $property->client_valuation_id = $client_valuation->client_valuation_id;
            $property->save();

            // Saved Now Return Back...
            $request->session()->flash('message_success', 'Successfully created market valuation!');
            return redirect('admin/market-valuation/'. $client_valuation->client_valuation_id.'/edit');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        return view('backend.client_valuations.edit',
            [
                'tab'            => $request->input('tab'),
                'pageTitle'      => 'Edit Property Valuation',
                'property_types' => PropertyType::orderBy('name')->get(),
                'data'           => ClientValuation::where('client_valuation_id', $id)->first()
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate
        $rules = array
        (
            'client_name'                               => 'required',
            'client_email'                              => 'required',
            'client_valuation_beds'                     => 'required',
            'client_valuation_baths'                    => 'required',
            'client_valuation_postcode'                 => 'required',
            'client_valuation_map'                      => 'required',
            'client_valuation_street'                   => 'required',
            'client_valuation_date'                     => 'required',
            'client_valuation_property_description'     => 'required',
            'client_valuation_location_info'            => 'required',
            'property_type_id'                          => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            // First Update The Client (If needs be)...
            $client = Client::find($request->input('client_id'));
            $client->client_name = $request->input('client_name');
            $client->client_email = $request->input('client_email');
            $client->save();

            // Now Update The Details....
            $client_valuation = ClientValuation::find($id);
            $client_valuation->client_valuation_street = $request->input('client_valuation_street');
            $client_valuation->client_valuation_town = $request->input('client_valuation_town');
            $client_valuation->client_valuation_city = $request->input('client_valuation_city');
            $client_valuation->client_valuation_region = $request->input('client_valuation_region');
            $client_valuation->client_valuation_postcode = $request->input('client_valuation_postcode');
            $client_valuation->client_valuation_date = $request->input('client_valuation_date');
            $client_valuation->property_type_id = $request->input('property_type_id');
            $client_valuation->client_valuation_beds = $request->input('client_valuation_beds');
            $client_valuation->client_valuation_baths = $request->input('client_valuation_baths');
            $client_valuation->client_valuation_map = $request->input('client_valuation_map');
            $client_valuation->client_valuation_latitude = $request->input('latitude');
            $client_valuation->client_valuation_longitude = $request->input('longitude');

//            if(settings('overseas') == 0)
//            {
//                // Get the Lat / Long For The Property...
//                $lat_long = $this->get_lat_long($request->input('client_valuation_postcode'));
//
//                $client_valuation->client_valuation_latitude = $lat_long['latitude'];
//                $client_valuation->client_valuation_longitude = $lat_long['longitude'];
//            }

            if($request->has('client_valuation_instruction_date'))
            {
                // Check if a date was set...
                if($request->input('client_valuation_instruction_date'))
                {
                    $client_valuation->client_valuation_status = 'instructed';
                    $client_valuation->client_valuation_instructed_date = $request->input('client_valuation_instruction_date');
                }
            }

            $client_valuation->client_valuation_price = $request->input('client_valuation_price');
            $client_valuation->client_valuation_price_advice = $request->input('client_valuation_price_advice');
            $client_valuation->client_valuation_property_description = $request->input('client_valuation_property_description');
            $client_valuation->client_valuation_location_info = $request->input('client_valuation_location_info');
            $client_valuation->save();

            // Saved Now Return Back...
            $request->session()->flash('message_success', 'Successfully updated market valuation!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client_valuation = ClientValuation::find($id);
        $client_valuation->delete();

        // Send Confirmed Response...
        return response()->json([
            'error' => 'false',
            'message' => 'Valuation Deleted!'
        ]);


    }

    public function list_index()
    {
        return view('backend.client_valuations.list.index',
            [
                'pageTitle' => 'Why list with us items',
                'items'     => WhyList::orderBy('order', 'asc')->get()
            ]
        );
    }

    public function create_list_item()
    {
        return view('backend.client_valuations.list.create');
    }

    public function store_list_item(Request $request)
    {
        // validate
        $rules = array
        (
            'title'       => 'required',
            'content'     => 'required',
            'icon'        => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            // Create Item...
            $list_item = new WhyList;
            $list_item->title = $request->input('title');
            $list_item->content = $request->input('content');
            $list_item->order = 0; // Default...
            $list_item->icon = $request->input('icon');
            $list_item->save();

            $request->session()->flash('message_success', 'Successfully saved item!');

            // Go Back To Why List Section...
            return redirect('admin/market-valuation/why-list');
        }
    }

    public function edit_list_item($id)
    {
        return view('backend.client_valuations.list.edit',
            [
                'item' => WhyList::find($id)
            ]);
    }

    public function update_list_item(Request $request, $id)
    {
        // validate
        $rules = array
        (
            'title'       => 'required',
            'content'     => 'required',
            'icon'        => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            $list_item = WhyList::find($id);
            $list_item->title = $request->input('title');
            $list_item->content = $request->input('content');
            $list_item->icon = $request->input('icon');
            $list_item->save();

            $request->session()->flash('message_success', 'Successfully updated item!');

            // Go Back To Why List Section...
            return redirect('admin/market-valuation/why-list');
        }
    }

    public function delete_item($id)
    {
        $item = WhyList::find($id);

        if($item)
        {
            $item->delete();

            // Now Send jSON Response....
            return response()->json(
                [
                    'message' => 'Item Deleted'
                ]
            );
        }
    }

    public function sort_items(Request $request)
    {
        $items =  $request->input("item");

        foreach($items as $order => $id)
        {
            $item = WhyList::find($id);
            $item->order = $order;
            $item->save();
        }

        return response()->json(
            [
                'message' => 'Reordering Successful'
            ]
        );
    }

    public function create_note($id)
    {
        return view('backend.client_valuations.create-note',
            [
                'id' => $id
            ]);
    }

    public function save_note(Request $request)
    {
        // validate
        $rules = array
        (
            'client_valuation_note_title'       => 'required',
            'client_valuation_text'             => 'required'
        );

        $messages = [
            'client_valuation_text.required' => 'Please enter some text for this note!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            // Get Valuation ID
            $valuation_id = $request->input('client_valuation_id');;

            // Store Note...
            $note = new ClientValuationNote;
            $note->client_valuation_note_title = $request->input('client_valuation_note_title');
            $note->client_valuation_text = $request->input('client_valuation_text');
            $note->client_valuation_id = $valuation_id;
            $note->client_valuation_note_type = 'internal';
            $note->save();

            $request->session()->flash('message_success', 'Successfully saved note!');

            // Go Back To Notes Section...
            return redirect('admin/market-valuation/'.$valuation_id.'/edit?tab=notes');
        }
    }

    public function create_property_record(Request $request, $id)
    {
        // Find The Valuation Record...
        $valuation = ClientValuation::find($id);

        if($valuation->property)
        {
            $request->session()->flash('message_danger', 'Property Record already exists!');

            return redirect()->back();
        }
        else
        {
            $property = new Property;
            $property->user_id = Auth::user()->id;
            $property->status = -1; // Inactive...
            $property->client_valuation_id = $valuation->client_valuation_id;
            $property->property_type_id = $valuation->property_type_id;
            $property->is_rental = 0; // Always Sale...
            $property->street = $valuation->client_valuation_street;
            $property->town = $valuation->client_valuation_town;
            $property->city = $valuation->client_valuation_city;
            $property->region = $valuation->client_valuation_region;
            $property->postcode = $valuation->client_valuation_postcode;
            $property->beds = $valuation->client_valuation_beds;
            $property->baths = $valuation->client_valuation_baths;
            $property->country = "United Kingdom"; // May need to add _country fields to client_valuation table
            $property->latitude = $valuation->client_valuation_latitude;
            $property->longitude = $valuation->client_valuation_longitude;
            $property->price = $valuation->client_valuation_price;
            $property->description = $valuation->client_valuation_property_description;
            $property->save();

            // Now Update The Property Ref...
            $property_update = Property::find($property->id);
            $ref_prefix = settings('ref_prefix');
            $property_update->ref = $ref_prefix.$property->id;
            $property_update->save();

            $data = [ 'success' => 'Property Created' ];

            return redirect(admin_url('properties/'.$property->id.'/edit'))->with($data);
        }
    }

    public function send_email(Request $request)
    {
        // Get Valuation Details...
        $valuation = ClientValuation::find($request->input('id'));
        $testimonial = Testimonial::all()->random(1)->first();

        // Send Accepted Valuation to Client...
        SendValuationEmail::dispatch($valuation, $testimonial);

        // Now Send jSON Response....
        return response()->json(
            [
                'message' => 'Email Sent Successfully'
            ]
        );
    }
}
