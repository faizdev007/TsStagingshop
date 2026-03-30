// $(function(){
//   /*--------------------------------------------------------
//   * PRICE RANGE SEARCH - SUPPORTS MULTIPLE INSTANCE
//   --------------------------------------------------------*/
//   $(".price-slider-attr a.price_toggle").click(function(e){
//     e.preventDefault();
//     //alert('test');
//     $(this).next('.price-range-container').slideToggle(100);
//     $(this).toggleClass('active');
//     $(this).prev('.select2-container').toggleClass('select2-container--open');
//     return false;
//   });

//   $("html").click(function(){
//     $('.price-range-container').slideUp(100, function(){
//        $(this).prev('.price_toggle').prev('.select2-container').removeClass('select2-container--open');
//     });
//   });

//   var sliderOject = $(".price-slider-attr");
//   var inputPriceSlider;
//   var trans;
//   var trans2;
//   var pr_values;
//   var pr_values_sale;
//   var pr_values_rent;
//   var currency = $('#header').data('currency');

//   function render_slider(mainId, mode = 'sale') {

//     inputPriceSlider = $("#"+mainId).find('.price-slider');
//     var sliderMin = $("#"+mainId).find('.slider-min');
//     var sliderMax = $("#"+mainId).find('.slider-max');
//     var priceRangeInput = $("#"+mainId).find('.price-range-input');
//     var formatPrice = $("#"+mainId).find('.formatPrice');

//     var minsel = (typeof inputPriceSlider.data('minsel') !== 'undefined') ? inputPriceSlider.data('minsel') : 0;
//     var maxsel = (typeof inputPriceSlider.data('maxsel') !== 'undefined') ? inputPriceSlider.data('maxsel') : 20000000;

//     var minval = 0;
//     var maxval = 20000000;

//     tenure = mode;
//     pr_values = [
//       0, 50000,60000,70000,80000,90000,100000,110000,120000,125000,130000,140000,150000,160000,170000,175000,180000,190000,200000,210000,220000,230000,240000,250000,260000,270000,280000,290000,300000,325000,350000,375000,400000,425000,450000,475000,500000,550000,600000,650000,700000,800000,900000,1000000,1250000,1500000,1750000,2000000,2500000,3000000,4000000,5000000,7500000,10000000,15000000,20000000
//     ];
//     pr_values_sale = pr_values;
//     pr_values_rent = [
//       0, 100,150,200,250,300,350,400,450,500,600,700,800,900,1000,1100,1200,1250,1300,1400,1500,1750,2000,2250,2500,2750,3000,3500,4000,4500,5000,5500,6000,6500,7000,8000,9000,10000,12500,15000,17500,20000,25000,30000,35000,40000,45000
//     ];

//     pr_values = (tenure == 'rent') ? pr_values_rent :  pr_values;

//     minindex = minval = 0; // (tenure === 'rental') ? 0 : 0;
//     maxindex = maxval = pr_values.length - 1; // (tenure === 'rental') ? 50000 : 30000000;

//     minsel = minval;
//     if (typeof inputPriceSlider.data('minsel') !== 'undefined') {
//       levalue = inputPriceSlider.data('minsel');
//       minindex = pr_values.indexOf( levalue );
//       if (minindex >= 0) {
//         minsel = pr_values[minindex];
//       }
//     }
//     maxsel = maxval;
//     if (typeof inputPriceSlider.data('maxsel') !== 'undefined') {
//       levalue = inputPriceSlider.data('maxsel');
//       maxindex = pr_values.indexOf( levalue );
//       maxindex = (maxindex == -1) ? pr_values.length - 1 : maxindex;
//       if (maxindex >= 0) {
//         maxsel = pr_values[maxindex];
//       }
//     }

//     inputPriceSlider.slider({
//       range: true,
//       min: minval,
//       max: maxval,
//       step: 1,
//       values: [minindex, maxindex],
//       slide: function( event, ui ) {

//           /* Fixed bug when on multiple tenure... Jan:2020-02-27*/
//           trans1a = $('select[name="for"].'+mainId).val();
//           trans1b = $('input[name="for"].'+mainId).val();
//           trans2a = $('select[name="is_rental"].'+mainId).val();
//           trans2b = $('input[name="is_rental"].'+mainId).val();

//           if(
//               trans1a == 'sale' || trans1b == 'sale' ||
//               trans2a == 0 || trans2b == 0
//           ){
//               pr_values = pr_values_sale;
//           }else{
//               pr_values = pr_values_rent;
//           }
//           /* -end fixed */

//         priceRangeInput.val(pr_values[ ui.values[0] ] + "-" + pr_values[ ui.values[1] ]);

//         sliderMin.html(pr_values[ ui.values[0] ]);
//         sliderMax.html(pr_values[ ui.values[1] ]);

//         formatPrice.formatCurrency({symbol: currency, colorize: false, negativeFormat: '-%s%n', roundToDecimalPlace: 0 });
//       }
//     });

//     sliderMin.html(currency+(minsel));
//     sliderMax.html(currency+(maxsel));

//     formatPrice.formatCurrency({symbol: currency, colorize: false, negativeFormat: '-%s%n', roundToDecimalPlace: 0 });

//   }


//   sliderOject.each(function(e){

//     var mainId = ($(this).attr('id'));

//     inputPriceSlider = $("#"+mainId).find('.price-slider');

//     if ( $('select[name="for"].'+mainId)[0]){
//         trans = $('select[name="for"].'+mainId).val();
//         render_slider(mainId, trans);
//     }

//     if ( $('input[name="for"].'+mainId)[0]){
//         trans = $('input[name="for"].'+mainId).val();
//         render_slider(mainId, trans);
//     }

//     if ( $('select[name="is_rental"].'+mainId)[0]){
//         trans2 = $('select[name="is_rental"].'+mainId).val();
//         trans2 = (trans2 == 1) ? 'rent' : 'sale';
//         render_slider(mainId, trans2);
//     }

//     if ( $('input[name="is_rental"].'+mainId)[0]){
//         trans2 = $('input[name="is_rental"].'+mainId).val();
//         trans2 = (trans2 == 1) ? 'rent' : 'sale';
//         render_slider(mainId, trans2);
//     }

//     /*----------------------------------------------------------------------------------
//     * Update slider : Be sure to add the id as a class in the trigger(for field)...
//     ------------------------------------------------------------------------------------*/

//     $('select[name="for"].'+mainId).change(function(){
//       var mode = $(this).val();
//       render_slider(mainId, mode);
//     });

//     $('select[name="is_rental"].'+mainId).change(function(){
//       var mode = $(this).val();
//       if(mode ==1){
//           mode='rent';
//       }else{
//            mode='sale';
//       }
//       render_slider(mainId, mode);
//     });

//   });


// });
