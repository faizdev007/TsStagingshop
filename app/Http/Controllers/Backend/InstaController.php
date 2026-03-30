<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InstaController extends Controller
{
    public function index()
    {
        $properties = DB::table('properties')
            ->select('ref', 'name', 'price')
            ->paginate(10);

        return view('backend.insta.index', compact('properties'));
    }
}