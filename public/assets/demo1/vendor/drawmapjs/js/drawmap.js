
var $drawmap = $('#drawmap');
var $D = {
	loading: $drawmap.find('#drawmap-loading'),
	closeHelp: $drawmap.find('#closeHelp'),
	help: $drawmap.find('#drawmap-help'),
	searchStatus: $drawmap.siblings('#search-status'),
	btnClear: $drawmap.find('#drawmap-button-clear'),
	btnHelp: $drawmap.find('#drawmap-button-help').hide(),
	btnGoto: $drawmap.find('#drawmap-button-goto')
};
var basepath = $('base').attr('href');
var themeAsset = $('header').data('themeAsset');

if (! basepath) basepath = '/';
var O = {
    defaultLatitude: (typeof defaultLat != 'undefined' ? defaultLat : $('#drawmap-map').data('lat')),
    defaultLongitude: (typeof defaultLng != 'undefined' ? defaultLng : $('#drawmap-map').data('lng')),
	basePath: basepath,
	themeAsset: themeAsset,
	maxPropertiesGet: 500,
	rentSale: 1,
	cookieName: 'poly_saved',
	currentMarkers: [],
	mapMarkers: [],
	urlKeys: [],
	polygonOptions: {
		fillColor: '#042849',
		fillOpacity: 0.3,
		strokeColor: '#042849',
		strokeOpacity: 1,
		strokeWeight: 3,
		clickable: true,
		editable: true,
		zIndex: 1
	}
};

var polygon;
var polyObj = getPoly();

var mapOptions = {
	center: new google.maps.LatLng(O.defaultLatitude, O.defaultLongitude),
	zoom: $('#drawmap-map').data('zoom') ? $('#drawmap-map').data('zoom') : 7,
	scrollwheel: true, // false,
	mapTypeId: google.maps.MapTypeId.ROADMAP,
	mapTypeControl: false,
	panControl: false,
	scaleControl: false,
	streetViewControl: false
};

var map = new google.maps.Map(document.getElementById('drawmap-map'), mapOptions);


var oms = new OverlappingMarkerSpiderfier(map, {
	markersWontMove: true,   // we promise not to move any markers, allowing optimizations
	markersWontHide: true,   // we promise not to change visibility of any markers, allowing optimizations
	basicFormatEvents: true  // allow the library to skip calculating advanced formatting information
});


var drawingManager = new google.maps.drawing.DrawingManager({
	drawingMode: google.maps.drawing.OverlayType.POLYGON,
	drawingControl: false,
	drawingControlOptions: {
		drawingModes: [
			google.maps.drawing.OverlayType.POLYGON
		]
	},
	polygonOptions: O.polygonOptions
});

var markerCluster;

drawingManager.setMap(map);

google.maps.event.addListener(drawingManager, 'polygoncomplete', function(poly) {
	stopDrawing();
	polygon = poly;
	google.maps.event.addListener(polygon.getPath(), "set_at", onCreate);

	onCreate();
});

// Clear any v1 saved Drawmaps
if (polyExists()) {
	if ("number" != typeof polyObj.ver) {
		clearUp();
	}
}

if (polyExists()) {
	stopDrawing();

	var the_coords = [];

	for (i in polyObj.coords) {
		the_coords.push (new google.maps.LatLng(
            polyObj.coords[i].lat,
            polyObj.coords[i].lng
        ));
	}

	opts = O.polygonOptions;
	opts.map = map;
	opts.paths = the_coords;

	polygon = new google.maps.Polygon(opts);

	map.setCenter(new google.maps.LatLng(polyObj.lat, polyObj.lng));
	map.setZoom(polyObj.zoom);

	polygon.setMap(map);

	google.maps.event.addListener(polygon.getPath(), 'set_at', onRefresh);

	onRefresh();
} else {
	$D.btnClear.hide();
}


function onCreate() {
	onUpdate();
}

function onRefresh() {
	onUpdate();
}

$(".mode").change(function(){
	deleteMarkers();
	getProperties();
});

function onUpdate() {
	savePoly();
	deleteMarkers();
	getProperties();
}

function polyExists() {
	return (polyObj !== null);
}

function getPoly() {
	return JSON.parse(getCookie(O.cookieName));
}

function savePoly() {
	var coords = [];

	for (var i = 0; i < polygon.getPath().length; i++) {
		var xy = polygon.getPath().getAt(i);
		coords.push(xy);
	}

	center = map.getCenter();

    var cookieData = JSON.stringify({
        coords: coords,
        center: JSON.stringify(center),
        lat: center.lat(),
        lng: center.lng(),
        ver: 2,
        zoom: map.getZoom()
    });

	return setCookie(O.cookieName, cookieData, 2880);
}

function deletePoly() {
	deleteCookie(O.cookieName);
	polygon.setMap(null);
	return;
}

function getProperties() {

	var bounds = new google.maps.LatLngBounds();
	if(polygon){
		polygon.getPath().forEach( function(latlng) { bounds.extend(latlng); } );
	}

	var propertyRange = {
		NE_LAT: bounds.getNorthEast().lat(),
		NE_LNG: bounds.getNorthEast().lng(),
		SW_LAT: bounds.getSouthWest().lat(),
		SW_LNG: bounds.getSouthWest().lng()
	};

	$D.loading.fadeIn();
	if ($D.btnClear.is(':hidden')) { $D.btnClear.fadeIn(); }

	var mode = $(".mode:checked").val();
	//ajax/get_properties/?ne_lat=53.45095358461929&ne_lng=4.711755539062551&sw_lat=49.655738313175895&sw_lng=-4.319006179687449&max=500&mode=1
	var ajax_url = O.basePath+'drawmap/ajax/get_properties/?ne_lat='+propertyRange.NE_LAT+'&ne_lng='+propertyRange.NE_LNG+'&sw_lat='+propertyRange.SW_LAT+'&sw_lng='+propertyRange.SW_LNG+'&max=500&mode='+parseInt(mode);


	$.get(ajax_url, function(d){

		var properties = JSON.parse(d);

		var infowindow = new google.maps.InfoWindow({
			size: new google.maps.Size(500, 530),
			pixelOffset: new google.maps.Size(0,0)
		});

		function bindInfoWindow(marker, map, contentString, url) {
			google.maps.event.addListener(marker, 'click', (function() {
	          infowindow.setContent(contentString);
	          infowindow.open(map,marker);
	        }));
		}

		for (var i in properties) {
			var propid = properties[i].id;
			var myLatLng = new google.maps.LatLng(properties[i].latitude, properties[i].longitude);

			// if within the boundaries, proceed
			if (polygon.Contains(myLatLng)) {

				if (properties[i].is_sale == true) {
					tender_text = 'Available For Sale';
				} else {
					tender_text = 'Available For Rent';
				}

				var html =   properties[i].html;

				var contentString = html;
				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					icon: new google.maps.MarkerImage(O.themeAsset+'/images/drawmap/marker.png', new google.maps.Size(29,40) ),
					title: properties[i].title
				});

				bindInfoWindow(marker, map, contentString, properties[i].url);

				O.urlKeys = properties[i].url_key;
				O.currentMarkers.push(propid);
				O.mapMarkers.push(marker);
				oms.addMarker(marker);
			}
		}

		var clusterStyles = [
		  {
		    textColor: '#032849',
			textSize: '18',
		    url: O.themeAsset+'/images/drawmap/cluster-marker.png',
		    height: 99,
		    width: 54,
			backgroundPosition:'0px 25px'
		  }
		];

		markerCluster = new MarkerClusterer(map,O.mapMarkers,
			{
				imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
				maxZoom: 12,
				styles: clusterStyles,
			}
		);

		var marker_count, id_str, results_href;
		marker_count = O.currentMarkers.length;

		if (marker_count == 0) {
			results_href = 'javascript:void(0);';
			$D.btnGoto
				.css({cursor:'text !important'})
				.find('#drawmap-small')
				.text('Try extending your search area');
		} else {

			$D.btnGoto
				.find('#drawmap-small')
				.text('View ' + (marker_count == 1 ? 'it' : 'them') + ' now');

			if (marker_count >= O.maxPropertiesGet) { marker_count = O.maxPropertiesGet + '+'; }

			id_str = base64_encode(O.currentMarkers.join(','));

			if(mode == 0){
				$D.loading.fadeOut();
				$D.btnGoto
				.find('#drawmap-propcount')
					.html(marker_count + ' Propert' + (marker_count == 1 ? 'y' : 'ies') + ' Found')
					.end()
				.fadeIn();
			}
		}

		$D.loading.fadeOut();

		$D.btnGoto
			.find('#drawmap-propcount')
				.html(marker_count + ' Propert' + (marker_count == 1 ? 'y' : 'ies') + ' Found')
				.end()
			.fadeIn();

		$('#drawmap-map').blur(); // check
	});
}

function stopDrawing() {
	drawingManager.setDrawingMode(null);
}

function startDrawing() {
	drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
}

function clearUp() {
	deleteMarkers();
	deletePoly();
	$D.btnClear.hide();
	$D.btnGoto.hide();
	$D.searchStatus.remove();
	startDrawing();
}

function deleteMarkers() {

	if(O.mapMarkers.length){
		markerCluster.clearMarkers(); //REMOVING THE CLUSTER
	}

	for (i in O.mapMarkers) {
		O.mapMarkers[i].setMap(null);
	}

	O.currentMarkers = [];
	O.mapMarkers.length = 0;
	oms.forgetAllMarkers();
}

function number_format (number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

// Start cookie functions
function setCookie(name, value, mins) {
	if (mins) {
		var date = new Date();
		date.setTime(date.getTime() + (mins*60*1000));
		var expires = '; expires=' + date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

function deleteCookie(name) {
	setCookie(name,'',-1);
}
// End cookie functions

if (!google.maps.Polygon.prototype.getBounds)
	google.maps.Polygon.prototype.getBounds = function() {
	var bounds = new google.maps.LatLngBounds();
	this.getPath().forEach( function(latlng) { bounds.extend(latlng); } );
	return bounds;
}

if (!google.maps.Polygon.prototype.contains) {
	google.maps.Polygon.prototype.Contains = function(point) {
		var j=0;
		var oddNodes = false;
		var x = point.lng();
		var y = point.lat();
		for (var i=0; i < this.getPath().length; i++) {
			j++;
			if (j == this.getPath().length) {j = 0;}
			if (((this.getPath().getAt(i).lat() < y) && (this.getPath().getAt(j).lat() >= y))
				|| ((this.getPath().getAt(j).lat() < y) && (this.getPath().getAt(i).lat() >= y))) {
				if ( this.getPath().getAt(i).lng() + (y - this.getPath().getAt(i).lat())
					/  (this.getPath().getAt(j).lat()-this.getPath().getAt(i).lat())
					*  (this.getPath().getAt(j).lng() - this.getPath().getAt(i).lng())<x ) {
					oddNodes = !oddNodes
				}
			}
		}
		return oddNodes;
	}
}

function base64_encode (data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Tyler Akins (http://rumkin.com)
  // +   improved by: Bayron Guevara
  // +   improved by: Thunder.m
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Pellentesque Malesuada
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Rafal Kukawski (http://kukawski.pl)
  // *     example 1: base64_encode('Kevin van Zonneveld');
  // *     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
  // mozilla has this native
  // - but breaks in 2.0.0.12!
  //if (typeof this.window['btoa'] == 'function') {
  //    return btoa(data);
  //}
  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
	ac = 0,
	enc = "",
	tmp_arr = [];
  if (!data) {
	return data;
  }
  do { // pack three octets into four hexets
	o1 = data.charCodeAt(i++);
	o2 = data.charCodeAt(i++);
	o3 = data.charCodeAt(i++);
	bits = o1 << 16 | o2 << 8 | o3;
	h1 = bits >> 18 & 0x3f;
	h2 = bits >> 12 & 0x3f;
	h3 = bits >> 6 & 0x3f;
	h4 = bits & 0x3f;
	// use hexets to index into b64, and append result to encoded string
	tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);
  enc = tmp_arr.join('');
  var r = data.length % 3;
  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}

$(function(){

	$D.btnClear
	.click(function(){
		if (confirm ('Discard your drawing and start again?')) { clearUp(); }
	});

	/*
	$D.btnHelp
	.click(function(){
		$D.help
			.css({'left':'50px'})
			.fadeToggle('slow');
	});
	*/


	$D.btnHelp
	.click(function(e){
		e.preventDefault();
		$D.help
			.css({'left':'10px'})
			.fadeToggle('slow');

		$D.btnHelp
			.css({'left':'50px'})
			.hide('slow');
	});

	$D.closeHelp
	.click(function(e){
		e.preventDefault();
		$D.btnHelp
			.css({'left':'50px'})
			.fadeToggle('slow');

		$D.help
			.css({'left':'10px'})
			.hide('slow');

	});


});

/* ------------------------------------------------------------- */
/* for show all map */

var set_elem_id = 'showall-map';
var singular = false;
var query = false;
