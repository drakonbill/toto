
function onPopupClose(evt) {
	this.destroy();
	
	map.removeLayer(markers);
	markers='null';
	popup='null';
}
	
function init(){
	var myLocation = new OpenLayers.Geometry.Point(lon, lat).transform('EPSG:4326', 'EPSG:3857');
	
	var popup = new OpenLayers.Popup.FramedCloud("Popup", 
        myLocation.getBounds().getCenterLonLat(), null,
        'Event', null,
        true // <-- true if we want a close (X) button, false otherwise
    );
	
	map = new OpenLayers.Map('map');
	layer = new OpenLayers.Layer.OSM("OpenStreetMap",null, {transitionEffect: "resize"});
	map.addLayer(layer);
	map.setCenter(
		new OpenLayers.LonLat(lon, lat).transform(
			new OpenLayers.Projection("EPSG:4326"),
			map.getProjectionObject()
		), 11
	);    
	markers = new OpenLayers.Layer.Markers("Evènement");
	map.addLayer(markers);	
			
	feature = new OpenLayers.Feature(map, new OpenLayers.Geometry.Point(lon, lat));
	marker = feature.createMarker();
	markers.addMarker(marker);
	
	/*popup = new OpenLayers.Popup("Event",
	   myLocation,
	   new OpenLayers.Size(215,75),
	   "Event",
	   true, onPopupClose);	
	
	map.addPopup(popup);*/
}

$(document).ready(function() {
	init();
});
		
pos_scroll=0;
$("#sidebar-right").live('click', function() {
	alert(pos_scroll);
	//alert($(window).height()+" "+$("#sidebar-left").height());
//alert($(window).scrollTop() - ($(".blue-box-top.blue-box-asdf").offset().top - $("#sidebar-left").height()));
});
$(window).scroll(function() {
	
	if($(window).height() >= $("#sidebar-left").height()) { 
		position_left = $("#sidebar-left").offset();
		position_right = $("#sidebar-right").offset();
		
		if($(window).scrollTop() - ($(".blue-box-top.blue-box-asdf").offset().top - $("#sidebar-left").height()) >= 0) {
			if(pos_scroll != 2)
				$("#sidebar-left").css({"position":"absolute","bottom":"0","left":"0","margin-left":"0","top":"","z-index":""});
			pos_scroll=2;
		}
		else if($(window).scrollTop() - position_right.top >= 0) {
			if(pos_scroll != 1)
				$("#sidebar-left").css({"position":"fixed","top":"0","left":"0","margin-left":"0","bottom":"","z-index":"15000"});
			pos_scroll=1;
		}
		
		// En attendant un repère je prends la div de droite comme repère
		if($(window).scrollTop() - position_right.top < 0) {
			if(pos_scroll!=0)
				$("#sidebar-left").css({"position":"relative","margin-left":"-100%","top":"","bottom":"","left":"","z-index":""});
			pos_scroll=0;
		}
	}
	else if(pos_scroll!=0) {
		$("#sidebar-left").css({"position":"relative","margin-left":"-100%","top":"","bottom":"","left":"","z-index":""});
	}
});