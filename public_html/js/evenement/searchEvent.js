var geocoder;
var map;
var circle;
var pos;

function handleNoGeolocation(errorFlag) {
    if (errorFlag) {
        var content = 'Error: The Geolocation service failed.';
    } else {
        var content = 'Error: Your browser doesn\'t support geolocation.';
    }
}

function initialize() {
    geocoder = new google.maps.Geocoder();
    var myLatlng = new google.maps.LatLng(-25.363882, 131.044922);
    var mapOptions = {
        zoom: 4,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            pos = new google.maps.LatLng(position.coords.latitude,
                    position.coords.longitude);

            var infowindow = new google.maps.InfoWindow({
                map: map,
                position: pos,
                content: 'Je suis ici actuellement.'
            });

            map.setCenter(pos);
            
            circle = new google.maps.Circle({
            map: map,
            center: pos,
            fillColor: '#00FF00',
            fillOpacity: 0.2,
            strokeColor: '#00FF00',
            strokeOpacity: 0.4,
            strokeWeight: 2,
            radius: 18362.55489862987
            });
            
            $("#distance").slider({
                from: 1,
                to: 500,
                heterogeneity: ['12.5/25', '50/100', '75/250'],
                scale: [1, '|', 50, '|', '100', '|', 250, '|', 500],
                step: 1,
                dimension: '&nbsp;km',
                onstatechange: function(value) {
                    changeRadiusMap(value);
                }
            });
        }, function() {
            handleNoGeolocation(true);
        });
    } else {
        // Browser doesn't support Geolocation
        handleNoGeolocation(false);
    }

    google.maps.event.addListener(marker, 'click', function() {

    });
}

function codeAddress() {
    var address = document.getElementById('address').value;
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

function changeRadiusMap(distance) {
    circle.setRadius(distance*1000);
}


$(function() {

    google.maps.event.addDomListener(window, 'load', initialize);

});