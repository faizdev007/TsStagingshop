$(function(){
    var base_url = $('.base-url').val();

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
        SELECT 2 - AJAX - AGENT
    ------------------------------------*/
    $(".select-pw-ajax-agent").select2({
        multiple: false,
        //minimumInputLength: 1,
        //maximumSelectionLength: 1,
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url+"/users/get/agents",
            dataType: 'json',
            type: 'POST',
            delay: 250,
            data: function (params) {
                //console.log(params);
                var query = {
                  q: params.term,
                  page: params.page || 1,
                  type: 'public'
                }
                return query;
                /*
                return {
                  q: params.term, // search term
                  page: params.page
                };
                */
            },
            /*
            processResults: function(data) {
                console.log(data);
                return {
                    results: data.items
                };
            },
            */
            processResults: function (data, params) {
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
        SELECT 2 - AJAX - LOCATIONs
    ------------------------------------*/
    $(".select-pw-ajax-locations").select2({
        minimumInputLength: 1,
        tags: false,
        ajax: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url+"/properties/get/locations",
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


});
