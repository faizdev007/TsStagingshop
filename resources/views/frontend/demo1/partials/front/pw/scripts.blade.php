@stack('frontend-footer-scripts')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="{{mix('assets/pw/js/app.js')}}" ></script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
<script src="{{url('assets/pw/libraries/smooth-scroll/cdn.js')}}"></script>
@stack('frontend-footer-scripts-end')
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: -50
    });

    SmoothScroll({
        animationTime    : 750,
        touchpadSupport   : true,
    });
</script>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: !1
        }, 'google_translate_element')
    }
</script>
