$(function()
{
    $('.shortlist-add').on('click', function(e)
    {
        
        var property_id = $(this).data('propertyId');

        var saveText = $(this).data('saveText');
        var removeText = $(this).data('removeText');
        var url = $(this).data('url');
        var item = $(this);
        var parent_div = $('#p-'+property_id);

        var check_confirm = true;
console.log(url);
        if( $(item).hasClass('shortlist-confirm-action') )
        {
            console.log(1234);
            check_confirm = false;

            //var check_confirm = confirm ('Are you sure you want to remove?');
            $('#global-modal .modal-dialog').addClass('modal-sm');
            $('#global-modal .modal-title').html('Delete Shortlist Item');
            $('#global-modal').modal('show');

            $('#global-modal .modal-body').html('<p>Are you sure you want to delete this shortlist item?</p>' +
                '<div class="u-mt2">' +
                '<a class="button -primary u-block u-fullwidth confirm-delete-modal" href="#">' +
                '<span class="text-center u-block f-bold">Yes - Delete</span></a>' +
                '</div> ');

            $('.confirm-delete-modal').click(function()
            {
                // Do Delete Through Modal....
                $.ajax(
                    {
                        headers:
                            {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        type: 'POST',
                        dataType: 'json',
                        url: url,
                        data: {property_id: property_id},
                        success: function (e)
                        {
                            $(item).html('<span class="shortlist-text-label text-uppercase"><i class="fas fa-heart"></i></span>');

                            // Set Timeout...
                            setTimeout(function()
                            {
                                $(item).html('<i class="far fa-heart"></i>');
                            }, 2000) // 2 Seconds...

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

                            $('.member-notify .alert-heading').html('Removed property from your shortlist');
                            $('.member-notify').fadeIn();

                            setTimeout(function()
                            {
                                $('.member-notify').fadeOut();

                            }, 5000); // 5 Seconds...

                            // Now Hide The Global...
                            $('#global-modal').modal('hide');
                            $('.shortlist-total').html(e.total);
                        }
                    })

                return false;
            })
        }

        if(check_confirm == true)
        {
             console.log(777);
            $(this).html('Saving...');

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
                        if(e.url)
                        {
                            // We have a URL Return the User To It...
                            $(location).attr('href', e.url);
                        }
                        else
                        {
                            $(item).html(''); // Clear...

                            var flag = parseInt(e.flag);

                            if(flag == 1)
                            {
                                $(item).html('<i class="fas fa-check"></i> <span class="shortlist-text-label text-uppercase">Saved</span>');

                                // Set Timeout...
                                setTimeout(function()
                                {
                                    $(item).html('<i class="fas fa-heart"></i>');
                                }, 2000) // 2 Seconds...

                                $(item).addClass('shortlist-confirm-action');
                            }

                            var members_area = $('.members_area').val();

                            if(members_area == 'y')
                            {
                                $('.member-notify .alert-heading').html('Added property to your shortlist. <a class="c-white" href="/account/shortlist">View your shortlist now</a>');
                                $('.member-notify').fadeIn();

                                setTimeout(function()
                                {
                                    $('.member-notify').fadeOut();

                                }, 9000); // 9 Seconds...
                            }
                            else
                            {
                                $('.member-notify .alert-heading').html('Added property to your shortlist');
                                $('.member-notify').fadeIn();

                                setTimeout(function()
                                {
                                    $('.member-notify').fadeOut();

                                }, 5000); // 5 Seconds...
                            }
                        }

                        $('.shortlist-total').html(e.total);
                    }
                }
            )
        }

        e.preventDefault();
        e.stopPropagation();

    })


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
