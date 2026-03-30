$(function(){

    /*
    * Event listeners
    */

    // keydown or typing... FOR ONCHANGE
    $('.stmp-calculator--trigger').on('keydown', function () {
        //applied delay to avoid consicutive request...
        var obj = $(this);
        delay1(function ()
        {
          trigger_stmp_calculator(obj);
        });
    });

    // keyup or typing... FOR COPY&PASTE
    $('.stmp-calculator--trigger').on('keyup', function () {
        //applied delay to avoid consicutive request...
        var obj = $(this);
        delay1(function ()
        {
          trigger_stmp_calculator(obj);
        });
    });

    //click tabs...
    $('.stmp-calculator--tab').click(function(){
        var calc_type = $(this).data('type');
        var obj = $('.stmp-calculator--trigger');
        obj.removeClass('single');
        obj.removeClass('additional');
        obj.addClass(calc_type);
        val = obj.val();
        if(val != ''){
            stmp_calculator(obj, calc_type);
        }
    });

    var objCheck = $('.stmp-calculator--trigger.active');
    if( objCheck.val() != '' ){
        $(".stmp-calculator--tab.active").trigger("click");
    }

    /*
    * Trigger...
    */
    function trigger_stmp_calculator(obj){
        var calc_type = 'single';
        if(obj.hasClass('single')){
            calc_type = 'single';
        }
        if(obj.hasClass('additional')){
            calc_type = 'additional';
        }
        //console.log(calc_type+'-keydown');
        stmp_calculator(obj, calc_type);
    }

    /*
    * Stamp Duty Calulations...
    */
    function stmp_calculator(obj, calc_type){

        //Animations...
        obj.prop("disabled", true);
        $('.stmp-calculator--trigger-container').addClass("input-loading");

        //Caculations...
        var _price = obj.val();
        var data = {
            'price': _price,
            'calc_type': calc_type,
        };

        // execute ajax
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType: "json",
            url:'/stamp-duty-calculator',
            data:data,
            success: function(e){
                //console.log(e);
                //Estimated result
                $('.stmp--estimated-result').html(e.total_display); //e.total_display

                // Price...
                $('.stmp--property-price').html(e.amount); //e.amount

                // Update table tax value...
                $('.stmp--tax-table-results').html(e.rows);

                // effective value
                $('.value-smpt-effective').html(e.effective_rate);

                //Show the result
                $('.stmp--tax-table-results-container').removeClass('d-none');

                //enable back
                obj.prop("disabled", false);
                $('.stmp-calculator--trigger-container').removeClass("input-loading");
            }
        });
    }

    /*--------------------------------------
    * Delay functions
    ---------------------------------------*/
    var _time = 2000;

    var delay1 = (function () {
      var timer = 0;
      return function (callback) {
          clearTimeout(timer);
          timer = setTimeout(callback, _time);
      };
    })()

    var delay2 = (function () {
      var timer = 0;
      return function (callback) {
          clearTimeout(timer);
          timer = setTimeout(callback, _time+200);
      };
    })()

    var delay3 = (function () {
      var timer = 0;
      return function (callback) {
          clearTimeout(timer);
          timer = setTimeout(callback, 200);
      };
    })();

    /*--------------------------------------
    * Format price input during typing
    ---------------------------------------*/
    var formatPriceInput = function()
    {

      $(".price--format").each(function()
      {
        $(this).on('keyup click change paste input', function (event)
        {
          var val = $(this).val();
          if(val !== '')
          {
              formatNumber($(this));
          }
        });
        formatNumber($(this));
      });

      function formatNumber(_input) {

        _input.val(function (index, value) {

          // remove currency

          // clear table data
          if (value != "") {
              //return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              var decimalCount;
              value.match(/\./g) === null ? decimalCount = 0 : decimalCount = value.match(/\./g);

              if (decimalCount.length > 1) {
                  value = value.slice(0, -1);
              }

              var components = value.toString().split(".");
              if (components.length === 1)
                components[0] = value;
                components[0] = components[0].replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
              if (components.length === 2) {
                components[1] = components[1].replace(/\D/g, '').replace(/^\d{3}$/, '');
              }

              if (components.join('.') != ''){
                var n = components.join('.');
                var m = n.replace(/^0+/, '');

                //_input.closest('span').addClass('show-currency');

                return m;
              }else{
                return '';
              }
          }
        });
      }
    }
    formatPriceInput();
});
