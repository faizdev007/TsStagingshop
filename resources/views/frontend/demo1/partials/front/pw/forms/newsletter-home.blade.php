<form
    action="{{ url('/newsletter/') }}"
    method="POST"
    class="required-form newsletter-form--trigger"
    id="news-letter"
>
    @csrf

    <div class="newsletter-valication--style text-center"></div>

    <div class="u-mw-430 ms-auto me-auto">

        <div class="form-control--style-2">
            <input
                type="text"
                name="name"
                id="hname"
                class="required"
                placeholder="NAME"
                autocomplete="on"
            >
        </div>

        <div class="form-control--style-2">
            <input
                type="email"
                name="email"
                id="hemail"
                class="required email"
                placeholder="EMAIL ADDRESS"
                autocomplete="on"
            >
        </div>

        <div class="text-center mt-5">
            <button
                type="submit"
                name="button"
                class="g-recaptcha button -default-2 -left-liner f-14 f-semibold"
                data-sitekey="{{ env('reCAPTCHA_site_key') }}"
                data-callback="sendNewsletter"
            >
                SUBMIT
            </button>
        </div>

    </div>
</form>
@push('frontend-footer-scripts-end')
    <script>
        function sendNewsletter(token) {
            let newsletterForm = jQuery('#news-letter');
            
            jQuery('#news-letter [name=recaptcha_token]').val(token);

            newsletterForm.submit()
        }
    </script>
@endpush

