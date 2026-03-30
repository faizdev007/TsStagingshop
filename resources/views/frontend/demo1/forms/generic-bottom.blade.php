@php $form_id = "generic-bottom-form"; @endphp
<form
    action="{{ url('ajax/bottom_generic') }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-{{ $form_id }}"
>
    @csrf

    <section class="vertical-form-section c-bg-gray-text_color4">
        <div class="container">
            <div class="-wrap">

                <div class="section-title f-two text-center f-30">
                    Join Our Network
                </div>

                <div class="section-subtitle text-center f-15 u-mb3">
                    Keep up to date with new listings, the latest market trends and opportunities.
                </div>

                <div id="response-{{ $form_id }}"></div>

                <input type="hidden" name="recaptcha_token">

                <div class="row">

                    {{-- First name --}}
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group form-group-1 -transparent-bg u-mb1">
                            <input
                                type="text"
                                name="firstname"
                                placeholder="First Name *"
                                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->firstname : '' }}"
                                required
                            >
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    {{-- Last name --}}
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group form-group-1 -transparent-bg u-mb1">
                            <input
                                type="text"
                                name="lastname"
                                placeholder="Last Name *"
                                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->lastname : '' }}"
                                required
                            >
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group form-group-1 -transparent-bg u-mb1">
                            <input
                                type="email"
                                name="email"
                                placeholder="Your Email Address *"
                                value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->email : '' }}"
                                @if(settings('members_area') == '1' && Auth::check()) readonly @endif
                                required
                            >
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group form-group-1 -transparent-bg u-mb2">
                            <input
                                type="text"
                                name="message"
                                placeholder="Message"
                            >
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group form-group-1 text-center">

                            <div
                                id="gr-{{ $form_id }}"
                                class="g-recaptcha-pw"
                                data-sitekeypw="{{ settings('recaptcha_public_key') }}"
                                data-sizepw="invisible"
                                data-callbackpw="genericContactSubmit"
                                data-counterpw=""
                            ></div>

                            <button
                                type="submit"
                                id="btn-{{ $form_id }}"
                                class="cta -secondary -wider-3c f-14"
                            >
                                JOIN TODAY
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</form>

<script type="text/javascript">
    function genericContactSubmit(response) {
        $("#ajax-form-<?=$form_id?>").find('input[name="recaptcha_token"]').val(response);
    }
</script>
<!-- // $("#ajax-form-<?=$form_id?>").trigger('submit'); -->
