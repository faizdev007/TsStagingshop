<script src="{{url('/assets/js/my.min.js')}}" type="text/javascript"></script>


<script src="{{url('/assets/js/slick2.min.js')}}"></script>
<script>
    $(function () {
        $('.slideforme').slick2({
            dots: true,
            draggable: false,
            speed: 300,
            slidesToShow: 1
        })
        $('.slick2-arrow').remove()
    })
</script>


<script src="{{ asset('assets/js/intlTelInput-jquery.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // jQuery
        $('[name="phone"]').intlTelInput({
            allowExtensions: true,
            autoFormat: false,
            autoHideDialCode: false,
            autoPlaceholder: false,
            defaultCountry: "auto",
            ipinfoToken: "yolo",
            nationalMode: false,
            numberType: "MOBILE",
            preventInvalidNumbers: true,
            utilsScript: "{{ asset('assets/js/intlTelInput-jquery.min.js') }}",
        })
    })
</script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>
<script>
    var list
    list = document.querySelectorAll('img')
    console.log(list)
    for (var i = 0; i < list.length; ++i) {
        list[i].classList.add('lozad')
    }
    const observer = lozad() // lazy loads elements with default selector as '.lozad'
    observer.observe()
</script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: !1
        }, 'google_translate_element')
    }

    jQuery(document).ready(function () {
        $(window).width() > 800 ? $('.filter-show').length > 0 && showFilter() : $('#laxury-message').slideToggle('slow')
        $('#property-search-form option').each(function () {
            if (!($(this).attr('value').length > 0)) {
                $(this).addClass('bold-option')
            }

            $(this).text($(this).text().toUpperCase())
        })
    })

    function showFilter() {
        $('#filter-form').slideToggle('slow'), $('#laxury-message').slideToggle('slow')
    }

    function senden() {
        if (jQuery('#MyForm').validate({
            rules: {
                name: 'required',
                email: {
                    required: !0,
                    email: !0
                },
                phone: 'required',
                subject: 'required',
                message: 'required'
            },
            messages: {
                name: 'Name is required.',
                email: {
                    required: 'Email is required.',
                    email: 'Please enter valid email.'
                },
                phone: 'Phone is required',
                subject: 'Subject is required',
                message: 'Message is required'
            }
        }), !jQuery('#MyForm').valid()) {
            return !1
        }
        jQuery('#enquery_now_new').prop('disabled', !0)
        var e = $('#MyForm').serialize(), r = $('#MyForm').attr('action')
        $.ajax({
            url: r,
            type: 'POST',
            data: e,
            success: function (e) {
                jQuery('#MyForm').hide(), jQuery('.succemsg').html(e), jQuery('#enquery_now').prop('disabled', !1)
            }
        })
    }

    jQuery(document).ready(function () {
        $(window).width() > 800 ? $('.filter-show').length > 0 && showFilter() : $('#laxury-message').slideToggle('slow')
        $('#property-search-form option').each(function () {
            if (!($(this).attr('value').length > 0)) {
                $(this).addClass('bold-option')
            }
            $(this).text($(this).text().toUpperCase())
        })
    })

    function searchbuyrentchange() {
        if ($('#action').val() == 'rent') {
            $('#property-price').html('<option value="" selected="selected">PRICE</option><option value="daily">DAILY</option><option value="daily-5-10">5-10K THB</option><option value="daily-10-20">10-20K THB</option><option value="daily-20-30">20-30K THB</option><option value="daily-30-50">30-50K THB</option><option value="daily->50">&gt;50K THB</option><option value="monthly">MONTHLY</option><option value="monthly-30-50">30-50K THB</option><option value="monthly-50-70">50-70K THB</option><option value="monthly-70-100">70 -100K THB</option><option value="monthly-100-150">100 -150K THB</option><option value="monthly->150">&gt;150K THB</option>')
        } else {
            $('#property-price').html('<option value="">PRICE</option><option value=" ">ANY</option><option value="<5"><5M THB</option><option value="5-10">5 - 10M THB</option><option value="10-20">10 - 20M THB</option><option value="20-30">20 - 30M THB</option><option value="30-50">30 - 50M THB</option><option value="50-100">50 - 100M THB</option><option value=">100">&gt;100M THB</option>')
        }
    }

    function showSubmenu(menu) {
        if ($(menu).parent().find('ul').is(':visible')) {
            $(menu).parent().parent().find('ul').slideUp('slow')
        } else {
            if ($(menu).parent().parent().find('ul').is(':visible')) {
                $(menu).parent().parent().find('ul').slideUp('slow')
            }
            $(menu).parent().find('ul').toggle()
        }
    }

    function showDetail() {
        $('#page-detail').slideDown('slow')
        $('#read-more').slideUp('slow')
    }

    function hideDetail() {
        $('#page-detail').slideUp('slow')
        $('#read-more').slideDown('slow')
    }
</script>

@include('partials.google-analytics')
