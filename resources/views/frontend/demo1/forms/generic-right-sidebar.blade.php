@php $form_id = "generic-rhs-form"; @endphp
<div class="-title f-28 f-two text-center">Contact us today</div>
<form
    action="{{ url('ajax/generic_enquiry') }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-{{ $form_id }}"
>
    @csrf

    <div id="response-{{ $form_id }}"></div>

    <input type="hidden" name="recaptcha_token">

    <div class="-fields">

        {{-- First name --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="text"
                name="firstname"
                placeholder="FIRST NAME *"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->firstname : '' }}"
                required
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Last name --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="text"
                name="lastname"
                placeholder="LAST NAME *"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->lastname : '' }}"
                required
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Email --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="email"
                name="email"
                placeholder="EMAIL ADDRESS *"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->email : '' }}"
                @if(settings('members_area') == '1' && Auth::check()) readonly @endif
                required
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Telephone --}}
        <div class="form-group form-group-1 -transparent-bg u-mb1">
            <input
                type="tel"
                name="telephone"
                placeholder="TELEPHONE NUMBER"
                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->telephone : '' }}"
                @if(settings('members_area') == '1' && Auth::check()) readonly @endif
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Message --}}
        <div class="form-group form-group-1 -transparent-bg u-mb2">
            <input
                type="text"
                name="message"
                placeholder="MESSAGE"
            >
            <span class="form-control-feedback" aria-hidden="true"></span>
        </div>

        {{-- Submit --}}
        <div class="form-group form-group-1 text-center">

            <div
                id="gr-{{ $form_id }}"
                class="g-recaptcha-pw"
                data-sitekeypw="{{ settings('recaptcha_public_key') }}"
                data-sizepw="invisible"
                data-callbackpw="contactSubmitRHS"
                data-counterpw=""
            ></div>

            <button
                type="submit"
                id="btn-{{ $form_id }}"
                class="cta -primary -wider-3 f-14"
            >
                GET IN TOUCH
            </button>
        </div>

    </div>
</form>
<script type="text/javascript">
    function contactSubmitRHS(response) {
        $("#ajax-form-<?=$form_id?>").find('input[name="recaptcha_token"]').val(response);
        $("#ajax-form-<?=$form_id?>").trigger('submit');
    }
</script>
