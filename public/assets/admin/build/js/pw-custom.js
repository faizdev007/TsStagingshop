/*------------------------------------
	RESET IMAGE
-----------------------------------
$(window).load(function() {
	reset_img();
});

$(window).resize(function() {
	reset_img();
});

function reset_img()
{
	$('img.responsivejs-img').each(function(){
		var theWindow = $(window);
		var img = $(this);
		var img_src = img.attr('src');

		var img_datasrc = img.data('src');
		var img_orig = img.data('orig');

		// save the orig src
		if(!img_orig){
			var img_origW = img.width();
			if(img_origW){
				img.css('max-width', img_origW+'px');
			}
			img.attr('data-orig', img_src);
			img_orig = img.data('orig');
		}

		// set the responsive img and width
		if(theWindow.width() <= 768){
			img.attr('src', img_datasrc);
		}else{
			img.attr('src', img_orig);
		}

	});
}
-*/

$(function(){

	var base_url = $('.base-url').val();

	$('.cta-collapse').on('click', function (e) {
		e.preventDefault();
	});

	/*------------------------------------
		Datepicker
	------------------------------------*/
	$('.datepicker').datepicker({dateFormat:'yy-mm-dd'});

	/*------------------------------------
		SELECT 2
	------------------------------------*/
	$(".select-pw").select2({
		minimumResultsForSearch: Infinity
	});

	$(".select-pw-category").select2({
		minimumResultsForSearch: Infinity,
		tags: true
	});

	$('.location-select').select2(
		{
			maximumSelectionLength: 3
		}
	)

	var unsaved = false;

	$(":input").change(function(){ //triggers change in all input fields including text type
		unsaved = true;
	});
	$("textarea").change(function(){ //triggers change in all input fields including text type
		unsaved = true;
	});
	$("select").change(function(){ //triggers change in all input fields including text type
		unsaved = true;
	});

	function unloadPage(){
		if(unsaved){
			return "You have unsaved changes on this page. Do you want to leave this page and discard your changes?";
		}
	}

	//window.onbeforeunload = unloadPage;
	$('.pw-inner-tabs a[data-toggle="tab"]').on('click', function (e) {
		if(unsaved){
			var flag = confirm("You have unsaved changes on this page. Do you want to leave this page and discard your changes?");
			if(!flag){
				e.preventDefault();
				return false;
			}else{
				unsaved = false;
			}
		}
	});
	//$('.check-unsave').click(function(e){
	$('.check-unsave').on('click', function (e) {
		if(unsaved){
			var flag = confirm("You have unsaved changes on this page. Do you want to leave this page and discard your changes?");
			if(!flag){
				e.preventDefault();
				return false;
			}else{
				unsaved = false;
			}
		}
	});

	/*------------------------------------
	* TINY MCE
	------------------------------------*/
	initTinymce();


// Load Typo.js dictionary

function initTinymce()
{
    tinymce.init({
        editor_selector : "mceEditor",
        mode : "specific_textareas",
        relative_urls : false,
        remove_script_host : false,
        convert_urls : false,
        forced_root_block : "",
        force_p_newlines : true,
        entity_encoding: 'raw',
        external_filemanager_path:"/assets/admin/build/vendors/tinymce-external-plugins/filemanager/",
        filemanager_title:"Responsive Filemanager" ,
        menubar: 'file edit insert view tools help',
        theme: 'modern',
        plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link charmap hr insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help responsivefilemanager code paste',
        toolbar1: 'formatselect | bold italic | link | alignleft aligncenter alignright alignjustify  | forecolor | numlist bullist outdent indent  | removeformat | responsivefilemanager | blockquote | code ',
        image_advtab: true,
        removed_menuitems: 'newdocument',
        contextmenu: false, // Completely disable context menu
        browser_spellcheck: true,
        spellcheck: true,
		
		// Ensure default right-click behavior
        setup: function(ed) {
            // Remove any potentially problematic event listeners
            ed.off('contextmenu');
            
            ed.on('contextmenu', function(e) {
                // Prevent TinyMCE's custom context menu
                e.preventDefault();
                // Allow default browser context menu
                return true;
            });

			var maxlength = parseInt($("#" + (ed.id)).attr("maxlength") || "0");
            var count = parseInt($("#" + (ed.id)).val().length || "0");

            // Simplified spell-check and auto-correct logic
            ed.on('keyup', function(e) {
                try {
                    var content = ed.getContent({format: 'text'});
                    
                    // Simple misspelling check
                    var commonMisspellings = {
                        'recieve': 'receive',
                        'seperate': 'separate',
                        'definately': 'definitely',
                        'occured': 'occurred',
                        'concious': 'conscious'
                    };

                    // Safely handle potential undefined or null content
                    if (content && typeof content === 'string') {
                        var words = content.split(/\s+/);
                        
                        words.forEach(function(word, index) {
                            if (commonMisspellings[word.toLowerCase()]) {
                                content = content.replace(word, commonMisspellings[word.toLowerCase()]);
                            }
                        });
                    }
                } catch (error) {
                    console.error('Error in spell-check:', error);
                }
            });

            // Existing character limit logic
            ed.on("keydown", function(e) {
                try {
                    var textME = tinymce.get(ed.id).getContent({format: 'html'});
                    count = (textME || "").length;
                    unsaved = true;
                    
                    if (typeof charLimitMCE === 'function') {
                        charLimitMCE(ed, maxlength, count);
                    }

                    if (count > maxlength) {
                        // Excepting delete, backspace and arrow
                        if(e.keyCode == '8' || e.keyCode == '46' || e.keyCode == '37' || e.keyCode == '38' || e.keyCode == '39' || e.keyCode == '40'){
                            return true;
                        }else{
                            tinymce.get(ed.id).setContent(textME.substring(0, (maxlength+1)));
                            e.stopPropagation();
                            return false;
                        }
                    }
                } catch (error) {
                    console.error('Error in character limit check:', error);
                }
            });

        },
    });
}




	function enablePagewideSpellCheck() {
		var elements = document.querySelectorAll('textarea, [contenteditable]');
		
		elements.forEach(function(element) {
			element.setAttribute('spellcheck', 'true');
			element.setAttribute('lang', 'en-US');
		});
	}
	
	// Ensure it runs on page load
	document.addEventListener('DOMContentLoaded', enablePagewideSpellCheck);
	


	function charLimitMCE(ed, maxlength, count){
	 if (count > maxlength) {
		  $("#"+ed.editorContainer.id).css({'border':'2px solid #ff0000'});
	  }else{
		  $("#"+ed.editorContainer.id).css({'border':'1px solid #c5c5c5'});
	  }
	  $('#'+ed.editorContainer.id).next('textarea').next('.counter-display').children('.counter-value').html(count);
	}


	/*------------------------------------
	* TINY MCE
	------------------------------------*/
	if ($('.tagEditor-style-1').length > 0) {
		$('.tagEditor-style-1').tagEditor({
		    forceLowercase: false,
		    placeholder: 'Please enter...',
			maxTags: 20
		});
	}


	/*------------------------------------
	* Confirmation prompt
	------------------------------------*/
	alertify.defaults.theme.ok = "btn btn-primary";
	alertify.defaults.theme.cancel = "btn btn-danger";
	alertify.defaults.theme.input = "form-control";
	alertify.defaults.glossary.title = "Confirm";

	$('.confirm-action').click(function(){
		var $t = $(this);
		var title = $t.prop('title');
		var question = 'Are you sure you want to ' + (title ? title : 'do this') + '?';

		alertify.confirm( question,
  		function(){

			if($t.context.nodeName == "A"){
				//for href
				window.location.href = $t.attr('href');
			}
			if($t.context.nodeName == "BUTTON"){
				//for forms
				var form = $t.parents('form:first');
				form.submit();
			}

			//alertify.success('Ok');
  		},
  		function(){
			//alertify.error('Cancel');
  		});

		return false;
	});


	/*------------------------------------
	* SORTABLE
	------------------------------------*/
	if ($('#property-media-sort').length > 0) {
		$("#property-media-sort").sortable({
	      update: function (event, ui){
			var data = $(this).sortable('serialize');
			var base = $(".base-url").val();
			var url = $("#media-sort-url").val();
			$.ajax({
				headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
  				type : "POST",
  				url: url,
  				data : data,
  				success: function(e) {	}
  			});
	      }
	    });
		$( "#property-media-sort").disableSelection();
	}

    if ($('#development-media-sort').length > 0) {
        $("#development-media-sort").sortable({
            update: function (event, ui){
                var data = $(this).sortable('serialize');
                var base = $(".base-url").val();
                var url = $("#media-sort-url").val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type : "POST",
                    url: url,
                    data : data,
                    success: function(e) {	}
                });
            }
        });
        $( "#property-media-sort").disableSelection();
    }

	/*if ($('.property-media-caption').length > 0) {
		$('.property-media-caption').keyup(function(){
		Change caption on click as per request
		*/

		$('.caption-save').click(function(){

			var url = $("#media-caption-url").val();
			var mediaID =  $(".property-media-caption").data('id');
			var caption =  $(".property-media-caption").val();
			$('.pmcl-'+mediaID).show();

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type : "POST",
				dataType: "json",
				url: url,
				data : {'mediaID':mediaID, 'caption':caption},
				success: function(e) {
					if(e.flag == 1){
						$('.pmcl-'+mediaID).hide();
					}
					// Toastr Success Message....
						toastr.success(e.message);

						// Now Reload Page....
						setTimeout(function()
						{
							window.location.reload();
						}, 2000);
						
				}
			});

		});
	

	/*--------------------------------------------------------------------------------------------------------
	* SORT - public/assets/admin/build/js/pw-custom.js
	--------------------------------------------------------------------------------------------------------*/
	if ($('#communities-sort').length > 0) {
	    $("#communities-sort").sortable({
	      update: function (event, ui){
	        var data = $(this).sortable('serialize');
	        var url = $("#communities-sort").data('url');
	        $.ajax({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
	            type : "POST",
	            url: url,
	            data : data,
	            success: function(e) {	}
	        });
	      }
	    });
	    $( "#communities-sort").disableSelection();
	}


	var num_search_blocks = 1;

	var edit = $('.edit_num_blocks');

	if(edit.length > 0)
	{
		num_search_blocks = parseInt(edit.val()) +1;
	}

	// Add More Search Content Blocks...
	$('.add-search-blocks').click(function()
	{
		// Add To Additional Blocks
		$('.additional-blocks').append('<div class="row">\n' +
			'        <div class="col-md-12 col-sm-12 col-xs-12">\n' +
			'            <div class="x_panel pw">\n' +
			'                <div class="x_content ">\n' +
			'                    <p class="text-muted font-13 m-b-30"><br/></p>\n' +
			'                    <div class="x_panel pw-inner-tabs">\n' +
			'                        <div class="x_title">\n' +
			'                            <h2>Block '+ num_search_blocks +'</h2>\n' +
			'                            <ul class="nav navbar-right panel_toolbox">\n' +
			'                                <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>\n' +
			'                            </ul>\n' +
			'                            <div class="clearfix"></div>\n' +
			'                        </div>\n' +
			'                        <div class="x_content pw-open">\n' +
			'                            <div class="xpw-fields">\n' +
			'                                <div class="row">\n' +
			'                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">\n' +
			'                                        <div class="form-group">\n' +
			'                                            <label>Heading: *</label>\n' +
			'                                            <input name="block['+num_search_blocks+'][heading]" type="text" class="form-control" placeholder="Content Title" required>\n' +
			'                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>\n' +
			'                                        </div>\n' +
			'                                    </div>\n' +
			'                                </div>\n' +
			'                                <div class="row">\n' +
			'                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">\n' +
			'                                        <div class="form-group">\n' +
			'                                            <label>Content *</label>\n' +
			'                                            <textarea name="content-'+num_search_blocks+'" class="mceEditor description" placeholder="Please enter" required></textarea>\n' +
			'                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>\n' +
			'                                        </div>\n' +
			'                                    </div>\n' +
			'                                </div>\n' +
			'                            </div>\n' +
			'                        </div>\n' +
			'                    </div>\n' +
			'                </div>\n' +
			'            </div>\n' +
			'        </div>\n' +
			'    </div>');

		initTinymce();

		num_search_blocks++;

		return false;
	})

	// Delete Modal....
	$('.modal-toggle').click(function()
	{
		var modal_size = $(this).data('modal-size');
		var modal_title = $(this).data('modal-title');
		var modal_type = $(this).data('modal-type');
		var item_id = $(this).data('item-id');

		if(modal_size == 'small')
		{
			$('#global-modal .modal-dialog').addClass('modal-sm');
		}

		$('#global-modal .modal-title').html(modal_title);

		if(modal_type == 'delete')
		{
			var delete_type = $(this).data('delete-type');

			$('#global-modal .modal-body').html('<p>Are you sure you want to delete this item?</p>' +
				'<div class="u-mt2">' +
				'<a class="btn btn-small btn-danger u-block u-fullwidth confirm-delete-modal" href="#">' +
				'<span class="text-center u-block t-bold">Yes - Delete</span></a>' +
				'</div> ');

			confirm_delete(delete_type, item_id);
		}

		if(modal_type == 'data')
		{
			var data_type = $(this).data('data-type');

			var url = '/admin/data/get/' + data_type + '/' + item_id;

			$.ajax(
				{
					type: 'GET',
					url: url,
					dataType: 'json',
					success: function( data )
					{
						$('#global-modal .modal-body').append('<table class="table table-striped jambo_table modal-data table-bordered-"> <thead> <tr> <th>URL</th> <th>Clicks</th> </tr></thead><tbody>');

						if(data_type == 'email-clicks')
						{
							// Now Populate #enquiry_status
							$.each(data.data, function( i, val )
							{
								$('#global-modal .modal-body .modal-data tbody').append('<tr><td><a target="_blank" href="' + val.url + '">'+truncateString(val.url, 60)+'</a></td> <td align="center">'+val.clicks+'</td></tr>');
							})
						}

						$('#global-modal .modal-body').append('</tbody></table>');
					}
				}
			)

		}

		if(modal_type == 'email')
		{
			var send_type = $(this).data('send-type');

			$('#global-modal .modal-body').html('<p>Are you sure you want to send this valuation?</p>' +
				'<div class="u-mt2">' +
				'<a class="btn btn-small btn-success u-block u-fullwidth confirm-send-modal" href="#">' +
				'<span class="text-center u-block t-bold"><i class="fa fa-check"></i> Yes - Send</span></a>' +
				'</div> ');

			// Hide Footer
			$('#global-modal .modal-footer').hide();

			confirm_send(send_type, item_id);
		}

	})

	function confirm_send(send_type, id)
	{
		$('.confirm-send-modal').click(function()
		{
			var url = '/admin/'+ send_type;

			$.ajax(
				{
					type: 'POST',
					url: url,
					headers:
					{
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: { id : id },
					success: function( data )
					{
						// Toastr Success Message....
						toastr.success(data.message);

						// Now Reload Page....
						setTimeout(function()
						{
							window.location.reload();
						}, 2000);
					}
				}
			)

			return false;
		})
	}

	function confirm_delete(delete_type, id)
	{
		$('.confirm-delete-modal').click(function()
		{
			var url = '/admin/' + delete_type + '/' + id;

			$.ajax(
				{
					type: 'DELETE',
					url: url,
					'headers' : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					success:function( data )
					{
						var redirect = data.redirect;

						// Toastr Success Message....
						toastr.success(data.message);

						if(redirect !== '' && redirect)
						{
							// Now Redirect
							setTimeout(function()
							{
								window.parent.location.href= data.redirect;
							}, 2000);
						}
						else
						{
							// Now Reload Page....
							setTimeout(function()
							{
								window.location.reload();
							}, 2000);
						}
					}
				}
			)

			return false;
		})
	}

	// Global Sortable...

	if ($('.sortable').length > 0)
	{
		$(".sortable").sortable(
		{
			update: function (event, ui)
			{
				var data = $(this).sortable('serialize');
				var url = $(this).data('sorturl');
				$.ajax(
					{
						headers:
						{
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						type : "POST",
						url: url,
						data : data,
						success: function(data)
						{
							toastr.success(data.message);

							// Now Reload Page....
							setTimeout(function()
							{
								window.location.reload();
							}, 2000);
						}
				});
			}
		});
	}

	$('.url_type').on('change', function()
	{
		var type = $(this).val();

		if(type == 'custom-link')
		{
			$('.existing-url').fadeOut();
		}
		else
		{
			$('.custom-link').fadeOut();
		}

		$('.'+type+'').fadeIn();

	})

	$('.get-slug').change(delay(function (e)
	{
		var type = $(this).data('type');
		var url = '/admin/'+ type + '/generate-slug';
		var title = $(this).val();

		if(title != '')
		{
			$.ajax(
				{
					headers:
						{
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
					type: 'POST',
					url: url,
					data: { title: title },
					success: function ( data )
					{
						$('.slug-return').val(data);
					}
				}
			)
		}
		else
		{
			$('.slug-return').val('');
		}

	}, 500));

	function delay(fn, ms)
	{
		let timer = 0
		return function(...args)
		{
			clearTimeout(timer)
			timer = setTimeout(fn.bind(this, ...args), ms || 0)
		}
	}

	function truncateString(str, length)
	{
		return str.length > length ? str.substring(0, length - 3) + '...' : str
	}

	$('.add-section').on('click', function()
	{
		$('.section-options').fadeToggle();
		$('.no-data').fadeOut();

		return false;
	})

	$('.create-block').on('click', function()
	{
		var type = $(this).data('block-type');

		$('#'+type).fadeIn();

		// Scroll To...
		$('html, body').animate(
			{
				scrollTop: $('#'+type).offset().top - 20
			}, 800);

	})

	var icon_picker = $('.icon-picker');

	if(icon_picker.length > 0)
	{
		// Icon Picker
		$('.icon-picker').iconpicker(
			{
			}
		);
	}

	// Social Media Sharing...
    $('.social-share').click(function()
    {
        var share_url = $(this).data('url');
        var share_type = $(this).data('share');
        var message = $(this).data('message');
        var social_url = '';

        if(share_type == 'facebook')
        {
            social_url = 'https://www.facebook.com/sharer/sharer.php?u='+ share_url +'&quote=' + message + '';
        }
        else if(share_type == 'twitter')
        {
            social_url = 'https://twitter.com/intent/tweet/?text=Take+a+look+at+this+link..&url=' + current_url + '';
        }

        windowPopup(social_url, 640, 480);

        return false;

    })

    function windowPopup(url, width, height)
    {
        // Calculate the position of the popup so
        // it’s centered on the screen.
        var left = (screen.width / 2) - (width / 2),
            top = (screen.height / 2) - (height / 2);

        window.open(
            url,
            "",
            "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width=" + width + ",height=" + height + ",top=" + top + ",left=" + left
        );
    }

});
