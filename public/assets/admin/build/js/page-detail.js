$(function(){

    /*---------------------------------------
    *  AJAX JSON
    ----------------------------------------*/
    function getData(json_url, callback) {
        $.ajax({
            dataType: "json",
            url: json_url,
            success:callback,
            error: function(request, status, error) {
                //alert(status);
            }
        });
    }





    /*---------------------------------------
    *  SHOW/HIDE fields base on type
    ----------------------------------------*/
    var mode_val = $('#id-mode').val();
    modeSettings(mode_val);

    $('#id-mode').change(function(){
        var catClass = $(this).data('category-attr');
        modeSettings($(this).val());
    });

    function modeSettings(mode_val){
        $('.for-rent').addClass('hide');
        $('.for-sale').addClass('hide');
        if(mode_val == 1){ //Rent
            $('.for-rent').removeClass('hide');
        }
        if(mode_val == 0){ //Sale
            $('.for-sale').removeClass('hide');
        }
    }

    /* category
    ----------------------------------------*/

    $('.mode-attr').change(function(){
        var modeId = $(this).attr('id');
        catSettings(modeId);
    });
    var modeId = $('.mode-attr').attr('id');
    catSettings(modeId);

    function catSettings(modeId){

        var mode_val = $('#'+modeId).val();
        var catClass = $('#'+modeId).data('category');;
        var site_url = $('.site-url').val();
        var selectedVal = $(catClass).data('val');

        if (typeof selectedVal === "undefined") {
             selectedVal = '';
        }

        $(catClass+'-'+'field').addClass('-disable');

        getData(site_url+'/json/get?category_type=1', function(response) {
            cat_values = response.sale;
            cat_values_rent = response.rent;

            if(mode_val == 'sale' || mode_val == 0){
                var dataOptions = '';
                $.each(cat_values, function( index, value ) {
                    var selected = ((index)===selectedVal)?'selected':'';
                    dataOptions = dataOptions+'<option value='+index+' '+selected+'><span>'+value+'</span></option>';
                });
            }

            if(mode_val == 'rent' || mode_val == 1){
                var dataOptions = '';
                $.each(cat_values_rent, function( index, value ) {;
                    var selected = ((index)===selectedVal)?'selected':'';
                    dataOptions = dataOptions+'<option value='+index+' '+selected+'><span>'+value+'</span></option>';
                });
            }

            $(catClass).html(dataOptions);
            $(catClass+'-'+'field').removeClass('-disable');

        });
    }

    /*---------------------------------------
    *  Property change statu to sold
    ----------------------------------------*/
    $('#id-property-status').change(function(){
        var status =  $(this).val();
        if(status=='Sold'){
            alertify.confirm('Would you like to set this property as inactive?',
            function(){
                $("#id-status").val('-1').trigger('change');
            });
        }
    });

  // Attach change event listener to the first Select2 dropdown
  $('#id-status').change(function(){
        var status =  $(this).val();
    // Status is changed to Price on Application then the Price qualifier automatically changes,
    if (status == 10) {
      $('.price_qualifier').val('POA').trigger('change');
    } 
  });


});
