import $ from 'jquery';
window.$ = window.jQuery = $;

$(function(){
    /*------------------------------------------------------
    * PROPERTY
    -------------------------------------------------------*/
    var $propertyImageSlide = '.propertySlider-style-1';
    $($propertyImageSlide).slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      fade: true,
      adaptiveHeight: false,
      asNavFor: '.propertySliderNav-style-1'
    });

    $('.propertySliderNav-style-1').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.propertySlider-style-1',
        dots: false,
        centerMode: false,
        arrows: false,
        focusOnSelect: true,
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 4
            }
          },
          {
              breakpoint: 992,
              settings: {
                slidesToShow: 5
            }
          },
          {
              breakpoint: 768,
              settings: {
                slidesToShow: 4
            }
          },
          {
              breakpoint: 501,
              settings: {
                slidesToShow: 3
            }
          }
        ]
    });

    $($propertyImageSlide).on('afterChange', function(event, slick, currentSlide, nextSlide){
      var imgSlider = $($propertyImageSlide+' .slick-slide.slick-current.slick-active').find('.pw-lazy').data('src');
      $($propertyImageSlide+' .slick-slide.slick-current.slick-active').find('.pw-lazy').attr('src',imgSlider);
    });

    /*------------------------------
    * STIICKY
    *------------------------------*/
   
    /*------------------------------
    * CURRENCY
    *------------------------------*/
    $('.-currency-js-change').click(function(){
        var currency = $(this).data('currency');
        var price = $(this).data('price');
        $('.-currency-js-change').removeClass('-active');
        $(this).addClass('-active');
        $('.-js-price-display').html(price);
        $('.currency-current').html($(this).text());
        $('.property-currency-item').removeClass('show');
        $('.dropdown-menu').removeClass('show');
         
        return false;
    });


    /*------------------------------
    * PROPERTY MORTGAGE
    *------------------------------*/
    function ajaxFormMortgage(id){
        var formObject = $("#ajax-form-"+id);
        formObject.submit(function(){
            var data = $(this).serialize();
            var url = $(this).attr("action");
            var button = $("#btn-"+id).html();
            $("#btn-"+id).html('Loading...');
            $('.mortgage-result-wrap').addClass('d-none');
            $.ajax({
                type: "POST",
                dataType: "json",
                url: url,
                data:data,
                success: function(e){
                  $("#response-"+id).html(e.alert);
                  console.log(e);
                  if(e.output){
                    $("#mortgage_total").html(e.output);
                    $('.mortgage-result-wrap').removeClass('d-none');
                  }

                  $("#btn-"+id).html(button);
                }
            });
            return false;
        });
    }
    /** AJAX FORM **/
    ajaxFormMortgage("mortgage");

});
