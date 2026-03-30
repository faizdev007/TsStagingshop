$(function(){
  /*--------------------------------------------------
  * PRICE RANGE SEARCH - SUPPORTS MULTIPLE INSTANCE
  --------------------------------------------------*/
  $(".price-slider-attr a").click(function(){
    $(this).next('.price-range-container').slideToggle(100);
    $(this).toggleClass('active');
    return false;
  });

  var sliderOject = $(".price-slider-attr");
  var inputPriceSlider;
  var trans;
  var pr_values;
  var pr_values_rent;
  var currency = $('.settings-currency').val();

  function render_slider(mainId, mode = 'sale', trigger) {

    inputPriceSlider = $("#"+mainId).find('.price-slider');
    var sliderMin = $("#"+mainId).find('.slider-min');
    var sliderMax = $("#"+mainId).find('.slider-max');
    var priceRangeInput = $("#"+mainId).find('.price-range-input');

    var formatPrice = $("#"+mainId).find('.formatPrice');

    var minval = 0;
    var maxval = 500000000;

    if(trigger=='1'){
        sliderMin.html(minval);
        sliderMax.html(maxval);
        inputPriceSlider.data('minsel', minval);
        inputPriceSlider.data('maxsel', maxval)
    }

    var minsel = (typeof inputPriceSlider.data('minsel') !== 'undefined') ? inputPriceSlider.data('minsel') : 0;
    var maxsel = (typeof inputPriceSlider.data('maxsel') !== 'undefined') ? inputPriceSlider.data('maxsel') : 500000000;

    tenure = mode;
    pr_values = [
      0, 50000,60000,70000,80000,90000,100000,110000,120000,125000,130000,140000,150000,160000,170000,175000,180000,190000,200000,210000,220000,230000,240000,250000,260000,270000,280000,290000,300000,325000,350000,375000,400000,425000,450000,475000,500000,550000,600000,650000,700000,800000,900000,1000000,1250000,1500000,1750000,2000000,2500000,3000000,4000000,5000000,7500000,10000000,15000000,20000000,30000000,40000000,50000000,60000000,70000000,80000000,90000000,100000000,200000000,300000000,400000000,500000000
    ];
    pr_values_rent = [
      0, 100, 150, 200, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1250, 1300, 1400, 1500, 1750, 2000, 2250, 2500, 2750, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 8000, 9000, 10000, 12500, 15000, 17500, 20000, 25000, 30000, 35000, 40000, 45000, 50000, 55000, 60000, 65000, 70000, 75000, 80000, 85000, 90000, 95000, 100000, 105000, 110000, 115000, 120000, 125000, 130000, 135000, 140000, 145000, 150000, 155000, 160000, 165000, 170000, 175000, 180000, 185000, 190000, 195000, 200000, 205000, 210000, 215000, 220000, 225000, 230000, 235000, 240000, 245000, 250000, 255000, 260000, 265000, 270000, 275000, 280000, 285000, 290000, 295000, 300000, 305000, 310000, 315000, 320000, 325000, 330000, 335000, 340000, 345000, 350000, 355000, 360000, 365000, 370000, 375000, 380000, 385000, 390000, 395000, 400000, 405000, 410000, 415000, 420000, 425000, 430000, 435000, 440000, 445000, 450000, 455000, 460000, 465000, 470000, 475000, 480000, 485000, 490000, 495000, 500000
    ];

    pr_values = (tenure == 'rent') ? pr_values_rent :  pr_values;

    minindex = minval = 0; // (tenure === 'rental') ? 0 : 0;
    maxindex = maxval = pr_values.length - 1; // (tenure === 'rental') ? 50000 : 30000000;

    minsel = minval;
    if (typeof inputPriceSlider.data('minsel') !== 'undefined') {
      levalue = inputPriceSlider.data('minsel');
      minindex = pr_values.indexOf( levalue );
      if (minindex >= 0) {
        minsel = pr_values[minindex];
      }
    }
    maxsel = maxval;
    if (typeof inputPriceSlider.data('maxsel') !== 'undefined') {
      levalue = inputPriceSlider.data('maxsel');
      maxindex = pr_values.indexOf( levalue );
      maxindex = (maxindex == -1) ? pr_values.length - 1 : maxindex;
      if (maxindex >= 0) {
        maxsel = pr_values[maxindex];
      }
    }

    inputPriceSlider.slider({
      range: true,
      min: minval,
      max: maxval,
      step: 1,
      values: [minindex, maxindex],
      slide: function( event, ui ) {

        priceRangeInput.val(pr_values[ ui.values[0] ] + "-" + pr_values[ ui.values[1] ]);

        sliderMin.html(pr_values[ ui.values[0] ]);
        sliderMax.html(pr_values[ ui.values[1] ]);

        formatPrice.formatCurrency({symbol: currency, colorize: false, negativeFormat: '-%s%n', roundToDecimalPlace: 0 });
      }
    });

    sliderMin.html(currency+(minsel));
    sliderMax.html(currency+(maxsel));

    formatPrice.formatCurrency({symbol: currency, colorize: false, negativeFormat: '-%s%n', roundToDecimalPlace: 0 });

  }


  sliderOject.each(function(e){

    var mainId = ($(this).attr('id'));
    inputPriceSlider = $("#"+mainId).find('.price-slider');

    if ( $('select[name="for"].'+mainId)[0]){
        trans = $('select[name="for"].'+mainId).val();
        render_slider(mainId, trans);
    }

    if ( $('input[name="for"].'+mainId)[0]){
        trans = $('input[name="for"].'+mainId).val();
        render_slider(mainId, trans);
    }

    if ( $('select[name="is_rental"].'+mainId)[0]){
        trans2 = $('select[name="is_rental"].'+mainId).val();
        trans2 = (trans2 == 1) ? 'rent' : 'sale';
        render_slider(mainId, trans2);
    }

    if ( $('input[name="is_rental"].'+mainId)[0]){
        trans2 = $('input[name="is_rental"].'+mainId).val();
        trans2 = (trans2 == 1) ? 'rent' : 'sale';
        render_slider(mainId, trans2);
    }


    /*----------------------------------------------------------------------------------
    * Update slider : Be sure to add the id as a class in the trigger(for field)...
    ------------------------------------------------------------------------------------*/
    $('select[name="for"].'+mainId).change(function(){
      var mode = $(this).val();
      render_slider(mainId, mode, '1');
      $('.price-range-input').val('');
    });

    $('select[name="is_rental"].'+mainId).change(function(){
      var mode = $(this).val();
      if(mode ==1){
          mode='rent';
      }else{
           mode='sale';
      }
      render_slider(mainId, mode);
    });

  });


});
