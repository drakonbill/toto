
function preload_image(_image) 
{
var image = new Image;
image.src = _image;
}
function survolregion(nb)
{
                $("img#background-map-img").fadeIn();
                $("img#background-map-img").attr({'src':'/Images/country-map/france/region_'+nb+'.png'});
                $(".tooltip-map").html(nb);
                $(".tooltip-map").append(searchevent_dep[''+nb+'']);

}
function nonsurvolregion()
{
        var cartefond=document.getElementById('background-map-img');
        cartefond.src='/Images/country-map/france/transparent.png';
}

for(var i=1; i<=95; i++) {
        if(i==20) {
                preload_image("/Images/country-map/france/region_2a.png");
                preload_image("/Images/country-map/france/region_2b.png");
        }
        else if(i<10)
                preload_image("/Images/country-map/france/region_0"+i+".png");
        else
                preload_image("/Images/country-map/france/region_"+i+".png");
}

$(function() {
    $("body").prepend("<div class='tooltip-map'></div>");
        $("area").mousemove(function(e) { 
            $('.tooltip-map').stop(true, true).css('left', e.pageX + 10).css('top', e.pageY + 10);

        });
        
        $("area").mouseover(function() {
             $('.tooltip-map').stop(true, true).animate({opacity:'100'}).show();
        });

        $("area").mouseout(function() { 
            $('.tooltip-map').fadeOut("slow");
        });

});