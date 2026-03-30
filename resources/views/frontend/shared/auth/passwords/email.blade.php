@extends('frontend.demo1.layouts.frontend')
@section('main_content')
    <section @if(themeOptions() == 'demo1') style="margin-top: 100px;" @endif>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="u-mb2">
                        <h1 class="text-uppercase f-18">Reset Your Password</h1>
                        <p class="f-14">Enter your email address below</p>
                    </div><!-- /.u-mb2 -->
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('member.password.email') }}" data-toggle="validator" role="form">
                        {{ csrf_field() }}
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
