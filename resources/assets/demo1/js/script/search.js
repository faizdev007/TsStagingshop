jQuery(document).ready(function($) {

    $('select[name=sort]').on('change', function(){
        var dest = $(this).val();
        if (dest) { window.location.replace(dest); }
    });

 /*------------------------------------
        CURRENCY CONVERT ON PRICE GRID
    ------------------------------------*/
 let currency_preference = $('#navbarDropdownCurrency').attr('data-current');
          $('.property-price').each(function() {
              let obj = $(this),
                  conv = obj.data('conversion');

              obj.text(conv[currency_preference]);
          });

          let sc = $('.site_currency'),
              oc = sc.val(),
              conv = sc.data('conversion');

          sc.val(conv[currency_preference]);
          let c = sc.val();

          $('.formatPrice').each(function() {
              $(this).text($(this).text().replace(oc, c));
          });


    /*------------------------------------
        DROPDOWN FORMAT
    ------------------------------------*/
    function formatRepo (repo) {

      if (repo.loading) return repo.name;
       var markup = "<div class='select2-result-repository clearfix'>" +
       "<div class='select2-result-repository__meta'>"
       if (repo.name) {
         markup += "<div class='select2-result-repository__description'><strong>" + repo.name+'</strong>';
       }
       markup += "</div></div>";
       return markup;
     }

    function formatRepoSelection (repo) {
      if (repo.name === undefined) {
           return repo.text;
       } else {
           return repo.name;
       }
    }

    /*------------------------------------
        SELECT 2 - AJAX - LOCATIONS
    ------------------------------------*/
    $(".select-pw-ajax-locations").select2({
        multiple: false,
        minimumInputLength: 1,
        theme:" select2-style-1",
        tags: false,
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/properties/get_locationList",
            dataType: 'json',
            type: 'POST',
            delay: 250,
            data: function (params) {
                var query = {
                  q: params.term,
                  page: params.page || 1,
                  type: 'public'
                }
                return query;
            },
            processResults: function (data, params) {
                //console.log(data.items);
                var query = {
                    results: data.items,
                    pagination: {
                        more: (data.page * 10) < data.total_count
                    }
                }
                return query;
            },
            cache: true,
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });

      /*------------------------------------
        SELECT 2 - AJAX - LOCATIONS
    ------------------------------------*/
    $(".select-pw-ajax-country").select2({
        multiple: false,
        theme:" select2-style-1",
        tags: false,
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/properties/get_countryList",
            dataType: 'json',
            type: 'POST',
            delay: 250,
            data: function (params) {
                var query = {
                  q: params.term,
                  page: params.page || 1,
                  type: 'public'
                }
                return query;
            },
            processResults: function (data, params) {
                //console.log(data.items);
                var query = {
                    results: data.items,
                    pagination: {
                        more: (data.page * 10) < data.total_count
                    }
                }
                
                return query;
            },
            cache: true,
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });

 /*------------------------------------
        SELECT 2 - AJAX - LOCATIONS
    ------------------------------------*/
   // Attach an event listener to the country select dropdown
    $('.locaion_in').on('change', function() {
      var countryId = $(this).val();

      // Make an AJAX request to get the list of states/provinces for the selected country
      $.ajax({
         headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        url: '/properties/get_areaList', // replace with your endpoint to get states/provinces
        type: 'POST', // or 'GET' depending on your server-side implementation
        data: {
          country: countryId
        },
        dataType: 'json',
        success: function(data) {
          // Clear the existing options in the state/province select dropdown
          $('.select-pw-ajax-area').empty();

          // Iterate over the list of states/provinces and add them as options to the select dropdown
           $('.select-pw-ajax-area').append('<option value="">AREA</option>');
          $.each(data, function(index, state) {

            $('.select-pw-ajax-area').append('<option value="' + state.id + '">' + state.name + '</option>');
          });
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    });



    //

    /* Multiple Selection Features1...
    ------------------------------------------*/
    $('.mutliple-selection--attr').each(function() {

        var multipleAttr = $(this).attr('id');
        var multipleObj = $('#'+multipleAttr);
        var ptype = multipleObj.find('.type-select');


        ptype.on("select2:select", function(e) {
            showMultipleSelect(multipleObj, 3);
        });
        ptype.on("select2:selecting", function(e) {
            showMultipleSelect(multipleObj, 1);
        });
        ptype.on("change.select2", function(e) {
            showMultipleSelect(multipleObj);
        });
        ptype.on("select2:unselect", function(e) {
            showMultipleSelect(multipleObj);
        });
        ptype.on("select2:close", function(e) {
            showMultipleSelect(multipleObj, 2);
        });



        multipleObj.find('.multiple-selected label').click(function(){
            ptype.select2('open');
        });

        showMultipleSelect(multipleObj);

    });

    //
	function showMultipleSelect(multipleObj, close){
		var _ptype = multipleObj.find('.type-select');
		var ptypeLi = multipleObj;
		var ptypeLabel = multipleObj.find('.multiple-selected');
		var ptypeCount = 0;
        var vLabel = ptypeLabel.data('label');
		var selectedTypes = _ptype.select2("val");

		ptypeCount = (selectedTypes) ? selectedTypes.length : 0;

        if(ptypeCount > 0 && (vLabel=='Location or MLS ID' || vLabel=='Preferred Location or MLS ID' )){
        //if( (typeof close !== 'undefined' && close==2) && (vLabel=='Location or MLS ID' || vLabel=='Preferred Location or MLS ID' )){
            vLabel = 'Location';
        }
		//ptypeCountTxt = ptypeCount > 1 ? ' '+vLabel+'s selected' : ' '+vLabel+' selected';
        ptypeCountTxt = ptypeCount > 1 ? ' selected' : ' selected';

        if(ptypeCount){
            ptypeLabel.find('label').html(ptypeCount+ptypeCountTxt);
        }else{
            ptypeLabel.find('label').html(vLabel);
        }

        /* This for the dropdown location...
        ------------------------------------------*/
        if(ptypeCount > 0){
            ptypeLi.addClass('has-selected');
        }else{
            ptypeLi.removeClass('has-selected');
        }
	}
});
