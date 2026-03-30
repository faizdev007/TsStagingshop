<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PropertyAlert;
use App\PropertyType;
use Auth;

class PropertyAlertController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
        $this->moduleTitle = "Property Alerts";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $alerts = PropertyAlert::orderby('id', 'ASC')->paginate(10);
        $data = [
            'pageTitle'  => $this->moduleTitle,
            'alerts' => $alerts
        ];

        return view('backend.alerts.index')->with($data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect(admin_url('property-alerts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect(admin_url('property-alerts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect(admin_url('property-alerts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $PropertyAlert = PropertyAlert::find($id);
        $propertyTypes = propertyType::orderBy('name')->get();

        $data = [
            'pageTitle'  => $this->moduleTitle,
            'PropertyAlert' => $PropertyAlert,
            'propertyTypes' => $propertyTypes,
        ];

        return view('backend.alerts.edit')->with($data);

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
        $PropertyAlert = PropertyAlert::find($id);

        $inputs = $request->input();
        $email = $request->input('email');

        $request->validate(
            [
                'fullname'=>'required|max:80',
                'email'=>'required|email|max:80',
                'contact'=>'max:80',
            ]
        );

        $inputs = prepare_inputs($inputs);

        foreach($inputs as $field => $input){
            if($field == 'price_range'){
                if(!empty($input)){
                    list($price_from, $price_to) = explode('-',$input);
                    $PropertyAlert->price_from = $price_from;
                    $PropertyAlert->price_to = $price_to;
                }
            }elseif($field == 'property_type_ids'){
                if( is_array($input) ){
                    $PropertyAlert->property_type_ids = implode(',',$input);
                }else{
                    $PropertyAlert->property_type_ids = '';
                }
            }else{
                $PropertyAlert->{$field} = $input;
            }
        }

        if (!$request->has('property_type_ids')){

            $PropertyAlert->property_type_ids = '';

        }

        $PropertyAlert->save();

        $data = [ 'success' => 'Property Alert Updated' ];

        return redirect(admin_url('property-alerts/'.$id.'/edit'))->with($data);

    }

    public function destroy($id, Request $request)
    {
        $PropertyAlert = PropertyAlert::find($id);

        if($PropertyAlert)
        {
            $PropertyAlert->delete();

            // Send Confirmed Response...
            return response()->json(
                [
                    'error' => 'false',
                    'redirect' => '/admin/property-alerts',
                    'message' => 'Alert Removed!'
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $PropertyAlert = PropertyAlert::find($id);
        if(empty($PropertyAlert)){ return redirect(admin_url('property-alerts')); }
        $PropertyAlert->delete();
        $data = ['success' => 'Successfully deleted alert!'];
        return redirect(admin_url('property-alerts'))->with($data);
    }
}
