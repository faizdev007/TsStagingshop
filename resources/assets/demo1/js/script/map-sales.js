import $ from 'jquery';
window.$ = window.jQuery = $;

$('.showMap').click(function(){
     setupMap();
});

$('.search-map--toggler').click(function(){
   if($(this).hasClass('active')){
        $('#mapToggle').fadeOut();
        $(this).removeClass('active');
    }else{
        $('#mapToggle').fadeIn();  
         $(this).addClass('active');     
        setupMap();
    }
    return false;
});

function setupMap(){
    // Setup the different icons and shadows
    var iconURLPrefix = 'https://maps.google.com/mapfiles/ms/icons/';

    var icons = [
        iconURLPrefix + 'red-dot.png',
    ]
    var iconsLength = icons.length;

    var map = new google.maps.Map(document.getElementById('map-search'), {
        zoom: 5,
        minZoom: 4,
        maxZoom: 15,
        scrollwheel: true,
        center: new google.maps.LatLng(14.113239320935786, 99.57413977500005),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        streetViewControl: false,
        panControl: false,
        zoomControlOptions: {
            position: google.maps.ControlPosition.LEFT_BOTTOM
        }
    });

    var infowindow = new google.maps.InfoWindow({
        maxWidth: 160
    });

    var markers = new Array();

    var iconCounter = 0;

// Add the markers and infowindows to the map
    for (var i = 0; i < locations.length; i++) {
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: icons[iconCounter]
        });

        markers.push(marker);

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));

        iconCounter++;
        // We only have a limited number of possible icon colors, so we may have to restart the counter
        if (iconCounter >= iconsLength) {
            iconCounter = 0;
        }
    }

    function autoCenter() {
        //  Create a new viewpoint bound
        var bounds = new google.maps.LatLngBounds();
        //  Go through each...
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].position);
        }
        //  Fit these bounds to the map
        map.fitBounds(bounds);
    }
    autoCenter();
}

function destroyMap(){

}
