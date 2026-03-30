<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;

class BranchesController extends Controller
{
    private $is_set;

    public function __construct()
    {
        $this->is_set = settings('branches_option');

        // Prevent Access if Turned Of....
        if($this->is_set == '0')
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
        $branches = Branch::paginate(20)
            ->when($request->input('q'), function($query) use ($request)
            {
                return $query->where(function ($query) use ($request)
                {
                    $query->where('branch_name', 'like', '%'.$request->input('q').'%');
                });
            });

        return view('backend.branches.index', [
            'pageTitle' => 'Branches',
            'branches' => $branches
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.branches.create', ['pageTitle' => 'Create New Branch']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save The Branch...
        $branch = new Branch;
        $branch->fill($request->all());
        $branch->save();

        return redirect(admin_url('branches'))->with(
            [
                'success' => 'Branch Created'
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('backend.branches.edit',
            [
                'pageTitle' => 'Edit Branch Details',
                'branch' => Branch::find($id)
            ]
        );
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
        $branch = Branch::find($id);
        $branch->branch_name = $request->input('branch_name');
        $branch->branch_address1 = $request->input('branch_address1');
        $branch->branch_address2 = $request->input('branch_address2');
        $branch->branch_town = $request->input('branch_town');
        $branch->branch_postcode = $request->input('branch_postcode');
        $branch->branch_phone = $request->input('branch_phone');
        $branch->branch_email = $request->input('branch_email');
        $branch->save();

        // redirect
        $request->session()->flash('message_success', 'Successfully updated branch!');
        return redirect('admin/branches/'.$id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);
        $branch->delete();

        // Send Confirmed Response...
        return response()->json(
            [
                'error' => 'false',
                'message' => 'Branch Deleted!'
            ]
        );
    }
}
