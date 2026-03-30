@php $formId = 'ajax-member-register-popup' @endphp
<form id="{{$formId}}" method="POST" action="{{ url('ajax-register') }}" class="ajax-form" data-toggle="validator">
    {{ csrf_field() }}

    <div class="sns2-fields">

        <div id="response-{{$formId}}" class="member-validation-msg"></div>

        <div class="form-row">
            <div class="col-12">
                <div class="form-group u-mb1">
                    <input class="modal-form__input u-block u-fullwidth border" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="{{ trans_fb('app.app_Full_Name', 'First Name') }}*" required autocomplete="off">
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group u-mb1">
                    <input class="modal-form__input u-block u-fullwidth border" type="text" name="surname" value="{{ old('name') }}" placeholder="{{ trans_fb('app.app_Full_Name', 'Surname') }}*" required autocomplete="off">
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
        </div>

        <div class="form-group u-mb1">
            <input class="modal-form__input u-block u-fullwidth border" id="email-login" type="email" name="email" value="{{ old('email') }}" placeholder="{{ trans_fb('app.app_Email_Address', 'Email Address') }} *" required autocomplete="off">
            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
        </div>

        <div class="form-group u-mb1">
            <input class="modal-form__input u-block u-fullwidth border" type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" placeholder="{{ trans_fb('app.app_Telephone', 'Telephone') }}" autocomplete="off">
            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
        </div>

        <div class="form-group u-mb1">
            <input class="modal-form__input u-block u-fullwidth border" id="password" type="password" name="password" placeholder="{{ trans_fb('app.app_Password', 'Password') }} *" required autocomplete="off">
            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
        </div>

        <div class="form-group u-mb1">
            <input class="modal-form__input u-block u-fullwidth border" id="password-confirm" type="password" name="password_confirmation" placeholder="{{ trans_fb('app.app_Confirm_Password', 'Confirm your password') }} *" required autocomplete="off">
            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
        </div>

        <div class="sns2-cta-style-1 login-margin-btn text-center">
            <button class="button -primary -wider-3c f-bold text-uppercase u-inline-block w-100 p-2 u-pr4 u-pl4 u-mb1" onclick="submitpopupf('{{$formId}}')" id="btn-{{$formId}}" type="button">{{ trans_fb('app.app_Signup', 'Sign Up Now') }}</button>
        </div>
    </div>
</form>
