import $ from 'jquery';
window.$ = window.jQuery = $;

$(function(){

    var base_url = $('base').attr('href');
    var mapObject = $(".map-attr");

    function setMap(){
      mapObject.each(function(e){
        var id = ($(this).attr('id'));
        var latitude = parseFloat($(this).data('lat'));
        var longitude = parseFloat($(this).data('lng'));
        var zoom = parseInt($(this).data('zoom'));
        var streetview = parseInt($(this).data('streetview'));
        var mapPin = ($(this).data('pin'));

        if (typeof mapPin === "undefined"){ mapPin = "marker-1.png"; }
        if (typeof streetview === "undefined"){ streetview = ""; }
        if (isNaN(zoom)){ zoom = 10; }

        initMap(id,latitude,longitude,zoom,mapPin,streetview);
      });
    }


    function initMap(id,latitude,longitude,zoom,mapPin,streetview) {
      var  map = "";
        // Specify features and elements to define styles.
      var styleArray=[{featureType:"water",elementType:"geometry",stylers:[{color:"#e9e9e9"},{lightness:17}]},{featureType:"landscape",elementType:"geometry",stylers:[{color:"#f5f5f5"},{lightness:20}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{color:"#ffffff"},{lightness:17}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{color:"#ffffff"},{lightness:29},{weight:.2}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:18}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:16}]},{featureType:"poi",elementType:"geometry",stylers:[{color:"#f5f5f5"},{lightness:21}]},{featureType:"poi.park",elementType:"geometry",stylers:[{color:"#dedede"},{lightness:21}]},{elementType:"labels.text.stroke",stylers:[{visibility:"on"},{color:"#ffffff"},{lightness:16}]},{elementType:"labels.text.fill",stylers:[{saturation:36},{color:"#333333"},{lightness:40}]},{elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"geometry",stylers:[{color:"#f2f2f2"},{lightness:19}]},{featureType:"administrative",elementType:"geometry.fill",stylers:[{color:"#fefefe"},{lightness:20}]},{featureType:"administrative",elementType:"geometry.stroke",stylers:[{color:"#fefefe"},{lightness:17},{weight:1.2}]}];

      var themeAsset = $('header').data('themeAsset');

        var isDraggable = $(document).width() > 1024 ? true : false;
        var myLatLng = {lat: parseFloat(latitude), lng: parseFloat(longitude)}



        // Create a map object and specify the DOM element for display.
        map = new google.maps.Map(document.getElementById(id), {
            center: myLatLng,
            scrollwheel: true,
            streetViewControl: false,
            // Apply the map style array to the map.
            //styles: styleArray,
            draggable: isDraggable,
            zoom: zoom
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: ''
            , icon: themeAsset + '/images/map/'+mapPin
        });

        //alert(themeAsset + '/images/map/'+mapPin);
        //map.panBy(0,-30);

        if (streetview==1){

          var heading = parseFloat($('#'+id).data('heading'));
          var pitch = parseFloat($('#'+id).data('pitch'));
          var panorama = new google.maps.StreetViewPanorama(
           document.getElementById(id), {
             position: myLatLng,
             pov: {
               heading: heading,
               pitch: pitch
             }
           });
           map.setStreetView(panorama);
        }
    }

    setMap();

});
