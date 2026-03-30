@extends('backend.layouts.auth')

@section('content')

    <form method="POST" action="{{ route('password.update') }}">
        <div class="card-header" style="margin-bottom: 15px; color: #fff; font-size:21px; font-weight: 300;">{{ __('Reset Password') }}</div>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus placeholder="{{ __('E-Mail Address') }}">

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ __('Password') }}">

        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif

        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="{{ __('Confirm Password') }}">

        <input type="submit" name="submit" id="submit_btn" value="{{ __('Reset Password') }}" />


    </form>

@endsection
