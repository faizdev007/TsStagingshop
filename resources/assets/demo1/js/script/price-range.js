// $(function(){
//   /******************************************
//   * PRICE RANGE SEARCH
//   *******************************************/
//  $('.filters-price span').click(function () {
//    $(this).parent('.filters-price').toggleClass('active');
// });

// $('.search-form select').click(function () {
//    $('.filters-price').removeClass('active');
// });

//   var trans = $('select[name="for"]').val();
//   var minval = 0;
//   var maxval = trans == 'rent' ? 40000 : 30000000;
//   var minsel = (typeof $( "#slider-range" ).data('minsel') !== 'undefined') ? $( "#slider-range" ).data('minsel') : 0;
//   var maxsel = (typeof $( "#slider-range" ).data('maxsel') !== 'undefined') ? $( "#slider-range" ).data('maxsel') : maxval;
//   var pr_values;
//   var tenure;

//   /* price range */
//   function render_slider(mode) {
// var rentType = mode;
// if(mode=='shortterm' || mode=='longterm'){
//   mode = 'rent';
// }
// var period = '';
// if(rentType == 'shortterm'){
// var period = 'PW';
// }
// if(rentType == 'longterm'){
// var period = 'PM';
// }
//     //mode = !mode ? 'sale' : 'rent';
//     pr_values = [
//       0, 70000, 80000, 90000, 100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000, 1000000, 1500000, 2000000, 2500000, 3000000, 3500000, 4000000, 5000000,6000000,7000000,8000000,9000000,10000000,20000000,30000000,40000000,50000000,60000000,70000000,80000000,90000000,100000000,200000000,300000000,400000000,500000000
//     ];
//     pr_values_rent = [
//       0, 100,150,200,250,300,350,400,450,500,600,700,800,900,1000,1100,1200,1250,1300,1400,1500,1750,2000,2250,2500,2750,3000,3500,4000,4500,5000,5500,6000,6500,7000,8000,9000,10000,12500,15000,17500,20000,25000,30000,35000,40000,45000

//     ];

//     tenure = mode;

//     pr_values = (tenure == 'rent') ? pr_values_rent :  pr_values;

//     minindex = minval = 0; // (tenure === 'rental') ? 0 : 0;
//     maxindex = maxval = pr_values.length - 1; // (tenure === 'rental') ? 50000 : 30000000;

//     minsel = minval;
//     if (typeof $("#slider-range").data('minsel') !== 'undefined') {
//       levalue = $("#slider-range").data('minsel');
//       minindex = pr_values.indexOf( levalue );
//       if (minindex >= 0) {
//         minsel = pr_values[minindex];
//       }
//     }
//     maxsel = maxval;
//     if (typeof $("#slider-range").data('maxsel') !== 'undefined') {
//       levalue = $("#slider-range").data('maxsel');
//       maxindex = pr_values.indexOf( levalue );
//       maxindex = (maxindex == -1) ? pr_values.length - 1 : maxindex;
//       if (maxindex >= 0) {
//         maxsel = pr_values[maxindex];
//       }
//     }

//     /*$( "#slider-range" ).slider({
//       range: true,
//       min: minval,
//       max: maxval,
//       step: 1,
//       values: [minindex, maxindex],
//       slide: function( event, ui ) {

//         $("#price-range").val( pr_values[ ui.values[0] ]  + "-" + pr_values[ ui.values[1]] );
//         $("#slider-min").val("$"+  pr_values[ ui.values[0] ] + period );
//         $("#slider-max").val("$"+  pr_values[ ui.values[1] ] + period );
//         $(".slider-min").val("$"+  pr_values[ ui.values[0] ] + period );
//         $(".slider-max").val( "$"+ pr_values[ ui.values[1] ] + period );
//         $('.formatPrice').formatCurrency({symbol: 'AED ', colorize: false, negativeFormat: '-%s%n', roundToDecimalPlace: 0 });
//       }
//     });*/

//     $("#slider-min").val((minsel));
//     $("#slider-max").val((maxsel));
//     $(".slider-min").val("$"+(minsel)+ period);
//     $(".slider-max").val("$"+(maxsel)+ period);
//   // $('.formatPrice').formatCurrency({symbol: 'AED ', colorize: false, negativeFormat: '-%s%n', roundToDecimalPlace: 0 });
//   }

//   render_slider(trans);
//   /* end of:: price range */

//   $("#slider-min").val((minsel));
//   $("#slider-max").val((maxsel));
//   $(".slider-min").val("$"+(minsel));
//   $(".slider-max").val("$"+(maxsel));
//  //$('.formatPrice').formatCurrency({symbol: 'AED ', colorize: false, negativeFormat: '-%s%n', roundToDecimalPlace: 0 });

//   /*
//   * Update slider
//   */
//   $('select[name="for"]').change(function(){
//     var mode = $(this).val();

//     $( "#slider-range" ).slider('destroy');

//     render_slider(mode);
//   });

// });

// $(document).mouseup(function(e)
// {
//     var container = $(".price-range-container");

//     // if the target of the click isn't the container nor a descendant of the container
//     if (!container.is(e.target) && container.has(e.target).length === 0)
//     {
//         container.hide();
//     }
// });
