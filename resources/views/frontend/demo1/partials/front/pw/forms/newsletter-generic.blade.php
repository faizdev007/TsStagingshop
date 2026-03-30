<form
    action="{{ url('/newsletter/') }}"
    method="POST"
    class="required-form newsletter-form--trigger"
    id="news-letter"
>
    @csrf

    <div class="row">

        <div class="col-md-3 col-sm-12">
            <h2 class="f-two mb-0 text-center p-0 pe-md-4">
                JOIN OUR <span>NETWORK</span>
            </h2>
        </div>

        <div class="col-md-6 col-sm-12">
            <div class="mt-3 position-relative">

                <div class="newsletter-valication--style -v2 text-center"></div>

                <div class="row">

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-4 mb-md-0">
                            <input
                                type="text"
                                name="name"
                                id="hname"
                                class="form-control"
                                placeholder="NAME"
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3 mb-md-0">
                            <input
                                type="email"
                                name="email"
                                id="hemail"
                                class="form-control"
                                placeholder="EMAIL ADDRESS"
                                autocomplete="off"
                            >
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-12 text-center">
            <button
                type="submit"
                class="g-recaptcha button -default-3 -left-liner f-14 f-semibold newsletter-form--btn ms-0 ms-md-4"
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
