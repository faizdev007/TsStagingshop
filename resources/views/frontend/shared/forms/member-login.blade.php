@php $formId = 'ajax-member-login-popup' @endphp
<form id="{{$formId}}" method="POST" action="{{ url('ajax-login') }}" class="ajax-form" data-toggle="validator" role="form">
    {{ csrf_field() }}
    <div class="modal-form-fields">
        <div id="response-{{$formId}}" class="member-validation-msg"></div>
        <input type="hidden" name="redirectVar" value="{{$property->id ?? ''}}" />
        <input type="hidden" name="property_id" value="{{$property->id ?? ''}}" />
        <div class="form-group u-mb1">
            <input id="email" type="email" class="modal-form__input u-fullwidth border" name="email" placeholder="{{ trans_fb('app.app_Email_Address', 'Email Address') }} *" required autocomplete="off">
            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
        </div>
        <div class="form-group u-mb1">
            <input type="password" class="modal-form__input u-block u-fullwidth border" placeholder="{{ trans_fb('app.app_Password', 'Password') }} *" name="password" required autocomplete="off">
            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
        </div>
        <div class="form-group u-mb1 u-mt2">
            <div class="text-center">
                <button class="button -primary -wider-3c f-bold text-uppercase u-inline-block w-100 p-2 u-pr4 u-pl4 u-mb1" onclick="submitpopupf('{{$formId}}')" id="btn-{{$formId}}" type="button">{{ trans_fb('app.app_Login', 'Login') }}</button>
                <a class="u-block u-mt05 f-13 c-dark-gray forgot-password" href="{{ url('password/reset') }}">{{ trans_fb('app.app_Forgot_Password', 'Forgotten your password?') }}</a>
            </div>
        </div>
    </div>
</form>
