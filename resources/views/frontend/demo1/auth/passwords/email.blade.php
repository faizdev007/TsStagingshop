@extends('frontend.demo1.layouts.frontend')

@section('main_content')
    @include('frontend.demo1.account.parts.banners')
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 h-100">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="c-bg-gray h-100 u-mt1 u-mb2">
                        <div class="text-center u-pt2">
                            <h4 class="f-24 f-two f-bold u-block u-mb1">Reset Password</h4>
                        </div>
                        <form id="reset-request-form" class="u-pl2 u-pr2 u-pb2" method="POST" action="{{ route('member.password.email') }}" data-toggle="validator" role="form">
                            @csrf
                            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} u-mb1">
                                <input 
                                    id="reset-email" 
                                    type="email" 
                                    class="form-control form__input -no-border" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    placeholder="Your Email Address *" 
                                    required="required"
                                    autocomplete="email"
                                >
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <a class="c-primary" href="{{ url('login') }}">I've remembered it</a>
                                </div>
                            </div>
                            <div class="row u-mt2">
                                <div class="col-xs-12 col-lg-12">
                                    <div class="text-center">
                                        <button 
                                            id="submit-button"
                                            type="submit" 
                                            class="button -primary f-bold -wider-3c u-pr4 u-pl4 text-uppercase"
                                            >Reset Password</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        function onFormSubmit(token) {
            document.getElementById('reset-request-form').submit();
        }
    </script>
@endsection