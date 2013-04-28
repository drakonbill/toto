
var resizeimg;
var x1, x2, y1, y2, w, h;

function previewAvatar(img, selection) {  
    var scaleX = 150 / selection.width;  
    var scaleY = 150 / selection.height;   

    $("div.imgResize > img").css({  
        width: Math.round(scaleX * $('img#photo').width()) + "px",  
        height: Math.round(scaleY * $('img#photo').height()) + "px",  
        marginLeft: "-" + Math.round(scaleX * selection.x1) + "px",  
        marginTop: "-" + Math.round(scaleY * selection.y1) + "px"  
    });  

    x1=selection.x1;  
    y1=selection.y1;  
    x2=selection.x2;  
    y2=selection.y2;  
    w=selection.width;  
    h=selection.height;  
} 

$(function(){
	
	$('a#modifAvatar').click(function(){
		$("body").prepend("<div class='popup-avatar'></div>");
        load($(".popup-avatar"));
        centrer_mini_popup($(".popup-avatar"));
		
		$(window).resize(function() {
			centrer_mini_popup($(".popup-avatar"));
		});
		
		$(".popup-avatar").append("<h2 class='popuptitle'>Modifier mon avatar</h2><div id='contenu_avatar'></div>");
		if($("div.modifavatar img").attr('class') == "noavatar")
			$(".popup-avatar div#contenu_avatar").append("<img class='avataractuelle' src='http://www.total-manga.com/forum/images/avatars/no_avatar_m.jpg' /><p>Pas d'avatar</p>");
		else
			$(".popup-avatar div#contenu_avatar").append("<img class='avataractuelle' src='"+$("img#avatar-profil").attr('src')+"' /><p>Avatar actuelle</p>");
		$(".popup-avatar div#contenu_avatar").append("<input type='file' name='fichier' id='fichier' /><div class='loading' id='content_resize_avatar'></div>");
		$(".popup-avatar").append("<a class='button_popup' id='annulerAvatar' href='javascript:void()'>Annuler</a><a class='button_popup' id='modifierAvatar' href='javascript:void()'>Modifier</a>");
		$(".popup-avatar").append("<div class='error'></div>");
		
		$("#fichier").change(function() {
							
			if(typeof(resizeimg) != 'undefined')
				resizeimg.cancelSelection();
				
			$("div#content_resize_avatar").html("<img src='/Images/ajax-loader.gif' alt='loading'/>");
			$(this).upload('/ajax/registerImageMember', function(res) {
				$("div.error").fadeOut();
				$("div.error").empty();
				if(res.substring(0,2) == "1;")
				{
					image = res.split(";");
					$("div#content_resize_avatar").empty();
					$("div.error").fadeOut("slow");
					$("div#content_resize_avatar").html("<div class='contentimgnoResize'><label class='contentinput'>Image d'origine :</label><img id='photo' src='"+image[1]+"' alt='icone' /></div>");
					$("div#content_resize_avatar").append("<div class='contentimgResize'><label class='contentinput'>Aperçu :</label><div class='imgResize'><img src='' /></div></div><hr class='clear' />");
					$("div.imgResize > img").attr("src",image[1]);
					
					var img = $("img#photo")[0]; // Get my img elem
					
					$("<img/>") // Make in memory copy of image to avoid css issues
					.attr("src", $(img).attr("src"))
					.load(function() {
						image_width = this.width;   // Note: $(this).width() will not
						image_height = this.height; // work for in memory images.

						resizeimg = $('img#photo').imgAreaSelect({
							onSelectEnd: previewAvatar,
							onInit: previewAvatar,
							aspectRatio: '1:1',
							zIndex: 1005,
							instance: true,
							handles: true,
							persistent: true,
							x1: 0, y1: 0, x2: 50, y2: 50,
							minWidth: 25,
							minHeight: 25,
							parent: "div.popup-avatar"
						});
					});	
				}
				else {
					$("div.error").append(res);
					$("div.error").fadeIn();
					$("div#content_resize_avatar").empty();
				}
				
			}, 'php');
		});
		
		$("a#modifierAvatar").click(function() {  
			$("div.error").fadeOut();
			$("div.error").empty();
			if(typeof($('img#photo').attr('src')) != "undefined") {
				if(typeof(x1) != "undefined" && typeof(y1) != "undefined" && typeof(x2) != "undefined" && typeof(y2) != "undefined" && typeof(w) != "undefined" && typeof(h) != "undefined" && typeof($('img#photo')) != "undefined" && typeof($('img#photo')) != "undefined" && parseInt(x1) == x1 && parseInt(x2) == x2 && parseInt(y1) == y1 && parseInt(y2) == y2 && parseInt(w) == w && parseInt(h) == h && parseInt($('img#photo').height()) == $('img#photo').height() && parseInt($('img#photo').width()) == $('img#photo').width()) {
					$.ajax({
						type: 'POST',
						url: '/ajax/registerImageProfilMember',
						data: "x1="+x1+"&y1="+y1+"&x2="+x2+"&y2="+y2+"&w="+w+"&h="+h+"&image="+image[2]+"&imageheight="+$('img#photo').height()+"&imagewidth="+$('img#photo').width(),
						success:
							function(result) {
								if(result.substring(0,2) == "2;") {
									location.reload();
									result = result.split(";");
									$("div.modifavatar img").attr('src', result[1]);
									$("div.modifavatar img").attr('class', '');
								}
								else {
									$("div.error").append(result);
									$("div.error").fadeIn();
								}
							}
					});
				}
				else {
					$("div.error").append("Veuillez recadrer la partie de l'image que vous souhaitez");
					$("div.error").fadeIn();
				}
			}
			else {
				$("div.error").append("Veuillez choisir une image dans vos dossiers");
				$("div.error").fadeIn();
			}
		});
		
		$('a#annulerAvatar, .background-avatar').click(function(){
			$(".popup-avatar").remove();
		});
    });
	

	//Fermeture de la pop-up et du fond
	$('.popup_block').live('click', function() { //Au clic sur le bouton ou sur le calque...
		$('#fade,.popup_block').fadeOut(function() {
		});
		resizeimg.cancelSelection();
		return false;
	});
});