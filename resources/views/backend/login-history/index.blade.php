@extends('backend.layouts.master')



@section('admin-content')

<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="x_panel pw">

            <div class="x_title">

                <h2>Login History</h2>

                <ul class="nav navbar-right panel_toolbox">

                </ul>

                <div class="clearfix"></div>

            </div>

            <div class="x_content">

                <form method="GET" action="{{ url('admin/login-history') }}">

                    <div class="row">

                        <div class="col-md-4 col-sm-6">

                            <div class="form-group">

                                <label>Filter by Role</label>

                                <select name="role_id" class="form-control">

                                    <option value="">All Roles</option>

                                    @foreach($roles as $role)

                                        <option value="{{ $role->user_role_id }}" {{ request('role_id') == $role->user_role_id ? 'selected' : '' }}>

                                            {{ $role->role_title == 'User / Member' ? 'Member' : ucwords($role->role_title) }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                        <div class="col-md-4 col-sm-6">

                            <div class="form-group">

                                <label>Search</label>

                                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">

                            </div>

                        </div>

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group" style="margin-top: 22px;">

                                <button type="submit" class="btn btn-primary">Filter</button>

                                @if(request('search') || request('role_id'))

                                    <a href="{{ url('admin/login-history') }}" class="btn btn-default">Reset</a>

                                @endif

                            </div>

                        </div>

                    </div>

                </form>



                <div class="table-responsive pw-table">

                    @if(count($loginHistory))

                    {{ $loginHistory->links('pagination::bootstrap-4') }}

                    <table class="table table-striped jambo_table bulk_action table-bordered-">

                        <thead>

                            <tr>

                                <th>S.No</th>

                                <th>Name</th>

                                <th>Email</th>

                                <th>Role</th>

                                <th>Last Login</th>

                                <th>IP Address</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($loginHistory as $key => $history)

                            <tr>

                                <td class="ref-link">{{ ($loginHistory->currentpage()-1) * $loginHistory->perpage() + $key + 1 }}</td>

                                <td>{{ $history->name }}</td>

                                <td>{{ $history->email }}</td>

                                <td>{{ $history->role ? (strtolower($history->role->role_title) == 'user / member' ? 'Member' : ucwords($history->role->role_title)) : 'N/A' }}</td>

                                <td>{{ $history->last_login_at ? \Carbon\Carbon::parse($history->last_login_at)->timezone('Asia/Dubai')->format('d-M-Y g:i:s A') : 'Never' }}</td>

                                <td>{{ $history->last_login_ip }}</td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                    {{ $loginHistory->links('pagination::bootstrap-4') }}

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

