<?php

namespace App\Http\Controllers\Frontend;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;



class ErrorLogController extends Controller
{

    // public function logFrontendError(Request $request)

    // {

    //     Log::channel('frontend_errors')->error('Frontend JavaScript Error', [

    //         'error' => $request->input('error'),

    //         'type' => $request->input('type'),

    //         'parentValue' => $request->input('parentValue'),

    //         'parentType' => $request->input('parentType'),

    //         'url' => $request->input('url'),

    //         'user_id' => auth()->id()

    //     ]);



    //     return response()->json(['status' => 'logged']);

    // }

}