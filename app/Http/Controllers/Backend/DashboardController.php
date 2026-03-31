<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Property;
use App\User;
use Illuminate\Support\Facades\Auth;

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
            $login_history = User::whereNot('role_id',4)->select('id', 'role_id', 'name', 'email', 'last_login_at','last_login_ip')->orderBy('last_login_at', 'DESC')->take(5)->get();
            
            // Do Dashboard Checks (Super Admin / Agents / Admin's can access this)....
            if($role_id <= 3)
            {
                $data = array(
                    'bodyClass'=>'dashboard-index',
                    'pageTitle'=>'Dashboard',
                    'login_history'=>$login_history
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
