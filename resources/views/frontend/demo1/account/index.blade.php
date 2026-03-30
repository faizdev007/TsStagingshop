@extends('frontend.demo1.layouts.frontend')
@section('main_content')
    @include('frontend.demo1.account.parts.banners')
    <section class="u-pt0 u-pb2">
        <div class="container">
            @if (session('message_success'))
                <div class="row u-mt2">
                    <div class="col">
                        <div class="alert alert-success">
                            {{ session('message_success') }}
                        </div>
                    </div>
                </div>
            @endif
            @include('frontend.demo1.account.nav.nav')
            <div class="row u-mt1">
                <div class="col">
                    <div class="info-box c-bg-gray">
                        <form method="post" action="{{ url('user/'.Auth::user()->id.'/update') }}" data-toggle="validator">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <h4 class="f-28 f-bold u-block u-mb1">Personal Info</h4>
                                    <div class="form-group row u-mb1">
                                        <label class="col-sm-12 col-lg-3 form__label col-form-label d-flex align-items-center">Your First Name</label>
                                        <div class="col-sm-12 col-lg-9">
                                            <input name="first_name" type="text" class="form-control form__input -no-border" placeholder="Your First Name" value="{{ $user->firstname }}" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            @if ($errors->has('first_name'))
                                                <span class="help-block">
                                                    <strong>{!! $errors->first('first_name') !!}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row u-mb1">
                                        <label class="col-sm-12 col-lg-3 form__label col-form-label d-flex align-items-center">Your Last Name</label>
                                        <div class="col-sm-12 col-lg-9">
                                            <input name="last_name" type="text" class="form-control form__input -no-border" placeholder="Your Last Name" value="{{ $user->lastname }}" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            @if ($errors->has('last_name'))
                                                <span class="help-block">
                                                    <strong>{!! $errors->first('last_name') !!}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row u-mb1">
                                        <label class="col-sm-12 col-lg-3 form__label col-form-label d-flex align-items-center">Your Email</label>
                                        <div class="col-sm-12 col-lg-9">
                                            <input name="email" type="email" class="form-control form__input -no-border" placeholder="Your Email Address" value="{{ Auth::user()->email ?? "" }}" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                <strong>{!! $errors->first('email') !!}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row u-mb1">
                                        <label class="col-sm-12 col-lg-3 form__label col-form-label d-flex align-items-center">Phone</label>
                                        <div class="col-sm-12 col-lg-9">
                                            <input name="telephone" type="tel" class="form-control form__input -no-border" placeholder="Your Phone Number" value="{{ Auth::user()->telephone ?? ""}}">
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            @if ($errors->has('telephone'))
                                                <span class="help-block">
                                                <strong>{!! $errors->first('telephone') !!}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <h4 class="f-28 f-bold u-block u-mb1">Change Password</h4>
                                    <div class="form-group row u-mb1">
                                        <label class="col-sm-12 col-lg-4 form__label col-form-label d-flex align-items-center">New Password</label>
                                        <div class="col-sm-12 col-lg-8">
                                            <input id="password" type="password" class="form-control form__input -no-border" name="password" placeholder="Your Password">
                                            @if ($errors->has('password'))
                                                <span class="help-block"><strong>{!! $errors->first('password') !!}</strong></span>
                                            @endif
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row u-mb1">
                                        <label class="col-sm-12 col-lg-4 form__label col-form-label d-flex align-items-center">Confirm Password</label>
                                        <div class="col-sm-12 col-lg-8">
                                            <input id="password-confirm" type="password" class="form-control form__input -no-border" name="password_confirmation" placeholder="Confirm your password">
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="u-pull-right">
                                        <button type="submit" class="button -primary f-bold -wider-3c u-pr4 u-pl4 u-block-mobile text-uppercase">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>

@endsection
