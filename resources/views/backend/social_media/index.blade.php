@extends('backend.layouts.master')

@section('admin-content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="x_panel tile">
                            <div class="x_title">
                                <h4>Facebook</h4>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div>
                                    @if($login_required == false)
                                        <a class="btn btn-primary" href="#"><i class="fa fa-check-square-o"></i> Logged in to Facebook</a>
                                        <p>Token expires : <strong>{{ $expires_at }}</strong></p>
                                    @else
                                        <a class="btn btn-primary" href="{{ $facebook_login_url }}"><i class="fa fa-facebook-square"></i> Login to Facebook</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection