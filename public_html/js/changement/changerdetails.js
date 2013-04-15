function stristr (haystack, needle, bool) {
    // Finds first occurrence of a string within another, case insensitive  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/stristr
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfxied by: Onno Marsman
    // *     example 1: stristr('Kevin van Zonneveld', 'Van');
    // *     returns 1: 'van Zonneveld'
    // *     example 2: stristr('Kevin van Zonneveld', 'VAN', true);
    // *     returns 2: 'Kevin '
    var pos = 0;
 
    haystack += '';
    pos = haystack.toLowerCase().indexOf((needle + '').toLowerCase());
    if (pos == -1) {
        return false;
    } else {
        if (bool) {
            return haystack.substr(0, pos);
        } else {
            return haystack.slice(pos);
        }
    }
}
	
$(function(){
	
    $("#changerdetails").submit(function(){

        valid = true;

        // Pour le pseudo
		if($("#prenom").val() != "" && !$("#prenom").val().match(/^[a-zA-Z]+$/)) {
            $("#prenom").next(".error-input").fadeIn().html("- Le pseudo n'est pas au format valide.");
            valid = false;
        }
        else
        {
            $("#prenom").next(".error-input").fadeOut();
        }
        
        if(!$("#prenom").val().match(/^conne|merde|pede|pédé|pedes|pédés|encul|bougnoul|connard|couille|salope|putain|trouduk|enfoiré|tapette|baltringue|grognasse|pédale|pouffiasse|pétasse|enflure|bordel|batard|poufiasse|emmerde|niquer|fiotte|filsdepute|racaille|grognasse|pourriture|branleur|saleporc|ducon|mangemerde|enculé|trouduc|nique|violeur|pédophile|pedophile|nazi|hitler|sarko|baiseur|défonce|defoncer|défoncer|niquer$/i)) {
        }
        else {
            $("#prenom").next(".error-input").fadeIn().html("- Merci de ne pas mettre de grossiereté.");
            valid = false;
        }

        if($("#description").val() != "" && ($("#description").val().length < 25 || $("#description").val().length > 1500)){
            $("#description").next(".error-input").fadeIn().text("- La taille votre description doit être comprise entre 50 et 1500 caractères.");
            valid = false;
        }
		else
        {
            $("#description").next(".error-input").fadeOut();
        }
		
		if(!$("#description").val().match(/^conne|merde|pede|pédé|pedes|pédés|encul|bougnoul|connard|couille|salope|putain|trouduk|enfoiré|tapette|baltringue|grognasse|pédale|pouffiasse|pétasse|enflure|bordel|batard|poufiasse|emmerde|niquer|fiotte|filsdepute|racaille|grognasse|pourriture|branleur|saleporc|ducon|mangemerde|enculé|trouduc|nique|violeur|pédophile|pedophile|nazi|hitler|sarko|baiseur|défonce|defoncer|défoncer|niquer$/i)) {
        }
        else {
            $("#description").next(".error-input").fadeIn().html("- Merci de ne pas mettre de grossiereté.");
            valid = false;
        }

        if($("#recherche").val() != "" && ($("#recherche").val().length < 25 || $("#recherche").val().length > 1500)){
            $("#recherche").next(".error-input").fadeIn().text("- La taille de la description de votre recherche doit être comprise entre 50 et 1500 caractères.");
            valid = false;
        }
		else
        {
            $("#recherche").next(".error-input").fadeOut();
        }
		
		if(!$("#recherche").val().match(/^conne|merde|pede|pédé|pedes|pédés|encul|bougnoul|connard|couille|salope|putain|trouduk|enfoiré|tapette|baltringue|grognasse|pédale|pouffiasse|pétasse|enflure|bordel|batard|poufiasse|emmerde|niquer|fiotte|filsdepute|racaille|grognasse|pourriture|branleur|saleporc|ducon|mangemerde|enculé|trouduc|nique|violeur|pédophile|pedophile|nazi|hitler|sarko|baiseur|défonce|defoncer|défoncer|niquer$/i)) {
        }
        else {
            $("#recherche").next(".error-input").fadeIn().html("- Merci de ne pas mettre de grossiereté.");
            valid = false;
        }

		if($("#adressemsn").val() != "" && !$("#adressemsn").val().match(/^[a-z0-9._-]+@[a-z0-9.-]{2,}[.][a-z]{2,4}$/) && !stristr($("#adressemsn").val(), '@hotmail') && !stristr($("#adressemsn").val(), '@live') && !stristr($("#adressemsn").val(), '@msn')) {
            $("#adressemsn").next(".error-input").fadeIn().text("- L'adresse msn n'est pas au format valide.");
            valid = false;
        }
        else
        {
            $("#adressemsn").next(".error-input").fadeOut();
        }
		
		if($("#facebook").val() != "" && !$("#facebook").val().match(/^[a-z0-9._-]+@[a-z0-9.-]{2,}[.][a-z]{2,4}$/) && !stristr($("#facebook").val(), 'facebook.com')) {
            $("#facebook").next(".error-input").fadeIn().text("- Le lien facebook n'est pas au format valide.");
            valid = false;
        }
        else
        {
            $("#facebook").next(".error-input").fadeOut();
        }

        // Pour le captcha
        if($("#captcha").val() != ""){
            valid = false;
        }

        return valid;
    });
});