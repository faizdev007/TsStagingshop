@extends('frontend.demo1.layouts.frontend')
@section('main_content')
<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                 <div class="u-mb2">
                    <h1 class="text-uppercase f-18">Login</h1>
                    <p class="f-14">Enter your login details below</p>
                </div><!-- /.u-mb2 -->
                <form method="POST" action="{{ route('login') }}" data-toggle="validator" role="form">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} u-mb1">
                        <label for="email" class="control-label">Email Address</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Your Email Address" required="required">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                        @endif
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div><!-- /form-group -->
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} u-mb1">
                        <label for="password" class="control-label form__label">Password</label>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Your Password" required>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                        @endif
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div><!-- /.form-group -->
                    <div class="row u-mt2">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <a class="t-dark" href="{{ url('password/reset') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div><!-- /.col-lg-6 -->
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <button type="submit" class="button -primary pull-right">Login</button>
                        </div><!-- /.col-lg-6 -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
