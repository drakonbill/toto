
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

function comeon(idevent) {
	$.ajax({
		type: 'GET',
		url: '/ajax/partipateEvent',
		data: "idevent="+idevent,
		success:
			function(result) {
				result = result.split(";");
				if(result[0] == "ok")
					$("#comeon").text(result[1]);
			}
	});
}

function fan(idevent) {
	$.ajax({
		type: 'GET',
		url: '/ajax/fanEvent',
		data: "idevent="+idevent,
		success:
			function(result) {
				$("#fan").text(result);
			}
	});
}

$(document).ready(function() {
	init();
	
	
	$("form.comment input#sendComment").live('keydown', function(event) {
		if(event.which == 13) {
			if($.trim($(this).val()) != "" && $.trim($("form.comment input#idevent").val()) != "") {
				$("form.comment input#sendComment").css({'background':'url(/Images/ajax-loader.gif) no-repeat right'});
				$.ajax({
					type: 'POST',
					url: '/ajax/addCommentEvent',
					data: "idevent="+$.trim($("form.comment input#idevent").val())+"&message="+$.trim($(this).val())+"&note="+($("select#note").length > 0?$("select#note").val():""),
					success:
						function(result) {
							result = $.parseJSON(result);
							if(result[0]=="ok") {
								if($("ul.listComment li:first").text() == "Aucun commentaires pour le moment")
									$("ul.listComment").html("<li style='height:0;'><img src='"+result[1][1]+"' alt=''/><span class='text_comment'><h4>"+result[1][0]+" "+result[1][3]+(result[1][4]!=-1?" Note : "+result[1][4]:"")+"</h4>"+result[1][2]+"</span></li>");
								else
									$("ul.listComment").prepend("<li style='height:0;'><img src='"+result[1][1]+"' alt=''/><span class='text_comment'><h4>"+result[1][0]+" "+result[1][3]+(result[1][4]!=-1?" Note : "+result[1][4]:"")+"</h4>"+result[1][2]+"</span></li>");
								
								$("ul.listComment li:first").animate({height:'45px'});
							}
							else if(result[0]=="error") {
								alert(result[1]);
							}
							$("form.comment input#sendComment").css({'background':'none'});
							$("form.comment input#sendComment").val("");
						}
				});
			}
			
			return false;
		}
	});
	
	$("form.comment input#sendComment").live({
		'focus': function() {
			if($(this).val() == "Saisissez votre message" || $(this).val() == "Soyez le premier à commenter") {
				$(this).css({"color":"#000000"});
				$(this).val("");
			}
		},
		'blur': function() {
			if($(this).val() == "") {
				$(this).css({"color":"#dadbd9"});
				$(this).val("Saisissez votre message");
			}
		}			
	});
});

pos_scroll=0;
$(window).scroll(function() {
	
	if($(window).height() >= $("#sidebar-left-in").height()) { 
		$("#sidebar-left").css({"height":$("#sidebar-left").height()+"px"});
		
		position_left = $("#sidebar-left-in").offset();
		position_right = $("#sidebar-left").offset();
		
		if($(window).scrollTop() - ($(".blue-box-top.blue-box-asdf").offset().top - $("#sidebar-left-in").height()) >= 0) {
			if(pos_scroll != 2)
				$("#sidebar-left-in").css({"position":"absolute","bottom":"0","margin-left":"0","top":"","z-index":""});
			pos_scroll=2;
		}
		else if($(window).scrollTop() - position_right.top >= 0) {
			if(pos_scroll != 1)
				$("#sidebar-left-in").css({"position":"fixed","width":"218px","top":"0","margin-left":"0","bottom":"","z-index":"15000"});
			pos_scroll=1;
		}
		
		// En attendant un repÃ¨re je prends la div de droite comme repÃ¨re
		if($(window).scrollTop() - position_right.top < 0) {
			if(pos_scroll!=0)
				$("#sidebar-left-in").css({"position":"relative","margin-left":"0","top":"","bottom":"","left":"","z-index":""});
			pos_scroll=0;
		}
	}
	else if(pos_scroll!=0) {
		$("#sidebar-left-in").css({"position":"relative","margin-left":"0","top":"","bottom":"","left":"","z-index":""});
	}
});