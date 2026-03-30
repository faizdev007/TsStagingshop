@extends('frontend.demo1.layouts.frontend')
@push('scripts')
<script>
function onPasswordResetSubmit(token) {
    document.getElementById("password-reset-form").submit();
}
</script>
@endpush
@section('main_content')
    @include('frontend.demo1.account.parts.banners')
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
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
                    <div class="c-bg-gray u-mt1 u-mb2">
                        <div class="text-center u-pt2">
                            <h4 class="f-24 f-two f-bold u-block u-mb1">Set Your Password</h4>
                            <form id="password-reset-form" class="u-pl2 u-pr2 u-pb2" method="POST" action="{{ route('member.password.set') }}" data-toggle="validator" role="form">
                                {{ csrf_field() }}
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} -has-label u-mb1">
                                    <input id="email" type="email" class="form-control form__input -no-border" name="email" value="{{ old('email') }}" placeholder="Your Email Address *" required="required">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} u-mb1">
                                    <input id="password" type="password" class="form-control form__input -no-border" placeholder="Password *" name="password" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <input id="password-confirm" type="password" class="form-control form__input -no-border" placeholder="Confirm Your Password *" name="password_confirmation" required>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="row u-mt2">
                                    <div class="col-xs-12 col-lg-12">
                                        <div class="text-center">
                                            <button type="submit" 
                                                class="button -primary f-bold -wider-3c u-pr4 u-pl4 text-uppercase"
                                                data-action="password_reset"
                                                data-size="invisible">Reset Password</button>
                                        </div>
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