Dropzone.autoDiscover = false;
$(function(){

	/* Communities
	------------------------------------*/
	if ($('#communities-photo-upload').length > 0) {
	    function communitiesPhoto(){
	        var id = $('.entry-id').val();
	        var redirect = $('.redirect-photo-upload').val();
			console.log(redirect);
			var dz = {
	            id: 'communities-photo-upload',
	            submitId: 'submit-all',
	            url: '/admin/communities/'+id+'/photo-upload',
	            autoProcessQueue: true,
	            uploadMultiple: true,
	            maxFilesize:100,
	            maxFiles:8,
	            acceptedFiles: ".jpeg,.jpg,.png,.gif,.tif",
	            redirect: redirect
	        };
	        dropZonePW(dz);
	    }
	    communitiesPhoto();
	}

	/*------------------------------------
	* DROPZONE FOR PROPERTY PHOTO
	------------------------------------*/
	if ($('#photo-upload').length > 0) {
			function propertyPhoto(){
	        var property_id = $('.property-id').val();
	        var dz = {
	            id: 'photo-upload',
	            submitId: 'submit-all',
	            url: '/admin/properties/'+property_id+'/photo-upload',
				autoProcessQueue: false,
				uploadMultiple: true,
				maxFilesize:100,
				maxFiles:35,
				acceptedFiles: ".jpeg,.jpg,.png,.gif,.tif,.webp",
	        };
	        dropZonePW(dz);
		}
	    propertyPhoto();
	}

	/*------------------------------------
	* DROPZONE FOR FLOORPLAN
	------------------------------------*/
	if ($('#floorplan-upload').length > 0) {
			function propertyFloorplan(){
			var property_id = $('.property-id').val();
			var dz = {
				id: 'floorplan-upload',
				submitId: 'submit-all',
				url: '/admin/properties/'+property_id+'/floorplan-upload',
				autoProcessQueue: false,
				uploadMultiple: true,
				maxFilesize:100,
				maxFiles:35,
				acceptedFiles: ".jpeg,.jpg,.png,.gif,.tif,.pdf",
			};
			dropZonePW(dz);
		}
		propertyFloorplan();
	}

	/*------------------------------------
	* DROPZONE FOR DOCUMENTS#document-upload
	------------------------------------*/
	if ($('#document-upload').length > 0) {
		function propertyDocument(){
			var property_id = $('.property-id').val();
			var dz = {
				id: 'document-upload',
				submitId: 'submit-all',
				url: '/admin/properties/'+property_id+'/document-upload',
				autoProcessQueue: false,
				uploadMultiple: true,
				maxFilesize:100,
				maxFiles:35,
				acceptedFiles: ".jpeg,.jpg,.doc,.docx,.rtf,.pdf,.png",
			};
			dropZonePW(dz);
		}
		propertyDocument();
	}

	/*------------------------------------
	* DROPZONE FOR PAGE PHOTO (Basic Uploader)
	------------------------------------*/
	if ($('#page_dropzone').length > 0) {
		var action = $("#page_dropzone").attr('action');
		var redirect = $("#page_dropzone").data('redirect');
		var page_dropzone = $("#page_dropzone").dropzone({
			url: action,
			complete: function(){
				$(location).attr('href', redirect);
			}
		});
	}

	/*------------------------------------
	* DROPZONE FOR SLIDE PHOTO (Basic Uploader)
	------------------------------------*/
	if ($('#slide_dropzone').length > 0) {
		var action = $("#slide_dropzone").attr('action');
		var redirect = $("#slide_dropzone").data('redirect');
		var slide_dropzone = $("#slide_dropzone").dropzone({
			url: action,
			complete: function(){
				$(location).attr('href', redirect);
			}
		});
	}

	/*------------------------------------
	* DROPZONE FOR SLIDE PHOTO (Basic Uploader)
	------------------------------------*/
	if ($('#news_dropzone').length > 0) {
		var action = $("#news_dropzone").attr('action');
		var redirect = $("#news_dropzone").data('redirect');
		var slide_dropzone = $("#news_dropzone").dropzone({
			url: action,
			complete: function(){
				$(location).attr('href', redirect);
			}
		});
	}

	/*------------------------------------
	* DROPZONE FOR USER PHOTO
	------------------------------------*/
	if ($('#user-photo-upload').length > 0) {
			function userPhoto(){
			var user_id = $('.user-id').val();
			var dz = {
				id: 'user-photo-upload',
				submitId: 'submit-all',
				url: '/admin/users/'+user_id+'/photo-upload',
				autoProcessQueue: false,
				uploadMultiple: false,
				maxFilesize:100,
				maxFiles:1,
				acceptedFiles: ".jpeg,.jpg,.png,.gif,.tif",
			};
			dropZonePW(dz);
		}
		userPhoto();
	}

	/*------------------------------------
	* DROPZONE FOR THINGS TO DO
	------------------------------------*/
	if ($('#things_dropzone').length > 0) {
		var action = $("#things_dropzone").attr('action');
		var redirect = $("#things_dropzone").data('redirect');
		var slide_dropzone = $("#things_dropzone").dropzone({
			url: action,
			complete: function(){
				$(location).attr('href', redirect);
			}
		});
	}

    /*---------------------------------------------
	* PW TEMPLATE DROPZONE - This can be reusable
	-----------------------------------------------*/
    function dropZonePW(dz){

		var status = true;
        var imageUpload =  new Dropzone("#"+dz.id, {
            url: dz.url,
            autoProcessQueue: dz.autoProcessQueue,
            uploadMultiple: dz.uploadMultiple,
            maxFilesize: dz.maxFilesize,
            maxFiles: dz.maxFiles,
            acceptedFiles: dz.acceptedFiles,
            parallelUploads:1,
            uploadMultiple: false,
			addRemoveLinks: true,
			timeout: 180000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function() {
                //imageUpload = this; // closure
                var submitButton = $("#"+dz.submitId);
                submitButton.click(function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    imageUpload.processQueue(); // Tell Dropzone to process all queued files.
                });

                this.on("addedfile", function() {
					// Show submit button here and/or inform user to click it.
					submitButton.show();
                });

				this.on("complete", function (file) {

					if(file.status == 'error'){
						status = false;
					}

					if(status){
						if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
							if (typeof dz.redirect !== 'undefined'){
							    window.location.replace(dz.redirect);
							}else{
							    location.reload();
							}
						}
					}else{
						$('#refresh-page').show();
					}

			    });

				this.on("error", function (file, response) {
					if (typeof response.errors !== 'undefined'){
						var errorMessages = response.errors.file;
						var errorDisplay = "";
						$.each( errorMessages, function( key, error ) {
							errorDisplay = errorDisplay+' '+error;
						});
						if(errorDisplay != ""){
							$('.dz-error-message').html(errorDisplay);
							$('.dz-error-message-display').html('<div class="alert alert-danger">'+errorDisplay+'</div>');
						}
					}else{
						if (typeof response !== 'undefined'){
							$('.dz-error-message-display').html('<div class="alert alert-danger">'+response+'</div>');
						}
					}
				});

            }
        });

        imageUpload.on('success', imageUpload.processQueue.bind(imageUpload));

        imageUpload.on('success', function(file, response) {
			var args = Array.prototype.slice.call(arguments);

        });

    }

    /*------------------------------------
	* DROPZONE FOR PROPERTY PHOTO
	------------------------------------*/
    if ($('#unit-photo-upload').length > 0) {
        function unitPhoto()
        {
            var unit_id = $('.unit-id').val();
            var dz = {
                id: 'unit-photo-upload',
                submitId: 'submit-all',
                url: '/admin/development-unit/'+unit_id+'/photo-upload',
                autoProcessQueue: false,
                uploadMultiple: true,
                maxFilesize:100,
                maxFiles:35,
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.tif",
            };

            dropZonePW(dz);
        }
        unitPhoto();
    }
});
