<section class="copyright u-mobile-center d-none">
    <div class="container-footer">
        <div class="wrap">
            <div class="row">
                <div class="col-md-5 col-sm-12">
                    <div class="cr-left">
                        <div class="copyright-text u-block-mobile">
                            © {{date('Y')}} {{ settings('site_name', config('app.name')) }}. All rights reserved.
                        </div>
                        <a href="{{lang_url('privacy-policy')}}">{{ trans_fb('app.app_Privacy_Policy', 'Privacy Policy') }}</a> |  <a href="{{lang_url('terms')}}">{{ trans_fb('app.app_Terms_of_Use', 'Terms and Conditions') }}</a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="footer-cp-sn">
                        @if(settings('facebook_url'))
                            <a href="{{ settings('facebook_url') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(settings('twitter_url'))
                            <a href="{{ settings('twitter_url') }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if(settings('instagram_url'))
                            <a href="{{ settings('instagram_url') }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(settings('linkedin_url'))
                            <a href="{{ settings('linkedin_url') }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                        @endif
                    </div>
                </div>
                <!-- <div class="col-md-3 col-sm-12">
                    <div class="cr-right">
                        <a href="#" target="_blank" title="Property Webmasters">{{ settings('footer_anchor_text') }}</a>
                        Property Webmasters
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</section>

<!-- Translate Set -->
@if(settings('translations'))
    <script>
        function googleTranslateElementInit()
        {
            new google.translate.TranslateElement(
                {
                    pageLanguage: '<?php echo $translate_settings->language_default; ?>',
                    includedLanguages: '<?php echo $translate_settings->language_settings; ?>',
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                    autoDisplay: false,
                    multilanguagePage: true
                }, 'google_translate_element');
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
@endif
