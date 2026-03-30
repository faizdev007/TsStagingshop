<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Property;
use App\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\TemplateTrait;
use App\Traits\PropertyBaseTrait;
use App\Traits\HelperTrait;

class TestFiltersController extends Controller
{
    use TemplateTrait;
    use PropertyBaseTrait;
    use HelperTrait;

    /**
     * Display the test filters page
     */
    public function index(Request $request)
    {
        try {
            // Get filter parameters
            $criteria = $request->all();
            
            // Get property types with counts
            $propertyTypes = DB::table('properties')
                ->select('property_type_id', 'property_types.name')
                ->join('property_types', 'properties.property_type_id', '=', 'property_types.id')
                ->selectRaw('COUNT(*) as properties_count')
                ->groupBy('property_type_id', 'property_types.name')
                ->get();

            // Get locations with counts
            $locations = DB::table('properties')
                ->select('country')
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->selectRaw('COUNT(*) as properties_count')
                ->groupBy('country')
                ->get();

            // Get areas with counts
            $areas = DB::table('properties')
                ->select('area')
                ->whereNotNull('area')
                ->where('area', '!=', '')
                ->selectRaw('COUNT(*) as properties_count')
                ->groupBy('area')
                ->get();

            // Get complexes with counts
            $complexes = DB::table('properties')
                ->select('complex_name')
                ->whereNotNull('complex_name')
                ->where('complex_name', '!=', '')
                ->selectRaw('COUNT(*) as properties_count')
                ->groupBy('complex_name')
                ->get();

            // Prepare query
            $query = Property::query();

            // Apply filters
            if ($propertyTypeIds = $this->getFilterValue($criteria, 'property-type-ids')) {
                $propertyTypeIds = explode(',', $propertyTypeIds);
                $query->whereIn('property_type_id', $propertyTypeIds);
            }

            if ($location = $this->getFilterValue($criteria, 'in')) {
                $query->where('country', $location);
            }

            if ($area = $this->getFilterValue($criteria, 'area')) {
                $query->where('area', $area);
            }

            if ($complex = $this->getFilterValue($criteria, 'complex')) {
                $query->where('complex_name', $complex);
            }

            // Get paginated results
            $properties = $query->paginate(12);

            // Pass data to view
            return view('frontend.demo1.page-templates.grid-filters-test', compact(
                'properties',
                'propertyTypes',
                'locations',
                'areas',
                'complexes',
                'criteria'
            ));

        } catch (\Exception $e) {
            // Log the error
            \Log::error('TestFiltersController Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            // Return error view
            return view('frontend.demo1.page-templates.grid-filters-test', [
                'error' => 'An error occurred while loading the properties. Please try again later.',
                'properties' => collect([]),
                'propertyTypes' => collect([]),
                'locations' => collect([]),
                'areas' => collect([]),
                'complexes' => collect([]),
                'criteria' => []
            ]);
        }
    }

    /**
     * Get filter value from criteria
     */
    private function getFilterValue($criteria, $key)
    {
        return isset($criteria[$key]) && !empty($criteria[$key]) ? $criteria[$key] : null;
    }

    /**
     * Get count of properties for a location
     */
    public function getLocationCount($location)
    {
        return DB::table('properties')
            ->where('country', $location)
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->count();
    }

    /**
     * Get count of properties for an area
     */
    public function getAreaCount($area)
    {
        return DB::table('properties')
            ->where('area', $area)
            ->whereNotNull('area')
            ->where('area', '!=', '')
            ->count();
    }

    /**
     * Get count of properties for a complex
     */
    public function getComplexCount($complex)
    {
        return DB::table('properties')
            ->where('complex_name', $complex)
            ->whereNotNull('complex_name')
            ->where('complex_name', '!=', '')
            ->count();
    }

    /**
     * Get property types with counts
     */
    public function getPropertyTypeCounts()
    {
        return DB::table('properties')
            ->select('property_type_id', 'property_types.name')
            ->join('property_types', 'properties.property_type_id', '=', 'property_types.id')
            ->selectRaw('COUNT(*) as properties_count')
            ->groupBy('property_type_id', 'property_types.name')
            ->get();
    }

    // public function newdevelopment(){
    //     $data = DB::table('properties')->where('is_development', '!=', 'y')->update([
    
    //     ]);

    //     dd($data);
    // }
}
