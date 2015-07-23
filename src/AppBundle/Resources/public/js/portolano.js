$(document).ready(function () {



    // Provide your access token
    L.mapbox.accessToken = 'pk.eyJ1Ijoid2FrYXJldmEiLCJhIjoiMzhjZGQ4M2VlZThhNjZlYWZmODg1OTE2MWUyNjlkNjYifQ.jBl7tEajPnDGJzYqxMGMag';
    // Create a map in the div #map
    var map = L.mapbox.map('map', 'mapbox.streets');
    var myLayer = L.mapbox.featureLayer()
        .loadURL('/json/markers.geojson')
        .addTo(map);

    var markerList = document.getElementById('marker-list');


    map.on('click', function (e) {
        var marker = L.marker([e.latlng.lat, e.latlng.lng]);
        $("#latitudine").val(e.latlng.lat);
        $("#longitudine").val(e.latlng.lng);
        marker.addTo(map);
        $("#modalPortolano").modal().on('hidden.bs.modal', function () {
            map.removeLayer(marker);
        });
    });


    myLayer.on('ready', function (e) {
        map.fitBounds(myLayer.getBounds());
    });

// This uses the HTML5 geolocation API, which is available on
// most mobile browsers and modern browsers, but not in Internet Explorer
//
// See this chart of compatibility for details:
// http://caniuse.com/#feat=geolocation
    if (!navigator.geolocation) {
        geolocate.innerHTML = 'Geolocation is not available';
    } else {
        geolocate.onclick = function (e) {
            e.preventDefault();
            e.stopPropagation();
            map.locate();
        };
    }

// Once we've got a position, zoom and center the map
// on it, and add a single marker.
    map.on('locationfound', function (e) {
        map.fitBounds(e.bounds);

        myLayer.setGeoJSON({
            type: 'Feature',
            geometry: {
                type: 'Point',
                coordinates: [e.latlng.lng, e.latlng.lat]
            },
            properties: {
                'title': 'Here I am!',
                'marker-color': '#ff8888',
                'marker-symbol': 'star'
            }
        });

        // And hide the geolocation button
        geolocate.parentNode.removeChild(geolocate);
    });

// If the user chooses not to allow their location
// to be shared, display an error message.
    map.on('locationerror', function () {
        geolocate.innerHTML = 'Position could not be found';
    });


});