<section class="vertical-form-section c-bg-gray-text_color4" id="interested">
    <div class="container">
        <div class="-wrap">
             <div class="section-title f-two text-center f-30 mb-5">Interested in this property? Enquire today!</div>
@php
$form_id = "property-bottom-form";
$property->ref = !empty($property->ref) ? $property->ref : 'AR';
@endphp
<form
    action="{{ url('ajax/property-bottom-form/' . $property->ref) }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-{{ $form_id }}"
>
    @csrf

    <div id="response-{{ $form_id }}"></div>

    <input type="hidden" name="recaptcha_token">

    <div class="row">

        {{-- First name --}}
        <div class="col-md-4 col-sm-6">
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
        </div>

        {{-- Last name --}}
        <div class="col-md-4 col-sm-6">
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
        </div>

        {{-- Email --}}
        <div class="col-md-4 col-sm-12">
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
        </div>

        {{-- Telephone --}}
        <div class="col-md-6 col-sm-6">
            <div class="form-group form-group-1 -transparent-bg u-mb1">
                <input
                    type="tel"
                    name="telephone"
                    placeholder="TELEPHONE NUMBER"
                    value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->telephone : '' }}"
                >
                <!-- @if(settings('members_area') == '1' && Auth::check()) readonly @endif -->
                <span class="form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        {{-- Message --}}
        <div class="col-md-6 col-sm-6">
            <div class="form-group form-group-1 -transparent-bg u-mb1">
                <input
                    type="text"
                    name="message"
                    placeholder="MESSAGE *"
                    required
                >
                <span class="form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>

        {{-- GDPR --}}
        <div class="col-md-12 col-sm-12">
            <div class="text-consent f-12 c-gray-26 u-mt05 text-center u-mb3 u-pl1 u-pr1">
                @include('frontend.' . themeOptions() . '.parts/gdpr')
            </div>
        </div>

        {{-- Submit --}}
        <div class="col-md-12 col-sm-12">
            <div class="form-group form-group-1 text-center">

                <input type="hidden" name="position" value="bottom">

                <div
                    id="gr-{{ $form_id }}"
                    class="g-recaptcha-pw"
                    data-sitekeypw="{{ settings('recaptcha_public_key') }}"
                    data-sizepw="invisible"
                    data-callbackpw="pEnquiryBottomSubmit"
                    data-counterpw=""
                ></div>

                <button
                    type="submit"
                    class="cta -primary -x-large"
                    id="btn-{{ $form_id }}"
                >
                    SUBMIT
                </button>
            </div>
        </div>

    </div>
</form>
</div>
</div>
</section>
<script type="text/javascript">
    function pEnquiryBottomSubmit(response) {
        $("#ajax-form-<?=$form_id?>").find('input[name="recaptcha_token"]').val(response);
        $("#ajax-form-<?=$form_id?>").trigger('submit');
    }
</script>
