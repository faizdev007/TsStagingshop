@extends('backend.layouts.master')

@section('admin-content')

<div class="row current_url" data-url="{{admin_url('users')}}">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2>{{$user->id}}: {{$user->fullname}}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="pw-tabs" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{ active_class('edit',4) }}">
                            <a href="{{admin_url('users/'.$user->id.'/edit')}}" role="tab" aria-expanded="true">Details</a>
                        </li>
                        <li role="presentation" class="{{ active_class('photo',4) }}">
                            <a href="{{admin_url('users/'.$user->id.'/photo')}}" role="tab" aria-expanded="true">Photo</a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in"  aria-labelledby="user-tab">
                            @yield('user-content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
