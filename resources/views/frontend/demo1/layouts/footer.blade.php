@include('frontend.demo1.partials.front.pw/section-join-home')
<style>
    .auth-block {
        position: absolute;
        inset: 0;
        opacity: 0;
        transform: scale(0.98);
        pointer-events: none;
        transition:
            opacity 420ms cubic-bezier(0.4, 0, 0.2, 1),
            transform 420ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .auth-block.is-active {
        opacity: 1;
        transform: scale(1);
        pointer-events: auto;
        position: relative;
    }
</style>
<footer class="bg-gray-11">
    <div class="container">
        <div class="footer--wrap ms-auto me-auto">
            <div class="text-center mb-5">
                <a href="#"><img src="{{ asset('assets/demo1/images/logos/white-logo.svg') }}" width="231" height="90" alt="Footer Logo"></a>
            </div>
            <div class="c-white text-center f-13 u-mw-711 ms-auto me-auto mb-5">
                Real Estate Brokerage showcasing an unrivalled collection of Luxury Properties in Dubai & beyond...
            </div>

            <div class="footer-sn--wrap text-center mb-5">

                @if( !empty(settings('telephone')) )
                <div class="footer-sn--item">
                    <a href="tel:{{ settings('telephone') }}" aria-label="Call us at {{ settings('telephone') }}" class="u-hover-opacity-70" target="_blank"><i class="fa fa-phone"></i></a>
                </div>
                @endif
                @if( !empty(settings('whatsapp_url')) )
                <div class="footer-sn--item">
                    <a href="https://api.whatsapp.com/send?phone={{settings('whatsapp_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Chat on WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
                @endif
                @if( !empty(settings('instagram_url')) )
                <div class="footer-sn--item">
                    <a href="{{settings('instagram_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                </div>
                @endif
                @if( !empty(settings('facebook_url')) )
                <div class="footer-sn--item">
                    <a href="{{settings('facebook_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                </div>
                @endif

                @if( !empty(settings('tiktok_url')) )
                <div class="footer-sn--item">
                    <a href="{{settings('tiktok_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on TikTok"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24">
                            <path fill="white" d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0 1 15.54 3h-3.09v12.4a2.592 2.592 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6c0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64c0 3.33 2.76 5.7 5.69 5.7c3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3s-1.88.09-3.24-1.48z" />
                        </svg></a>
                </div>
                @endif

                @if( !empty(settings('youtube_url')) )
                <div class="footer-sn--item">
                    <a href="{{settings('youtube_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Subscribe to our YouTube channel"><i class="fab fa-youtube-square"></i></a>
                </div>@endif
                @if( !empty(settings('twitter_url')) )
                <div class="footer-sn--item">
                    <a href="{{settings('twitter_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                </div>@endif

                @if( !empty(settings('linkedin_url')) )
                <div class="footer-sn--item">
                    <a href="{{settings('linkedin_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>@endif

                @if( !empty(settings('pinterest_url')) )
                <div class="footer-sn--item">
                    <a href="{{settings('pinterest_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on Pinterest"><i class="fab fa-pinterest-p"></i></a>
                </div>@endif

                @if( !empty(settings('email')) )
                <div class="footer-sn--item">
                    <a href="mailto:{{ settings('email') }}" target="_blank" aria-label="Email us"><i class="far fa-envelope"></i></a>
                </div>@endif


            </div>

            <div class="footer-link--container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="footer-link--container-divider">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="footer-link--header c-white f-14 f-medium mb-4" data-target=".footer-link-content-info">
                                        INFORMATION
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                    <div class="footer-link--header-content footer-link-content-info">
                                        <div class="">
                                            <a href="{{ url('/property-for-sale') }}" class="c-white-link f-13 f-regular u-hover-opacity-70" aria-label="View our properties for sale">
                                                Sales</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('/property-for-rent') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View our properties for rent">
                                                Rentals</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('property-for-development') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View our new developments">
                                                New Developments</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('property-for-sale/in/international/') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View our international properties">
                                                International</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('about-us') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="Learn more about us">
                                                About</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('blog') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="Read our blog">
                                                Blog</a>
                                        </div>
                                        <div class="">
                                            <a href="{{ url('contact-us') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="Contact us">
                                                Contact</a>
                                        </div>

                                        <div class="footer-link--header-content-settings">

                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mt-3 mt-lg-0">
                                        <div class="footer-link--header c-white f-14 f-medium mb-4" data-target=".footer-link-content-properties">
                                            LOCATIONS
                                            <i class="fas fa-angle-down"></i>
                                        </div>
                                        <div class="footer-link--header-content footer-link-content-properties">
                                            <div>
                                                <a href="{{ url('property-for-sale/area/Palm+Jumeirah') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View properties in Palm Jumeirah">
                                                    Palm Jumeirah</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('property-for-sale/area/Jumeirah+Beach+Residence') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View properties in Jumeirah Beach Residence (JBR)">
                                                    Jumeirah Beach Residence (JBR)</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('property-for-sale/area/bluewaters') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View properties in Bluewaters">
                                                    Bluewaters</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('/property-for-sale/area/Dubai+Harbour+Beachfront') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View properties in Dubai Harbour Beachfront">
                                                    Dubai Harbour Beachfront</a>
                                            </div>
                                            <div>
                                                <a href="{{ url('/property-for-sale/area/Downtown+%26+Burj+Khalifa+District') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View properties in Downtown & Business Bay">
                                                    Downtown & Business Bay</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 mt-4 mt-md-0 mt-lg-0">
                                    <div class="footer-link--header c-white f-14 f-medium mb-4" data-target=".footer-link-content-blog">
                                        QUICK LINKS
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                    <div class="footer-link--header-content footer-link-content-blog mb-4">
                                        <div>
                                            <a href="{{ url('apartment-for-sale/in/dubai%2C+united+arab+emirates') }}"  class="c-white-link f-13 u-hover-opacity-70" aria-label="View apartments in Dubai">
                                                Apartments In Dubai</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('villa-for-sale/in/dubai%2C+united+arab+emirates') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View villas in Dubai">
                                                Villas In Dubai</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('development-for-sale-in-dubai') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View new developments in Dubai">
                                                New Developments In Dubai</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('privacy-policy') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View privacy policy">
                                                Privacy Policy</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('terms') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View terms and conditions">
                                                Terms & Conditions</a>
                                        </div>
                                        <div>
                                            <a href="{{ url('cookie-policy') }}" class="c-white-link f-13 u-hover-opacity-70" aria-label="View cookie policy">
                                                Cookie Policy</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="text-center c-white f-11 f-light py-4">
                <p> Copyright © 2022-{{date('Y')}} terezaestates.com </p>
                <p>TEREZA REAL ESTATE LLC trading as Tereza Estates™ with real estate publishing platform terezaestates.com</p>
                <p>
                    TEREZA REAL ESTATE LLC is licensed under DED License #1113466, RERA ORN #32523, Dubai, United Arab Emirates</p>
            </div>
        </div>
    </div>
</footer>
@include('frontend.demo1.components.property-inquiry-modal')

<div class="social-bx-fix">
    <div class="container">
        <div class="social-bx-fix--wrap" style="margin-right:-50px!important;">
            <ul>

                @if( !empty(settings('telephone')) )
                <li>
                    <a href="tel:{{ settings('telephone') }}" target="_blank"><i class="fa fa-phone"></i></a>
                </li>
                @endif
                @if( !empty(settings('whatsapp_url')) )
                <li>
                    <a href="https://api.whatsapp.com/send?phone={{settings('whatsapp_url')}}" aria-label="Chat on WhatsApp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                </li>
                @endif
                @if( !empty(settings('instagram_url')) )
                <li>
                    <a href="{{settings('instagram_url')}}" aria-label="Follow us on Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                </li>
                @endif
                @if( !empty(settings('facebook_url')) )
                <li>
                    <a href="{{settings('facebook_url')}}" aria-label="Follow us on Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                </li>
                @endif

                @if( !empty(settings('tiktok_url')) )
                <li>
                    <a href="{{settings('tiktok_url')}}" aria-label="Follow us on TikTok" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24">
                            <path fill="white" d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0 1 15.54 3h-3.09v12.4a2.592 2.592 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6c0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64c0 3.33 2.76 5.7 5.69 5.7c3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3s-1.88.09-3.24-1.48z" />
                        </svg></a>
                </li>
                @endif

                @if( !empty(settings('youtube_url')) )
                <li>
                    <a href="{{settings('youtube_url')}}" aria-label="Subscribe to our YouTube channel" target="_blank"><i class="fab fa-youtube-square"></i></a>
                </li>@endif
                @if( !empty(settings('linkedin_url')) )
                <li>
                    <a href="{{settings('linkedin_url')}}" aria-label="Follow us on LinkedIn" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                </li>
                @endif
                @if( !empty(settings('twitter_url')) )
                <li>
                    <a href="{{settings('twitter_url')}}" aria-label="Follow us on Twitter" target="_blank"><i class="fab fa-twitter"></i></a>
                </li>
                @endif

                @if( !empty(settings('pinterest_url')) )
                <li>
                    <a href="{{settings('pinterest_url')}}" aria-label="Follow us on Pinterest" target="_blank"><i class="fab fa-pinterest-p"></i></a>
                </li>@endif

                @if( !empty(settings('email')) )
                <li>
                    <a href="mailto:{{ settings('email') }}" aria-label="Send us an email" target="_blank"><i class="far fa-envelope"></i></a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- Modals -->
@if(settings('members_area') == 1)
<!-- Login -->
<div class="modal fade global-modal member-login-account property-alert">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content overflow-hidden border-0" style="border-radius: 0.5rem;">
            <div class="modal-header text-white" style="background-color: #d9b483;">
                <h5 class="modal-title" id="loginModalLabel">
                    Login or Sign Up to Your Account
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-md-flex d-block bg-white">
                <div class="flex-1 w-100 d-flex flex-column justify-content-center text-center">
                    <img src="{{asset('assets/demo1/images/logos/logo.svg')}}"
                        alt="Login Page"
                        class="mx-auto img-fluid"
                        style="max-width: 200px;" />

                    <p class="mt-4">
                        Create a free Members Account & save properties you like!
                    </p>
                </div>
                <div class="flex-1 w-100 position-relative overflow-hidden">

                    <!-- LOGIN -->
                    <div id="loginblock" class="auth-block is-active">
                        @include('frontend.shared.forms.member-login')
                        <hr>
                        <div class="mb-3 text-center">
                            <h4 class="popup-heading f-two">Don't have an account?</h4>

                            <button
                                type="button"
                                data-action="show-signup"
                                class="-large -secondary button f-bold membercreatebtn p-2 text-uppercase w-100">
                                Sign Up
                            </button>
                        </div>
                    </div>

                    <!-- SIGNUP -->
                    <div id="signupblock" class="auth-block">
                        @include('frontend.shared.forms.member-register')
                        <hr>
                        <div class="mb-3 text-center">
                            <h4 class="popup-heading f-two">Already have an account?</h4>

                            <button
                                type="button"
                                data-action="show-login"
                                class="-large -secondary button f-bold membercreatebtn p-2 text-uppercase w-100">
                                Login Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@include('frontend.demo1.components.cookie-consent')

@include('frontend.shared.components.modals')

@if(settings('recaptcha_enabled') == '1')
<!-- MANDATORY invisible recaptcha -->
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const SITE_KEY = document.querySelector('meta[name="recaptcha-key"]').content;
        const recaptchaLink = `https://www.google.com/recaptcha/api.js?render=${SITE_KEY}`;
    
        // ✅ GLOBAL FLAGS (attach to window to avoid duplicate loads)
        window._recaptchaReady = window._recaptchaReady || false;
        window._recaptchaLoading = window._recaptchaLoading || false;
    
        function loadRecaptcha(callback) {
    
            if (window._recaptchaReady) {
                callback();
                return;
            }
    
            if (window._recaptchaLoading) {
                let check = setInterval(() => {
                    if (window._recaptchaReady) {
                        clearInterval(check);
                        callback();
                    }
                }, 100);
                return;
            }
    
            // ✅ Check again in DOM (extra safety)
            const existingScript = document.querySelector(`script[src="${recaptchaLink}"]`);
            if (existingScript) {
                window._recaptchaReady = true;
                callback();
                return;
            }
    
            window._recaptchaLoading = true;
    
            const script = document.createElement('script');
            script.src = recaptchaLink;
            script.async = true;
            script.defer = true;
    
            script.onload = function () {
                window._recaptchaReady = true;
                callback();
            };
    
            document.head.appendChild(script); // better than body
        }
    
        function ensureTokenInputs() {
            document.querySelectorAll('form:not([data-no-recaptcha])').forEach(form => {
                if (!form.querySelector('[name="recaptcha_token"]')) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'recaptcha_token';
                    form.appendChild(input);
                }
            });
        }
    
        let executing = false;
    
        function updateAllFormsToken() {
            if (!window._recaptchaReady || typeof grecaptcha === 'undefined' || executing) return;
    
            executing = true;
    
            grecaptcha.ready(function () {
                grecaptcha.execute(SITE_KEY, { action: 'global' })
                    .then(function (token) {
    
                        document.querySelectorAll('form:not([data-no-recaptcha])').forEach(form => {
                            const input = form.querySelector('[name="recaptcha_token"]');
                            if (input) input.value = token;
                        });
    
                        executing = false;
                    })
                    .catch(() => executing = false);
            });
        }
    
        function initRecaptcha() {
            loadRecaptcha(() => {
                ensureTokenInputs();
                updateAllFormsToken();

                // ✅ Start interval only when ready
                setInterval(updateAllFormsToken, 60 * 1000); // every 1 min
            });
        }
    
        let initialized = false;
    
        function lazyInit() {
            if (initialized) return;
            initialized = true;
            initRecaptcha();
        }
    
        document.addEventListener('focusin', (e) => {
            if (e.target.closest('form')) lazyInit();
        });
    
        setTimeout(lazyInit, 10000);
    });
</script>
@endif
<script async type="text/javascript">
    function submitpopupf(formId) {
        const form = document.getElementById(formId);
        const responseBox = document.getElementById('response-' + formId);
        const submitBtn = document.getElementById('btn-' + formId);

        if (!form || !responseBox) return;

        responseBox.innerHTML = '';

        // Store original button content
        submitBtn.dataset.originalText = submitBtn.innerHTML;

        // Disable button
        submitBtn.disabled = true;

        // Replace button content with spinner
        submitBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Processing...
                    `;

        const formData = new FormData(form);

        fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json();

                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.dataset.originalText;

                if (data.flag === 1) {
                    responseBox.innerHTML = `
                                <div class="alert alert-success">
                                    ${data.message ?? 'Login successful'}
                                </div>
                            `;

                    // Redirect (delay for UX)
                    setTimeout(() => {
                        window.location.href = data.redirect ?? window.location.href;
                    }, 800);

                } else {
                    responseBox.innerHTML = `
                                <div class="alert alert-danger">
                                    ${data.message ?? 'Invalid login credentials'}
                                </div>
                            `;

                    setTimeout(() => {
                        responseBox.innerHTML = '';
                    }, 3000);
                }
            })
            .catch(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.dataset.originalText;
                responseBox.innerHTML = `
                            <div class="alert alert-danger">
                                Something went wrong. Please try again.
                            </div>
                        `;
            });
    }
</script>

</body>

</html>
{{-- end of :: FOOTER --}}