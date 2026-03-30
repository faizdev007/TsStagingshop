<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JsonGuidesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->has('category_type')){

            $optionsSale = p_category();
            $optionsRent = p_category(false);

            $json = [
                'sale' => $optionsSale,
                'rent' => $optionsRent
            ];

            return response()->json($json);
        }


    }


}
