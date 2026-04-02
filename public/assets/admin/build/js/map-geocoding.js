$(function(){

	var map_id = 'map-view';

	if ($('#'+map_id).length > 0) {

		var map_zoom = 12;
		var map_lat = $('.os-latitude').val();
		var map_lng = $('.os-longitude').val();

		map_lat = (map_lat != '') ? map_lat : 54.18855725279879;
		map_lng = (map_lng != '') ? map_lng : -2.0777893066406254;

		var map = L.map(map_id).setView([map_lat, map_lng], map_zoom);

		L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
		    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);

		var myIcon = L.icon({
		    iconUrl: '/public/assets/admin/build/images/pw-theme/map/map-pin.png',
		    iconSize: [39, 47],
		    iconAnchor: [20, 47]
		});

		var marker = L.marker([map_lat, map_lng], {icon: myIcon, draggable: true})
		.addTo(map);

		// Update coordinates when marker dragged
		marker.on("drag", function(e) {
		    var marker = e.target;
		    var position = marker.getLatLng();
		    $('.os-latitude').val(position.lat);
			$('.os-longitude').val(position.lng);
		});

		// Search to openstreetmap
		$('#search_geocoding').click(function(){

			var searchBtn = $(this);
			var street = $('#id-street').val();
			var town = $('#id-town').val();
			var city = $('#id-city').val();
			var region = $('#id-region').val();
			var postcode = $('#id-postcode').val();
			var country = $('#id-country').val();

			var alertDiv = $('.addresSearchIndicator');
			var address = '';

			// generate the address
			// only city and country for better map search
			if(street){
				//address += street+', ';
			}
			if(town){
				//address += town+', ';
			}
			if(city){
				address += city+', ';
			}
			if(region){
				//address += region+', ';
			}
			if(postcode){
				//address += postcode+', ';
			}
			if(country){
				address += country;
			}

			searchBtn.html('Loading...');
			searchBtn.attr('disabled', true);

			// search through geocoding
			var url = 'https://nominatim.openstreetmap.org/search?format=json&polygon=0&addressdetails=0&q='+address;

			$.ajax({
		        type: "GET",
		        dataType: "json",
		        url: url,
		        success: function(result){

		        	if(result.length){
		        		var location_data = result[0];
		        		var location_latlng = [location_data.lat, location_data.lon];
						var newLatLng = new L.LatLng(location_data.lat, location_data.lon);

	    				marker.setLatLng(newLatLng);
	    				map.setView([location_data.lat, location_data.lon], map_zoom);

	    				$('.os-latitude').val(location_data.lat);
	    				$('.os-longitude').val(location_data.lon);

	    				alertDiv.html('');
		        	}else{
		        		var alert = '<div class="alert alert-danger alert-dismissible show" role="alert">'+
	  						'No result found for address: <strong>'+address+'</strong>'+
	  							'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
	    							'<span aria-hidden="true">&times;</span>'+
	  							'</button>'+
								'</div>';
		        		alertDiv.html(alert);
		        	}

		        	searchBtn.attr('disabled', false);
		        	searchBtn.html('Search location');
		        }
		    });

		});

	}

});
