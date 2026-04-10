import $ from 'jquery';
window.$ = window.jQuery = $;

import Blazy from 'blazy';

$(function () {
window.mobileCheck = function() {
    let check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
};
  

/* Home Slide
------------------------------------------  */
var time = 5;
var $bar,
    $slick,
    isPause,
    tick,
    percentTime;


$slick = $('.home-slider--slick');

// $slick.on('init', function(event, slick){
//     $('.home-slider--controller').append(`
//         <li class="d-none d-sm-inline-block home-slider--arrow-left"><button style="border:none;" class="btn home-prev" aria-label="Previous slide" tabindex="0"></button></li>
//         <li class="d-none d-sm-inline-block home-slider--arrow-right"><button style="border:none;" class="btn home-next" aria-label="Next slide" tabindex="0"></button></li>
//     `)
// });
if(!window.mobileCheck()) {
    $slick.slick({
         lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        speed: 1500,
        dots: true,
        appendDots: $('.home-slider--controllers'),
        dotsClass: 'list-unstyled home-slider--controller order-1',
        customPaging: function(slick, index) {
            let active = index == 0 ? 'active' : '';
            return `<a class="home-slider--dot slider-progress" aria-label="Go to slide ${index + 1}"><span class="progress ${active}"></span></a>`;
        }})
        .on('beforeChange', function($event, {
        slideCount: count  
        }, currentSlide, nextSlide){
            let currentProgress = $('.home-slider--controller li').eq(currentSlide);
            let nextProgress = $('.home-slider--controller li').eq(nextSlide);
            currentProgress.find('span').removeClass('active');
            nextProgress.find('span').addClass('active');
            

            let newSlide = $('.slide').eq(nextSlide);
            let newText = newSlide.find('img').attr('alt');
            let newLink = newSlide.find('a').attr('href');

            $('.home-slider--title').attr('onclick', `window.location.href='${newLink}'`);

            $('.home-slider--subtitle a').attr('href', newLink).text(newText);

            resetProgressbar();
            $bar = $('.slider-progress .progress.active');
            startProgressbar();
        });
} else {
    $slick.slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        speed: 1500,
        dots: false
    });
}

var bLazy = new Blazy({
  selector: '.b-lazy',
});

 $slick.on('afterChange', bLazy.revalidate);

//Controller
$('.home-prev').click(function(){
    $slick.slick("slickPrev");
});

$('.home-next').click(function(){
    $slick.slick("slickNext");
});


//animation
$bar = $('.slider-progress .progress.active');

function startProgressbar() {
    percentTime = 0;
    isPause = false;
    tick = setInterval(interval, 10);
}

function interval() {
if(isPause === false) {
    percentTime += 1 / (time+0.1);
    $bar.css({
        width: percentTime+"%"
    });
    if(percentTime >= 100)
    {
        $slick.slick('slickNext');
    }
}
}


function resetProgressbar() {
    $bar.css({
        width: 0+'%'
    });
    clearTimeout(tick);
}

startProgressbar();




/* Testimonials Slide
------------------------------------------  */
$('.testimonials--slick').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    fade: true,
     arrows: true,
    prevArrow: $('#prev-testimonial'),
    nextArrow: $('#next-testimonial')
});


/* Property grid Slide
------------------------------------------  */
$('.property-grid-thumb--slick').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    fade: false,
     lazyLoad: 'ondemand',
});

var bLazy = new Blazy({
    container: '.property-grid-thumb--slick',
    selector: '.b-lazy-slick',
    error: function(ele, msg){
        if(msg === 'missing'){
            ele.src = 'public/assets/demo1/images/placeholder/large.jpg'; // set placeholder image source if data-src is missing
        }
        else if(msg === 'invalid'){
            ele.src = 'public/assets/demo1/images/placeholder/large.jpg'; // set placeholder image source if data-src is invalid
        }
    }
});

$('.property-grid-thumb--slick').on('afterChange', function () {
    console.log('foo bar baz qux')
    bLazy.revalidate();
});

/* ** Property Details Slider ** */

function initSlider() {

    const $rootSingle = $('.cSlider--single');
    const $rootNav = $('.cSlider--nav');
    
    if (!$rootSingle.length || !$rootNav.length) {
        console.warn("Slider elements not found");
        return;
    }

    // prevent double init
    if ($rootSingle.hasClass('slick-initialized')) return;

    $rootSingle.slick({
        slidesToShow: 1,
        arrows: true,
        infinite: false,
    });

    $rootNav.slick({
        slidesToShow: 4,
        arrows: false,
        infinite: false,
    });

}

$(function () {
    if ($('.cSlider--single').length && $('.cSlider--nav').length) {
        initSlider();
    }
});

/* ** End Property Details Slider ** */

/* ** Similer Property ** */
$(document).ready(function(){
    $('.propery-sim-slide').slick({
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        swipeToSlide: true,
        swipe: true,
        arrows: true,
        infinite: true,
        // autoplaySpeed: 2000,
        cssEase: 'ease-in-out',
        // autoplay: true,
        speed:500
    });
});
/* ** End Similer Property ** */
});
