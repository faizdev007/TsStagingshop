<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subscriber;

class SubscribersController extends Controller
{
    public function __construct()
    {
        // Minor Middleware to Check if Module is Active....
        $this->middleware(function ($request, $next)
        {
            if(settings('show_subscribers'))
            {
                return $next($request);
            }
            else
            {
                $request->session()->flash('message_danger', 'You do not have access to this resource');
                return redirect('/admin');
            }

        });

        $this->moduleTitle = "Subscribers";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $criteria = '';

        if(!empty($request))
        $criteria = $request->input();

        $subscriber = new Subscriber();
        $subscribers = $subscriber->searchWhere($criteria);
        
        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'subscribers' =>  $subscribers,
            'request' => $request
        ];

        return view('backend.subscribers.index')->with($data);
     }

     public function destroy(Request $request, $id)
     {
         $subscriber = Subscriber::find($id);

         if($subscriber)
         {
             $subscriber->delete();

             // Send Confirmed Response...
             return response()->json([
                 'error' => 'false',
                 'message' => 'Subscriber Deleted!'
             ]);
         }
     }


    /**
     * This will delete the entry
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */

    public function delete($id)
    {
        $subscriber = Subscriber::find($id);
        if(empty($subscriber)){ return redirect(admin_url('subscribers')); }
        $data = ['success' => 'Subscriber '.$subscriber->email.' has been permanently deleted.'];
        $subscriber->delete();
        return redirect(admin_url('subscribers/'))->with($data);
    }

    /**
     * This will export subscribers in CSV.
     *
    */

    public function download()
    {
        $subscribers = Subscriber::select('email','fullname','telephone','created_at')->distinct()->get();
        $filename = 'download_subscribers_'.date ('YmdHis');
        header ("Content-type: text/csv");
        header ("Content-Disposition: attachment; filename={$filename}.csv");
        header ("Pragma: no-cache");
        header ("Expires: 0");
        echo '"Full Name","Email","Phone","Date Subscribed"' . PHP_EOL;
        foreach ($subscribers as $subscriber) {
            echo '"'.$subscriber->fullname.'",';
            echo '"'.$subscriber->email.'",';
            echo '"'.$subscriber->telephone.'",';
            echo '"'.$subscriber->created_at.'",';
            echo PHP_EOL;
        }
    }

}
