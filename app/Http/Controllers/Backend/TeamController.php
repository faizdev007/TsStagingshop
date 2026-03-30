<?php

namespace App\Http\Controllers\Backend;

use App\Models\Team;
use App\Models\Community;
use App\Slide;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Image;

class TeamController extends Controller
{
    public function __construct()
    {
        // Minor Middleware to Check if Module is Active....
        $this->middleware(function ($request, $next)
        {
            if(settings('team_page'))
            {
                return $next($request);
            }
            else
            {
                $request->session()->flash('message_danger', 'You do not have access to this resource');
                return redirect('/admin');
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
        return view('backend.team.index', [
            'teams' => Team::orderBy('order', 'asc')->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teammember = User::with('role')->whereNotIn(
            'email',
            DB::table('team_members')
            ->whereNotNull('user_id')
            ->pluck('team_member_email')
        )->where('role_id','!=',4)->select('id','name','email','role_id')->get();

        return view('backend.team.create',compact('teammember'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'                   => 'required',
            'team_member_name'          => 'required',
            'team_member_role'          => 'required',
            'team_member_phone'         => 'required',
            'team_member_email'         => 'required',
            'team_member_description'   => 'required'
        ]);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            $baseSlug = Str::slug($request->team_member_name);
            $count = Team::where('team_member_slug', 'LIKE', "{$baseSlug}%")->count();
            
            $expertieslist = array_map('intval', $request->input('team_member_experties'));
            // Create
            $team = new Team;
            $team->user_id  = $request->input('user_id');
            $team->team_member_name  = $request->input('team_member_name');
            $team->team_member_slug = $baseSlug.($count ? '-'.$count : '');
            $team->team_member_role  = $request->input('team_member_role');
            $team->team_member_description = $request->input('team_member_description');
            $team->team_member_phone = $request->input('team_member_phone');
            $team->team_member_email = $request->input('team_member_email');
            $team->team_member_languages = $request->input('team_member_languages');
            $team->team_member_experience = $request->input('team_member_experience');
            $team->team_member_broker_licence = $request->input('team_member_broker_licence');
            $team->team_member_experties = $expertieslist;
            $team->save();

            // Redirect
            $request->session()->flash('message_success', 'Successfully created team member!');
            return redirect('admin/team/'.$team->team_member_id.'/edit');
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
    public function edit(Request $request, $id)
    {
        $team = Team::find($id);
        $teammember = User::with('role')->whereNotIn(
            'email',
            DB::table('team_members')
            ->where('user_id', '!=', $team->user_id)
            ->pluck('team_member_email')
        )->where('role_id','!=',4)->select('id','name','email','role_id')->get();

        $communities = Community::whereIn('id',$team->team_member_experties)->get()->pluck('name','id');

        return view('backend.team.edit', [
            'pageTitle'     => 'Edit Team Member',
            'team'          => $team,
            'tab'           => $request->input('tab'),
            'teammember'    => $teammember,
            'communities'   => $communities
        ]);
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
        $validator = Validator::make($request->all(), [
            'team_member_name'          => 'required',
            //'team_member_role'          => 'required',
            'team_member_phone'          => 'required',
            'team_member_description'   => 'required'
        ]);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            $team_member = Team::find($id);
            $setting = $team_member->team_member_setting ?? [];
            $setting['total_deals_visibility'] = $request->input('team_member_total_deals_visibility', 'no');
            $team_member->user_id  = $request->input('user_id');
            $team_member->team_member_name  = $request->input('team_member_name');
            $team_member->team_member_role  = $request->input('team_member_role');
            $team_member->team_member_description = $request->input('team_member_description');
            $team_member->team_member_meta_description = makeMetaDescription($request->input('team_member_description'));
            $team_member->team_member_phone = $request->input('team_member_phone');
            $team_member->team_member_email = $request->input('team_member_email');
            $team_member->team_member_languages = $request->input('team_member_languages');
            $team_member->team_member_experience = $request->input('team_member_experience');
            $team_member->team_member_broker_licence = $request->input('team_member_broker_licence');
            $team_member->team_member_experties = $request->input('team_member_experties');
            $team_member->team_member_setting = $setting;
            $team_member->location = $request->input('location');
            $team_member->save();
            // if($request->file('location_photo')){
            //        $path = $request->file('location_photo')->store('team');
            //         if($path)
            //                 {
            //                     // remove existing photo
            //                     if(!empty($team_member->location_photo))
            //                     {
            //                         Storage::delete($team_member->location_photo);
            //                     }

            //                     // update photo
            //                     $team_member->location_photo = $path;
            //                     $team_member->save();

            //                     $img = Image::make(storage_path('app/public/team/'.basename($path)));
            //                     $img->save(storage_path('app/public/team/'.basename($path)));

            //                 }
            // }


            // Redirect
            $request->session()->flash('message_success', 'Successfully updated team member!');
            return redirect('admin/team/'.$team_member->team_member_id.'/edit');
        }
    }

    public function upload(Request $request, $id)
    {
        // Validate
        $rules = array(
            'file' => 'required|max:10240|mimes:jpeg,png',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $errors = $validator->errors();
            $error_txt = false;

            foreach ($errors->all() as $message)
            {
                $error_txt .= "$message ";
            }

            $request->session()->flash('message_danger', $error_txt);
        }
        else
        {
            $path_image = processImage($request->file('file'),'/team',800,800);
            // $path = $request->file('file')->store('team');

            if($path_image)
            {
                // store file to db
                $team = Team::find($id);

                // remove existing photo
                if(!empty($team->team_member_photo))
                {
                    Storage::delete($team->team_member_photo);
                }

                // update photo
                $team->team_member_photo = 'team/'.$path_image;
                $team->save();

                // $img = Image::make(storage_path('app/public/team/'.basename($path)));
                // $img->save(storage_path('app/public/team/'.basename($path)));

                $request->session()->flash('message_success', 'Successfully uploaded image!');
            }
        }

        return 'completed';
    }

    public function sort(Request $request)
    {
        $items =  $request->input("item");

        foreach($items as $order => $id)
        {
            $team = Team::find($id);
            $team->order = $order;
            $team->save();
        }

        echo 'completed!';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
        $team = Team::find($id);
        $team->delete();

        // Send Confirmed Response...
        return response()->json([
            'error' => 'false',
            'message' => 'Team Member Deleted!'
        ]);
    }

    public function delete_photo(Request $request, $id)
    {
        $team = Team::find($id);

        if(!empty($team))
        {
            $thumb_path = 'team/'.basename($team->team_member_photo);
            Storage::delete($team->team_member_photo);
            $team->team_member_photo = '';
            $team->save();
            $request->session()->flash('message_success', 'Successfully deleted photo!');
        }

        return redirect('admin/team/'.$team->team_member_id.'/edit');
    }
}
