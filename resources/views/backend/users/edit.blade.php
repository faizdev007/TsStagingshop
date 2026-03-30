@extends('backend.users.template')

@section('user-content')

<form action="{{ url('admin/users', $user->id) }}" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <!-- User Settings -->
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>User Settings</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content pw-open">
            <div class="xpw-fields">
                <div class="row">

                    @if(Auth::user()->role_id <= '2')

                        <!-- Status -->
                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Status: {!! required_label() !!}</label>
                                <select name="status" class="form-control select-pw">
                                    @foreach(u_states() as $key => $value)
                                        <option value="{{ $key }}" @if($user->status == $key) selected @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Role: {!! required_label() !!}</label>
                                <select
                                    id="role_id"
                                    class="form-control select-pw"
                                    name="role_id"
                                    data-placeholder="Please choose....">
                                    <option></option>
                                    @foreach($roles as $role)
                                        @if(Auth::user()->role_id == '2' && $role->user_role_id == '1')
                                            @continue
                                        @endif
                                        <option
                                            value="{{ $role->user_role_id }}"
                                            @if($role->user_role_id == $user->role->user_role_id) selected @endif>
                                            {{ $role->role_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Email Address: {!! required_label() !!}</label>
                                <input
                                    type="text"
                                    name="email"
                                    id="id-email"
                                    class="form-control"
                                    value="{{ $user->email }}"
                                    placeholder="Please enter..." />
                            </div>
                        </div>

                        <!-- Branch -->
                        @if(settings('branches_option') == 1 && Auth::user()->role_id <= '2')
                            <div class="col-md-3">
                                <div class="control-form">
                                    <label>Branch</label>
                                    <select
                                        id="branch_id"
                                        class="form-control select-pw"
                                        name="branch_id"
                                        data-placeholder="Optional....">
                                        <option></option>
                                        @foreach($branches as $branch)
                                            <option
                                                value="{{ $branch->branch_id }}"
                                                @if($user->branch && $branch->branch_id == $user->branch->branch_id) selected @endif>
                                                {{ $branch->branch_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                    @else
                        <!-- Hidden fields for non-admin -->
                        <input type="hidden" name="status" value="{{ $user->status }}">
                        <input type="hidden" name="role_id" value="{{ $user->role->user_role_id }}">

                        <!-- Readonly Email -->
                        <div class="col-md-3">
                            <div class="control-form">
                                <label>Email Address: {!! required_label() !!}</label>
                                <input
                                    type="text"
                                    name="email"
                                    id="id-email"
                                    class="form-control"
                                    value="{{ $user->email }}"
                                    placeholder="Please enter..."
                                    readonly />
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>Details</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="xpw-fields">
                <div class="row">

                    <!-- Full Name -->
                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Full Name: {!! required_label() !!}</label>
                            <input
                                type="text"
                                name="name"
                                id="id-first-name"
                                class="form-control"
                                value="{{ $user->name }}"
                                placeholder="Please enter..." />
                        </div>
                    </div>

                    <!-- Telephone -->
                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Telephone:</label>
                            <input
                                type="text"
                                name="telephone"
                                id="id-telephone"
                                class="form-control"
                                value="{{ $user->telephone }}"
                                placeholder="Please enter..." />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="x_panel pw-inner-tabs">
        <div class="x_title">
            <h2>Change Password</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="xpw-fields">
                <div class="row">

                    <!-- Password -->
                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Password:</label>
                            <input
                                type="password"
                                name="password"
                                id="id-password"
                                class="form-control"
                                placeholder="Please enter..." />
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-3">
                        <div class="control-form">
                            <label>Confirm Password:</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="id-password_confirmation"
                                class="form-control"
                                placeholder="Please enter..." />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary" name="action">
            Save
        </button>

        @if(Auth::user()->role_id == '1')
            <a href="{{ admin_url('users') }}" class="btn btn-default btn-spacing">
                Cancel <span>and Return</span>
            </a>

            <a
                href="{{ admin_url('users/'.$user->id.'/delete') }}"
                class="confirm-action btn btn-danger btn-spacing"
                title="permanently delete this user">
                <i class="fas fa-trash"></i> Delete
            </a>
        @endif
    </div>
</form>

@endsection
