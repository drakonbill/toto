
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

    x1 = selection.x1;
    y1 = selection.y1;
    x2 = selection.x2;
    y2 = selection.y2;
    w = selection.width;
    h = selection.height;
}

$(function() {

    $('a#modifAvatar').click(function() {
        $("body").prepend("<div class='popup-avatar'></div>");

        centrer_mini_popup($(".popup-avatar"));

        $(window).resize(function() {
            centrer_mini_popup($(".popup-avatar"));
        });

        $(".popup-avatar").append("<h2 class='popuptitle'>Modifier mon avatar</h2><div id='contenu_avatar'></div>");
        if ($("div.modifavatar img").attr('class') == "noavatar")
            $(".popup-avatar div#contenu_avatar").append("<img class='avataractuelle' src='http://www.total-manga.com/forum/images/avatars/no_avatar_m.jpg' /><p>Pas d'avatar</p>");
        else
            $(".popup-avatar div#contenu_avatar").append("<img class='avataractuelle' src='" + $("img#avatar-profil").attr('src') + "' /><p>Avatar actuelle</p>");
        $(".popup-avatar div#contenu_avatar").append("<input type='file' name='fichier' id='fichier' /><div class='loading' id='content_resize_avatar'></div>");
        $(".popup-avatar").append("<a class='button_popup' id='annulerAvatar' href='javascript:void()'>Annuler</a><a class='button_popup' id='modifierAvatar' href='javascript:void()'>Modifier</a>");
        $(".popup-avatar").append("<div class='error'></div>");

        $("#fichier").change(function() {

            if (typeof(resizeimg) != 'undefined')
                resizeimg.cancelSelection();

            $("div#content_resize_avatar").html("<img src='/Images/ajax-loader.gif' alt='loading'/>");
            $(this).upload('/ajax/registerImageMember', function(res) {
                $("div.error").fadeOut();
                $("div.error").empty();
                if (res.substring(0, 2) == "1;")
                {
                    image = res.split(";");
                    $("div#content_resize_avatar").empty();
                    $("div.error").fadeOut("slow");
                    $("div#content_resize_avatar").html("<div class='contentimgnoResize'><label class='contentinput'>Image d'origine :</label><img id='photo' src='" + image[1] + "' alt='icone' /></div>");
                    $("div#content_resize_avatar").append("<div class='contentimgResize'><label class='contentinput'>Aperçu :</label><div class='imgResize'><img src='' /></div></div><hr class='clear' />");
                    $("div.imgResize > img").attr("src", image[1]);

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
            if (typeof($('img#photo').attr('src')) != "undefined") {
                if (typeof(x1) != "undefined" && typeof(y1) != "undefined" && typeof(x2) != "undefined" && typeof(y2) != "undefined" && typeof(w) != "undefined" && typeof(h) != "undefined" && typeof($('img#photo')) != "undefined" && typeof($('img#photo')) != "undefined" && parseInt(x1) == x1 && parseInt(x2) == x2 && parseInt(y1) == y1 && parseInt(y2) == y2 && parseInt(w) == w && parseInt(h) == h && parseInt($('img#photo').height()) == $('img#photo').height() && parseInt($('img#photo').width()) == $('img#photo').width()) {
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/registerImageProfilMember',
                        data: "x1=" + x1 + "&y1=" + y1 + "&x2=" + x2 + "&y2=" + y2 + "&w=" + w + "&h=" + h + "&image=" + image[2] + "&imageheight=" + $('img#photo').height() + "&imagewidth=" + $('img#photo').width(),
                        success:
                                function(result) {
                                    if (result.substring(0, 2) == "2;") {
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

        $('a#annulerAvatar, .background-avatar').click(function() {
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
    
    var number_photo=1;
    $("a#addPicture").live('click', function() {
        if ($("div.popup-other").length == 0) {
            $("body").prepend("<div class='popup-other'><div class='contentdetail'><h2>Ajouter une photo</h2></div></div>");
            $(".popup-other .contentdetail").append("<div class='album_upload_photo'><label for='album'>Selectionner un évènement ou votre album de profil</label><select name='album' id='album'><option value='0'>Album personnel</option></select><span class='see-more-album'><a href='javascript:void();'>Voir plus d'albums</a></span></div>");
            for (key in var_albums)
                $(".popup-other select#album").append("<option value='" + var_albums[key]['id_event'] + "'>" + var_albums[key]['name_event'] + "</option>");

            $(".popup-other .contentdetail").append("<div id='content_global_photo'><div class='content_upload_photo'><input class='contentinput' type='text' name='libelle' id='libelle' value='Saississez un libelle' /><br/><div class='content_photo_preview' id='content_photo_preview"+number_photo+"'></div><label for='file'>Photo : </label><input type='file' name='fichier' id='fichier"+number_photo+"' /><br/><label for='main_photo"+number_photo+"'>Photo principale : </label><input type='radio' name='main_photo"+number_photo+"' id='main_photo"+number_photo+"' value='"+number_photo+"' /></div></div><hr class='clear'/>");
            $(".popup-other .contentdetail").append("<div class='contentButtonBottom'><a class='buttonBottom' href='javascript:void();' id='buttonAddPhoto'>Ajouter</a> <a class='buttonBottom' href='javascript:void();' id='cancel'>Annuler</a></div>");
            $(".content_upload_photo input[name=fichier]").live('change' ,function() {
                
                
                number_file = $(this).attr('id').split("fichier");
                number_file = number_file[1];
                $("div#content_photo_preview"+number_file).html("<img src='/Images/ajax-loader.gif' alt='loading'/>");
                $(this).upload('/ajax/registerImagePhoto', "id="+number_file, function(res) {alert(res);
                    if (res.substring(0, 2) == "1;")
                    {
                        image = res.split(";");
                        $("div#content_photo_preview"+number_file).html("<img class='picture' src='"+image[1]+"' alt='' />");
                        number_photo++;
                        $(".popup-other .contentdetail .content_upload_photo:last").after("<div class='content_upload_photo' id='content_upload_photo"+number_photo+"'><input class='contentinput' type='text' name='libelle"+number_photo+"' id='libelle"+number_photo+"' value='Saississez un libelle' /><br/><div class='content_photo_preview' id='content_photo_preview"+number_photo+"'></div><label for='file'>Photo : </label><input type='file' name='fichier' id='fichier"+number_photo+"' /><br/><label for='main_photo"+number_photo+"'>Photo principale : </label><input type='radio' name='main_photo"+number_photo+"' id='main_photo"+number_photo+"' value='"+number_photo+"' /></div>");
                    }
                });

            });
            
            $("a#buttonAddPhoto").live('click', function() {
                 $.ajax({
                    type: 'POST',
                    url: '/ajax/registerPhoto',
                    data: "",
                    success:
                        function(result) {
                            
                        }  
                 });
            });

            centrer_mini_popup($(".popup-other"));
            $(window).resize(function() {
                centrer_mini_popup($(".popup-other"));
            });
            
            
        }
    });
});