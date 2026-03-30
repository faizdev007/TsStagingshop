$(function(){

	/*------------------------------------
	* DATEPICKER		
	------------------------------------*/
	if($(".pw-datepicker").length){
		$(".pw-datepicker").datepicker({ dateFormat: 'yy-mm-dd' });		
	}

	/*------------------------------------
	* SORTABLE
	------------------------------------*/

	if ($('#testimonials-sort').length > 0) {
		$("#testimonials-sort").sortable({
	      update: function (event, ui){
			var data = $(this).sortable('serialize');
			var url = $("#testimonials-sort").data('sorturl');
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
		$( "#testimonials-sort").disableSelection();
	}

});