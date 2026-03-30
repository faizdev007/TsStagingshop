@php // $form_id = "ajax-form-newsletter-form"; @endphp
@php $form_id = "newsletter-form"; @endphp

<form
    action="{{ url('ajax/subscribe') }}"
    method="POST"
    data-toggle="validator"
    id="ajax-form-newsletter-form"
    class="sign-up-form form-ajax-attr required-form newsletter-form--trigger"
>
    @csrf

    <div id="response-newsletter-form" class="text-center"></div>

    <input type="hidden" name="recaptcha_token" id="recaptcha_token">

    <div class="newsletter-form u-mw-430 ms-auto me-auto text-center">
        <div class="newsletter-input">

            {{-- Full name --}}
            <div class="single-form form-control--style-2 py-4">
                <div class="form-group mb-0">
                    <input
                        type="text"
                        name="fullname"
                        placeholder="{{ trans_fb('app.app_Full Name','FULL NAME ') }}*"
                        required
                        autocomplete="off"
                    >
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>

            <div class="single-form form-control--style-2 d-flex row mb-5">

                {{-- Email --}}
                <div class="form-group col-6">
                    <input
                        type="email"
                        name="email"
                        placeholder="{{ trans_fb('app.app_Email Address','EMAIL ADDRESS') }}*"
                        required
                        autocomplete="off"
                    >
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>

                {{-- Honeypot --}}
                <div class="form-group col-6 d-none">
                    <input
                        type="email"
                        name="email_confirm"
                        class="d-none"
                        aria-hidden="true"
                        autocomplete="email-confirm"
                    >
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                </div>

                {{-- Telephone --}}
                <div class="col-md-6 col-sm-6 col-6">
                    <div class="form-group form-group-1 u-mb1">
                        <input
                            type="tel"
                            name="telephone"
                            id="phone"
                            class="form-control rounded-0"
                            placeholder="PHONE"
                            autocomplete="contact-number"
                            value="{{ settings('members_area') == '1' && Auth::check() ? Auth::user()->telephone : '' }}"
                        >
                        <!-- @if(settings('members_area') == '1' && Auth::check()) readonly @endif -->
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>

            </div>

            {{-- Submit --}}
            <button
                type="submit"
                id="btn-{{ $form_id }}"
                class="button -default-2 -left-liner f-14 f-semibold"
                style="color:#fff !important;"
            >
                {{ trans_fb('app.app_Subscribe', 'SUBMIT') }}
            </button>

        </div>
    </div>
</form>



<script type="text/javascript">
    const responseBox = document.getElementById('response-newsletter-form');

    const observer = new MutationObserver(() => {
        setTimeout(() => {
            responseBox.innerHTML = '';
        }, 5000);
    });

    observer.observe(responseBox, { childList: true, subtree: true });
</script>