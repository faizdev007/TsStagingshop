<?php

namespace App\Http\Controllers\Backend;

use App\Mail\NewMessage;
use App\Models\LeadAutomation;
use App\Models\Message;
use App\Models\Note;
use App\Models\SaveSearch;
use App\PropertyAlert;
use App\Shortlist;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MembersController extends Controller
{

    private $is_set;

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);

        $this->is_set = settings('members_area');

        //Prevent Access if Turned Of....
        if($this->is_set == '0' || !$this->is_set)
        {
            return redirect('/admin')->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::getWhereRole(4)
            ->when($request->input('q'), function($query) use ($request)
                {
                      return $query->where(function ($query) use ($request)
                      {
                        $query->where('name', 'like', '%'.$request->input('q').'%');
                      });
                })
                ->paginate(20);

        return view('backend.members.index',
            [
                'pageTitle' => 'Members',
                'users'     =>  $users
            ]
        );
    }

    public function messages()
    {
        $members = User::where('role_id', '1')->pluck('id')->toArray();

        return view('backend.members.messages',
            [
                'pageTitle' => 'Messages',
                'messages'  => Message::whereNotIn('from_id', $members)->orderBy('created_at', 'desc')->get()
            ]);
    }

    public function show_message($id)
    {
        $message = Message::find($id);

        if($message->message_read == 'n')
        {
            // Mark as Read...
            $read_message = Message::find($id);
            $read_message->message_read = 'y';
            $read_message->message_read_date = Carbon::now();
            $read_message->save();
        }

        $others = Message::where('from_id', $message->from_id)
                          ->orWhere('to_id', $message->from_id)
                          ->where('message_id', '!=', $message->message_id)
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('backend.members.message-show', [
            'message'       => $message,
            'other_messages'=> $others,
            'pageTitle'     => 'Viewing Message'
        ]);
    }

    public function send_new_message($id = false)
    {
        if($id)
        {
            $member = User::find($id);
            $members = null;
        }
        else
        {
            $member = null;
            $members = User::where('role_id', '4')->get();
        }

        return view('backend.members.message-send',
            [
                'id'        => $id,
                'member'    => $member,
                'members'   => $members
            ]);
    }

    public function send_reply(Request $request)
    {
        // Create The Message....
        $message = new Message;
        $message->from_id = Auth::user()->id;
        $message->to_id = $request->input('to_id');
        $message->message_content = $request->input('message_content');
        $message->save();

        if($request->has('message_id'))
        {
            $update_message = Message::find($request->input('message_id'));
            $update_message->reply_sent = 'y';
            $update_message->save();
        }

        // Send Message to User To Notify Them of a New Message....
        SendNotificationEmail::dispatch($message);
        
        // redirect
        $request->session()->flash('message_success', 'Successfully sent your message!');

        if($request->has('message_type'))
        {
            return redirect('admin/members/messages');
        }
        else
        {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, Request $request)
    {
        $automations = LeadAutomation::where('user_id', $user_id)->with('messages')->get();
        $automations->groupBy('lead_type');
        return view('backend.members.show',
            [
                'pageTitle'     => 'Viewing Member',
                'user'          => User::find($user_id),
                'shortlist'     => Shortlist::where('user_id', $user_id)->with('property')->orderBy('created_at', 'desc')->get(),
                'alerts'        => PropertyAlert::where('user_id', $user_id)->orderBy('created_at', 'desc')->get(),
                'searches'      => SaveSearch::where('user_id', $user_id)->orderBy('created_at', 'desc')->get(),
                'notes'         => Note::where('user_id', $user_id)->whereHas('property')->orderBy('created_at', 'desc')->get(),
                'messages'      => Message::where('from_id', $user_id)->orWhere('to_id', $user_id)->orderBy('created_at', 'desc')->get(),
                'automations'   => $automations,
                'tab'           => $request->input('tab')
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if($user)
        {
            // Delete User...
            $user->delete();

            // Redirect to account with message - Deleted...
            \session()->flash('message_success', 'Account Deleted!');

            return response()->json(
                [
                    'message' => 'Account Deleted!'
                ]
            );
        }
        else
        {
            return false;
        }
    }
}
