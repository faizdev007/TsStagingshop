@extends('frontend.demo1.layouts.frontend')
@section('main_content')
    <section @if(themeOptions() == 'demo1') style="margin-top: 100px;" @endif>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="u-mb2">
                        <h1 class="text-uppercase f-18">Reset Your Password Shared</h1>
                        <p class="f-14">Reset your password below</p>
                    </div><!-- /.u-mb2 -->
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('message_warning'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('message_warning') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('member.password.set') }}" data-toggle="validator" role="form">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} -has-label u-mb1">
                            <label for="email" class="control-label">Email Address<strong class="required" title="This field is mandatory">*</strong></label>
                            <input id="email" type="email" class="form-control form__input" name="email" value="{{ old('email') }}" placeholder="Your Email Address" required="required">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div><!-- /form-group -->
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label form__label">Password<strong class="required" title="This field is mandatory">*</strong></label>
                            <input id="password" type="password" class="form-control form__input" placeholder="Password" name="password" required>
                        </div><!-- /.form-group -->
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="control-label form__label">Confirm Password<strong class="required" title="This field is mandatory">*</strong></label>
                            <input id="password-confirm" type="password" class="form-control form__input" placeholder="Confirm Your Password" name="password_confirmation" required>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                        </div><!-- /.from-group -->
                        <div class="row u-mt2">
                            <div class="col-xs-12 col-lg-12">
                                <div class="text-center">
                                    <button type="submit" class="button -primary pull-right">Reset Password</button>
                                </div>
                            </div><!-- /.col-lg-6 -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
