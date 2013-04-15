var popacti = 0;

function load(){
    if(popacti == 0) {
        $('.background').css({
            "opacity" : "0.6"
        });
        $('.background').fadeIn('slow');
        $('.popup').fadeIn('slow');
        popacti = 1;
    }
} 

function centrer(popup){
//request data for centering
var windowWidth = document.documentElement.clientWidth;
var windowHeight = document.documentElement.clientHeight;
var popupHeight = $(popup).height();
var popupWidth = $(popup).width();
//centering
$(popup).css({
"position": "fixed",
"top": "25px",
"left": windowWidth/2-popupWidth/2
});
//only need force for IE6

$("#background").css({
"height": windowHeight
});

}



function disable(){
    if(popacti == 1) {
        $('.background').fadeOut('slow');
        $('.popup').fadeOut('slow');   
        popacti = 0;
    }
}

$(document).ready(function(){
    $('.pop').click(function(){
        load();
        centrer();
    });
    
    $('.close').click(function(){
        disable();
    });
    
    //$('.popup').resizable();
    $('.popup').draggable( {
        containment: '#background'
      } );

});