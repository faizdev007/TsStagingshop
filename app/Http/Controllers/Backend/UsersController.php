<?php

namespace App\Http\Controllers\Backend;

use App\Models\Branch;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\User;
use App\Property;
use File;
use Auth;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->moduleTitle = "Users";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::user()->role_id == '2')
        {
            $users = User::orderby('id', 'DESC')
                    ->where('role_id', '!=', '1')
                    ->where('role_id', '!=', '4')
                    ->paginate(10);
        }
        else
        {
            $users = User::orderby('id', 'ASC')->where('role_id', '!=', '4')->paginate(10);
        }

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'users' =>  $users
        ];

        return view('backend.users.index')->with($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role_id == '3' )
        {
            return redirect(admin_url(''));
        }
        $data = [
          'pageTitle'=>'Create User',
          'roles'   => UserRole::all(),
          'branches' => Branch::all()
        ];

        return view('backend.users.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status'=>'required',
            'role_id'=>'required|max:10',
            'name'=>'required|max:190',
            'email'=>'required|max:190|email|unique:users',
            'password'=>'confirmed|confirmed|min:8|max:190'
        ],[
            'name.required' => 'The <span class="field-name">Full Name</pan> is required.'
        ]);


        $user = new User;
        $inputs = $request->input();
        $inputs = prepare_inputs($inputs);
        unset($inputs['password_confirmation']);

        foreach($inputs as $field => $input){
            if($field == 'password'){
                $input = bcrypt($input);
            }
            $user->{$field} = $input;
        }
        // Always Active...
        $user->status = 1;
        $user->save();

        $data = [ 'success' => 'User Created' ];

        return redirect(admin_url('users/'.$user->id.'/edit'))->with($data);

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
        if(Auth::user()->role == 'agent'){
          if($id !=  Auth::user()->id){
            return redirect('admin/users/'.Auth::user()->id.'/edit');
          }
        }

        $user = User::find($id);
        $data = [
            'pageTitle'  =>  'User',
            'user' =>  $user,
            'roles' => UserRole::all(),
            'branches' => Branch::all()
        ];

        return view('backend.users.edit')->with($data);
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
        $user = User::find($id);
        $inputs = $request->input();
        $password = $request->input('password');
        $email = $request->input('email');

        $validate = [
            'status'=>'required',
            'role_id'=>'required|max:10',
            'name'=>'required|max:190',
            'telephone'=>'max:40'
        ];


        if( $email != $user->email ){
            $validate['email'] = 'required|max:190|email|unique:users';
        }

        // Add validation to change password
        if(!empty($password)){
            $validate['password'] = 'confirmed|confirmed|min:8|max:190';
        }else{
            unset($inputs['password']);
        }

        $request->validate($validate,[
            'name.required' => 'The <span class="field-name">Full Name</span> is required.'
        ]); // Validation here...

        $inputs = prepare_inputs($inputs);
        unset($inputs['password_confirmation']);

        foreach($inputs as $field => $input){
            if($field == 'password'){
                $input = bcrypt($input);
            }
            $user->{$field} = $input;
        }

        $user->save();

        $data = [ 'success' => 'User Updated' ];

        return redirect(admin_url('users/'.$id.'/edit'))->with($data);

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

    /**
     * Show the form for photo the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function photo($id)
    {
        $user = User::find($id);

        if(empty($user)){ return redirect(admin_url('users')); }

        $data = [
            'pageTitle'  =>  $this->moduleTitle,
            'user'  =>  $user,
        ];

        return view('backend.users.photo')->with($data);
    }

    /**
     * Show the form for photo the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function photoUpload(Request $request, $id)
    {

        $photo = User::find($id);

        $request->validate([
            'file'=>'mimes:jpeg,jpg,gif,png,tif,webp|nullable|max:5999'
        ]);

        if(!empty($photo->path)){
            Storage::delete($photo->path);
        }

        if( $request->hasFile('file') ){
            $path_users = 'users';
            $path_user_photo = '/'.$id.'/photo';

            $path_image = processImage($request->file('file'),$path_users.$path_user_photo);

            $path_save_image = 'users'.$path_user_photo.'/'.$path_image;

            if(!empty($path_image)){
                $photo = User::find($id);
                $photo->path = $path_save_image;
                $photo->save();
            }
        }

    }

    /**
     * Delete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePhoto(Request $request, $id)
    {
        $photo = User::find($id);
        if(!empty($photo->path)){
            Storage::delete($photo->path);
            if(!empty($photo->path)){
                $photo->path = '';
                $photo->save();
            }
            $data = [ 'success' => 'Photo deleted!' ];
        }

        return redirect(admin_url('users/'.$id.'/photo'))->with($data);

    }

    /**
     * GET AGENT VIA AJAX.
     */
    public function get_all_agents(Request $request)
    {
        //init
        $q =(!empty($request->q)) ? $request->q : "";
        $items = [];
        $where = [];

        //query
        //$where[] = ['role', 'agent'];
        $where[] = ['status', 1];
        if( !empty($q) ){
            $where[] = ['name', 'LIKE', $q."%"];
        }

        $users = User::where($where)->where('role_id', '!=', '4')->get();
        
        //loop
        foreach( $users as $user){
            $user->name = $user->fullname;
            $items[] = $user;
        }

        // ARRAY PAGINATION
        $page = ! empty( $request->page ) ? (int) $request->page : 1;
        $total = count( $items ); //total items in array
        $limit = 10; //per page
        $totalPages = ceil( $total/ $limit ); //calculate total pages
        //$page = max($page, 1); //get 1 page when$request->page <= 0
        $page = min($page, $totalPages); //get last page when $request->page > $totalPages
        $offset = ($page - 1) * $limit;
        if( $offset < 0 ) $offset = 0;
        $itemsSliced = array_slice( $items, $offset, $limit );
        $morePages = $page + 1;
        // END OF ARRAY PAGINATION

        //JSON
        $json["q"] = $q;
        $json["pagination"] = array( "more" => $morePages );
        $json["total_count"] = count($items);
        $json["page"] = $page;
        $json["items"] = $itemsSliced;

        echo json_encode( $json );
    }


    public function delete($id)
    {
        // delete
        $user = User::find($id);
        if($user->role_id != '1'){

            $data = ['success' => 'Successfully deleted the '.$user->fullname.'!'];

            //Re-assign agent's properties to admin
            $admin_user = User::where('role_id','2')->orderby('id', 'ASC')->first();
            if(!empty($admin_user)){
                Property::where('user_id', $id)->update(['user_id' => $admin_user->id]);
            }

            $user->delete();
            return redirect(admin_url('users/'))->with($data);

        }else{
            $data = ['success' => 'Cannot delete '.$user->fullname.'!'];
            return redirect(admin_url('users/'))->with($data);
        }
    }

}
