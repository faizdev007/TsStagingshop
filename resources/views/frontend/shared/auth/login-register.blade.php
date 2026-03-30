@extends('frontend.demo1.layouts.frontend')
@section('main_content')
<section @if(themeOptions() == 'demo1') style="margin-top: 100px;" @endif>
    <div class="container">
        <div class="row u-mb2">
            <div class="col-12">
                <div class="text-center"><h1 class="f-18">Login / Register</h1></div>
            </div><!-- /.col-12 -->
        </div><!-- /.row -->
        @if (session('message_success'))
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message_success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (session('message_warning'))
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ session('message_warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-md-6 order-sm-last">
                <div class="card u-mb1">
                    <div class="card-header">
                        <h2 class="text-uppercase f-18">Register</h2>
                    </div>
                    <div class="card-body">
                        <div class="u-mb2">
                            <p class="f-14">Join {{ settings('site_name') }} today. Joining benefits include : </p>
                            <ul>
                                <li>Save Property Alerts</li>
                                <li>Save Notes</li>
                                <li>Create Shortlists</li>
                                <li>Save your searches</li>
                            </ul>
                        </div>
                            <form method="POST" action="{{ url('register') }}" data-toggle="validator">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} -has-label u-mb1">
                                    <label for="name" class="control-label">Name<strong class="required" title="This field is mandatory">*</strong></label>
                                    <input id="name" type="text" class="form-control form__input" name="name" value="{{ old('name') }}" placeholder="Your Name" required>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{!! $errors->first('name') !!}</strong>
                                    </span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} -has-label u-mb1">
                                    <label for="email" class="control-label">E-Mail Address<strong class="required" title="This field is mandatory">*</strong></label>
                                    <input id="email" type="email" class="form-control form__input" name="email" value="{{ old('email') }}" placeholder="Your Email Address" required>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{!! $errors->first('email') !!}</strong>
                                        </span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>

                                <div class="form-group{{ $errors->has('telephone') ? 'has-error' : '' }} -has-label u-mb1">
                                    <label for="telephone" class="control-label">Phone Number</label>
                                    <input type="tel" id="telephone" name="telephone" class="form-control form__input" value="{{ old('telephone') }}" placeholder="Your Telephone Number">
                                    @if ($errors->has('telephone'))
                                        <span class="help-block">
                                    <strong>{!! $errors->first('telephone') !!}</strong>
                                </span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} -has-label  u-mb1">
                                    <label for="password" class="control-label">Password<strong class="required" title="This field is mandatory">*</strong></label>
                                    <input id="password" type="password" class="form-control form__input" name="password" placeholder="Your Password" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block"><strong>{!! $errors->first('password') !!}</strong></span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>

                                <div class="form-group -has-label u-mb1">
                                    <label for="password-confirm" class="control-label">Confirm Password<strong class="required" title="This field is mandatory">*</strong></label>
                                    <input id="password-confirm" type="password" class="form-control form__input" name="password_confirmation" placeholder="Confirm your password" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>

                                <p class="consent-txt">@include('frontend.demo1.parts/gdpr')</p>

                                <button type="submit" class="button -primary">Register</button>
                            </form>
                        </div>
                    </div>
            </div>
            <div class="col-12 col-md-6 order-sm-last order-first">
                <div class="card u-mb1">
                    <div class="card-header">
                        <h2 class="text-uppercase f-18">Login</h2>
                    </div>
                    <div class="card-body">
                        <p class="f-14">Enter your login details below</p>
                        <form method="POST" action="{{ route('login') }}" data-toggle="validator" role="form">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} -has-label u-mb1">
                                <label for="email" class="control-label">Email Address<strong class="required" title="This field is mandatory">*</strong></label>
                                <input id="email" type="email" class="form-control form__input" name="email" value="{{ old('email') }}" placeholder="Your Email Address" required="required">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                            <strong>{!! $errors->first('email') !!}</strong>
                                        </span>
                                @endif
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div><!-- /form-group -->
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} -has-label u-mb1">
                                <label for="password" class="control-label form__label">Password<strong class="required" title="This field is mandatory">*</strong></label>
                                <input id="password" type="password" class="form-control form__input" name="password" placeholder="Your Password" required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div><!-- /.form-group -->
                            <div class="form-group">
                                <button type="submit" class="button -primary">&nbsp; Login &nbsp;</button>
                                <div class="u-block u-mt1">
                                    <a class="c-dark" href="{{ url('password/reset') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
