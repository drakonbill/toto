var geocoder;
var map;
var circle;
var pos;
var pos_me;
pos_me = new google.maps.LatLng(lat, lon);
var markers = new Array();

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

            pos = new google.maps.LatLng(lat,
                    lon);

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
            
            map.fitBounds(circle.getBounds());
            initializeEvent();

    /*google.maps.event.addListener(marker, 'click', function() {

    });*/
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
    
    map.fitBounds(circle.getBounds());
}

function changePos(where) {
    if(where == "other") {
       var address = document.getElementById('address').value;
        geocoder.geocode({'address': address}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                pos = results[0].geometry.location;
                map.setCenter(results[0].geometry.location);
                circle.setCenter(results[0].geometry.location);
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }
    else {
        pos = pos_me;
        map.setCenter(pos);
        circle.setCenter(pos);
    }
}

function hideInfoevent() {
    if($("#infobulle_searchevent").is(':visible'))  
        $("#infobulle_searchevent").animate({top:"-200px"}, 1000, "easeOutBounce", function() {
            $("#infobulle_searchevent").html("");
        });
}

function initializeEvent() {
    $("#map-canvas").append("<div id='infobulle_searchevent'></div><div id='queue'></div>");
    for(key in event_data) {
        var div = '<div id="marker'+event_data[key]['id_event']+'" class="marker_searchevent"><img src="'+event_data[key]['image_event']+'" alt="'+event_data[key]['name_event']+'" />'+event_data[key]['name_event']+'</div>';
       
        /*var image = {
            url: '/Images/iconesevent/iconfete.png',
            // This marker is 20 pixels wide by 32 pixels tall.
            size: new google.maps.Size(32, 37),
            // The origin for this image is 0,0.
            origin: new google.maps.Point(0,0),
            // The anchor for this image is the base of the flagpole at 0,32.
            anchor: new google.maps.Point(18.5, 37)
          };*/
         var marker = new google.maps.Marker({
               position: new google.maps.LatLng(event_data[key]['latitude_event'], event_data[key]['longitude_event']),
               map: map
          });
          
        markers.push(new RichMarker({
          map: map,
          position: new google.maps.LatLng(event_data[key]['latitude_event'], event_data[key]['longitude_event']),
          content: div,
          flat: true,
          nameevent: event_data[key]['name_event'],
          idevent: event_data[key]['id_event'],
          imageevent: event_data[key]['image_event'],
          descriptionevent: event_data[key]['description_event']
        }));

    }
    
    for(key in markers) {
        google.maps.event.addListener(markers[key], "mouseover", function(event) {
            $("#marker"+this.get('idevent')).css({"background":"white"});
            this.setZIndex(15);
         });
         google.maps.event.addListener(markers[key], "mouseout", function(event) {
            $("#marker"+this.get('idevent')).css({"background":"#0e72b5"});
            this.setZIndex(10);
         });
         google.maps.event.addListener(markers[key], "click", function(event) {
             var imageevent = this.get('imageevent');
             var nameevent = this.get('nameevent');
             var descriptionevent = this.get('descriptionevent');
              
                if($("#infobulle_searchevent").is(':visible'))  
                    $("#infobulle_searchevent").animate({top:"-200px"}, 1000, "easeOutBounce", function() {
                        $("#infobulle_searchevent").html("<img src='"+imageevent+"' alt='"+nameevent+"'/><h2>"+nameevent+"</h2>"+descriptionevent+"<a href='javascript:void();' onclick='hideInfoevent()'>Fermer</a>");
                    });
                else
                    $("#infobulle_searchevent").html("<img src='"+imageevent+"' alt='"+nameevent+"'/><h2>"+nameevent+"</h2>"+descriptionevent+"<a href='javascript:void();' onclick='hideInfoevent()'>Fermer</a>");
            
            
            $("#infobulle_searchevent").animate({top:"10px"}, 1000, "easeOutBounce");
         });
    }
}


$(function() {

    google.maps.event.addDomListener(window, 'load', initialize);
    
    $("#from").live('change', function() {
        if($(this).val() == "other")
            $("#changepos").html('<input type="text" id="address" name="address" value="" /><a href="javascript:void();" onclick="changePos(\'other\');">Changer de position</a>');
        else {
            changePos('here');
            $("#changepos").html("");
        }
    });
});