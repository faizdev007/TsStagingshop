<?php

namespace App\Http\Controllers\Backend;

use Config;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Property;
use App\PropertyType;

class PropertyTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pageTitle = 'Property Types';
        $propertyTypes = PropertyType::propertyType()->orderBy('name', 'asc')->get();
        return view('backend.property_types.index', compact('pageTitle', 'propertyTypes'));
    }

    public function create()
    {
        $pageTitle = 'Add Property Type';
        return view('backend.property_types.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:property_types,name',
        ]);

        $propertyType = new PropertyType();
        $propertyType->name = $request->name;
        $propertyType->types = 'property_type';
        $propertyType->save();

        return redirect()->route('admin.property-types.index')
            ->with('success', 'Property type created successfully');
    }

    public function edit($id)
    {
        $propertyType = PropertyType::findOrFail($id);
        $pageTitle = 'Edit Property Type';
        return view('backend.property_types.edit', compact('pageTitle', 'propertyType'));
    }

    public function update(Request $request, $id)
    {
        $propertyType = PropertyType::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:property_types,name,' . $id,
        ]);

        $propertyType->name = $request->name;
        $propertyType->save();

        return redirect()->route('admin.property-types.index')
            ->with('success', 'Property type updated successfully');
    }

    public function destroy($id)
    {
        $propertyType = PropertyType::findOrFail($id);
        
        // Check if there are properties using this type
        if ($propertyType->properties()->count() > 0) {
            return redirect()->route('admin.property-types.index')
                ->with('error', 'Cannot delete property type as it is being used by properties');
        }

        $propertyType->delete();

        return redirect()->route('admin.property-types.index')
            ->with('success', 'Property type deleted successfully');
    }
}