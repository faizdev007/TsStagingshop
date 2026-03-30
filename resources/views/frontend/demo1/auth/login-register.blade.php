@extends('frontend.demo1.layouts.frontend')
@push('body_class')login-page @endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const firstNameInput = document.querySelector('input[name="fullname"]');
        const lastNameInput = document.querySelector('input[name="lastname"]');
        const submitButton = document.querySelector('.custom-btn-submit');

        function validateNameLength() {
            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();

            // Check if either first name or last name is longer than 10 characters
            if (firstName.length > 10 || lastName.length > 10) {
                submitButton.disabled = true;
                submitButton.classList.add('disabled');

                // Optional: Add error message
                let errorMessage = document.getElementById('name-length-error');
                if (!errorMessage) {
                    errorMessage = document.createElement('div');
                    errorMessage.id = 'name-length-error';
                    errorMessage.className = 'text-danger f-12 u-mt05';
                    errorMessage.innerText = 'First name and last name must be 10 characters or less';
                    submitButton.closest('.form-group').appendChild(errorMessage);
                }
            } else {
                submitButton.disabled = false;
                submitButton.classList.remove('disabled');

                // Remove error message if exists
                let errorMessage = document.getElementById('name-length-error');
                if (errorMessage) {
                    errorMessage.remove();
                }
            }
        }

        // Add event listeners to both inputs
        firstNameInput.addEventListener('input', validateNameLength);
        lastNameInput.addEventListener('input', validateNameLength);
    });
</script>
@endpush

@section('main_content')
<section class="page-title c-bg-gray-8 -generic u-mb2">
    <div class="container">
        <div class="-wrap">
            <div class="-inner-wrap">
                <h1 class="-page-title-heading f-two f-50 f-400 c-white text-center">
                    {!! $page->title !!}
                    <span class="-border {{ !empty($page->header_title)?'border-remove-title':'' }}"></span>
                </h1>
                @if($page->header_title)
                <h2 class="-page-subtitle-heading f-16 f-two f-400 c-white d-block text-center text-uppercase">{{ $page->header_title }}</h2>
                @endif
            </div>
        </div>
    </div>
</section>

<section>
    {{-- Success Message --}}
    @if (session('success'))
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Warning Message --}}
    @if (session('warning'))
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="container">
        <div class="row u-mb2">
            <div class="col-sm-12 col-md-12 col-xl-7 u-mb2">
                <div class="c-bg-gray h-100 u-mt1 u-mb1">
                    <div class="text-center u-pt2">
                        <h4 class="f-28 f-two f-regular u-block u-mb1">Register</h4>
                    </div>
                    <form id="register-form" class="u-pl2 u-pr2 u-pb2" method="POST" action="{{ url('register') }}" data-toggle="validator">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }} u-mb1">
                                    <input id="first_name" type="text" class="form-control form__input -no-border" name="first_name" value="{{ old('first_name') }}" placeholder="First Name *" required>
                                    @if ($errors->has('first_name'))
                                    <span class="f-12 help-block text-danger">
                                        <strong>{!! $errors->first('first_name') !!}</strong>
                                    </span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }} u-mb1">
                                    <input id="surname_name" type="text" class="form-control form__input -no-border" name="surname" value="{{ old('surname') }}" placeholder="Surname *" required>
                                    @if ($errors->has('surname'))
                                    <span class="f-12 help-block text-danger">
                                        <strong>{!! $errors->first('surname') !!}</strong>
                                    </span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} u-mb1">
                                    <input id="email" type="email" class="form-control form__input -no-border" name="email" value="{{ old('email') }}" placeholder="Your Email Address *" required>
                                    @if ($errors->has('email'))
                                    <span class="f-12 help-block text-danger">
                                        <strong>{!! $errors->first('email') !!}</strong>
                                    </span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group{{ $errors->has('telephone') ? 'has-error' : '' }} u-mb1">
                                    <input type="tel" id="telephone" name="telephone" class="form-control form__input -no-border" value="{{ old('telephone') }}" placeholder="Your Telephone Number">
                                    @if ($errors->has('telephone'))
                                    <span class="f-12 help-block text-danger">
                                        <strong>{!! $errors->first('telephone') !!}</strong>
                                    </span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} u-mb1">
                                    <input id="password" type="password" class="form-control form__input -no-border" name="password" placeholder="Your Password *" required>
                                    @if ($errors->has('password'))
                                    <span class="f-12 help-block text-danger"><strong>{!! $errors->first('password') !!}</strong></span>
                                    @endif
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group u-mb1">
                                    <input id="password-confirm" type="password" class="form-control form__input -no-border" name="password_confirmation" placeholder="Confirm your password *" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                        <p class="c-lighter-gray f-12 u-block u-mt1 u-mb1">@include('frontend.demo1.parts/gdpr')</p>
                        <div class="text-center u-mb1 u-mt1">
                            <button type="submit"
                                class="button -primary -wider-3c f-18 f-bold u-pt2 u-pb2 text-uppercase">
                                   Sign Up</button>
                            </div>
                            @if(settings('propertybase'))
                                <input type="hidden" name="url" value="{{ request()->url() }}">
                            @endif
                    </form>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-xl-5 u-mb2">
                <div class="c-bg-gray u-mt1 h-100 u-mb1">
                    <div class="text-center u-pt2">
                        <h4 class="f-28 f-two f-regular u-block u-mb1">Login</h4>
                    </div>
                    <form method="POST" class="u-pl2 u-pr2 u-pb2" action="{{ url('login') }}" data-toggle="validator" role="form">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} u-mb1">
                            <input id="email" type="email" class="form-control form__input -no-border" name="email" value="{{ old('email') }}" placeholder="Your Email Address *" required="required">
                            @if ($errors->has('email'))
                            <span class="f-12 help-block text-danger">
                                <strong>{!! $errors->first('email') !!}</strong>
                            </span>
                            @endif
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} u-mb1">
                            <input id="password" type="password" class="form-control form__input -no-border" name="password" placeholder="Your Password *" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group u-mt2">
                            <div class="text-center">
                                <button type="submit" class="button -secondary f-18 f-bold -wider-3c text-uppercase">&nbsp; Login &nbsp;</button>
                            </div>
                            <div class="text-center u-block u-mt1 u-mb2">
                                <a class="u-block u-mt05 f-14 c-dark-gray forgot-password" href="{{ url('password/reset') }}">Forgotten your password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    button[type="submit"].disabled,
    button[type="submit"]:disabled {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
        background-color: #cccccc !important;
    }
</style>
@endpush