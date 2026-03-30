/*---------------------------------------
* GLOBAL MODAL
--------------------------------------- */

// Lazy images
function lazyImages()
{
  $('.lazyBg').each(function(){
    var _bg = $(this).data('background');
    if(_bg){
        $(this).attr('style','background-image: '+_bg);
    }
  });

  $('.lazyImg').each(function(){
    var _img = $(this).data('src');
    if(_img){
        $(this).attr('src', _img);
    }
  });

}

jQuery(window).on('load', function () {

    lazyImages();

});

// Modal(s).....
$('.modal-toggle').click(function()
{
    var modal_size = $(this).data('modal-size');
    var modal_title = $(this).data('modal-title');
    var modal_type = $(this).data('modal-type');
    var item_id = $(this).data('item-id');
    var modal_message = $(this).data('modal-message');
    var item = $(this).data('item');

    if(modal_size == 'small')
    {
        $('#global-modal .modal-dialog').addClass('modal-sm');
    }

    $('#global-modal .modal-title').html(modal_title);

    if(modal_type == 'delete')
    {
        var delete_type = $(this).data('delete-type');

        if(modal_message)
        {
            $('#global-modal .modal-body').html('<p>' + modal_message + '</p>' +
                '<div class="u-mt2">' +
                '<a class="button -primary u-block u-fullwidth confirm-delete-modal" href="#">' +
                '<span class="text-center u-block f-bold">Yes - Delete</span></a>' +
                '</div> ');
        }
        else
        {
            $('#global-modal .modal-body').html('<p>Are you sure you want to delete this item?</p>' +
                '<div class="u-mt2">' +
                '<a class="button -primary u-block u-fullwidth confirm-delete-modal" href="#">' +
                '<span class="text-center u-block f-bold">Yes - Delete</span></a>' +
                '</div> ');
        }

        if(item)
        {

            confirm_delete(delete_type, item_id, item);
        }
        else
        {
            confirm_delete(delete_type, item_id);
        }
    }

})

function confirm_delete(delete_type, id, item = false)
{
    $('.confirm-delete-modal').click(function()
    {
        var url = '/' + delete_type + '/' + id;
        var message = delete_type;

        $.ajax(
            {
                type: 'DELETE',
                url: url,
                'headers' : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function( data )
                {
                    // Hide Modal...
                    $('#global-modal').modal('hide');
                    $('.modal-backdrop').hide();

                    if(delete_type == 'member')
                    {
                        // Redirect to Login....
                        setTimeout(function()
                        {
                            window.location.replace(data.url);
                        }, 1000) // 1 seconds
                    }
                    else
                    {
                        if(item)
                        {
                            if(item == 'save-search')
                            {
                                // Do Response Stuff for Removing a Saved Search Button...
                                //$('#'+item+'-'+id).html('<span class="c-primary"><i class="fas fa-check"></i></span> Removed');

                                if(data.page == 'saved-searches')
                                {
                                    // Delete from Account page...
                                    setTimeout(function()
                                    {
                                        $('#'+item+'-'+id).fadeOut();
                                    }, 1500) // 1.5 seconds

                                    var remaining = data.remaining;

                                    if (remaining !== undefined || variable !== null)
                                    {
                                        if(remaining == '0')
                                        {
                                            setTimeout(function()
                                            {
                                                window.location.reload();
                                            }, 2500) // 2.5 seconds
                                        }
                                    }
                                    else
                                    {
                                        $('#'+item+'-'+id).fadeOut();
                                    }
                                }
                                else
                                {
                                    setTimeout(function()
                                    {
                                        window.location.reload();
                                    }, 2500) // 2.5 seconds
                                }

                                message = "Saved Search";
                            }
                        }
                        else
                        {
                            setTimeout(function()
                            {
                                $('#'+delete_type+'-'+id).fadeOut();
                                $('#'+delete_type+'-mobile-'+id).fadeOut();

                            },2000)

                            if(data.remaining)
                            {
                                if(data.remaining === '0')
                                {
                                    window.location.reload();
                                }
                            }
                        }
                    }

                    $('.member-notify .alert-heading').html('Removed '+ message +'!');
                    $('.member-notify').fadeIn();

                    setTimeout(function()
                    {
                        $('.member-notify').fadeOut();

                    }, 5000); // 5 Seconds...
                }
            }
        )

        return false;
    })
}

// CSRF Token for AJAX Requests...
var token = $('meta[name="csrf-token"]').attr('content');

search_remove();

// Save Search....
$('.search-save').on('click', function()
{
    // Get all form values & Serialise...
    var search_data = {};
    var item = $(this);
    var propertyTypeArray = [];

    $.each($('#search-form').serializeArray(), function()
    {
        if(this.name == 'property_type[]'){
            propertyTypeArray.push(this.value);
        }else{
            search_data[this.name] = this.value;
        }
    });

    if (propertyTypeArray.length !== 0) {
        search_data['property_type'] = propertyTypeArray;
    }

    if(item.hasClass('search-remove'))
    {
        return false;
    }

    $.ajax(
        {
            type: 'POST',
            dataType: 'json',
            url: '/save-search',
            data: { search_data : search_data, _token: token },
            success: function ( data )
            {
                $('.member-notify .alert-heading').html('Your search has been saved!');
                $('.member-notify').fadeIn();

                setTimeout(function()
                {
                    $('.member-notify').fadeOut();

                }, 5000); // 5 Seconds...

                // Reload Page...
                setTimeout(function()
                {
                    window.location.reload();
                }, 2500) // 2.5 seconds
            }
        }
    )

    return false;
})

function search_remove()
{
    $('.search-remove').on('click', function()
    {
        var item_id = $(this).attr('data-item-id');

        // Show the Remove Modal...
        $('#global-modal').modal('show');
        $('#global-modal .modal-dialog').addClass('modal-sm');

        $('#global-modal .modal-title').html('Delete Saved Search');

        var delete_type = 'save-search';

        $('#global-modal .modal-body').html('<p>Are you sure you want to delete this saved search?</p>' +
            '<div class="u-mt2">' +
            '<a class="button -primary u-block u-fullwidth confirm-delete-modal" href="#">' +
            '<span class="text-center u-block f-bold">Yes - Delete</span></a>' +
            '</div> ');

        confirm_delete(delete_type, item_id, 'save-search');

        return false;
    })
}



$('.jump-link').click(function()
{
    var header_height = $('#header').outerHeight();
    var scroll_to = $(this).attr("href");

    // Scroll To...
    $('html, body').animate(
        {
            scrollTop: $(scroll_to).offset().top - header_height
        }, 800);

    return false;
})

/* Edit User Details
------------------------------------------------------------------ */

$('.edit-user-details').click(function()
{
    $('.edit-details').slideToggle();

    return false;
})

// Market Valuation
$('.accept-list-property').click(function()
{
    var client_valuation_id = $('.client_valuation_id').val();

    $.ajax(
        {
            type: 'POST',
            url: '/valuation-report/accept',
            data: { _token: token, client_valuation_id: client_valuation_id },
            success: function( data )
            {
                if(data.success == true)
                {
                    $('.list-property').html('<i class="fas fa-check u-inline-block"></i> <span class="f-black">Accepted</span>');
                    $('.recommended').hide();

                    $('.response-message').show().html(data.message);
                    $('#list-property-modal').modal('hide');
                }
            }
        }
    )

    return false;
})

/* Member Modals
------------------------------------------------------------------ */

$(document).on('click', '[data-action="show-signup"]', function () {
    $('.member-login-account').modal('hide');
    $('.member-create-account').modal('show');
});

$(document).on('click', '[data-action="show-login"]', function () {
    $('.member-create-account').modal('hide');
    $('.member-login-account').modal('show');
});

/*--------------------------------------------------------
* MEMBER AJAX FORM
 --------------------------------------------------------*/
$('.ajax-form').submit(function(e)
{
    var id = $(this).attr('id');
    var action = $(this).attr('action');
    var data = $(this).serialize();
    var button_txt = $("#btn-" + id).html();

    $("#btn-" + id).html('loading...');
    $("#response-" + id).html('');


    $.ajax({
        headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            type: "POST",
            url: action,
            data: data,
            success: function (e)
            {

                $("#response-" + id).html(e.alert);

                if (e.flag == '1')
                {
                // when Success...
                $("#" + id).find("input[type=email],input[type=text],textarea,select").val("");

                if (typeof e.redirect !== "undefined")
                {
                    window.location.replace(e.redirect);
                }
            }

            $("#btn-" + id).html(button_txt);
        }
    });

    /*---------------------
    * Save Search
    ----------------------*/

    var redirectVar = $('#'+id).find('input[name="redirectVar"]').val();

    if(redirectVar=='save-search')
    {
        // Get all form values & Serialise...
        var search_data = {};

        $.each($('#search-form').serializeArray(), function()
        {
            search_data[this.name] = this.value;
        });

        var token = $('meta[name="csrf-token"]').attr('content');
        var base_url = $('header').data('url');

        setTimeout(function()
        {
            $.ajax(
                {
                    type: 'POST',
                    dataType: 'json',
                    url: '/save-search',
                    data: { search_data : search_data,  _token: token },
                    success: function ( data )
                    {
                        window.location.replace(base_url+'/save-search');
                    }
                }
            )
        }, 1500) // 1.5 seconds
    }

    return false;
});

// Members Nav (Mobile / Tablet)...
$('.toggle-link').on('click', function()
{
    var div_toggle = $(this).data('target');

    $('.'+div_toggle).fadeToggle();

    return false;
})

// Mobile Min / Max Price Options (On Sale Type Change)...
$('.sale').on('change', function()
{
    var value = $(this).val();

    if(value == 1){
        value = 'rent';
    }
    if(value == 0){
        value = 'sale';
    }

    $.ajax(
        {
            type: 'GET',
            url: '/price-ranges/'+value,
            dataType: 'json',
            success: function(data)
            {
                // Destroy Min / Max Select 2s to Re-Render...
                $('.min_price').html('<option value="" selected="selected">Min Price</option>');
                $('.max_price').html('<option value="" selected="selected">Max Price</option>');

                $.each(data, function( i, val )
                {
                    $('.min_price').append(
                        '<option value="' + i +'">' + val + '</option>'
                    )

                    $('.max_price').append(
                        '<option value="' + i +'">' + val + '</option>'
                    )
                })

                // Reinitalise Dropdowns...
                $(".min_price").select2("destroy");
                $(".max_price").select2("destroy");

                $('.min_price').select2(
                    {
                        placeholder: 'Min Price',
                        width: '100%',
                        minimumResultsForSearch: -1
                    }
                );
                $('.max_price').select2(
                    {
                        placeholder: 'Max Price',
                        width: '100%',
                        minimumResultsForSearch: -1
                    }
                );
            }
        }
    )
})


$('.sale-multiple').on('change', function()
{
    var value = $(this).val();
    var instanceClass = $(this).data('targetclass');

    if(value == 1){
        value = 'rent';
    }
    if(value == 0){
        value = 'sale';
    }

    $.ajax(
        {
            type: 'GET',
            url: '/price-ranges/'+value,
            dataType: 'json',
            success: function(data)
            {
                // Destroy Min / Max Select 2s to Re-Render...
                $('.min_price'+instanceClass).html('<option value="" selected="selected">Min Price</option>');
                $('.max_price'+instanceClass).html('<option value="" selected="selected">Max Price</option>');

                $.each(data, function( i, val )
                {
                    $('.min_price'+instanceClass).append(
                        '<option value="' + i +'">' + val + '</option>'
                    )

                    $('.max_price'+instanceClass).append(
                        '<option value="' + i +'">' + val + '</option>'
                    )
                })

                // Reinitalise Dropdowns...
                $(".min_price"+instanceClass).select2("destroy");
                $(".max_price"+instanceClass).select2("destroy");

                $('.min_price'+instanceClass).select2(
                    {
                        placeholder: 'Min Price',
                        width: '100%',
                        minimumResultsForSearch: -1
                    }
                );
                $('.max_price'+instanceClass).select2(
                    {
                        placeholder: 'Max Price',
                        width: '100%',
                        minimumResultsForSearch: -1
                    }
                );
            }
        }
    )
})


// Valuation Viewing
$( document ).ready(function()
{
    $('.date-selection').click(function()
    {
        var chosen_date = $(this).data('date');

        $( ".date-selection" ).each(function()
        {
            if($(this).hasClass('-selected'))
            {
                $(this).removeClass('-selected');
            }
        })

        $(this).addClass('-selected');

        $('.chosen-date').fadeIn();
        $('.chosen-date').html(chosen_date);
        $('.chosen-select').fadeIn();
        $('.date').val(chosen_date);

        return false;
    })

    var times = [];

    $('.time-block').click(function()
    {
        var id = $(this).data('id');
        var chosen_time = $(this).data('time');

        if ($('.-chosen').length < 3 || $(this).hasClass('-chosen'))
        {
            if($(this).hasClass('-chosen'))
            {
                $(this).removeClass('-chosen');

                times = $.grep(times, function(value)
                {
                    return value != id;
                });
            }
            else
            {
                times.push(id);

                $(this).addClass('-chosen');
            }
        }

        times.sort();

        var string = '';
        var time = '';

        $.each( times, function( key, value )
        {
            $('.time-block').each(function(i, obj)
            {
                if(value == $(this).data('id'))
                {
                    time = $(this).data('time');
                }
            });

            string+= ", " + time;
        });

        $('.at-time').fadeIn();

        string = string.replace(/^, /, '');

        $('.chosen-times').html(string);
        $('.times').val(times);
    })

})

$('.submit-viewing').on('click', function()
{
    var errors = 0;
    var error_msg = '';

    var date = $('.date').val();
    var times = $('.times').val();
    var name = $('.name').val();
    var email = $('.email').val();
    var phone = $('.phone').val();
    var property_ref = $('.property_ref').val();
    var url = $('.url').val();

    var form = $('#ajax-form-valuation');

    $('.error-message').html('');

    // Validate....
    $('#ajax-form-valuation').validator().on('submit', function (e)
    {
        if (e.isDefaultPrevented())
        {
            // handle the invalid form...
        }
        else
        {
            error_msg = '';

            console.log(date);

            if(date == '')
            {
                error_msg += '<p class="error-date">Please select at least one date</p>';
                errors++;
            }
            else
            {
                errors = 0;
            }

            console.log(times.length);

            if(times == 0)
            {
                errors++;
                error_msg += '<p class="error-times">Please select three times</p>';
            }
            else
            {
                if(times.length < 3)
                {
                    errors++;
                    error_msg += '<p class="error-times">Please select three times</p>';
                }
                else
                {
                    errors = 0;
                }
            }

            if(errors == 0)
            {
                $('.errors').fadeOut();

                $.ajax(
                    {
                        type: 'POST',
                        data: { _token: token, date: date, times: times, name: name, email: email, phone: phone, property_ref: property_ref, url: url },
                        url: '/ajax/request-viewing',
                        dataType: 'json',
                        success: function(data)
                        {
                            $('.member-notify').fadeIn();
                            $('.member-notify .alert-heading').html(data.message);

                            setTimeout(function()
                            {
                                // Close Modal
                                $('#viewing-modal').modal('hide');
                            }, 800);

                            setTimeout(function()
                            {
                                $('.member-notify').fadeOut();
                            }, 5000) // 5 Seconds...
                        }
                    }
                )
            }
            else
            {
                $('.errors').fadeIn();
                $('.error-message').html(error_msg);
            }

            e.preventDefault();
        }
    })
})



// Sticky Headers
$(window).scroll( function()
{
    var market_price = $('.market-price');

    if(market_price.length > 0)
    {
        if($(this).scrollTop() >= $('.market-price').position().top)
        {
            $('.market-price').addClass('-scroll');
        }

        if($(window).scrollTop() === 0)
        {
            $('.market-price').removeClass('-scroll');
        }
    }
});
