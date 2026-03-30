<?php

namespace App\Http\Controllers\Frontend;

use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cookie;

class DevelopmentsController extends Controller
{
    public function __construct()
    {
        $this->is_set = settings('new_developments');

        // Prevent Access if Turned Of....
        $this->middleware(function ($request, $next)
        {
            if($this->is_set)
            {
                return $next($request);
            }
            else
            {
                abort(404);
            }
        });
    }

    public function index()
    {
        // Get All New Developments....
        $properties = Property::whereHas('development')->paginate(12);

        $view_data = array
        (
            'properties' => $properties
        );

        // Developments View...
        $view = 'frontend.demo1.page-templates.developments';

        return view($view, $view_data);
    }




    // I am putting cookies controller functions here
    public function acceptEssential()
    {
        Cookie::queue('cookie_consent', json_encode([
            'essential' => true,
            'analytics' => false,
            'marketing' => false,
        ]), 60 * 24 * 365);

        return response()->json(['status' => 'ok']);
    }

    public function acceptAll()
    {
        Cookie::queue('cookie_consent', json_encode([
            'essential' => true,
            'analytics' => true,
            'marketing' => true,
        ]), 60 * 24 * 365);

        return response()->json(['status' => 'ok']);
    }

    public function custom(Request $request)
    {
        Cookie::queue('cookie_consent', json_encode([
            'essential' => true,
            'analytics' => $request->analytics == "true" ? true : false,
            'marketing' => $request->marketing == "true" ? true : false,
        ]), 60 * 24 * 365);

        return response()->json(['status' => 'saved']);
    }
}
