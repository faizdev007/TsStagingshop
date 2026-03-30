@extends('backend.layouts.master')

@section('admin-content')

<div class="row current_url" data-url="{{admin_url('property-alerts')}}">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <h2></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @yield('alerts-content')
            </div>
        </div>
    </div>
</div>

@endsection
