/**
 * Created by christiancannata on 13/05/15.
 */
$(document).ready(function () {



    // Provide your access token
    L.mapbox.accessToken = 'pk.eyJ1IjoiY2hyaXN0aWFuMTQ4OCIsImEiOiJZaldjZlM0In0.hXiRMyyCDLdQZUrqXF2eNw';
    // Create a map in the div #map
    var map = L.mapbox.map('map', 'mapbox.streets');
    var myLayer = L.mapbox.featureLayer()
        .loadURL('/json/porti.geojson')
        .addTo(map);
    var markerList = document.getElementById('marker-list');

    myLayer.on('click', function (e) {
        var marker = e.layer;
        feature = marker.feature;
        var id = feature.id;

        $.ajax({
            url: "/porti/" + id + ".json",
            dataType: "json",
            success: function (response) {
                $("#loading-porto").show();
                $(".porto-title").css("opacity","0.5");
                $(".porto-content").css("opacity","0.5");

                //SET CONTENUTO DEL PORTO

                $.ajax({
                    url: "porti/" + id + "/meteo.json",
                    dataType: "json",
                    success: function (response) {

                        console.log(response);

                        $.ajax({
                            url: "http://api.openweathermap.org/data/2.5/weather?units=metric&lat=" + e.latlng.lat + "&lon=" + e.latlng.lng,
                            dataType: "json",
                            success: function (location) {

                                $("#porto-nome").html(location.name);

                                $("#porto-temperatura").html(parseInt(location.main.temp)+"°");
                                console.log(location);
                                $("#loading-porto").hide();
                                $(".porto-title").css("opacity","1");
                                $(".porto-content").css("opacity","1");
                            }
                        });
                    }
                });

            }
        });

    });


    myLayer.on('ready', function (e) {
        map.fitBounds(myLayer.getBounds());

        map.featureLayer.eachLayer(function (layer) {
            var item = markerList.appendChild(document.createElement('li'));
            item.innerHTML = layer.toGeoJSON().properties.title;
            item.onclick = function () {
                map.setView(layer.getLatLng(), 14);
                layer.openPopup();
            };
        });
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