// Moved to = shared/js/shortlist.js

$(function(){

    $('.shortlist-add').on('click', function(e)
    {
        e.preventDefault();
        e.stopPropagation();

        var property_id = $(this).data('propertyId');
        var saveText = $(this).data('saveText');
        var removeText = $(this).data('removeText');
        var url = $(this).data('url');
        var item = $(this);
        var parent_div = $('#p-'+property_id);

        var check_confirm = true;

        // if( $(item).hasClass('shortlist-confirm-action') )
        // {
        //     var check_confirm = confirm ('Are you sure you want to remove?');
        // }

        if(check_confirm == true)
        {
            // $(this).html('Saving...');

            $.ajax(
                {
                    headers:
                    {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    dataType: 'json',
                    url: url,
                    data: { property_id: property_id },
                    success: function(e)
                    {
                        $(item).html(''); // Clear...

                        var flag = parseInt(e.flag);

                        if(flag == 1)
                        {
                            // $(item).html('<i class="fas fa-check"></i> <span class="shortlist-text-label text-uppercase">Saved</span>');
                            $(item).html('<i class="fas fa-heart"></i>');

                            // // Set Timeout...
                            // setTimeout(function()
                            // {
                            // }, 2000) // 2 Seconds...

                            $(item).addClass('shortlist-confirm-action');
                        }
                        else
                        {
                            // $(item).html('<i class="fas fa-check"></i> <span class="shortlist-text-label text-uppercase">Removed</span>');
                            
                            $(item).html('<i class="far fa-heart"></i>');
                            // // Set Timeout...
                            // setTimeout(function()
                            // {
                            // }, 2000) // 2 Seconds...

                            $(item).removeClass('shortlist-confirm-action');

                            if(parent_div.length > 0)
                            {
                                setTimeout(function()
                                {
                                    parent_div.fadeOut();
                                }, 800)

                                if(parseInt(e.total) == 0)
                                {
                                    // Reload page (Show non in shortlist)...
                                    setTimeout(function()
                                    {
                                        window.location.reload();
                                    }, 2000);
                                }

                            }
                        }

                        $('.shortlist-total').html(e.total);
                    }
                }
            )
        }

    })


    // function shortlistTotalAjax(){
    //     var url = $('.shortlist-total').data('url');
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: "POST",
    //         dataType: "json",
    //         url: url,
    //         data: '',
    //         success: function(e){
    //             $('.shortlist-total').html(e.total);
    //         }
    //     });
    // }
    // shortlistTotalAjax();

    function shortlistTotalAjax(){
        var url = $('.shortlist-total').data('url');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType: "json",
            url: url,
            data: '',
            success: function(e){
                $('.shortlist-total').html(e.total);
            }
        });
    }
    if ($(".shortlist-total")[0]){
        shortlistTotalAjax();
    }


});
