function tailleFichier()
{
	 $.ajax({
            type: 'POST',
            url: 'js/Ajax-PHP/changement/taillephotoprofil.php',
            data: $("#changerphoto").serialize(),
			async: false,
            success:
            function(result) {
                alert(result);
                }
        });
}

function preview(img, selection) {  
    var scaleX = 150 / selection.width;  
    var scaleY = 150 / selection.height;   

    $("#photo + div > img").css({  
        width: Math.round(scaleX * $('img#photo').width()) + "px",  
        height: Math.round(scaleY * $('img#photo').height()) + "px",  
        marginLeft: "-" + Math.round(scaleX * selection.x1) + "px",  
        marginTop: "-" + Math.round(scaleY * selection.y1) + "px"  
    });  

    $("#x1").val(selection.x1);  
    $("#y1").val(selection.y1);  
    $("#x2").val(selection.x2);  
    $("#y2").val(selection.y2);  
    $("#w").val(selection.width);  
    $("#h").val(selection.height);  
}  

$(function(){
	
	$('#fichier').change(function() {
				$(".barprogress").css({ display:"none",width:"0px" });
				$("#fichier").next(".error-input").fadeOut("slow");
				//$("#gh").html("<img src='css/images/loader-autocomplete.gif'/>");
				$(".barprogress").animate({ display:"block",width:"200px" },25000);
				$(this).upload('js/Ajax-PHP/changement/changerphotoprofil.php', function(res) {
					if(res.substring(0,2) == "1;")
					{
						image = res.split(";");
						$("#gh").empty();
						$(".barprogress").stop();
						$(".barprogress").animate({ display:"block",width:"400px" },1500);
						$("#fichier").next(".error-input").fadeOut("slow");
						$("#photo").attr("src",image[1]);
						$("#photo + div > img").attr("src",image[1]);
						
						resizeimg = $('img#photo').imgAreaSelect({
							onSelectEnd: preview,
							aspectRatio: '1:1',
							zIndex: 105,
							instance: true,
							handles: true,
							x1: 0, y1: 0, x2: 50, y2: 50
						});
						
						var popID = 'openparam';
						$('#' + popID).fadeIn();
						var popMargTop = ($('#' + popID).height() + 80) / 2;
						var popMargLeft = ($('#' + popID).width() + 80) / 2;
						$('#' + popID).css({
							'margin-top' : -popMargTop,
							'margin-left' : -popMargLeft,
						});
						$('#fade').css({'filter' : 'alpha(opacity=50)'}).fadeIn();
					}
					else
					{
						$("#fichier").next(".error-input").html(res).fadeIn("slow");
						$("#gh").empty();
						$(".barprogress").stop();
						$(".barprogress").css({ display:"none",width:"0px" });
					}
				}, 'php');
			});

	//Fermeture de la pop-up et du fond
	$('.popup_block').live('click', function() { //Au clic sur le bouton ou sur le calque...
		$('#fade,.popup_block').fadeOut(function() {
		});
		resizeimg.cancelSelection();
		return false;
	});
});