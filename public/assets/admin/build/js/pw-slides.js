$(function(){

	/*------------------------------------
	* SORTABLE
	------------------------------------*/

	if ($('#slides-sort').length > 0) {
		$("#slides-sort").sortable({
	      update: function (event, ui){
			var data = $(this).sortable('serialize');
			var url = $("#slides-sort").data('sorturl');
			$.ajax({
				headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
  				type : "POST",
  				url: url,
  				data : data,
  				success: function(e) {	
  					//console.log(e);
  				}
  			});
	      }
	    });
		$( "#slides-sort").disableSelection();
	}

});