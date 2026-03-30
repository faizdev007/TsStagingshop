// Initialize
var bLazy = new Blazy();

var defaults = {
  backFocus : true,
};
$('[data-fancybox]').fancybox(defaults);
$('[data-toggle="tooltip"]').tooltip();

/*-----------------------------------------
* SUBMENU
-----------------------------------------*/
$(".main-menu-container").mouseenter(function(){
    $(this).find('.main-menu').next('.sub-menu').stop().slideDown(120);
    return false;
}).mouseleave(function(){
    $(this).find('.main-menu').next('.sub-menu').stop().slideUp(120);
    return false;
});

/*-----------------------------------------
* Mobile Navigation
-----------------------------------------*/
var responsiveRight = $(window).width();

mobileNav();
$( window ).resize(function() {
  responsiveRight = $(window).width();
  mobileNav();
});

function mobileNav(){
  if(responsiveRight > "991"){
    responsiveRight = "-400";
  }else{
    responsiveRight = parseInt("-"+responsiveRight);
    $(".mobile-nav-menu .navigation").css({"right":responsiveRight+"px"});
    $(".mobile-nav-menu .background-black").css({"opacity":"0",display:"none"});
  }
}
$(".mobile-nav-menu .burger-icon").click(function(){
  $( ".mobile-nav-menu .navigation").animate({
    right: "0"
  }, 200, function() {
    $(".mobile-nav-menu .background-black").css({display:"block"});
    $(".mobile-nav-menu .background-black").animate({"opacity":"1"},200);
  });
  return false;
});

$(".mobile-nav-menu .mobile-x").click(function(){
  $(".mobile-nav-menu .background-black").animate({"opacity":"-0"},200, function(){
    $(this).css({display:"none"});
    $( ".mobile-nav-menu .navigation").animate({right:responsiveRight+"px"},200);
  });
  return false;
});

/*-----------------------------------------
* Scroll Animate
-----------------------------------------*/
$('a.linkSlide').click(function(){
  $('html, body').animate({
    scrollTop: $( $.attr(this, 'href') ).offset().top - 100
  }, 800);
  return false;
});


/*-----------------------------------------
* SELECT2
-----------------------------------------*/
$(".select-pw").select2({
  minimumResultsForSearch: Infinity,
  tags: true,
  dropdownAutoWidth : true,
});

/*-----------------------------------------
* HERO SLIDER
-----------------------------------------*/
$('.hero-slider-style-1').slick({
    slidesToShow: 1,
    infinite: true,
    arrows: false,
    dots: true,
    autoplay: true,
    autoplaySpeed: 6000,
    accessibility: false,
});

/*-----------------------------------------
* GRID PROPERTY SLICK
-----------------------------------------*/
$('.grid-image').slick({
    slidesToShow: 1,
    infinite: true,
    arrows: true,
    dots: false,
    autoplay: false,
    autoplaySpeed: 6000,
    accessibility: false,
});

/*-----------------------------------------
* TESTIMONIALS
-----------------------------------------*/
$('.testimonial-slider').slick({
    slidesToShow: 1,
    infinite: true,
    arrows: true,
    dots: true,
    autoplay: true,
    autoplaySpeed: 6000,
    accessibility: false,
    adaptiveHeight: true,

    appendDots: $(".slide-m-dots"),
    prevArrow: $(".slide-m-prev"),
    nextArrow: $(".slide-m-next")
});

/*-----------------------------------------
* MEMBERS - PROPERTY ALERTS
-----------------------------------------*/
$('.edit-alert').on('click', function()
{
    var id = $(this).data('alert-id');

    alert(id);

    $( ".update-alert" ).each(function(  )
    {
        $(this).hide();
    });

    $('.create-alert').hide();
    $('.edit-alert-'+id).show();

    return false;
})

/*---------------------------------------------------
* MEMBER NOTES (DEMO SPECIFIC DUE TO LOOK / FEEL...
----------------------------------------------------*/

$('.modal-toggle').click(function()
{
    var modal_size = $(this).data('modal-size');
    var modal_title = $(this).data('modal-title');
    var modal_type = $(this).data('modal-type');
    var item_id = $(this).data('item-id');
    var modal_message = $(this).data('modal-message');
    var item = $(this).data('item');
    var property_id = $('.property_id').val();

    if (modal_size == 'small')
    {
        $('#global-modal .modal-dialog').addClass('modal-sm');
    }

    $('#global-modal .modal-title').html(modal_title);

    if(modal_type == 'create-note')
    {
        $('#global-modal .modal-footer').hide();
        $('#global-modal .modal-body').html('<div class="popup-note"> <form class="member-note-form" data-toggle="validator">' +
            '<div class="form-group">' +
            '<label class="form__label u-block">Note Content</label>' +
            '<textarea class="form__input u-fullwidth u-p05 note-content u-no-resize -larger" required></textarea>' +
            '<span class="glyphicon form-control-feedback" aria-hidden="true"></span>' +
            '</div>' +
            '<div class="text-center u-mt05 u-mb05"><button class="button -primary f-two text-uppercase f-bold" type="submit">Save Note</button> </div> ' +
            '</form>' +
            '</div></div> ');

        // See if there's any existing notes for this user...
        $.ajax(
            {
                type: 'GET',
                url: '/account/get-user-notes/'+property_id,
                dataType: 'json',
                success: function( data )
                {
                    if(data.url)
                    {
                        // Possibly not needed, but in as a fallback....
                        window.location.replace(data.url);
                    }
                    else
                    {
                        if(data.notes == true)
                        {
                            $('.popup-note').prepend('<p>You have notes saved for this property <a href="/account/notes" class="c-secondary f-bold">View them now</a></p>')
                        }
                    }
                }
            }
        )
    }

    $('.member-note-form').on('submit', function()
    {
        var note_content = $('.note-content').val();

        if(note_content != '')
        {
            $.ajax(
                {
                    headers:
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    type: 'POST',
                    url: '/account/notes',
                    data: { note_content: note_content, property_id: property_id  },
                    success: function ( data )
                    {
                        $('.member-notify').fadeIn();
                        $('.member-notify .alert-heading').html(data.message);

                        setTimeout(function()
                        {
                            $('.member-notify').fadeOut();
                        }, 5000) // 5 Seconds...

                        // Close Modal
                        $('#global-modal').modal('hide');
                    }
                }
            )
        }
        else
        {
            alert("Please fill in some data!");
        }

        return false;
    })

})
