@extends('backend.layouts.auth')

@section('content')

    <form method="POST" action="{{ route('password.email') }}">

        <div class="card-header" style="margin-bottom: 15px; color: #fff; font-size:21px; font-weight: 300;">{{ __('Reset Password') }}</div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required utofocus  autocomplete="off" placeholder="Email address">

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <input type="submit" name="submit" id="submit_btn" value="{{ __('Send Password Reset Link') }}" />

        <p class="text-center">
            Remembered? <a class="btn btn-link" href="{{ url('admin') }}">Log in to your account </a>
        </p>

        @csrf

    </form>

@endsection
