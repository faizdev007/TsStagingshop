@extends('backend.layouts.master')

@section('admin-content')

<form action="{{ url('admin/store') }}" method="POST">
    @csrf
    <input type="hidden" name="status" value="0">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel pw">
                <div class="x_title">
                    <h2><br /></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <p class="text-muted font-13 m-b-30"><br /></p>

                    <!-- User Settings -->
                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>User Settings</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content pw-open">
                            <div class="xpw-fields">
                                <div class="row">

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
                                                    <option value="{{ $role->user_role_id }}">
                                                        {{ $role->role_title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Full Name -->
                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label>Full Name: {!! required_label() !!}</label>
                                            <input
                                                type="text"
                                                name="name"
                                                id="id-first-name"
                                                class="form-control"
                                                placeholder="Please enter..." />
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
                                                        <option value="{{ $branch->branch_id }}">
                                                            {{ $branch->branch_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="x_panel pw-inner-tabs">
                        <div class="x_title">
                            <h2>Password</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content pw-open">
                            <div class="xpw-fields">
                                <div class="row">

                                    <!-- Password -->
                                    <div class="col-md-3">
                                        <div class="control-form">
                                            <label>Password: {!! required_label() !!}</label>
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
                                            <label>Confirm Password: {!! required_label() !!}</label>
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

                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="form-group sticky-buttons">
        <button type="submit" class="btn btn-large btn-primary" name="action">
            Save
        </button>
        <a href="{{ admin_url('users') }}" class="btn btn-default btn-spacing">
            Cancel <span>and Return</span>
        </a>
    </div>

</form>

@endsection
