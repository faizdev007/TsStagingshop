<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
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

        $view = 'frontend.demo1.account.notes';

        $notes = Note::where('user_id', Auth::user()->id)
                ->whereHas('property')
                ->orderBy('created_at', 'desc')
                ->paginate(12);

        // Change View Per Template...
        if(view()->exists($view))
        {
            // Shared View in the Templates...
            return view($view,  ['notes' => $notes]);
        }
        else
        {
            // Load Shared View...
            return view('frontend.shared.account.notes', ['notes' => $notes]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Create Note...
        $note = new Note;
        $note->note_content = $request->input('note_content');
        $note->user_id = Auth::user()->id;
        $note->property_id = $request->input('property_id');
        $note->save();

        return response()->json(
            [
                'message' => 'Note created!'
            ]
        );
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

    public function get_user_property_notes($id, Request $request)
    {
        if(Auth::user())
        {
            // Get Notes for User against Property...
            $existing_notes = Note::where('property_id', $id)->where('user_id', Auth::user()->id)->get();

            $existing = false;

            if($existing_notes->count() > 0)
            {
                $existing = true;
            }

            return response()->json(
                [
                    'notes' => $existing
                ]
            );
        }
        else
        {
            // Prompt to login first...
            $request->session()->flash('message_warning', 'Please login or Register first!');

            return response()
                ->json(
                    [
                        'url' => url('register')
                    ]
                );
        }


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
        $note = Note::find($id);

        if($note)
        {
            $destroy = Note::destroy($id);

            // Send Confirmed Response...
            return response()->json(
                [
                    'id'   => $id,
                    'error' => 'false',
                    'remaining' => Note::where('user_id', Auth::user()->id)->count(),
                    'message' => 'Note Deleted!'
                ]
            );
        }
    }
}
