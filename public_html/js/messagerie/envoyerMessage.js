$(function(){

    $("#envoyerMessage").submit(function(){

        valid = true;

        // Pour le mot de passe
		 if($("#sujet").val() == ""){
            $("#sujet").next(".error-input").fadeIn().text("- Veuillez entrer le sujet.");
            valid = false;
        }
        else
        {
            $("#sujet").next(".error-input").fadeOut();
        }
		
        if($("#message").val() == ""){
            $("#message").next(".error-input").fadeIn().text("- Veuillez entrer le message.");
            valid = false;
        }
        else
        {
            $("#message").next(".error-input").fadeOut();
        }

        if($("#sujet").val().length > 40 || $("#sujet").val().length < 2){
            $("#sujet").next(".error-input").fadeIn().text("- Le sujet doit être compris entre 2 et 40 caractères.");
            valid = false;
        }

        if($("#idrecepteur").val() == ""){
            $("#idrecepteur").next(".error-input").fadeIn().text("- Veuillez sélectionner un contact.");
            valid = false;
        }
        else
        {
            $("#idrecepteur").next(".error-input").fadeOut();
        }
		
        // Pour le captcha
        if($("#captcha").val() != ""){
            valid = false;
        }

        // La partie AJAX
        $.ajax({
            type: 'POST',
            url: 'js/Ajax-PHP/messagerie/envoyerMessage.php',
            data: $(this).serialize(),
			async: false,
            success:
            function(result) {
                if(result=='1') {
                    $("#idrecepteur").next(".error-input").fadeIn().html("- Le contact est introuvable.");
					valid=false;
                }
            }
        });

        return valid;
    });
	
	//$("input#listecontact").
});
