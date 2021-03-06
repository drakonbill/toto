
var resizeimg, resizeimg_photo;
var x1, x2, y1, y2, w, h;
var x1_photo, x2_photo, y1_photo, y2_photo, w_photo, h_photo;
var image_width, image_height;
var event_confidentialite = new Array();
var geocoder, map;
var lon = "";
var lat = "";

function loadInputValue() {
    var inputvalue = new Array();

    function loadInputValueBlur(obj, i) {
        $(obj).live('blur', function() {
            if ($(obj).val() == "" || $(obj).val() == inputvalue[i]) {
                $(obj).val(inputvalue[i]);
            }
        });
    }

    function loadInputValueFocus(obj, i) {
        $(obj).live('focus', function() {
            if ($(obj).val() == inputvalue[i]) {
                $(obj).val("");
            }
        });
    }

    $("input.contentinput, textarea.contentinput, input#subject, textarea#content_happends").each(function() {
        inputvalue.push($(this).val());
    });

    var i = 0;
    $("input.contentinput, textarea.contentinput, input#subject, textarea#content_happends").each(function() {
        loadInputValueBlur(this, i);
        loadInputValueFocus(this, i);
        i++;
    });
}

function heure(hour)
{
    var heure = "";
    for (var i = 0; i < 24; i++)
        heure += "<option " + (i == parseInt(hour) ? "selected='selected'" : "") + " value='" + i + "'>" + (i < 10 ? "0" + i : i) + "</option>";
    return heure;
}

function minute(min)
{
    var minute = "";
    for (var i = 0; i < 60; i++)
        minute += "<option " + (i == parseInt(min) ? "selected='selected'" : "") + " value='" + i + "'>" + (i < 10 ? "0" + i : i) + "</option>";
    return minute;
}

function formatItem(row) {
    return "<span class='autocomplete_title'>" + row[1] + "</span>" + (row[2] != "" ? "<img src='" + row[2] + "' alt='" + row[0] + "' />" : "") + row[0];
}

function selectItem(row) {
    $("input#categorievalue").val(row.getElementsByTagName("span")[0].innerText);
}

function preview(img, selection) {
    var scaleX = 75 / selection.width;
    var scaleY = 75 / selection.height;

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

function preview_photo(img, selection) {
    var scaleX = 294 / selection.width;
    var scaleY = 294 / selection.height;

    $("div#event_organise div.fun-block-inside2 div#event_organise_photo div.imgResize > img").css({
        width: Math.round(scaleX * $('img#photo_event').width()) + "px",
        height: Math.round(scaleY * $('img#photo_event').height()) + "px",
        marginLeft: "-" + Math.round(scaleX * selection.x1) + "px",
        marginTop: "-" + Math.round(scaleY * selection.y1) + "px"
    });

    x1_photo = selection.x1;
    y1_photo = selection.y1;
    x2_photo = selection.x2;
    y2_photo = selection.y2;
    w_photo = selection.width;
    h_photo = selection.height;
}

function maj_calendrier(moi, annee, idcalendrier, title)
{
    $("table#" + idcalendrier + " tbody#cal_body").empty();

    var content_calendrier = "";

    if (annee <= 200)
        annee += 1900;

    jour = 1;

    mois = new Array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
    jours_dans_moi = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    if (annee % 4 == 0 && annee != 1900)
        jours_dans_moi[1] = 29;
    total = jours_dans_moi[moi];

    var date = new Date();
    date.setYear(annee);
    date.setMonth(moi);

    dep_j = date;
    dep_j.setDate(1);
    if (dep_j.getDate() == 2)
    {
        dep_j = setDate(0);
    }
    dep_j = (dep_j.getDay() == 0 ? 7 : dep_j.getDay());

    var date_aujourdui = jour + ' ' + mois[moi] + ' ' + annee;

    var mois_moisprecedent = ((moi - 1) < 0 ? 11 : (moi - 1));
    var mois_anneeprecedent = ((moi - 1) < 0 ? (annee - 1) : annee);

    var mois_moissuivant = ((parseInt(moi) + parseInt(1)) > 11 ? 0 : (parseInt(moi) + parseInt(1)));
    var mois_anneesuivant = ((parseInt(moi) + parseInt(1)) > 11 ? (parseInt(annee) + parseInt(1)) : annee);

    content_calendrier += '<tr class="dateaujourdhui"><th><a href="javascript:void()" onclick="maj_calendrier(\'' + moi + '\', \'' + (annee - 1) + '\', \'' + idcalendrier + '\', \'' + title + '\')">\<\<</a>&nbsp;&nbsp;<a href="javascript:void()" onclick="maj_calendrier(\'' + mois_moisprecedent + '\', \'' + mois_anneeprecedent + '\', \'' + idcalendrier + '\', \'' + title + '\')">\<</a></th><th class="dateaujourdhui date' + jour + '-' + moi + '-' + annee + '" colspan="5">' + title + ' ' + date_aujourdui + '</th><th><a href="javascript:void()" onclick="maj_calendrier(\'' + mois_moissuivant + '\', \'' + mois_anneesuivant + '\', \'' + idcalendrier + '\', \'' + title + '\')">\></a>&nbsp;&nbsp;<a href="javascript:void()" onclick="maj_calendrier(\'' + moi + '\', \'' + (parseInt(annee) + parseInt(1)) + '\', \'' + idcalendrier + '\', \'' + title + '\')">\>\></a></th></tr>';
    content_calendrier += '<tr class="cal_j_semaines"><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr><tr>';
    sem = 0;
    for (i = 1; i < dep_j; i++)
    {
        content_calendrier += '<td class="cal_jours_av_ap"></td>';
        sem++;
    }
    for (i = 1; i <= total; i++)
    {
        if (sem == 0)
        {
            content_calendrier += '<tr>';
        }
        if (jour == i)
        {
            content_calendrier += '<td class="cal_aujourdhui">' + i + '</td>';
        }
        else
        {
            content_calendrier += '<td><a class="changerdate" href="javascript:void()">' + i + '</a></td>';
        }
        sem++;
        if (sem == 7)
        {
            content_calendrier += '</tr>';
            sem = 0;
        }
    }
    for (i = 1; sem != 0; i++)
    {
        content_calendrier += '<td class="cal_jours_av_ap"></td>';
        sem++;
        if (sem == 7)
        {
            content_calendrier += '</tr>';
            sem = 0;
        }
    }

    $("table#" + idcalendrier + " tbody#cal_body").html(content_calendrier);

    $("table#" + idcalendrier + " a.changerdate").live('click', function() {

        $("table#" + idcalendrier + " th.dateaujourdhui").html(title + " " + val_jour + " " + mois[moi] + " " + annee);
        $("table#" + idcalendrier + " th.dateaujourdhui").attr('class', 'dateaujourdhui date' + val_jour + '-' + moi + '-' + annee);
    });
}

function calendrier(idcalendrier, title)
{
    var content_calendrier = "";

    var date = new Date();

    if (idcalendrier == "calendrier2") {
        var jour = eventdata_datefin_day;
        var moi = parseInt(eventdata_datefin_month - 1);
        var annee = eventdata_datefin_year;
        var hour = eventdata_datefin_hour;
        var min = eventdata_datefin_minute;
        var time_option = eventdata_time_option_fin;
    }
    else if (idcalendrier == "calendrier3") {
        var jour = eventdata_datedebut_day;
        var moi = parseInt(eventdata_datedebut_month - 1);
        var annee = eventdata_datedebut_year;
        var hour = eventdata_datedebut_hour;
        var min = eventdata_datedebut_minute;
        var time_option = eventdata_time_option_debut;
    }

    if (annee <= 200)
        annee += 1900;

    mois = new Array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
    jours_dans_moi = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    if (annee % 4 == 0 && annee != 1900)
        jours_dans_moi[1] = 29;
    total = jours_dans_moi[moi];

    dep_j = date;
    dep_j.setDate(1);
    if (dep_j.getDate() == 2)
    {
        dep_j = setDate(0);
    }
    dep_j = (dep_j.getDay() == 0 ? 7 : dep_j.getDay());

    var date_aujourdui = jour + ' ' + mois[moi] + ' ' + annee;

    var mois_moisprecedent = ((moi - 1) < 0 ? 11 : (moi - 1));
    var mois_anneeprecedent = ((moi - 1) < 0 ? (annee - 1) : annee);

    var mois_moissuivant = ((parseInt(moi) + parseInt(1)) > 11 ? 0 : (parseInt(moi) + parseInt(1)));
    var mois_anneesuivant = ((parseInt(moi) + parseInt(1)) > 11 ? (parseInt(annee) + parseInt(1)) : annee);

    content_calendrier += '<table class="calendrier" id="' + idcalendrier + '"><tbody id="cal_body"><tr class="dateaujourdhui"><th><a href="javascript:void()" onclick="maj_calendrier(\'' + moi + '\', \'' + (annee - 1) + '\', \'' + idcalendrier + '\', \'' + title + '\')">\<\<</a>&nbsp;&nbsp;<a href="javascript:void()" onclick="maj_calendrier(\'' + mois_moisprecedent + '\', \'' + mois_anneeprecedent + '\', \'' + idcalendrier + '\', \'' + title + '\')">\<</a></th><th class="dateaujourdhui date' + jour + '-' + moi + '-' + annee + '" colspan="5">' + title + ' ' + date_aujourdui + '</th><th><a href="javascript:void()" onclick="maj_calendrier(\'' + mois_moissuivant + '\', \'' + mois_anneesuivant + '\', \'' + idcalendrier + '\', \'' + title + '\')">\></a>&nbsp;&nbsp;<a href="javascript:void()" onclick="maj_calendrier(\'' + moi + '\', \'' + (parseInt(annee) + parseInt(1)) + '\', \'' + idcalendrier + '\', \'' + title + '\')">\>\></a></th></tr>';
    content_calendrier += '<tr class="cal_j_semaines"><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr><tr>';
    sem = 0;
    for (i = 1; i < dep_j; i++)
    {
        content_calendrier += '<td class="cal_jours_av_ap"></td>';
        sem++;
    }
    for (i = 1; i <= total; i++)
    {
        if (sem == 0)
        {
            content_calendrier += '<tr>';
        }
        if (jour == i)
        {
            content_calendrier += '<td class="cal_aujourdhui">' + i + '</td>';
        }
        else
        {
            content_calendrier += '<td><a class="changerdate" href="javascript:void()">' + i + '</a></td>';
        }
        sem++;
        if (sem == 7)
        {
            content_calendrier += '</tr>';
            sem = 0;
        }
    }
    for (i = 1; sem != 0; i++)
    {
        content_calendrier += '<td class="cal_jours_av_ap"></td>';
        sem++;
        if (sem == 7)
        {
            content_calendrier += '</tr>';
            sem = 0;
        }
    }
    content_calendrier += '</tbody><tfoot><tr><td colspan="7"><div class="time_recover"></div><input ' + (time_option == 1 ? 'checked="checked"' : '') + ' class="time_option" type="checkbox" name="time_option' + idcalendrier + '" id="time_option' + idcalendrier + '"/>Heures : <select>' + heure((time_option == 1 ? hour : date.getHours())) + '</select> Minutes : <select>' + minute((time_option == 1 ? min : date.getMinutes())) + '</select></td></tr></tfoot>';
    content_calendrier += '</table>';

    $("table#" + idcalendrier + " tfoot input#time_option" + idcalendrier).live('change', function() {
        if ($(this).attr('checked') != 'checked')
            $("> div.time_recover", $(this).parent()).css({"display": "block"});
        else
            $("> div.time_recover", $(this).parent()).css({"display": "none"});
    });
    $("table#" + idcalendrier + " a.changerdate").live('click', function() {
        $("> tr td.cal_aujourdhui", $(this).parent().parent().parent()).html("<a class='changerdate' href='javascript:void()'>" + $("> tr td.cal_aujourdhui", $(this).parent().parent().parent()).text() + "</a>");
        $("> tr td.cal_aujourdhui", $(this).parent().parent().parent()).removeClass('cal_aujourdhui');
        $(this).parent().addClass('cal_aujourdhui');
        val_jour = $(this).parent().text();
        $(this).parent().text(val_jour);
        $("table#" + idcalendrier + " th.dateaujourdhui").html(title + " " + val_jour + " " + mois[moi] + " " + annee);
        $("table#" + idcalendrier + " th.dateaujourdhui").attr('class', 'dateaujourdhui date' + val_jour + '-' + moi + '-' + annee);
    });

    return content_calendrier;
}

function addPrivate() {
    var pseudo_membre = $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose input#contactvalue").val();
    if (pseudo_membre != "") {
        $("div.error_privee").html("Chargement...");
        $.ajax({
            type: 'POST',
            url: '/ajax/verifMember',
            data: "pseudomembre=" + pseudo_membre,
            success:
                    function(result) {
                        if (result == "notok") {
                            $("div.error_privee").html("Ce contact est introuvable");
                        }
                        else {
                            ok_private = true;
                            $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose ul#private_contacts li").each(function() {
                                if ($(this).attr('id') == result)
                                    ok_private = false;
                            });
                            if (ok_private) {

                                $("div.error_privee").html("");
                                $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose ul#private_contacts").append("<li id='" + result + "'>" + $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose input#contactvalue").val() + " - <a href='javascript:void()'>Retirer</a></li>");

                                $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose ul#private_contacts li a").live('click', function() {
                                    $(this).parent().remove();
                                });
                            }
                            else {
                                $("div.error_privee").html("Contact déjà ajouté");
                            }
                        }
                    }
        });
    }
}

var markersArray = [];

function initialize() {
    geocoder = new google.maps.Geocoder();
    var myLatlng = new google.maps.LatLng(eventdata_lat, eventdata_lon);
    var mapOptions = {
        center: myLatlng,
        zoom: 7,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    marker = new google.maps.Marker({
        map: map,
        position: myLatlng,
        animation: google.maps.Animation.DROP
    });
    marker.setAnimation(google.maps.Animation.BOUNCE);
    markersArray.push(marker);
    
    map.setCenter(myLatlng);

}

function clearOverlays() {
    for (var i = 0; i < markersArray.length; i++) {
        markersArray[i].setMap(null);
    }
    markersArray = [];
}

function searchInfo(tab) {
    var returnInfo = new Array();
    for (key in tab) {
        if (tab[key]['types'][0] == 'country')
            returnInfo.push(tab[key]['long_name']);
        else if (tab[key]['types'][0] == 'administrative_area_level_1')
            returnInfo.push(tab[key]['long_name']);
    }
    return returnInfo;
}

function codeAddress() {
    var address = 'fr+' + document.getElementById('address').value;
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            infoMarker = searchInfo(results[0]['address_components']);
            if (typeof(results[0]["formatted_address"]) != 'undefined')
                infoMarker.push(results[0]["formatted_address"]);
            if (infoMarker.length == 3) {
                lat = results[0].geometry.location.lat();
                lon = results[0].geometry.location.lng();
                if (lon != eventdata_lon || lat != eventdata_lat) {
                    map.setZoom(7);
                    clearOverlays();
                    marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location,
                        animation: google.maps.Animation.DROP
                    });
                    marker.setAnimation(google.maps.Animation.BOUNCE);
                    markersArray.push(marker);

                    map.setCenter(results[0].geometry.location);

                    $("#new_place").html("Nouveau lieu : " + infoMarker[2] + " <a href='javascript:void();' onclick='cancel_localisation();'>Annuler</a>");
                }
            }
            else
                alert("Veuillez saisir un lieu plus précis");

        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

function cancel_localisation() {
    if (lon != "" && lat != "") {
        lon = "";
        lat = "";
        $("#new_place").html("");
        $("#address").val("");
        clearOverlays();
        var myLatlng = new google.maps.LatLng(eventdata_lat, eventdata_lon);
        marker = new google.maps.Marker({
            map: map,
            position: myLatlng,
            animation: google.maps.Animation.DROP
        });
        marker.setAnimation(google.maps.Animation.BOUNCE);
        markersArray.push(marker);

        map.setCenter(myLatlng);
    }
}

var isConfirmParticipant = false;
function addEvent() {
    $("div#event_organise div.fun-block-inside2 div#addThisEvent").html("Chargement...");

    membre_confidentialite = "";
    if (event_confidentialite_info == 2) {
        $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose ul#private_contacts li").each(function() {
            membre_confidentialite += $(this).attr('id') + ";";
        });
    }

    nb_participate = 0;
    nblimit = 0;

    passion = "";
    cpt_passion = 0;
    $("div#event_organise div.fun-block-inside2 div#event_organise_passion ul#liste_passion li").each(function() {
        passion += new String($(this).attr('class')).replace("passion", "") + ";";
        cpt_passion++;
    });

    ajout_date_calendrier1 = new String($('div#event_organise table#calendrier2 tbody tr th.dateaujourdhui').attr('class')).replace("dateaujourdhui date", "");
    heure_calendrier1 = $('div#event_organise table#calendrier2 tfoot td select:first').val();
    minute_calendrier1 = $('div#event_organise table#calendrier2 tfoot td select:last').val();
    time_option1 = $("div#event_organise table#calendrier2 tfoot input#time_optioncalendrier2").attr('checked');
    if (time_option1 != 'checked') {
        heure_calendrier1 = 0;
        minute_calendrier1 = 0;
    }

    ajout_date_calendrier2 = new String($('div#event_organise table#calendrier3 tbody tr th.dateaujourdhui').attr('class')).replace("dateaujourdhui date", "");
    heure_calendrier2 = $('div#event_organise table#calendrier3 tfoot td select:first').val();
    minute_calendrier2 = $('div#event_organise table#calendrier3 tfoot td select:last').val();
    time_option2 = $("div#event_organise table#calendrier3 tfoot input#time_optioncalendrier3").attr('checked');
    if (time_option2 != 'checked') {
        heure_calendrier2 = 0;
        minute_calendrier2 = 0;
    }

    subject = $('div#event_organise input#subject').val();
    content_happends = $('div#event_organise textarea#content_happends').val();

    nblimit = $('div#event_organise input#nblimit').val();
    if (nblimit != "")
        nblimit = $('div#event_organise input#nblimit').val();
    else
        nblimit = 0;

    var erreur = "";
    if (subject == "" || subject == "Saisissez le nom de l'évènement")
        erreur += "- Veuillez saisir le nom de l'évènement<br/>";

    if (content_happends == "" || content_happends == "Description...")
        erreur += "- Veuillez saisir une description<br/>";

    ajout_date_calendrier1 = ajout_date_calendrier1.split("-");
    ajout_date_calendrier2 = ajout_date_calendrier2.split("-");

    var date_debut = new Date(ajout_date_calendrier2[2] + "," + (parseInt(ajout_date_calendrier2[1]) + parseInt(1)) + "," + ajout_date_calendrier2[0]);
    date_debut.setHours(heure_calendrier2);
    date_debut.setMinutes(minute_calendrier2);
    var date_fin = new Date(ajout_date_calendrier1[2] + "," + (parseInt(ajout_date_calendrier1[1]) + parseInt(1)) + "," + ajout_date_calendrier1[0]);
    date_fin.setHours(heure_calendrier1);
    date_fin.setMinutes(minute_calendrier1);

    /*if (date_fin - date_debut < 0)
        erreur += "- Veuillez saisir une date de début antérieure à le date de fin";*/

    if (typeof($('div#event_organise_photo img#photo_event')) != "undefined" && $('div#event_organise_photo img#photo_event').length != 0) {
        if (!(typeof(x1_photo) != "undefined" && typeof(y1_photo) != "undefined" && typeof(x2_photo) != "undefined" && typeof(y2_photo) != "undefined" && typeof(w_photo) != "undefined" && typeof(h_photo) != "undefined" && parseInt(x1_photo) == x1_photo && parseInt(x2_photo) == x2_photo && parseInt(y1_photo) == y1_photo && parseInt(y2_photo) == y2_photo && parseInt(w_photo) == w_photo && parseInt(h_photo) == h_photo && parseInt($('div#event_organise_photo img#photo_event').height()) == $('div#event_organise_photo img#photo_event').height() && parseInt($('div#event_organise_photo img#photo_event').width()) == $('div#event_organise_photo img#photo_event').width()))
            erreur += "- Veuillez choisir une image de couverture et la redimensionner<br/>";
    }

    if (lon == "" || lat == "") {
        lon = eventdata_lon;
        lat = eventdata_lat;
    }

    if (event_confidentialite_info == 2 && membre_confidentialite == "")
        erreur += "- Vous devez ajouter les contacts autorisés à voir votre évènement privé<br/>";

    if (cpt_passion < 1 || passion == "")
        erreur += "- Vous devez lier 1 mot clef/passion minimum<br/>";

    if (erreur != "")
        $("div#event_organise div.fun-block-inside2 div#addThisEvent").html("<h2>Erreur</h2>" + erreur);
    else {

        var data_request = "";
        if (typeof($('div#event_organise_photo img#photo_event')) != "undefined" && $('div#event_organise_photo img#photo_event').length != 0)
            data_request = "id=" + eventdata_idevent + "&x1=" + x1_photo + "&x2=" + x2_photo + "&y1=" + y1_photo + "&y2=" + y2_photo + "&w=" + w_photo + "&h=" + h_photo + "&image=" + image_photo[2] + "&imagewidth=" + image_width_photo + "&imageheight=" + image_height_photo + "&subject=" + subject + "&content_happends=" + content_happends + "&lon=" + lon + "&lat=" + lat + "&event_confidentialite_info=" + event_confidentialite_info + "&nb_participate=" + nb_participate + "&membre_confidentialite=" + membre_confidentialite + "&passion=" + passion + "&date_debut=" + date_debut.getTime() + "&date_fin=" + date_fin.getTime() + "&time_option1=" + time_option1 + "&time_option2=" + time_option2 + "&nblimit=" + nblimit;
        else
            data_request = "id=" + eventdata_idevent + "&subject=" + subject + "&content_happends=" + content_happends + "&lon=" + lon + "&lat=" + lat + "&event_confidentialite_info=" + event_confidentialite_info + "&nb_participate=" + nb_participate + "&membre_confidentialite=" + membre_confidentialite + "&passion=" + passion + "&date_debut=" + date_debut.getTime() + "&date_fin=" + date_fin.getTime() + "&time_option1=" + time_option1 + "&time_option2=" + time_option2 + "&nblimit=" + nblimit;

        if (isConfirmParticipant) {
            var deleteParticipant = new Array();
            $("input[name=deleteParticipant]").each(function() {
                if ($(this).attr('checked') == "checked")
                    deleteParticipant.push($(this).val());
            });
            data_request += "&isConfirmParticipant=" + isConfirmParticipant + "&deleteParticipant=" + deleteParticipant;
        }
        $.ajax({
            type: 'POST',
            url: '/ajax/modifyEvent',
            data: data_request,
            success:
                    function(result) {

                        result = $.parseJSON(result);

                        if ($("div.popup-othermini").length > 0)
                            $("div.popup-othermini").hide();
                        if ($(".background-mini").length > 0)
                            $(".background-mini").remove();

                        if (result[0] == "ok") {
                            location.reload();
                        }
                        else if (result[0] == "error") {
                            isConfirmParticipant = false;
                            $("div#event_organise div.fun-block-inside2 div#addThisEvent").html(result[1]);
                        }
                        else if (result[0] == "error-participant") {
                            isConfirmParticipant = false;
                            for (key in result[1]) {
                                $("div#event_organise div.fun-block-inside2 div#addThisEvent").append(result[1][key]);
                            }
                        }
                        else if (result[0] == "error-confidentialite") {
                            isConfirmParticipant = false;
                            $("div#event_organise div.fun-block-inside2 div#addThisEvent").html("");

                            popup_deletefriend = $("div.popup-othermini");
                            popup_deletefriend.html("<div class='contentdetail'><h2>Voulez-vous supprimer un membre ?</h2><span class='information'>Un ou plusieurs membres ne correspondent pas au critères de confidentialité, vous pouvez désinscrire les membres que vous souhaitez ou poursuivre (Les membres seront alors toujours inscris malgré les nouveaux paramètres de confidentialité).</span></div><div class='contentdetail'><ul id='liste_participant_error'></ul><a href='javascript:void()' id='confirmErrorParticipant'>Poursuivre</a></div>");
                            for (key in result[1])
                                $("ul#liste_participant_error").append("<li><img src='" + result[1][key]['photo_member'] + "' />" + result[1][key]['pseudo_member'] + "<input type='checkbox' name='deleteParticipant' value='" + result[1][key]['id_member'] + "' /></li>");
                            $("body").prepend("<div class='background-mini'></div>");
                            $('.background-mini').css({
                                "opacity": "0.6"
                            }).show();
                            centrer(popup_deletefriend);
                            $(popup_deletefriend).show();

                            $("a#confirmErrorParticipant").live('click', function() {
                                isConfirmParticipant = true;
                                addEvent();
                            });

                            $('.background-mini').live('click', function() {
                                $(popup_deletefriend).hide();
                                $(".background-mini").remove();
                            });

                            $(window).resize(function() {
                                centrer($(popup_deletefriend));
                            });

                        }
                    }
        });
    }
}

var event_organise = new Array();

$(document).ready(function() {


    $("div#event_organise ul.fun-block-nav li a").live('click', function() {
        $("div#event_organise ul.fun-block-nav li a").removeAttr('class');
        $(this).attr('class', 'active');
        $(" > div.fun-block-inside2 div.etape_event", $(this).parent().parent().parent()).each(function() {
            event_organise[$(this).attr('id')] = $(this).val();
        });
        $(" > div.fun-block-inside div.fun-block-inside2 > div", $(this).parent().parent().parent()).hide();
        $(" > div.fun-block-inside div.fun-block-inside2 > div#" + $(this).attr('id'), $(this).parent().parent().parent()).show();

        if ($(this).attr('id') == 'event_organise_where')
            google.maps.event.trigger(map, 'resize');
        if ($(this).attr('id') == 'event_organise_whose')
            $("div#info_whose").show();
        if ($(this).attr('id') == 'event_organise_photo')
            $("div#event_organise div.fun-block-inside2 div#event_organise_photo div").show();
    });

    $("div#event_organise ul.fun-block-nav").html('<li><a href="javascript:void();" class="active" id="event_organise_whathappens">Qu\'est ce qu\'il ce passe ?</a></li><li><a href="javascript:void();" id="event_organise_when">Quand ?</a></li><li><a href="javascript:void();" id="event_organise_where">Ou ?</a></li><li><a href="javascript:void();" id="event_organise_whose">Confidentialite</a></li><li><a href="javascript:void();" id="event_organise_passion">Mots clefs / Passions</a></li><li><a href="javascript:void();" id="event_organise_photo">Image de couverture</a></li><li><a href="javascript:void();" id="addThisEvent">Modifier cet evenement</a></li>');

    $("div#event_organise div.fun-block-inside2").append("<div class='etape_event' id='event_organise_whathappens'></div><div class='etape_event' id='event_organise_when'></div><div class='etape_event' id='event_organise_where'></div><div class='etape_event' id='event_organise_whose'></div><div class='etape_event' id='event_organise_passion'></div><div class='etape_event' id='event_organise_photo'></div><div class='etape_event' id='addThisEvent'></div>");

    $("div#event_organise div.fun-block-inside2 div#event_organise_whathappens").append('<p><input type="text" class="text" name="_" id="subject" size="" value="' + eventdata_nomevenement + '" /></p><p><textarea name="" id="content_happends" class="text" cols="10" rows="10">' + eventdata_description + '</textarea></p><div class="inside-column-sep"></div>');

    $("div#event_organise div.fun-block-inside2 div#event_organise_where").append("<div id='panel'><input id='address' type='textbox' value='Saisissez une nouvelle adresse, un nouveau lieu'><input type='button' value='Changer de position cet évènement' onclick='codeAddress()'><br/><div id='new_place'></div>Lieu actuel : " + eventdata_lieu + "</div><div style='height: 250px;' id='map-canvas'></div>");

    $("div#event_organise div.fun-block-inside2 div#event_organise_photo").append("<img src='/" + eventdata_image + "'/><h4>Votre image ne doit pas dépassée 5 Mo.<br/>Les images sont recadrés au format 16:9.</h4><input type='file' name='photo' id='photo' /><div class='loading'></div><div class='error'></div>");

    $("div#event_organise div.fun-block-inside2 div#event_organise_passion").append("<input type='text' class='contentinput' name='passionvalue' id='passionvalue' value=\"Saisissez le nom de la passion\" /><input type='hidden' id='categorievalue' name='categorievalue' value='' /><a id='contentvalid' class='contentvalid' href='javascript:void()' onclick='lierPassion()'>Lier cette passion</a><ul id='liste_passion'></ul><hr class='clear'/><div class='inside-column-sep'></div>");

    $("div#event_organise div.fun-block-inside2 div#event_organise_when").append(calendrier("calendrier2", "Au") + calendrier("calendrier3", "Du") + "<hr class='clear'/><div class='inside-column-sep'></div>");

    $("div#event_organise div.fun-block-inside2 div#event_organise_whose").append("<h2>Visibilité</h2><label for='whose_public1'>Publique</label><input checked='checked' type='radio' name='whose_public' id='whose_public1' value='public' /><label for='whose_public2'>Amis</label><input type='radio' name='whose_public' id='whose_public2' value='amis' /><label for='whose_public3'>Privée</label><input type='radio' name='whose_public' id='whose_public3' value='privee' /><div id='info_whose'>L'évènement est ouvert et visible pour n'importe qui.</div><br/><label for='nblimit'>Nombre de places (Laissez vide si illimité)</label><input type='text' name='nblimit' id='nblimit' value='" + eventdata_nblimit + "'/><hr class='clear'/><div class='inside-column-sep'></div>");

    $("div#event_organise div.fun-block-inside2 div#addThisEvent").html("");

    google.maps.event.addDomListener(window, 'load', initialize);

    for (key in eventdata_passion) {
        $("ul#liste_passion").append("<li class='passion" + eventdata_passion[key]['id_passion'] + "'>" + eventdata_passion[key]['name_category'] + " : " + eventdata_passion[key]['name_passion'] + "<img src='/" + eventdata_passion[key]['iconepassion'] + "' alt='passion'/><a class='retirer_passion' href='javascript:void()'>Retirer</a></li>");
    }
    $("a.retirer_passion").live('click', function() {
        $(this).parent().remove();
    });

    $("div#event_organise div.fun-block-inside2 div#event_organise_passion .contentinput").live('blur', function() {
        if ($("div#event_organise div.fun-block-inside2 div#event_organise_passion .contentinput").val() == "" || $("div#event_organise div.fun-block-inside2 div#event_organise_passion .contentinput").val() == "Saisissez le nom de la passion") {
            $("div#event_organise div.fun-block-inside2 div#event_organise_passion .contentinput").val("Saisissez le nom de la passion");
        }
    });

    $("div#event_organise div.fun-block-inside2 div#event_organise_passion .contentinput").live('focus', function() {
        if ($("div#event_organise div.fun-block-inside2 div#event_organise_passion .contentinput").val() == "Saisissez le nom de la passion") {
            $("div#event_organise div.fun-block-inside2 div#event_organise_passion .contentinput").val("");
        }
    });

    $("div#event_organise div.fun-block-inside2 > div").hide();
    $("div#event_organise div.fun-block-inside2 div#event_organise_whathappens").show();


    if (eventdata_confidentialite == 1) {
        event_confidentialite_info = 1;
        $("input#whose_public1").attr({'checked': 'checked'});
        $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose").html("L'évènement est ouvert et visible pour n'importe qui.");
    }
    else if (eventdata_confidentialite == 0) {
        event_confidentialite_info = 0;
        $("input#whose_public2").attr({'checked': 'checked'});
        $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose").html("L'évènement est ouvert et visible pour vos amis.");
    }
    else if (eventdata_confidentialite == 2) {
        event_confidentialite_info = 2;
        $("input#whose_public3").attr({'checked': 'checked'});
        $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose").html("L'évènement est fermée et seulement visible par des contacts précis.<br/><input type='text' class='contentinput' name='contactvalue' id='contactvalue' value=\"Saisissez le nom du contact\" /><a id='contentvalid' class='contentvalid' href='javascript:void()' onclick='addPrivate()'>Ajouter un contact</a><div class='error_privee'></div><ul id='private_contacts'></ul>");
        for (key in eventdata_participant)
            $("ul#private_contacts").append("<li id='" + eventdata_participant[key]['id_member'] + "'>" + eventdata_participant[key]['pseudo_member'] + " - <a href='javascript:void()'>Retirer</a></li>");

        $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose ul#private_contacts li a").live('click', function() {
            $(this).parent().remove();
        });
    }

    $("div#event_organise div.fun-block-inside2 div#event_organise_whose input[name=whose_public]").live('change', function() {
        if ($(this).val() == 'public') {
            event_confidentialite_info = 1;
            $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose").html("L'évènement est ouvert et visible pour n'importe qui.");
        }
        else if ($(this).val() == 'amis') {
            event_confidentialite_info = 0;
            $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose").html("L'évènement est ouvert et visible pour vos amis.");
        }
        else if ($(this).val() == 'privee') {
            event_confidentialite_info = 2;
            $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose").html("L'évènement est fermée et seulement visible par des contacts précis.<br/><input type='text' class='contentinput' name='contactvalue' id='contactvalue' value=\"Saisissez le nom du contact\" /><a id='contentvalid' class='contentvalid' href='javascript:void()' onclick='addPrivate()'>Ajouter un contact</a><div class='error_privee'></div><ul id='private_contacts'></ul>");
            for (key in eventdata_participant)
                $("ul#private_contacts").append("<li id='" + eventdata_participant[key]['id_member'] + "'>" + eventdata_participant[key]['pseudo_member'] + " - <a href='javascript:void()'>Retirer</a></li>");

            $("div#event_organise div.fun-block-inside2 div#event_organise_whose div#info_whose ul#private_contacts li a").live('click', function() {
                $(this).parent().remove();
            });
        }
    });

    $("div#event_organise div.fun-block-inside2 div#event_organise_passion input#passionvalue").autocomplete(
            "/ajax/choicePassion",
            {
                minChars: 2,
                cacheLength: 10,
                onItemSelect: selectItem,
                onFindValue: findValue,
                formatItem: formatItem,
                autoFill: false,
                matchSubset: 0,
                delay: 400
            }
    );

    /******************************************************************************************************************************
     ************************************************** LIER PASSION **************************************************************/
    $("div#event_organise div.fun-block-inside2 div#event_organise_passion a#contentvalid").live('click', function() {
        if ($("input#passionvalue").val() != "" && $("input#passionvalue").val() != "Saisissez le nom de la passion" && $("input#passionvalue").val().length > 2) {
            $("#erreurpassion").fadeOut();
            $.ajax({
                type: 'POST',
                url: '/ajax/registerPassion',
                data: "passion=" + $("input#passionvalue").val() + "&categorie=" + $("input#categorievalue").val(),
                success:
                        function(result) {
                            $("a.retirer_passion").live('click', function() {
                                $(this).parent().remove();
                            });
                            if (result.substr(0, 2) == "2;") {
                                result_passion = result.split(";");
                                ok_passion = true;
                                $("div#event_organise div.fun-block-inside2 div#event_organise_passion ul#liste_passion li").each(function() {
                                    if (new String($(this).attr('class')).replace("passion", "") == result_passion[3])
                                        ok_passion = false;
                                });
                                if (ok_passion)
                                    $("div#event_organise div.fun-block-inside2 div#event_organise_passion ul#liste_passion").append("<li class='passion" + result_passion[3] + "'>" + result_passion[1] + " : " + result_passion[2] + "<img src='" + result_passion[4] + "' alt='passion'/><a class='retirer_passion' href='javascript:void()'>Retirer</a></li>");
                            }
                            else if (result == "1") {
                                popup = $("div.popup-othermini");
                                popup.html("<div class='contentdetail'><h2>Ajout d'une passion</h2><span class='information'>La passion n'est pour l'instant pas présente, vous pouvez utiliser le formulaire ci-dessous pour l'ajouter.</span></div>");
                                popup.append("<div class='contentdetail noverflow'><h2>Passion à ajouter : " + $("input#passionvalue").val() + "</h2><label class='contentinput'>Catégorie * : </label><ul id='listecategorie'></ul><label class='contentinput'>Image de cette passion : </label><input type='file' name='filepassion' id='filepassion' /><br/><div class='loading'></div><a id='addpassion' class='contentvalid contentvalidright' href='javascript:void();'>Ajouter cette passion</a><hr class='clear'/></div><div class='error contentdetail'></div>");

                                $.ajax({
                                    type: 'GET',
                                    url: '/ajax/categoryPassion',
                                    data: "mode=categorie",
                                    success:
                                            function(result) {
                                                $("ul#listecategorie").html(result);
                                                $("ul#listecategorie").imgDropDown({title: "-- Sélectionner une catégorie --", id: "listecategorie"});
                                            }
                                });

                                $("#filepassion").change(function() {

                                    if (typeof(resizeimg) != 'undefined')
                                        resizeimg.cancelSelection();

                                    $("div.popup-othermini div.loading").html("<img src='css/images/loader-autocomplete.gif'/>");
                                    $(this).upload('/ajax/registerImagePassion', function(res) {
                                        if (res.substring(0, 2) == "1;")
                                        {
                                            image = res.split(";");
                                            $("div.popup-othermini div.loading").empty();
                                            $("div.popup-othermini div.error").fadeOut("slow");
                                            $("div.popup-othermini div.loading").html("<div class='contentimgnoResize'><label class='contentinput'>Image d'origine :</label><img id='photo' src='" + image[1] + "' alt='icone' /></div>");
                                            $("div.popup-othermini div.loading").append("<div class='contentimgResize'><label class='contentinput'>Aperçu :</label><div class='imgResize'><img src='' /></div></div><hr class='clear' />");
                                            $("div.popup-othermini div.imgResize > img").attr("src", image[1]);

                                            var img = $("div.popup-othermini img#photo")[0]; // Get my img elem

                                            $("<img/>") // Make in memory copy of image to avoid css issues
                                                    .attr("src", $(img).attr("src"))
                                                    .load(function() {
                                                image_width = this.width;   // Note: $(this).width() will not
                                                image_height = this.height; // work for in memory images.

                                                resizeimg = $('div.popup-othermini img#photo').imgAreaSelect({
                                                    onSelectEnd: preview,
                                                    onInit: preview,
                                                    aspectRatio: '1:1',
                                                    zIndex: 1001,
                                                    instance: true,
                                                    handles: true,
                                                    persistent: true,
                                                    x1: 0, y1: 0, x2: 50, y2: 50,
                                                    minWidth: 25,
                                                    minHeight: 25,
                                                    parent: "div.popup-othermini"
                                                });
                                            });
                                        }
                                        else
                                        {
                                            $("div.error").fadeIn().html(res);
                                            $("div.loading").empty();
                                        }
                                    }, 'php');
                                });

                                $("a#addpassion").click(function() {
                                    if ($("div#listecategorie").val() == "" || parseInt($("div#listecategorie").val()) != $("div#listecategorie").val()) {
                                        $("div.error").append("Veuillez sélectionner une catégorie");
                                        $("div.error").fadeIn();
                                    }
                                    else {
                                        $("div.error").fadeOut();
                                        $("div.error").empty();

                                        if (typeof($('div.popup-othermini img#photo').attr('src')) != "undefined") {
                                            if (typeof(x1) != "undefined" && typeof(y1) != "undefined" && typeof(x2) != "undefined" && typeof(y2) != "undefined" && typeof(w) != "undefined" && typeof(h) != "undefined" && typeof($('div.popup-othermini img#photo')) != "undefined" && typeof($('div.popup-othermini img#photo')) != "undefined" && parseInt(x1) == x1 && parseInt(x2) == x2 && parseInt(y1) == y1 && parseInt(y2) == y2 && parseInt(w) == w && parseInt(h) == h && parseInt($('div.popup-othermini img#photo').height()) == $('div.popup-othermini img#photo').height() && parseInt($('div.popup-othermini img#photo').width()) == $('div.popup-othermini img#photo').width()) {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '/ajax/registerPassion',
                                                    data: "mode=enregistrericone&passion=" + $("input#passionvalue").val() + "&categorie=" + $("div#listecategorie").val() + "&x1=" + x1 + "&y1=" + y1 + "&x2=" + x2 + "&y2=" + y2 + "&w=" + w + "&h=" + h + "&image=" + image[2] + "&imageheight=" + $('div.popup-othermini img#photo').height() + "&imagewidth=" + $('div.popup-othermini img#photo').width(),
                                                    success:
                                                            function(result) {
                                                                if (result.substr(0, 2) == "2;") {
                                                                    $(popup).hide();
                                                                    $(".background-mini").remove();
                                                                    result_passion = result.split(";");
                                                                    ok_passion = true;
                                                                    $("div#event_organise div.fun-block-inside2 div#event_organise_passion ul#liste_passion li").each(function() {
                                                                        if (new String($(this).attr('class')).replace("passion", "") == result_passion[3])
                                                                            ok_passion = false;
                                                                    });
                                                                    if (ok_passion)
                                                                        $("div#event_organise div.fun-block-inside2 div#event_organise_passion ul#liste_passion").append("<li class='passion" + result_passion[3] + "'>" + result_passion[1] + " : " + result_passion[2] + "<img src='" + result_passion[4] + "' alt='passion'/><a class='retirer_passion' href='javascript:void()'>Retirer</a></li>");
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

                                            $.ajax({
                                                type: 'POST',
                                                url: '/ajax/registerPassion',
                                                data: "mode=enregistrergout&passion=" + $("input#passionvalue").val() + "&categorie=" + $("div#listecategorie").val(),
                                                success:
                                                        function(result) {
                                                            if (result.substr(0, 2) == "2;") {
                                                                $(popup).hide();
                                                                $(".background-mini").remove();
                                                                result_passion = result.split(";");
                                                                ok_passion = true;
                                                                $("div#event_organise div.fun-block-inside2 div#event_organise_passion ul#liste_passion li").each(function() {
                                                                    if (new String($(this).attr('class')).replace("passion", "") == result_passion[3])
                                                                        ok_passion = false;
                                                                });
                                                                if (ok_passion)
                                                                    $("div#event_organise div.fun-block-inside2 div#event_organise_passion ul#liste_passion").append("<li class='passion" + result_passion[3] + "'>" + result_passion[1] + " : " + result_passion[2] + "<img src='" + result_passion[4] + "' alt='passion'/><a class='retirer_passion' href='javascript:void()'>Retirer</a></li>");
                                                            }
                                                            else {
                                                                $("div.error").append(result);
                                                                $("div.error").fadeIn();
                                                            }
                                                        }
                                            });
                                        }
                                    }
                                });

                                $("body").prepend("<div class='background-mini'></div>");
                                $('.background-mini').css({
                                    "opacity": "0.6"
                                }).show();
                                centrer(popup);
                                $(popup).show();

                                $('.background-mini').live('click', function() {
                                    $(popup).hide();
                                    $(".background-mini").remove();
                                });

                                $(window).resize(function() {
                                    centrer($(popup));
                                });
                            }
                            else {
                                $.ajax({
                                    type: 'POST',
                                    url: 'js/Ajax-PHP/passion/enregistrergout.php',
                                    data: "passion=" + $("input#passionvalue").val() + "&categorie=" + $("div#listecategorie").val(),
                                    success:
                                            function(result) {
                                                if (result == "3") {

                                                }
                                                else {
                                                    $("div.error").append(result);
                                                    $("div.error").fadeIn();
                                                }
                                            }
                                });
                            }
                        }
            });
        }
        else {
            msgerreur = "Veuillez saisir une passion";
        }
    });

    /******************************************************************************************************************************
     ************************************************** PHOTO **************************************************************/
    $("input#photo").change(function() {

        if (typeof(resizeimg_photo) != 'undefined')
            resizeimg_photo.cancelSelection();

        $("div#event_organise div.fun-block-inside2  div#event_organise_photo div.loading").show();
        $("div#event_organise div.fun-block-inside2  div.loading").html("<img src='/css/images/loader-autocomplete.gif'/>");
        $(this).upload('/ajax/registerImageEvent', function(res) {
            if (res.substring(0, 2) == "1;")
            {
                image_photo = res.split(";");
                $("div#event_organise div.fun-block-inside2  div#event_organise_photo div.loading").empty();
                $("div#event_organise div.fun-block-inside2  div#event_organise_photo div.error").fadeOut("slow");
                $("div#event_organise div.fun-block-inside2  div#event_organise_photo div.loading").html("<div class='contentimgnoResize'><label class='contentinput'>Image d'origine :</label><img id='photo_event' src='" + image_photo[1] + "' alt='icone' /></div>");
                $("div#event_organise div.fun-block-inside2  div#event_organise_photo div.loading").append("<div class='contentimgResize'><label class='contentinput'>Aperçu :</label><div class='imgResize'><img src='' /></div></div><hr class='clear' />");
                $("div#event_organise div.fun-block-inside2  div#event_organise_photo div.imgResize > img").attr("src", image_photo[1]);

                var img = $("div#event_organise div.fun-block-inside2  div#event_organise_photo img#photo_event")[0]; // Get my img elem


                $("<img/>") // Make in memory copy of image to avoid css issues
                        .attr("src", $(img).attr("src"))
                        .load(function() {
                    image_width_photo = $("img#photo_event").width();   // Note: $(this).width() will not
                    image_height_photo = $("img#photo_event").height(); // work for in memory images.	

                    resizeimg_photo = $('img#photo_event').imgAreaSelect({
                        onSelectEnd: preview_photo,
                        onInit: preview_photo,
                        aspectRatio: '1:1',
                        zIndex: 1005,
                        instance: true,
                        handles: true,
                        persistent: true,
                        x1: 0, y1: 0, x2: 64, y2: 32,
                        minWidth: 64,
                        minHeight: 32,
                        parent: "div#event_organise div.fun-block-inside2  div#event_organise_photo div.contentimgnoResize"
                    });

                });


            }
            else
            {
                $("div#event_organise div.fun-block-inside2  div#event_organise_photo div.error").fadeIn().html(res);
                $("div#event_organise div.fun-block-inside2  div#event_organise_photo div.loading").empty();
            }
        }, 'php');
    });

    /********************************************** AddEvent ***********************************/
    $("div#event_mini a#addThisEvent").live('click', function() {
        $.ajax({
            type: 'POST',
            url: 'js/Ajax-PHP/evenement/addEventMini.php',
            data: "",
            success:
                    function(result) {
                        if (result == "3") {

                        }
                        else {
                            $("div.error").append(result);
                            $("div.error").fadeIn();
                        }
                    }
        });
    });

    $("div#event_organise a#addThisEvent").live('click', function() {

        addEvent();
    });

});

