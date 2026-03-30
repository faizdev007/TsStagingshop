var mapleaf;

$('.map-object').each(function() {

	var map_lat = $(this).data('lat');
	var map_lng = $(this).data('lng');
	var map_zoom = $(this).data('zoom');
	var map_marker = $(this).data('marker');
	var map_id = $(this).attr('id');
	map_id = !map_id ? 'property-map' : map_id;
	map_marker = !map_marker ? 'marker.png' : map_marker;

	var map_data = {
		'id' : map_id,
		'zoom' : map_zoom,
		'marker' : map_marker,
		'lat' : map_lat,
		'lng' : map_lng
	};

	leaflet_map(map_data);

});

function leaflet_map(map_data)
{
	var map_id = map_data.id;
	var map_zoom = map_data.zoom;
	var map_lat = map_data.lat;
	var map_lng = map_data.lng;
	var map_marker = map_data.marker;
	var themeAsset = $('header').data('themeAsset');

	mapleaf = L.map(map_id).setView([map_lat, map_lng], map_zoom);

	L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(mapleaf);

	// var myIcon = L.divIcon({
	// 	html: '<i class="fas fa-map-marker-alt c-primary"></i>',
    // 	iconSize: [20, 20],
    // 	//iconAnchor: [22, 94],
    // 	className: 'map--marker-con'
	// });

    var myIcon = L.AwesomeMarkers.icon(
        {
            prefix: 'fas fa-map-marker-alt',
            markerColor: 'purple', // Values = 'red', 'darkred', 'orange', 'green', 'darkgreen', 'blue', 'purple', 'darkpurple', 'cadetblue'
            extraClasses: 'c-primary'
        }
    );

	/*var myIcon = L.icon({
	    iconUrl: themeAsset+'/images/map/'+map_marker,
	    iconSize: [61, 87],
	    iconAnchor: [30, 50],
	    shadowSize: [0, 0],
	    popupAnchor: [0, 0]
	});*/

	L.marker([map_lat, map_lng], {icon: myIcon})
	.addTo(mapleaf);


	/*------------------------------
	* TAB MAP - PROPERTY 2
	*------------------------------*/
	$('#property-tab-1 a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var id = $(e.target).attr('id');
		if(id=='map-tab'){
			mapleaf.invalidateSize();
		}
	});

}
