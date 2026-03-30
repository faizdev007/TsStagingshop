<?php

namespace App\Http\Controllers\Frontend;

use App\Mail\NewMessage;
use App\Models\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MessagesController extends Controller
{
    private $is_set;

    public function __construct()
    {
        $this->is_set = settings('members_area');

        //Prevent Access if Turned Of....
        if($this->is_set == '0' || !$this->is_set)
        {
            return redirect('/')->send();
        }

        $this->middleware(function ($request, $next)
        {
            if(Auth::user())
            {
                $this->user_id = Auth::user()->id;

                return $next($request);
            }
            else
            {
                return redirect('login');
            }
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Redirect to admin if not a member
        if( Auth::user()->role_id != '4'){
            return redirect('admin');
        }

        $view = 'frontend.demo1.account.messages';

        // Mark any unread messages as read...
        $unread_messages = Message::where('to_id', Auth::user()->id)->where('message_read', 'n')->get();

        if($unread_messages->count() > 0)
        {
            foreach($unread_messages as $unread_message)
            {
                $read = Message::find($unread_message->message_id);
                $read->message_read = 'y';
                $read->message_read_date = Carbon::now();
                $read->save();
            }

        }

        $messages = Message::where('to_id', Auth::user()->id)->orWhere('from_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        $last_id = '';
        if($messages->count() > 0)
        {
            $last_ids = Message::where('to_id', Auth::user()->id)->select('from_id')->first();

            if($last_ids)
            {
                $last_id = $last_ids->from_id;
            }

        }

        $view_data = array(
            'messages'  => $messages,
            'last_id'   => $last_id
        );

        // Change View Per Template...
        if(view()->exists($view))
        {
            // Shared View in the Templates...
            return view($view,  $view_data);
        }
        else
        {
            // Load Shared View...
            return view('frontend.shared.account.messages', $view_data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate...
        $valid_data = $request->validate(
            [
                'message_content' => 'required'
            ]
        );

        // Valid...

        // Create The Message...
        $message = new Message;
        $message->from_id = Auth::user()->id;
        $message->message_content = $request->input('message_content');

        // Add To ID to message Back & Forth....
        if($request->has('to_id'))
        {
            $to_id = $request->input('to_id');

            if(!empty($to_id))
            {
                $message->to_id = $request->input('to_id');

                // Get Receiever...
                $receiever = User::find($request->input('to_id'))->first();

                SendNotificationEmail::dispatch($receiever);
            }
        }

        $message->save();

        // Confirm Message Created...
        if($message)
        {
            $request->session()->flash('message_success', 'Your message has been sent to us');

            return redirect('/account/messages');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
