import $ from 'jquery';
window.$ = window.jQuery = $;

$(function () {
// Initialize
var defaults = {
  backFocus : true,
};
// $('[data-fancybox]').fancybox(defaults);
// jQuery.fn.rotate = function(degrees) {
//     $(this).css({'transform' : 'rotate('+ degrees +'deg)'});
//     return $(this);
// };

/*-----------------------------------------
* SUBMENU
-----------------------------------------*/
$(document).ready(function(){
  $(".slideMenu").click(function(){    
    var target = $(this).parent().children(".slideContentMenu");
    $(target).slideToggle();
  });
});

$(".main-nav-dropdown").mouseenter(function(){
    $(this).find('.nav-dropdown-toggle').next('.h-nav-item-sub-1').stop().slideDown(120);
    return false;
}).mouseleave(function(){
    $(this).find('.nav-dropdown-toggle').next('.h-nav-item-sub-1').stop().slideUp(120);
    return false;
});


/*-----------------------------------------
* HEADER
------------------------------------------*/
function headerFix(){
    var headerObj = $('header');
    var headerH = headerObj.height();
    var scroll_top = $(window).scrollTop();
    //console.log(scroll_top);
    if(scroll_top > headerH){
        headerObj.addClass( "scrolled-down" );
    }else{
        headerObj.removeClass( "scrolled-down" );
    }
}
headerFix();
$(window).scroll(function()
{
    headerFix();
});

/*-----------------------------------------
* Mobile Navigation
-----------------------------------------*/
var responsiveRight = $(window).width();
var windowsWidth = $(window).width();

mobileNav();
$( window ).resize(function() {
    windowsWidth = $(window).width();
    mobileNav();
});

function mobileNav(){
    responsiveRight = "-320";
    $( ".mobile-nav-menu .navigation").css({right:responsiveRight+"px"},500);
}

$(".mobile-nav-menu-trigger .burger-icon").click(function(){
    $( ".mobile-nav-menu .navigation").animate({
        right: 0
    }, 800, function() {
        //$(".mobile-nav-menu .background-black").css({display:"block"});
    });
    return false;
});

$(".mobile-nav-menu .mobile-x").click(function(){
    $(".mobile-nav-menu .background-black").animate({"opacity":"-0"},200, function(){
        $(this).css({display:"none"});
        $( ".mobile-nav-menu .navigation").animate({right:parseFloat(responsiveRight)+"px"},500);
    });
    return false;
});

$('.search--read-more').click(function(){
    $('#page-detail').slideDown('slow');
    $('#read-more').slideUp('slow');
});

$('.search--read-less').click(function(){
    $('#page-detail').slideUp('slow');
    $('#read-more').slideDown('slow');
});

/*-----------------------------------------
* SIDEBAR SLIDER - MODAL
-----------------------------------------*/
function slideSidebar(){
    $(".pw-slidebar-attr").click(function(){
        var target = $(this).data('target');
        $(target).addClass('show-slide');
        return false;
    });
    $(".-sidebar-slide-close").click(function(){
        var target = $(this).data('target');
        $(target).removeClass('show-slide');
        return false;
    });
}

slideSidebar();

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
* FadeToggle
-----------------------------------------*/
$('a.fade-toggle').click(function(){
    var thisObj = $(this);
    var target = thisObj.data('target');
    var plusMinus = thisObj.find('.plus-minus').html();
    $(target).fadeToggle(200, function(){
        if(plusMinus == '+'){
            thisObj.find('.plus-minus').html('-');
        }else{
            thisObj.find('.plus-minus').html('+');
        }
    });
    return false;
});


/*-----------------------------------------
* SELECT2
-----------------------------------------*/

$(".select-pw").select2({
    minimumResultsForSearch: Infinity,
    tags: true,
    theme:"select2-style-1",
    dropdownAutoWidth : true,
});

$(".select-pw-mutiple").select2({
    minimumResultsForSearch: Infinity,
    theme: "default multiple select2-style-1",
    multiple: true,
    tags: false,
    closeOnSelect: false,
    dropdownAutoWidth: true
});

$('select[name=category].-blog').on('change', function(){
    var dest = $(this).val();
    if (dest) { window.location.replace(dest); }
});
/*-----------------------------------------
* FOOTER
-----------------------------------------*/

$('.footer-link--header').click(function () {
  var target = $(this).data('target');
  $(target).toggleClass('-open');
});

/*-----------------------------------------
* HERO SLIDER
-----------------------------------------*/
$('.hero-slider-style-1').slick({
    slidesToShow: 1,
    fade: true,
    infinite: true,
    arrows: false,
    dots: false,
    autoplay: true,
    autoplaySpeed: 6000,
    accessibility: false,
});

$('.hero-slider-style-1').on('afterChange', function(event, slick, currentSlide, nextSlide){
  var imgSlider = $('.hero-slider-style-1 .slick-slide.slick-current.slick-active').find('.pw-lazy').data('src');
  $('.hero-slider-style-1 .slick-slide.slick-current.slick-active').find('.pw-lazy').attr('src',imgSlider);
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


/*
-----------------------------------------*/
$(document).on('click', '[data-action="show-signup"]', function () {
    $('#loginblock').removeClass('is-active');
    $('#signupblock').addClass('is-active');
});

$(document).on('click', '[data-action="show-login"]', function () {
    $('#signupblock').removeClass('is-active');
    $('#loginblock').addClass('is-active');
});

/*-----------------------------------------
* MEMBERS - PROPERTY ALERTS
-----------------------------------------*/
$('.edit-alert').on('click', function()
{
    var id = $(this).data('alert-id');

    $( ".update-alert" ).each(function(  )
    {
        $(this).hide();
    });

    $('.create-alert').hide();
    $('.edit-alert-'+id).show();

    return false;
})

/*-----------------------------------------
* Search - FIlter toggle
-----------------------------------------*/
$('.li-filter a').on('click', function()
{
    $('#search-form ul li').toggleClass('show-fields');

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
            '<div class="text-center u-mt05 u-mb05"><button class="button -primary text-uppercase f-bold" type="submit">Save Note</button> </div> ' +
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

/*---------------------------------------------------
* contact us page slider.
----------------------------------------------------*/

$('.contact-slider').slick({
     slidesToShow: 1,
    infinite: true,
    dots: false,
    autoplay: false,
    autoplaySpeed: 6000,
    accessibility: false,
  fade: true,
  cssEase: 'linear',
 arrows: true,
    prevArrow: '<div class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>',
    nextArrow: '<div class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>'
});


// New Listings Slider
$('.listings-slide').slick(
    {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: false,
        arrows: true,
        autoplay:false,
        responsive: [
            {
                breakpoint: 600,
                settings:
                {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings:
                {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    }
)



$('.service-item-logo').slick({
    slidesToShow: 2,
    slidesToScroll: 1,
    mobileFirst: true,
    arrows: false,
    prevArrow   : '<div class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>',
    nextArrow: '<div class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>',
    responsive: [
          {
                  breakpoint: 768,
                  settings: 'unslick'
          }
    ]
  });

$(window).on("load", function(){
    if($(window).width() < 766){
        setTimeout(function(){
            $('.service-item-logo').slick('refresh');
        },500);
    }
}); 
/* about us page team content read more*/
$(document).ready(function() {
  // Hide all 'more' paragraphs on page load
  $('.more').hide();
  
  // Attach a click event listener to all 'read-more' buttons
  $('.read-more').click(function(e) {
    e.preventDefault(); // prevent default link behavior
    
    var $item = $(this).closest('.item'); // get the parent item element
    
    if ($item.hasClass('open')) {
      // If the item is already open, hide the 'more' paragraph and change the button text
      $item.find('.more').slideUp();
      $(this).text('Read more');
      $item.removeClass('open');
    } else {
      // If the item is not open, show the 'more' paragraph and change the button text
      $item.find('.more').slideDown();
      $(this).text('Read less');
      $item.addClass('open');
    }
  });
});
});
