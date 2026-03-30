<?php

namespace App\Http\Controllers\Backend;

use App\Models\Branch;
use App\Models\Development;
use App\Models\Languages;
use App\Models\Translation;
use App\Models\PropertyClaim;
use App\Mail\GenericMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Property;
use App\PropertyMedia;
use App\PropertyType;
use App\PropertyStats;
use App\Enquiry;
use App\Jobs\SendEnquiryEmail;
use App\Traits\TranslateTrait;
use Carbon\Carbon;
use App\Traits\ImageTrait;
use Auth;
use Barryvdh\DomPDF\PDF;
use DB;
use File;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Str;
use Image;







class PropertiesController extends Controller
{
    use TranslateTrait;
    use ImageTrait;

    /**
    * Create a new controller instance.
    *
    * @return void
    */

   


    public function __construct()
    {
        $this->moduleTitle = "Properties";

        $this->branches_enabled = settings('branches_option');

        $this->middleware(function ($request, $next)
        {
            $this->user_role = Auth::user()->role_id;
            $this->branch_id = Auth::user()->branch_id;

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $criteria = '';

        //echo  Auth::user()->role;

        if(!empty($request))
        {
            $setting = settings('sale_rent');

            // Set Search Criteria, Also Prevent Search for Sale if Rental only or vice versa...
            $criteria = $request->input();
            switch ($setting)
            {
                case "sale":
                    $criteria['for'] = 'sale';
                break;
                case "rent":
                    $criteria['for'] = 'rent';
                break;
                default:
                    $criteria = $request->input();
            }
        }

        if($this->branches_enabled == '1')
        {
            if($this->user_role == '3')
            {
                // Agent... // Need a Branch Manager for this...is_development
                //$criteria['branch_id'] = $this->branch_id;
            }
        }

        $property = new Property();

        if(!empty($criteria['property-type-ids']) && is_array($criteria['property-type-ids'])){
            $property_type_id_array = $criteria['property-type-ids'];
            if(empty($property_type_id_array[0]) && $property_type_id_array[0] == ''){
                unset($criteria['property-type-ids']);
            }
        }
        
        // Include agent_notes criteria if provided
        if ($request->filled('agent_notes')) {
            $criteria['agent_notes'] = $request->input('agent_notes');
        }

        // Add order by updated_at in descending order
        $criteria['order_by'] = 'updated_at';
        $criteria['order_direction'] = 'desc';

        $properties = $property->searchWhere($criteria, TRUE);
        
        $propertyTypes = propertyType::orderBy('name')->get();

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'properties' =>  $properties,
            'propertyTypes' => $propertyTypes,
        ];

        return view('backend.properties.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $propertyTypes = propertyType::orderBy('name')->get();

        
        $data = [
            'pageTitle'=>'Create Property',
            'propertyTypes' => $propertyTypes,
            'branches' => Branch::all()
        ];

        return view('backend.properties.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'status'=>'required',
            'is_rental'=>'required',
            'user_id'=>'required',
            'property_type_id'=>'required',
            'country'=>'required',
            'price'=>'required|numeric|max:3999999999',
            'beds'=>'required',
        ],
        [
            'country.required' => 'Please provide a country on the settings.'
        ]
        );

        $property = new Property;

        $inputs = $request->input();

        $inputs = prepare_inputs($inputs);
        
        if(auth()->check() && auth()->user()->role_id === 1) { // check if the current user is Super Admin then property aprrovel is 1 else 0;
            $inputs['admin_approval'] = 1;
        }
        
        foreach($inputs as $field => $input){
            if($field == 'property_type_ids'){
                if( is_array($input) ){
                    $property->property_type_ids = ','.implode(',',$input).',';
                }else{
                    $property->property_type_ids = '';
                }
            }else{
                $property->{$field} = $input;
            }
        }

        $property->save();

        //Update the ref#
        $property_update = Property::find($property->id);
        //$ref_prefix = settings('ref_prefix');
        $ref_prefix = $property->is_rental ? 'R-' : 'S-';
        $property_update->ref = $ref_prefix.$property->id;

        // If New Development (Logic)....
        if(settings('new_developments'))
        {
            // See If Property Type (New Development) Exists...
            $property_type = PropertyType::find($request->input('property_type_id'));

            if($property_type->slug == 'new-development')
            {
                // If New Development, Set To "y" is Development...
                $property_update->is_development = 'y';
            }
        }

        $property_update->save();

        $data = [ 'success' => 'Property Created' ];

        return redirect(admin_url('properties/'.$property->id.'/edit'))->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect(admin_url('properties'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $property = Property::withTrashed()->find($id);
        $propertyTypes = propertyType::orderBy('name')->get();

        if(empty($property)){ return redirect(admin_url('properties')); }
        // return redirect('/properties')->with('error','Unauthorize page');

        $agentSelected = ['Please Select'];
        if( !empty($property->user)){
            $agentSelected[] = [$property->user->id => $property->user->fullname];
        }

        if(settings('translations'))
        {
            $languages = Languages::first();
            $languages = $languages->languages_friendly_array;
        }
        else
        {
            $languages = '';
        }

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'languages' => $languages,
            'property'  =>  $property,
            'propertyTypes' => $propertyTypes,
            'agentSelected' => $agentSelected,
            'branches'  => Branch::all()
        ];

        return view('backend.properties.edit')->with($data);
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
        // \Log::channel('property_updates')->info("Attempting to update property with ID: " . $id, [
        //     'user_id' => auth()->id(),
        //     'request_data' => $request->except(['_token', '_method'])
        // ]);

        $property = Property::find($id);

        if(!$property)
        {
            $property = Property::withTrashed()->find($id);
        }

        $originalData = $property->getAttributes();

        $request->validate(
            [
                'status'=>'required',
                'is_rental'=>'required',
                'user_id'=>'required',
                'property_type_id'=>'required',
                'price'=>'required|numeric|max:3999999999',
                'beds'=>'required',
                'description'=>'required|max:60000',
                'name' => 'max:80',
                'property_status' => 'max:40',
                'land_area'=> 'max:40',
                'internal_area' => 'max:40',
                'terrace_area' => 'max:40',
                'terrace_area' => 'digits_between:0,10|numeric|nullable',
                'land_area'=> 'digits_between:0,10|numeric|nullable',
                'internal_area' => 'digits_between:0,10|numeric|nullable',
                'price_qualifier'=> 'max:20',
                'property_status'=> 'max:40',
                'youtube_id' => 'max:11',
                'add_info'  => 'max:60000'
            ]
        );

        // if(settings('new_developments'))
        // {
        //     if($property->is_development == 'y')
        //     {
        //         // Property Is A New Development, Do Validation....
        //         $request->validate(
        //             [
        //                 'development_title'          => 'required',
        //                 'development_heading'        => 'required',
        //                 'development_status'         => 'required',
        //                 'development_price_from'     => 'required'
        //             ]
        //         );
        //     }
        // }


        // Add this after property update but before redirect
        $userName = auth()->user()->name ?? 'System';
        $changes = [];
        foreach ($request->all() as $field => $newValue) {
            if (isset($originalData[$field]) && $originalData[$field] != $newValue) {
                $changes[$field] = [
                    'from' => $originalData[$field],
                    'to' => $newValue
                ];
            }
        }

        if (!empty($changes)) {
            $changeLog = [];
            foreach ($changes as $field => $values) {
                $changeLog[] = "$field: {$values['from']} → {$values['to']}";
            }
            
            \Log::channel('property_updates')->info("Property {$property->ref} updated by {$userName}", [
                'changes' => implode(', ', $changeLog)
            ]);
        }

        $inputs = $request->input();
        $inputs = prepare_inputs($inputs);

        // If Language Settings - Remove from Inputs...
        if(settings('translations'))
        {
            //To note adding new field must also add in app/Models/Translation fillable
            $translationFields = [
                'description'
            ];
            $this->saveTranslation($request, $property, $translationFields, 'App\Property'); //Saving translations...
            $inputs = $this->removeTransInput($inputs, $translationFields); //filtering translation field...
        }

        foreach($inputs as $field => $input)
        {
            if($field == 'property_type_ids'){
                if( is_array($input) ){
                    $property->property_type_ids = ','.implode(',',$input).',';
                }else{
                    $property->property_type_ids = '';
                }
            }else{
                $property->{$field} = $input;
            }
        }
        $ref_prefix = $property->is_rental ? 'R-' : 'S-';
        $property->ref = $ref_prefix.$property->id;
        // Catch Export BHS tickbox in case it was unticked - RH
        $property->export_bhs = $request->input('export_bhs');

        // If Development - Create / Update The Development...
        // if(settings('new_developments'))
        // {
        //     if ($property->is_development == 'y')
        //     {

        //         $development = Development::firstOrNew(
        //             array(
        //                 'development_title' => $request->input('development_title')
        //             ));
        //         $development->development_heading = $request->input('development_heading');
        //         $development->development_subheading = $request->input('development_subheading');
        //         $development->completion_date = $request->input('completion_date');
        //         $development->development_developer = $request->input('development_developer');
        //         $development->development_status = $request->input('development_status');
        //         $development->development_construction_start = $request->input('development_construction_start');
        //         $development->development_price_from = $request->input('development_price_from');
        //         $development->development_price_to = $request->input('development_price_to');
        //         $development->property_id = $id;
        //         $development->save();
        //         $property->development_id = $development->development_id;
        //     }
        // }
        unset($property->development_title);
        unset($property->development_heading);
        unset($property->development_subheading);
        unset($property->completion_date);
        unset($property->development_developer);
        unset($property->development_status);
        unset($property->development_construction_start);
        unset($property->development_price_from);
        unset($property->development_price_to);

        $property->save();

        $data = [ 'success' => 'Property Updated' ];

        return redirect(admin_url('properties/'.$id.'/edit'))->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function locationUpdate(Request $request, $id)
{
    $request->validate([
        'city'=>'required|max:80',
        'street'=> 'max:80',
        'town'=> 'max:80',
        'complex_name'=> 'max:80',
        'region'=> 'max:80',
        'postcode'=> 'max:8',
        'country'=> 'required|max:80',
    ]);

    $property = Property::find($id);
    
    // Store original values for logging
    $originalData = $property->getAttributes();

    $inputs = $request->input();
    $inputs = prepare_inputs($inputs);

    // Track changes for logging
    $changes = [];
    foreach($inputs as $field => $input) {
        if (isset($originalData[$field]) && $originalData[$field] !== $input) {
            $changes[$field] = [
                'from' => $originalData[$field],
                'to' => $input
            ];
        }
        $property->{$field} = $input;
    }

    $property->save();

    // Log the changes if any were made
    if (!empty($changes)) {
        $userName = auth()->user()->name ?? 'System';
        $changeLog = [];
        foreach ($changes as $field => $values) {
            $changeLog[] = "$field: {$values['from']} → {$values['to']}";
        }
        
        \Log::channel('property_updates')->info("Property {$property->ref} location updated by {$userName}", [
            'property_id' => $id,
            'changes' => implode(', ', $changeLog),
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    $data = [
        'success' => 'Property location updated'
    ];

    return redirect(admin_url('properties/'.$id.'/location'))->with($data);
}
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function agentnoteUpdate(Request $request, $id)
    {
        $property = Property::find($id);

        $request->validate([
            'agent_notes'=>'max:60000',
        ]);

        $property->agent_notes =  $request->input('agent_notes');
        $property->save();

        $data = [
            'success' => 'Agent notes updated'
        ];
        return redirect(admin_url('properties/'.$id.'/notes'))->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $property = Property::find($id);

        if(!$property)
        {
            $property = Property::withTrashed()->find($id);
        }

        if($property)
        {
            // Soft Delete (Change Status To -2)....
            $property->status = '-2';
            $property->save();
            $property->delete();

            // Send Confirmed Response...
            return response()->json([
                'error' => 'false',
                'message' => 'Property Removed!'
            ]);
        }
    }

    /**
     * Show the form for location the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function location($id)
    {
        $property = Property::find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'property' => $property
        ];

        return view('backend.properties.location')->with($data);
    }

    /**
     * Show the form for photo the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function photos($id)
    {
        $property = Property::find($id);

        if(empty($property)){ return redirect(admin_url('properties')); }

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'property' => $property
        ];

        return view('backend.properties.photos')->with($data);
    }

    public function duplicate($id)
{
    \Log::info("Attempting to duplicate property with ID: " . $id);

    DB::beginTransaction();
    try {
        $originalProperty = Property::findOrFail($id);
        $newProperty = $originalProperty->replicate();
        
        // Set new properties
        $newProperty->ref = ($newProperty->is_rental ? 'R-' : 'S-') . (Property::max('id') + 1);
        $newProperty->status = 0; // Set to inactive
        $newProperty->created_at = now();
        $newProperty->updated_at = now();
        
        // Ensure unique title/name
        $newProperty->name = 'Copy of ' . $originalProperty->name;
        
        $newProperty->save();

        \Log::info("New property created with ID: " . $newProperty->id);

        // Function to copy media (photos and documents)
        $copyMedia = function($mediaItems, $type) use ($newProperty, $originalProperty) {
            foreach ($mediaItems as $item) {
                try {
                    $newItem = $item->replicate();
                    $newItem->property_id = $newProperty->id;
                    
                    $originalPath = $item->path;
                    $newPath = str_replace($originalProperty->id, $newProperty->id, $originalPath);
                    
                    // Ensure the directory exists
                    $directory = dirname(storage_path('app/public/' . $newPath));
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    // Copy the actual file
                    if (Storage::disk('public')->exists($originalPath)) {
                        Storage::disk('public')->copy($originalPath, $newPath);
                        \Log::info("Copied {$type} from {$originalPath} to {$newPath}");
                    } else {
                        \Log::warning("Original {$type} not found: {$originalPath}");
                    }

                    $newItem->path = $newPath;
                    $newItem->save();

                    \Log::info("Duplicated {$type} with ID: " . $newItem->id);
                } catch (\Exception $e) {
                    \Log::error("Error copying {$type}: " . $e->getMessage());
                    continue; // Continue with next item even if one fails
                }
            }
        };

        // Duplicate photos
        if ($originalProperty->propertyMediaPhotos) {
            $copyMedia($originalProperty->propertyMediaPhotos, 'photo');
        }

        // Duplicate documents
        if ($originalProperty->propertyMediaDocuments) {
            $copyMedia($originalProperty->propertyMediaDocuments, 'document');
        }

        // Duplicate amenities
        if ($originalProperty->amenities) {
            $newProperty->amenities()->sync($originalProperty->amenities->pluck('id'));
        }

        // Duplicate features
        if ($originalProperty->features) {
            $newProperty->features()->sync($originalProperty->features->pluck('id'));
        }

        // Duplicate floorplans if they exist
        if (method_exists($originalProperty, 'propertyMediaFloorplans') && $originalProperty->propertyMediaFloorplans) {
            $copyMedia($originalProperty->propertyMediaFloorplans, 'floorplan');
        }

        // Duplicate development info if it exists and property is a development
        if ($originalProperty->is_development == 'y' && $originalProperty->development) {
            $newDevelopment = $originalProperty->development->replicate();
            $newDevelopment->property_id = $newProperty->id;
            $newDevelopment->save();
        }

        DB::commit();
        \Log::info("Property duplication completed successfully");

        return redirect()->route('properties.edit', $newProperty->id)
                         ->with('success', 'Property duplicated successfully. You are now editing the new property.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Error duplicating property: " . $e->getMessage());
        return back()->with('error', 'Failed to duplicate property: ' . $e->getMessage());
    }
}
    

    public function photo_upload(Request $request, $id)
    {
        $request->validate([
            'file' => 'mimes:jpeg,jpg,gif,png,tif,webp|nullable|max:5999'
        ]);

        $propertyMediaCount = PropertyMedia::
                            where('property_id', $id)
                            ->where('type', 'photo')->count();

        if($propertyMediaCount < 70)
        {
            if ($request->hasFile('file') )
            {

                // $filenameWithExt = $request->file('file')->getClientOriginalName();
                // // $extension = $request->file('file')->getClientOriginalExtension();
                // // $fileNameToStore = uniqid() . '.' . strtolower($extension);

                // $extension = 'webp'; // force webp
                // $fileNameToStore = uniqid() . '.'.$extension;

                $path_properties = 'app/public/';
                $path_property_photo = 'properties/' . $id . '/photos';

                $path_image = processImage($request->file('file'),$path_property_photo,1000,667);

                if ($path_image)
                {
                    $photo = new PropertyMedia;
                    $photo->property_id = $id;
                    $photo->type = 'photo';
                    $photo->extension = strtolower('webp');
                    $photo->path = $path_property_photo.'/'.$path_image;
                    $photo->save();
                }
                // $getRealPath = ($_FILES['file']['tmp_name']);
                // $image_resize = Image::make($getRealPath);

                // $checkWidth = $image_resize->width();
                // $checkheight = $image_resize->height();

                // if($checkWidth < 2000 && $checkheight < 3000)
                // {
                //     //Resize image constrain ratio Max 1000
                //     $image_resize->orientate();
                //     $image_resize->resize(1000, 1000, function ($constraint) {
                //         $constraint->aspectRatio();
                //         $constraint->upsize();
                //     });


                //     /**
                //      * 🔥 COMPRESS IMAGE HERE
                //      * 70 = quality (0=lowest, 100=highest)
                //      * You can adjust:
                //      * - 60 = smaller size
                //      * - 80 = better quality
                //      */
                //     $compressedImage = $image_resize->encode($extension, 70);

                //     // Store image
                //     $path_save_image = 'properties' . $path_property_photo . $fileNameToStore;

                //     // Switch Between Public / S3...
                //     $disk = "public";

                //     if(settings('store_s3') !== 'false')
                //     {
                //         $disk = "s3";
                //     }

                //     // $path = Storage::disk($disk)->put($path_save_image, $compressedImage);
                   

                //     if ($path)
                //     {
                //         $photo = new PropertyMedia;
                //         $photo->property_id = $id;
                //         $photo->type = 'photo';
                //         $photo->extension = strtolower($extension);
                //         $photo->orientation = $this->get_image_orientation(Storage::url($path_image));
                //         $photo->path = $path_save_image;
                //         $photo->save();
                //     }
                // }
                // else
                // {
                //     header('HTTP/1.1 500 Internal Server Error');
                //     header('Content-type: text/plain');
                //     $msg = 'Image width must not greater than 2000px or <br/>the height must not greater than 3000px.';
                //     exit($msg);
                // }
            }
        }
        else
        {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-type: text/plain');
            $msg = 'You have reached the maximum number of images for a property.';
            exit($msg);
        }
    }

     // torage::disk($disk)->put($path_save_image, $image_resize->stream());

    public function photoSort(Request $request, $id)
    {
        $items = $request->input('item');
        foreach($items as $order => $media_id){
            $photo = PropertyMedia::find($media_id);
            $photo->sequence = $order;
            $photo->save();
		}
    }

    public function photoCaption(Request $request, $id)
    {
        $media_id = $request->input('mediaID');
        $photo = PropertyMedia::find($media_id);
        $photo->title = $request->input('caption');
        $check = $photo->save();
        if($check){
            $json["message"] = 'updated successfully';
            $json["flag"] = 1;
        }else{
            $json["message"] = 'Not Updated';
            $json["flag"] = 0;
        }
        echo json_encode($json);
    }

    public function photoDestroy(Request $request, $id)
    {
        $delete_ids = $request->input('delete_ids');
        if(!empty($delete_ids)){
            foreach($delete_ids as $PropertyMedia_id){
                $photo = PropertyMedia::find($PropertyMedia_id);
                if(!empty($photo->path)){
                    Storage::delete($photo->path);
                    $photo->delete();
                    $data = [
                        'success' => 'Property photo has been deleted'
                    ];
                }
            }
        }else{
            $data = [
                'error' => 'No photo selected'
            ];
        }

        return redirect(admin_url('properties/'.$id.'/photos'))->with($data);
    }

    /**
     * Show the form for floorplan the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function floorplans($id)
    {
        $property = Property::find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'property'  =>  $property,
        ];

        return view('backend.properties.floorplans')->with($data);
    }

    public function floorplan_upload(Request $request, $id)
    {
        $request->validate([
            'file'=>'mimes:jpeg,jpg,gif,png,tif,pdf,webp|nullable|max:5999'
        ]);

        $propertyMediaCount = PropertyMedia::
                            where('property_id', $id)
                            ->where('type', 'floorplan')->count();

        if($propertyMediaCount < 35){
            if( $request->hasFile('file') ){
                $filenameWithExt = $request->file('file')->getClientOriginalName();
                $extension = $request->file('file')->getClientOriginalExtension();
                $fileNameToStore = uniqid().'.'.strtolower($extension);

                // $path_properties = 'app/public/properties';
                $path_property_floorplan = '/'.$id.'/floorplans';

                // $getRealPath = ($_FILES['file']['tmp_name']);

                // $image_resize = false;
                // $checkWidth = false;
                // $checkheight = false;

                // if(strtolower($extension) != 'pdf'){
                //     $image_resize = Image::make($getRealPath);
                //     $checkWidth = $image_resize->width();
                //     $checkheight = $image_resize->height();
                // }

                if(strtolower($extension) == 'pdf'){

                    //store pdf file
                    $path_properties = 'properties';
                    $path_image = $request->file('file')->storeAs($path_properties.$path_property_floorplan.'/',$fileNameToStore);
                    $path_save_image = 'properties'.$path_property_floorplan.'/'.$fileNameToStore;

                    if(!empty($path_image)){
                        $floorplan = new PropertyMedia;
                        $floorplan->property_id = $id;
                        $floorplan->type = 'floorplan';
                        $floorplan->extension = strtolower($extension);
                        $floorplan->path = $path_save_image;
                        $floorplan->save();
                    }

                }else{

                    $path_image = processImage($request->file('file'),'properties'.$path_property_floorplan,1000, 1000);

                    //Resize image constrain ratio Max 1000
                    // $image_resize->resize(1000, 1000, function ($constraint) {
                    //     $constraint->aspectRatio();
                    //     $constraint->upsize();
                    // });

                    // //Store image
                    // File::makeDirectory(storage_path($path_properties.$path_property_floorplan), $mode = 0777, true, true);
                    // $path_image = $image_resize->save(storage_path($path_properties.$path_property_floorplan.$fileNameToStore));
                    $path_save_image = 'properties'.$path_property_floorplan.'/'.$path_image;

                    if(!empty($path_image)){
                        $floorplan = new PropertyMedia;
                        $floorplan->property_id = $id;
                        $floorplan->type = 'floorplan';
                        $floorplan->extension = strtolower($extension);
                        $floorplan->path = $path_save_image;
                        $floorplan->orientation = $this->get_image_orientation(Storage::path($path_save_image));
                        $floorplan->save();
                    }

                }
                // else{
                //     header('HTTP/1.1 500 Internal Server Error');
                //     header('Content-type: text/plain');
                //     $msg = 'Image width must not greater than 2000px or <br/>the height must not greater than 3000px.';
                //     exit($msg);
                // }
            }
        }else{
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-type: text/plain');
            $msg = 'You have reach the maximum limit of images.';
            exit($msg);
        }

    }

    public function floorplanSort(Request $request, $id)
    {
        $items = $request->input('item');
        foreach($items as $order => $media_id){
            $floorplan = PropertyMedia::find($media_id);
            $floorplan->sequence = $order;
            $floorplan->save();
        }
    }

    public function floorplanCaption(Request $request, $id)
    {
        $media_id = $request->input('mediaID');
        $floorplan = PropertyMedia::find($media_id);
        $floorplan->title = $request->input('caption');
        $check = $floorplan->save();
        if($check){
            $json["flag"] = 1;
        }else{
            $json["flag"] = 0;
        }
        echo json_encode($json);
    }

    public function floorplanDestroy(Request $request, $id)
    {
        $delete_ids = $request->input('delete_ids');
        if(!empty($delete_ids)){
            foreach($delete_ids as $PropertyMedia_id){
                $floorplan = PropertyMedia::find($PropertyMedia_id);
                if(!empty($floorplan->path)){
                    Storage::delete($floorplan->path);
                    $floorplan->delete();
                    $data = [ 'success' => 'Property floorplan has been deleted' ];
                }
            }
        }else{
            $data = [ 'error' => 'No floorplan selected' ];
        }

        return redirect(admin_url('properties/'.$id.'/floorplans'))->with($data);
    }


    /**
     * Show the form for documents the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function documents($id)
    {
        $property = Property::find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'property'  =>  $property,
        ];

        return view('backend.properties.documents')->with($data);
    }

    public function document_upload(Request $request, $id)
{
    $file = $request->file('file');

    $request->validate([
        'file'=>'mimes:jpeg,jpg,doc,pdf,docx,rtf,webp,png|nullable|max:5999'
    ]);

    $propertyMediaCount = PropertyMedia::where('property_id', $id)
                            ->where('type', 'document')->count();

    if($propertyMediaCount < 35) {
        if($request->hasFile('file')) {
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = uniqid().'.'.strtolower($extension);

            $path_properties = 'properties';
            $path_property_document = '/'.$id.'/documents/';

            //store file
            $path_image = $request->file('file')->storeAs($path_properties.$path_property_document,$fileNameToStore);
            $path_save_image = 'properties'.$path_property_document.$fileNameToStore;

            if(!empty($path_image)) {
                $document = new PropertyMedia;
                $document->property_id = $id;
                $document->type = 'document';
                $document->extension = strtolower($extension);
                $document->path = $path_save_image;
                $document->filename = $filenameWithExt;
                $document->save();

                // Log document upload
                $userName = auth()->user()->name ?? 'System';
                $property = Property::find($id);
                \Log::channel('property_updates')->info("New document uploaded for Property {$property->ref} by {$userName}", [
                    'property_id' => $id,
                    'document_name' => $filenameWithExt,
                    'document_type' => strtolower($extension),
                    'timestamp' => now()->toDateTimeString()
                ]);
            }
        }
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-type: text/plain');
        $msg = 'You have reach the maximum limit of files.';
        exit($msg);
    }
}

    public function documentSort(Request $request, $id)
    {
        $items = $request->input('item');
        foreach($items as $order => $media_id){
            $document = PropertyMedia::find($media_id);
            $document->sequence = $order;
            $document->save();
        }
    }

    public function documentCaption(Request $request, $id)
    {
        $media_id = $request->input('mediaID');
        $document = PropertyMedia::find($media_id);
        $oldCaption = $document->title;
        $newCaption = $request->input('caption');
        $document->title = $newCaption;
        $check = $document->save();
    
        if($check) {
            // Log caption update
            $userName = auth()->user()->name ?? 'System';
            $property = Property::find($id);
            \Log::channel('property_updates')->info("Document caption updated for Property {$property->ref} by {$userName}", [
                'property_id' => $id,
                'document_id' => $media_id,
                'changes' => "caption: {$oldCaption} → {$newCaption}",
                'timestamp' => now()->toDateTimeString()
            ]);
    
            $json["flag"] = 1;
        } else {
            $json["flag"] = 0;
        }
        echo json_encode($json);
    }

    public function documentDestroy(Request $request, $id)
{
    $delete_ids = $request->input('delete_ids');
    if(!empty($delete_ids)) {
        foreach($delete_ids as $PropertyMedia_id) {
            $document = PropertyMedia::find($PropertyMedia_id);
            if(!empty($document->path)) {
                // Log document deletion before deleting
                $userName = auth()->user()->name ?? 'System';
                $property = Property::find($id);
                \Log::channel('property_updates')->info("Document deleted from Property {$property->ref} by {$userName}", [
                    'property_id' => $id,
                    'document_name' => $document->filename,
                    'document_id' => $PropertyMedia_id,
                    'timestamp' => now()->toDateTimeString()
                ]);

                Storage::delete($document->path);
                $document->delete();
                $data = [ 'success' => 'Property document has been deleted' ];
            }
        }
    } else {
        $data = [ 'error' => 'No document selected' ];
    }

    return redirect(admin_url('properties/'.$id.'/documents'))->with($data);
}

    /**
     * Show the form for notes the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function notes($id)
    {
        $property = Property::find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'property'  =>  $property,
        ];
        return view('backend.properties.notes')->with($data);
    }

    public function history($id)
    {
        $property = Property::find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'property'  =>  $property,
        ];
        return view('backend.properties.history')->with($data);
    }
    /**
     * Show the form for enquires the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leads($id)
    {
        $property = Property::find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }

        $propertyStats = new PropertyStats();

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'property'  =>  $property,
            'property_views' => $propertyStats->total($id)
        ];

        return view('backend.properties.leads')->with($data);
    }


    /**
     * Show the stats the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function stats($id)
    {
        $property = Property::find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }

        $propertyStats = new PropertyStats();
        $property_view_total = $propertyStats->total($id);
        $property_search_total = $propertyStats->total($id,'property_search');
        $property_30days_view_total = $propertyStats->total_past_30days_by_property($id);
        $property_30days_search_total = $propertyStats->total_past_30days_by_property($id, 'property_search');
        $property_search_stats = $propertyStats->get_search_stats($id, 'property_search');
        $property_search_30days_stats = $propertyStats->get_search_stats_30days_by_property($id, 'property_search');
        $whatsappClickCount = DB::table('whatsapp_clicks')
        ->where('ref', $property->ref)
        ->count();

        $enquiry = new Enquiry();
        $total_past_30days = $enquiry->total_past_30days_by_property($property->ref);

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'property'  =>  $property,
            'property_view_total' => $property_view_total,
            'property_search_total' => $property_search_total,
            'property_search_stats' => $property_search_stats,
            'property_search_30days_stats' => $property_search_30days_stats,
            'total_past_30days' => $total_past_30days,
            'property_30days_view_total' => $property_30days_view_total,
            'property_30days_search_total' => $property_30days_search_total,
            'whatsappClickCount' => $whatsappClickCount
        ];

        return view('backend.properties.stats')->with($data);
    }

    public function generatePropertyStatsPdf($id)
    {
        try {
            // Find the property
            $property = Property::findOrFail($id);
    
            // Initialize services
            $propertyStats = new PropertyStats();
            $enquiry = new Enquiry();
    
            // Retrieve stats
            $property_view_total = $propertyStats->total($id);
            $property_search_total = $propertyStats->total($id,'property_search');
            $property_30days_view_total = $propertyStats->total_past_30days_by_property($id);
            $property_30days_search_total = $propertyStats->total_past_30days_by_property($id, 'property_search');
            
            $whatsappClickCount = DB::table('whatsapp_clicks')
                ->where('ref', $property->ref)
                ->count();
    
            $total_past_30days = $enquiry->total_past_30days_by_property($property->ref);
    
            // Retrieve search stats safely
            $property_search_stats = $propertyStats->get_search_stats($id, 'property_search') ?? [];
    
            // Prepare data for PDF
            $data = [
                'property' => $property,
                'past_30_days_views' => $property_30days_view_total ?? 0,
                'all_time_views' => $property_view_total ?? 0,
                'past_30_days_search_views' => $property_30days_search_total ?? 0,
                'all_time_search_views' => $property_search_total ?? 0,
                'past_30_days_whatsapp_clicks' => $whatsappClickCount ?? 0,
                'all_time_whatsapp_clicks' => $whatsappClickCount ?? 0,
                'past_30_days_enquiries' => $total_past_30days ?? 0,
                'all_time_enquiries' => $total_past_30days ?? 0,
                'property_search_stats' => $property_search_stats,
            ];
    
            // Generate PDF
            $pdf = PDF::loadView('backend.properties.stats-pdf', $data)
                ->setPaper('a4')
                ->setWarnings(false);
    
            // Generate filename
            $filename = "Property_Stats_Report_{$property->ref}.pdf";
    
            // Return PDF for download
            return $pdf->download($filename);
    
        } catch (\Exception $e) {
            Log::error('PDF Generation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'property_id' => $id,
            ]);
    
            return back()->with('error', 'Unable to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Archive a property
     */
    public function archive($id)
    {
        $property = Property::find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }
        $property->status = 1;
        $property->archived_at = Carbon::now()->format('Y-m-d H:i:s');
        $property->save();
        $data = [ 'success' => 'Property #'.$property->ref.' - '.$property->search_headline.' has been archived.'];
        return redirect(admin_url('properties/'))->with($data);
    }

    /**
     * Reactive a property
     */
    public function reactive($id)
    {
        $property = Property::withTrashed()->find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }
        $property->status = -1;
        $property->archived_at = NULL;
        $property->save();
        $data = ['success' => 'Property #'.$property->ref.' - '.$property->search_headline.' has been reactivated.'];
        //return redirect(admin_url('properties/'))->with($data);
        $url = url()->previous();
        return redirect($url)->with($data);
    }


    /**
     * Permanent delete a property
     */
    public function delete($id)
    {
     $property = Property::withTrashed()->find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }
        $data = ['success' => 'Property #'.$property->ref.' - '.$property->search_headline.' has been permanently deleted.'];

        if(count($property->propertyMediaPhotos)){
            foreach( $property->propertyMediaPhotos as $media ){
                $PropertyMedia_id = $media->id;
                $photo = PropertyMedia::find($PropertyMedia_id);
                if(!empty($photo->path)){
                    Storage::delete($photo->path);
                    $photo->delete();
                    $data = [
                        'success' => 'Property photo has been deleted'
                    ];
                }
            }
        }

        $property->forceDelete();
        return redirect(admin_url('properties/'))->with($data);
    }

    public function response($id, $lead_id)
    {
        $property = Property::find($id);
        if(empty($property)){ return redirect(admin_url('properties')); }

        $enquiry = Enquiry::find($lead_id);

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'property'  =>  $property,
            'enquiry'       =>  $enquiry,
        ];

        return view('backend.properties.response')->with($data);
    }

    /**
     * GET LOCATIONS VIA AJAX.
     */
    public function get_all_locations(Request $request)
    {
        //init
        $q =(!empty($request->q)) ? $request->q : "";
        $items = [];
        $properties = [];
        $temp_locations = [];
        $ctr = 0;

        //FIELD TO SEARCH
        if(settings('overseas') == 1){
            $locations = ['street', 'town', 'city', 'complex_name', 'postcode'];
        }else{
            $locations = ['street', 'town', 'city', 'region', 'postcode'];
        }

        foreach( $locations as $location){ //FIELD TO SEARCH
            $properties = Property::where([[$location, 'LIKE', $q.'%']])->get();
            if(!empty($properties)){

                foreach( $properties as $property){ //LOOP PROPERTIES HAVING THE SAME Q
                    if( !in_array($property->{$location}, $temp_locations) ){
                        $new_item = new \stdClass();
                        //$new_item->id = time().'-'.mt_rand();
                        $new_item->id = urlencode($property->{$location});
                        $new_item->name = $property->{$location};
                        $items[] = $new_item;
                        $temp_locations[] = $property->{$location};
                    }
                }
            }
        }

        // ARRAY PAGINATION
        $page = ! empty( $request->page ) ? (int) $request->page : 1;
        $total = count( $items ); //total items in array
        $limit = 10; //per page
        $totalPages = ceil( $total/ $limit ); //calculate total pages
        $page = min($page, $totalPages); //get last page when $request->page > $totalPages
        $offset = ($page - 1) * $limit;
        if( $offset < 0 ) $offset = 0;
        $itemsSliced = array_slice( $items, $offset, $limit );
        $morePages = $page + 1;
        // END OF ARRAY PAGINATION

        //JSON
        $json["q"] = $q;
        $json["pagination"] = array( "more" => $morePages );
        $json["total_count"] = count($items);
        $json["page"] = $page;
        $json["items"] = $itemsSliced;
        echo json_encode( $json );
    }

    public function addnewstatus(Request $request){
        $request->validate([
            'p_status_input' => 'unique:property_statuses,name',
        ],[
            'p_status_input.unique'=>'Status is already exist!',
        ]);

        if (!empty($request->deletestatus) && count($request->deletestatus) > 0) {
            DB::table('property_statuses')
                ->whereIn('id', $request->deletestatus)
                ->delete();
        }

        if($request->p_status_input){
            DB::table('property_statuses')->insert([
                'name' => $request->p_status_input,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        return back()->with('success', 'Status updated!');
        
    }


    //claim properties function area

    public function claimproperties(Request $request){
        if(Auth::user()->role_id == 4) return back(); // return back if the Auth user role is not Admin, SuperAdmin , Agent;  

        $properties = Property::with(['claimedByUsers', 'user'])
        ->withCount('claimedByUsers')
        ->orderBy('updated_at', 'desc');

        // if(Auth::user()->role_id == 3){
        //     $properties->where('user_id',Auth::id());
        // }

        if($request->request->count() > 0){
            $properties = $this->searchdata($request,$properties);
        }

        $properties = $properties->paginate(12);
        return view('backend.properties_claim.index',compact('properties'));
    }

    public function saveclaimproperties(request $request){
        if(!empty($request->claim)){
            foreach($request->claim ?? [] as $propertyId => $status){
                $query = PropertyClaim::where([
                    'property_id' => $propertyId,
                    'user_id'     => $status['agent'],
                    'property_provide_date' => isset($status['provide_date']) ? $status['provide_date'] : Property::find($propertyId)->updated_at,
                ]);

                try {
                    if ($status['claim'] == 'true') {
                        if($query->exists()){    
                            return back()->with('error', 'Your already Claimed This Property.For further Information please contact Super Admin.');
                        }else{
                            $property = Property::with(['updateHistories' => function ($q) {
                                $q->latest('updated_at');
                            }])->find($propertyId);
                            PropertyClaim::create([
                                'property_id' => $propertyId,
                                'user_id'     => $status['agent'],
                                'property_status' => isset($status['property_value']) && !is_numeric($status['property_value']) ? $status['property_value'] : 'Confidential',
                                'property_value' => isset($status['property_value']) && is_numeric($status['property_value']) ? $status['property_value'] : null,
                                'property_is_rental' => Property::find($propertyId)->is_rental ?? null,
                                'property_provide_date' => $status['provide_date'] ?? ($property->updateHistories->count() > 0 ? $property->updateHistories->first()->updated_at : $property->updated_at),
                                'property_claim_approved' => FacadesAuth::user()->role_id == 1 ?? 0,
                            ]);

                            if(FacadesAuth::user()->role_id == 3){
                                $subject = 'Property Claim Request';
                                $userName = FacadesAuth::user()->name;
                                $msg = <<<HTML
                                <p>Hi Admin There is a Property Claim request submit by
                                <strong>{$userName}</strong>. Please perform further action!</p>
                                HTML;
                                // send to inquiries@terezaestates.com only settings('from_email')
                                $message = ['sub'=>$subject,'msg'=>$msg];
                                SendEnquiryEmail::dispatch(null,$message,'faizdev007@gmail.com');
                            }
                        }
                    }elseif ($status['claim'] == 'false' && $query->exists()) {
                        $query->delete();
                    }

                } catch (\Throwable $e) {

                    report($e); // log error

                    return back()->with('error', 'Something went wrong while updating property claims.'.$e->getMessage());
                }
            }   
            return back()->with('success', 'Properties Claim Updated!');
        }

        return back();
    }


    function searchdata($criteria, $query)
    {
        foreach ($criteria->request as $key => $value) {

            if ($value === null || $value === '') {
                continue;
            }

            switch ($key) {

                case 'for':
                    $query->where('is_rental', $value === 'rent');
                    break;

                case 'in':
                    if (strtolower($value) === 'international') {
                        $query->whereNotNull('country')
                            ->whereRaw('LOWER(country) NOT LIKE ?', ['%dubai%'])
                            ->whereRaw('LOWER(country) NOT LIKE ?', ['%uae%']);
                    } else {
                        $query->where(function ($q) use ($value) {
                            $q->where('name', 'LIKE', "%$value%")
                            ->orWhere('ref', 'LIKE', "%$value%")
                            ->orWhere('street', 'LIKE', "%$value%")
                            ->orWhere('town', 'LIKE', "%$value%")
                            ->orWhere('city', 'LIKE', "%$value%")
                            ->orWhere('region', 'LIKE', "%$value%")
                            ->orWhere('postcode', 'LIKE', "%$value%")
                            ->orWhere('country', 'LIKE', "%$value%")
                            ->orWhere('complex_name', 'LIKE', "%$value%");
                        });
                    }
                    break;

                case 'area':
                    if ($value === 'others') {
                        $areas = array_values(get_areas());
                        $query->where(function ($q) use ($areas) {
                            $q->whereNotIn('street', $areas)
                            ->whereNotIn('town', $areas)
                            ->whereNotIn('city', $areas)
                            ->whereNotIn('region', $areas)
                            ->whereNotIn('complex_name', $areas);
                        });
                    } else {
                        $area = urldecode(trim($value));
                        $query->where(function ($q) use ($area) {
                            $q->where('street', 'LIKE', "%$area%")
                            ->orWhere('town', 'LIKE', "%$area%")
                            ->orWhere('city', 'LIKE', "%$area%")
                            ->orWhere('region', 'LIKE', "%$area%")
                            ->orWhere('complex_name', 'LIKE', "%$area%");
                        });
                    }
                    break;
                case 'complex':
                    $query->where('complex_name', 'LIKE', '%'.$value.'%');
                    break;
                case 'property_type_id':
                    if (!empty($value)) {
                        $query->where(function($q) use ($value) {
                            $q->where('property_type_id', $value)
                              ->orWhere('property_type_ids', 'LIKE', '%"'.$value.'"%')
                              ->orWhere('property_type_ids', 'LIKE', '%,'.$value.',%')
                              ->orWhere('property_type_ids', 'LIKE', '%['.$value.']%')
                              ->orWhere('property_type_ids', 'LIKE', '%'.$value.'%');
                        });
                    }
                    break;
                case 'ref':
                    $query->where('ref', 'LIKE', '%'.$value.'%');
                    break;
                case 'is_featured':
                    $query->where('is_featured', $value);
                    break;
                case 'status':
                    $query->where('status',$value);
                    break;
                case 'claim_request':
                    if ($value == 1) {
                        // YES: claimedByUsers count >= 1
                        $query->has('claimedByUsers');

                    } elseif ($value == 0) {
                        // NO: claimedByUsers is null / empty
                        $query->doesntHave('claimedByUsers');
                    }
                    break;
            }
        }

        return $query;
    }

    

    // property listing approvel function
    public function property_approvel(Request $request,$pid){
        $subject = 'Property Listing Notification';
        $findP = Property::with('user')->where('id',$pid);
        if(isset($findP)){
           $findP->update([
            // 'status'=>6,
            'admin_approval'=>1,
           ]);
            $msg = <<<HTML
            <p>We’re pleased to inform you that your property listing with reference
            <strong>{$findP->first()->ref}</strong> has been approved by the Super Admin.</p>
            HTML;
            // send to inquiries@terezaestates.com only settings('from_email')
            $message = ['sub'=>$subject,'msg'=>$msg];
            SendEnquiryEmail::dispatch(null,$message,$findP->first()->user->email);
           return back()->with('success',$findP->first()->ref.' Property Listing Successfully Approved');
        }else{
            return back()->with('error','There seems to be an issue. Please try again.');
        }
    }

    // property listing rejection function
    public function property_reject(Request $request,$pid){
        $subject = 'Property Listing Notification';
        $findP = Property::with('user')->where('id',$pid);
        $propertyRef = $findP->first()->ref;
        $email = $findP->first()->user->email;
        if(isset($findP)){
            $findP->update([
                'status'=>1,
                'admin_approval'=>0,
                'archived_at'=> Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $msg = <<<HTML
            <p>Your property listing with reference
            <strong>{$propertyRef}</strong> has been rejected. Please contact the Super Admin for further clarification</p>
            HTML;
            // send to inquiries@terezaestates.com only settings('from_email')
            $message = ['sub'=>$subject,'msg'=>$msg];
            SendEnquiryEmail::dispatch(null,$message,$email);

           return back()->with('success',$propertyRef.' Property Listing Successfully Rejected');
        }else{
            return back()->with('error','There seems to be an issue. Please try again.');
        }
    }

    // property claim approvel function 
    public function property_claim_approvel(Request $request,$pid,$uid){
        $subject = 'Property Claim Notification';
        $findP = PropertyClaim::with('property','user')->where(['property_id'=>$pid,'user_id'=>$uid]);
        if(isset($findP)){
            $findP->update([
                'property_claim_approved'=>1,
                'property_provide_date'=>null,
            ]);
            $propertyRef = $findP->first()->property->ref;
            $msg = <<<HTML
            <p>We’re pleased to inform you that your property claim with reference
            <strong>{$propertyRef}</strong> has been approved by the Super Admin and is now available on your profile page.</p>
            HTML;
            // send to inquiries@terezaestates.com only settings('from_email')
            $message = ['sub'=>$subject,'msg'=>$msg];
            SendEnquiryEmail::dispatch(null,$message,$findP->first()->user->email);
            return back()->with('success',$findP->first()->property->ref.' Property Claim Request Successfully Approved!');
        }else{
            return back()->with('error','There seems to be an issue. Please try again.');
        }
    }

    // property claim rejection function
    public function property_claim_reject(Request $request,$pid,$uid){
        $subject = 'Property Claim Notification';
        $findP = PropertyClaim::with('property','user')->where(['property_id'=>$pid,'user_id'=>$uid]);
        $ref = $findP->first()->property->ref;
        $email = $findP->first()->user->email;
        if(isset($findP)){
            $findP->delete();
            $msg = <<<HTML
            <p>Your property claim with reference <strong>{$ref}</strong> has been rejected. Please contact the Super Admin for further clarification.</p>
            HTML;
            // send to inquiries@terezaestates.com only settings('from_email')
            $message = ['sub'=>$subject,'msg'=>$msg];
            SendEnquiryEmail::dispatch(null,$message,$email);
            return back()->with('success',$ref.' Property Claim Request Successfully Rejected!');
        }else{
            return back()->with('error','There seems to be an issue. Please try again.');
        }
    }

    public function revoke_claim_property(Request $request,$pid,$uid,$provide_date){
        $subject = 'Property Claim Notification';
        $findP = PropertyClaim::with('property','user')->where(['property_id'=>$pid,'user_id'=>$uid,'property_provide_date'=>$provide_date]);
        $ref = $findP->first()->property->ref;
        $user = $findP->first()->user;
        if(isset($findP)){
            $findP->delete();
            $msg = <<<HTML
            <p>We regret to inform you that your property claim with reference <strong>{$ref}</strong> has been revoked and is no longer visible on your profile. For further clarification, please contact the Super Admin.</p>
            HTML;
            // send to inquiries@terezaestates.com only setting('from_email')
            if($user->role_id == 1 || $user->role_id == 2){
                // if the user is admin , superadmin do not send email
                return back()->with('success',$ref.' Property Claim Successfully Revoke!');
            }
            $message = ['sub'=>$subject,'msg'=>$msg];
            SendEnquiryEmail::dispatch(null,$message,$user->email);
            return back()->with('success',$ref.' Property Claim Successfully Revoke!');
        }else{
            return back()->with('error','There seems to be an issue. Please try again.');
        }
    }
}
