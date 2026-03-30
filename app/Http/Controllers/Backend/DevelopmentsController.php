<?php

namespace App\Http\Controllers\Backend;

use App\Models\Development;
use App\Models\DevelopmentUnit;
use App\Models\DevelopmentUnitMedia;
use App\Property;
use App\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Auth;

class DevelopmentsController extends Controller
{
    function __construct()
    {
        $this->moduleTitle = "Developments";

        $this->branches_enabled = settings('branches_option');

        
        $this->middleware(function ($request, $next)
        {
            if(!settings('new_developments'))
            {
                $this->user_role = Auth::user()->role_id;
                $this->branch_id = Auth::user()->branch_id;
                // No Access Allowed...
                $request->session()->flash('message_danger', 'You do not have access to this resource');
                return redirect('/admin');
            }

            return $next($request);
        });
    }

    public function index()
    {
        if(Auth::user()->role_id == 4) return back();
        $properties = Property::where('is_development', 'y')->orderby('created_at','desc');
        if(Auth::user()->role_id == 3){
            $properties->where('user_id',Auth::id());
        }
        
        return view('backend.developments.list',
            [
                'pageTitle'  =>  'Developments',
                'developments' => $properties->paginate(12)
            ]
        );
    }

    // public function newdevlopmentsearch(Request $request)
    // {
    //     $criteria = '';

    //     //echo  Auth::user()->role;

    //     if(!empty($request))
    //     {
    //         $setting = settings('sale_rent');

    //         // Set Search Criteria, Also Prevent Search for Sale if Rental only or vice versa...
    //         $criteria = $request->input();
    //         switch ($setting)
    //         {
    //             case "sale":
    //                 $criteria['for'] = 'sale';
    //             break;
    //             case "rent":
    //                 $criteria['for'] = 'rent';
    //             break;
    //             default:
    //                 $criteria = $request->input();
    //         }
    //     }

    //     if($this->branches_enabled == '1')
    //     {
    //         if($this->user_role == '3')
    //         {
    //             // Agent... // Need a Branch Manager for this...is_development
    //             //$criteria['branch_id'] = $this->branch_id;
    //         }
    //     }

    //     $property = new Property();

    //     if(!empty($criteria['property-type-ids']) && is_array($criteria['property-type-ids'])){
    //         $property_type_id_array = $criteria['property-type-ids'];
    //         if(empty($property_type_id_array[0]) && $property_type_id_array[0] == ''){
    //             unset($criteria['property-type-ids']);
    //         }
    //     }

        
    //     // Include agent_notes criteria if provided
    //     if ($request->filled('agent_notes')) {
    //     $criteria['agent_notes'] = $request->input('agent_notes');
    //     }

    //     // Add order by updated_at in descending order
    //     $criteria['order_by'] = 'updated_at';
    //     $criteria['order_direction'] = 'desc';

    //     $properties = $property->searchWhere($criteria, TRUE);

    //     $propertyTypes = propertyType::orderBy('name')->get();

    //     $data = [
    //         'pageTitle'  =>  $this->moduleTitle,
    //         'developments' =>  $properties,
    //         'propertyTypes' => $propertyTypes,
    //     ];

    //     return view('backend.developments.list')->with($data);
    // }

    public function units(Request $request, $id)
    {
        // Find The Property
        $property = Property::find($id);

        if($property->development->count() > 0)
        {
            // List All Units For That Development
            return view('backend.developments.index',
                [
                    'property'    => $property,
                    'development' => $property->development,
                    'units'       => $property->development->units
                ]
            );
        }
        else
        {
            $request->session()->flash('message_danger', 'This property does not have any developments assigned to it');
            return redirect()->back();
        }
    }

    public function create_unit($property, Request $request)
    {
        // Get Development ID
        $property = Property::find($property);

        if($property->development)
        {
            return view('backend.developments.units.create',
                [
                    'property_types'    => PropertyType::all(),
                    'property'          => $property,
                    'development'       => $property->development
                ]
            );
        }
        else
        {
            $request->session()->flash('message_danger', 'This property does not have any developments assigned to it');
            return redirect('admin/properties');
        }
    }

    public function store_unit(Request $request)
    {
        // Validate...
        $validate = $request->validate(
            [
                'development_unit_name'     => 'required',
                'property_type_id'          => 'required',
                'development_unit_bedrooms' => 'required|integer',
                'development_unit_bathrooms'=> 'required|integer',
                'development_unit_price'    => 'required',
            ]
        );

        // Create The Development Unit....
        $unit = new DevelopmentUnit;
        $unit->development_unit_name = $request->input('development_unit_name');
        $unit->property_type_id = $request->input('property_type_id');
        $unit->development_id = $request->input('development_id');
        $unit->development_unit_bedrooms = $request->input('development_unit_bedrooms');
        $unit->development_unit_bathrooms = $request->input('development_unit_bathrooms');
        $unit->development_unit_status = $request->input('development_unit_status');
        $unit->development_unit_availability = $request->input('development_unit_availability');
        $unit->development_unit_price = $request->input('development_unit_price');
        $unit->development_unit_outside_area = $request->input('development_unit_outside_area');
        $unit->development_unit_inside_area = $request->input('development_unit_inside_area');
        $unit->save();

        // Get Parent Property For This Development...
        $development = Development::find($request->input('development_id'));

        $request->session()->flash('message_success', 'Unit Created');

        return redirect('admin/properties/'.$development->property->id.'/units');
    }

    /**
     * @param $id
     */

    public function edit_unit($id)
    {
        return view('backend.developments.units.edit' ,
            [
                'property_types'=> PropertyType::all(),
                'unit'          => DevelopmentUnit::find($id)
            ]
        );
    }

    public function photo_sort($id, Request $request)
    {
        $items = $request->input('item');

        foreach($items as $order => $media_id)
        {
            $photo = DevelopmentUnitMedia::find($media_id);
            $photo->order = $order;
            $photo->save();
        }
    }

    public function delete_photo($id, Request $request)
    {
        $delete_ids = $request->input('delete_ids');
        if(!empty($delete_ids))
        {
            foreach($delete_ids as $id)
            {
                $photo = DevelopmentUnitMedia::find($id);
                if(!empty($photo->path))
                {
                    Storage::delete($photo->path);
                    $photo->delete();

                    $request->session()->flash('message_success', 'Photo Deleted');
                }
            }
        }
        else
        {
            $request->session()->flash('message_warning', 'No Photos Selected');
        }

        return redirect()->back();
    }

    public function update_unit(Request $request)
    {
        // Validate...
        $validate = $request->validate(
            [
                'development_unit_name'     => 'required',
                'property_type_id'          => 'required',
                'development_unit_bedrooms' => 'required|integer',
                'development_unit_bathrooms'=> 'required|integer',
                'development_unit_price'    => 'required',
            ]
        );

        $unit = DevelopmentUnit::find($request->input('development_unit_id'));
        $unit->development_unit_name = $request->input('development_unit_name');
        $unit->property_type_id = $request->input('property_type_id');
        $unit->development_id = $request->input('development_id');
        $unit->development_unit_bedrooms = $request->input('development_unit_bedrooms');
        $unit->development_unit_bathrooms = $request->input('development_unit_bathrooms');
        $unit->development_unit_status = $request->input('development_unit_status');
        $unit->development_unit_availability = $request->input('development_unit_availability');
        $unit->development_unit_price = $request->input('development_unit_price');
        $unit->development_unit_outside_area = $request->input('development_unit_outside_area');
        $unit->development_unit_inside_area = $request->input('development_unit_inside_area');
        $unit->save();

        // Get Parent Property For This Development...
        $development = Development::find($request->input('development_id'));

        $request->session()->flash('message_success', 'Unit Updated');

        return redirect('admin/properties/'.$development->property->id.'/units');
    }

    public function duplicate_unit($id, Request $request)
    {
        // Find Current Unit...
        $unit = DevelopmentUnit::find($id);
        $new_unit = $unit->replicate();
        $new_unit->save();

        // Get Parent Property For This Development...
        $development = Development::find($unit->development_id);

        $request->session()->flash('message_success', 'Unit Duplicated');

        return redirect('admin/development-unit/'.$new_unit->development_unit_id.'/edit');
    }

    public function photo_upload($id, Request $request)
    {
        $request->validate([
            'file' => 'mimes:jpeg,jpg,gif,png,tif|nullable|max:5999'
        ]);

        $mediacount = DevelopmentUnitMedia::where('development_id', $id)
                        ->where('type', 'photo')
                        ->count();

        if($mediacount < 35)
        {
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = uniqid() . '.' . strtolower($extension);

            $path_property_photo = '/' . $id . '/photos/';

            $getRealPath = ($_FILES['file']['tmp_name']);
            $image_resize = Image::make($getRealPath);

            $checkWidth = $image_resize->width();
            $checkheight = $image_resize->height();

            if($checkWidth < 2000 && $checkheight < 3000)
            {
                //Resize image constrain ratio Max 1000
                $image_resize->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Store image
                $path_save_image = 'developments' . $path_property_photo . $fileNameToStore;

                // Switch Between Public / S3...
                $disk = "public";

                if(settings('store_s3') !== 'false')
                {
                    $disk = "s3";
                }

                $path = Storage::disk($disk)->put($path_save_image, $image_resize->stream());

                if ($path)
                {
                    $photo = new DevelopmentUnitMedia;
                    $photo->unit_id = $id;
                    $photo->development_id = $request->input('development_id');
                    $photo->type = 'photo';
                    $photo->path = $path_save_image;
                    $photo->save();
                }
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

    public function sort_units(Request $request)
    {
        $items =  $request->input("item");

        foreach($items as $order => $id)
        {
            $unit = DevelopmentUnit::find($id);
            $unit->order = $order;
            $unit->save();
        }

        return response()->json(
            [
                'message' => 'Reordering Successful'
            ]
        );
    }

    public function destroy_unit($id)
    {
        $unit = DevelopmentUnit::find($id);

        $unit->delete();

        // Send Confirmed Response...
        return response()->json(
            [
                'error' => 'false',
                'message' => 'Unit Deleted!'
            ]
        );
    }
}
