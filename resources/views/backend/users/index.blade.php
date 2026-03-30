@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li class="top-button"><a href="{{admin_url('users/create')}}" class="btn btn-small btn-primary">Create User</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p class="text-muted font-13 m-b-30"></p>
                <div class="table-responsive pw-table">
                    @if(count($users))
                    {{ $users->links('pagination::bootstrap-4') }}
                    <table class="table table-striped jambo_table bulk_action table-bordered-">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Telephone</th>
                                @if(settings('branches_option') == 1)
                                    <th>Branch</th>
                                @endif
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td width="100" style="background:#e4e4e4"><img src="{{ $user->main_photo }}" alt="{{ $user->full_name }}" class="pw-thumbnail-75"></td>
                                <td class="ref-link"><a href="{{admin_url('users/'.$user->id.'/edit')}}">{{ $user->id }}</a></td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->telephone }}</td>
                                @if(settings('branches_option') == 1 && Auth::user()->role_id <= '2')
                                    <td>
                                        @if($user->branch)
                                            {{ $user->branch->branch_name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                @endif
                                <td>{{ ucwords($user->role->role_title) }}</td>
                                <td>{{ $user->status_label }}</td>
                                <td class="text-center table-active-btn">
                                    <a href="{{admin_url('users/'.$user->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="margin-top: 15px;">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                    @else
                        <div class="no-data">
                            No data found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
