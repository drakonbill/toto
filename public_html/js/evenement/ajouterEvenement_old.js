function checkdate (m, d, y) {
    return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();
}

function time () {
    return Math.floor(new Date().getTime() / 1000);
}

function mktime () {
    var d = new Date(),
        r = arguments,
        i = 0,
        e = ['Hours', 'Minutes', 'Seconds', 'Month', 'Date', 'FullYear'];
 
    for (i = 0; i < e.length; i++) {
        if (typeof r[i] === 'undefined') {
            r[i] = d['get' + e[i]]();
            r[i] += (i === 3); // +1 to fix JS months.
        } else {
            r[i] = parseInt(r[i], 10);
            if (isNaN(r[i])) {
                return false;
            }
        }
    }
    r[5] += (r[5] >= 0 ? (r[5] <= 69 ? 2e3 : (r[5] <= 100 ? 1900 : 0)) : 0);
    d.setFullYear(r[5], r[3] - 1, r[4]);
    d.setHours(r[0], r[1], r[2]);
    return (d.getTime() / 1e3 >> 0) - (d.getTime() < 0);
}

$(function(){
    
	$("input[type=text],textarea").uniform();
	
	$("select#visiblepar").live('change', function() {
		if($(this).val() == 3)
			$("div#groupepredefinie").css("display","inline");
		else
			$("div#groupepredefinie").css("display","none");
		if($(this).val() == 4)
			$("div#contactpredefinie").css("display","inline");
		else
			$("div#contactpredefinie").css("display","none");
	});
	
	$("#cp").keyup(function(){
        if($("#cp").val().length == 5) {
        
            $.ajax({
                type: 'POST',
                url: 'js/Ajax-PHP/inscription/inscription2.php',
                data: $(this).serialize(),
                dataType: 'json',
                success:
                function(json) {
                    if(json != null) {
                        $('label[for=ville]').fadeIn("slow");
                        $('select[name="ville"]').fadeIn("slow");
                        $("#cp").next(".error-input").fadeOut();
						$("br#ville").fadeIn("slow");

                        var $selectVille = $('select[name="ville"]');
                        $selectVille.empty();

                        for(var key in json) {
                            var ville = json[key];
                            $selectVille.append('<option value="'+ville+'">'+json[key]+'</option>');
                        }
                    }
                    if(json == null) {
                        $('label[for=ville]').fadeOut();
                        $('select[name="ville"]').fadeOut();
                        $("#cp").next(".error-input").fadeIn("slow").text("- Le code postal est inconnu.");
						$("br#ville").hide();
                    }
                }
            });
        }
		else
		{
			$('label[for=ville]').fadeOut();
			$('select[name="ville"]').fadeOut();
			$("#cp").next(".error-input").fadeOut();
			$("br#ville").hide();
		}
    });
	
    $("#ajouterEvenement").submit(function(){

        var valid = true;
		var message = "";
		var groupepredefinie = "";
		var contactpredefinie = "";
		var jour = $("#jour").val();
		var mois = $("#mois").val();
		var annee = $("#annee").val();
		var jourfin = $("#jourfin").val();
		var moisfin = $("#moisfin").val();
		var anneefin = $("#anneefin").val();
		
		if(!checkdate(mois, jour, annee) || mktime(0,0,0,mois,jour,annee) < time()) {
			message += "- La date de début de l'évènement n'est pas valide<br/>";
            valid = false;
		}
		
		if(!checkdate(moisfin, jourfin, anneefin) || mktime(0,0,0,moisfin,jourfin,anneefin) < time()) {
			message += "- La date de fin de l'évènement n'est pas valide<br/>";
            valid = false;
		}
		
        // Pour le pseudo
        if($("#nomevenement").val() == ""){
           message += "- Veuillez saisir le nom de l'évènement<br/>";
           valid = false;
        }
		else if($("#nomevenement").val().length<2 || $("#nomevenement").val().length>70){
           message += "- La taille du nom de l'évènement doit être compris entre 2 et 70 caractères<br/>";
            valid = false;
        }
        else if(!$("#nomevenement").val().match(/^[a-z0-9A-Z._-]+$/)) {
            message += "- Le nom de l'évènement n'est pas au format valide.<br/>";
            valid = false;
        }
        
        if(!$("#nomevenement").val().match(/^conne|merde|pede|pédé|pedes|pédés|encul|bougnoul|connard|couille|salope|putain|trouduk|enfoiré|tapette|baltringue|grognasse|pédale|pouffiasse|pétasse|enflure|bordel|batard|poufiasse|emmerde|niquer|fiotte|filsdepute|racaille|grognasse|pourriture|branleur|saleporc|ducon|mangemerde|enculé|trouduc|nique|violeur|pédophile|pedophile|nazi|hitler|sarko|baiseur|défonce|defoncer|défoncer|niquer$/i)) {
        }
        else {
            message += "- Merci de ne pas mettre de grossiereté.<br/>";
            valid = false;
        }
		
		if($("#nombreparticipant").val() == ""){
			message += "- Veuillez entrer le nombre de participants maximum.<br/>";
			valid = false;
		}
		else if(!$("#nombreparticipant").val().match(/^[0-9]+$/)) {
			message += "- Le nombre de participants n'est pas au format valide.<br/>";
			valid = false;
		}
		
		if($("#description").val() == ""){
			message += "- Veuillez entrer la description de l'évènement.<br/>";
			valid = false;
		}
		else if($("#description").val().length<50 || $("#description").val().length>999){
			message += "- La taille de la description doit être compris entre 50 et 1000 caractères.<br/>";
			valid = false;
		}
		
		if($("#type").val() == 1){
			message += "- Veuillez sélectionner le type d'évènement.<br/>";
			valid = false;
		}
		
		if(valid == false)
		{
			$("#error").fadeIn().html(message);
		}
		
		valid == false;
        return valid;
    });
});