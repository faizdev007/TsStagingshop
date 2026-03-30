<?php

namespace App\Http\Controllers\Backend;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Property;

class DashboardController extends Controller
{

    /**
    * Create a new controller instance.
    *
    * @return void
    */

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // Check If Logged In....
		if(Auth::check())
        {
            // Find The User's Role....
            $role_id = Auth::user()->role_id;

            // Do Dashboard Checks (Super Admin / Agents / Admin's can access this)....
            if($role_id <= 3)
            {
                $data = array(
                    'bodyClass'=>'dashboard-index',
                    'pageTitle'=>'Dashboard',
                );

                return view('backend.dashboard.index')->with($data);
            }
            else
            {
                return redirect('account');
            }

        }
		else
        {
            $referrer = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getPrefix();

            // Match to Referrer (If Admin, Send back to Admin Login)....
            if($referrer == '/admin' || 'admin/')
            {
                return view('backend.auth.login');
            }
            else
            {
                return view('frontend.shared.auth.login');
            }
        }
    }

    public function home()
    {
        $data = array(
            'bodyClass'=>'dashboard-index',
            'pageTitle'=>'Dashboard'
        );
        return view('backend.dashboard.index')->with($data);
    }

}
