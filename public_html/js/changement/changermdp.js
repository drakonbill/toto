$(function(){

    $("#changermdp").submit(function(){

        valid = true;

        // Pour le mot de passe
		 if($("#password1").val() == ""){
            $("#password1").next(".error-input").fadeIn().text("- Veuillez entrer votre mot de passe actuel.");
            valid = false;
        }
        else if(!$("#password1").val().match(/^[a-z0-9A-Z._-]+$/)) {
            $("#password1").next(".error-input").fadeIn().text("- Le mot de passe n'est pas au format valide.");
            valid = false;
        }
        else
        {
            $("#password1").next(".error-input").fadeOut();
        }
		
        if($("#password2").val() == ""){
            $("#password2").next(".error-input").fadeIn().text("- Veuillez entrer votre mot de passe.");
            valid = false;
        }
        else if(!$("#password2").val().match(/^[a-z0-9A-Z._-]+$/)) {
            $("#password2").next(".error-input").fadeIn().text("- Le mot de passe n'est pas au format valide.");
            valid = false;
        }
        else
        {
            $("#password2").next(".error-input").fadeOut();
        }

        if($("#password2").val().length > 40){
            $("#password2").next(".error-input").fadeIn().text("- La taille du mot de passe doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }

        if($("#password2").val().length < 2){
            $("#password2").next(".error-input").fadeIn().text("- La taille du mot de passe doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }

        if($("#confirmation").val() == ""){
            $("#confirmation").next(".error-input").fadeIn().text("- Veuillez entrer votre mot de passe.");
            valid = false;
        }
        else if(!$("#confirmation").val().match(/^[a-z0-9A-Z._-]+$/)) {
            $("#confirmation").next(".error-input").fadeIn().text("- Le mot de passe n'est pas au format valide.");
            valid = false;
        }
        else
        {
            $("#confirmation").next(".error-input").fadeOut();
        }

        if($("#confirmation").val().length > 40){
            $("#confirmation").next(".error-input").fadeIn().text("- La taille du mot de passe doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }

        if($("#confirmation").val().length < 2){
            $("#confirmation").next(".error-input").fadeIn().text("- La taille du mot de passe doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }

        if($("#confirmation").val() != $("#password2").val()){
            $("#confirmation").next(".error-input").fadeIn().text("- Les mots de passe doivent être indentiques.");
            valid = false;
        }
		
        // Pour le captcha
        if($("#captcha").val() != ""){
            valid = false;
        }

        // La partie AJAX
        $.ajax({
            type: 'POST',
            url: 'js/Ajax-PHP/changermdp.php',
            data: $(this).serialize(),
			async: false,
            success:
            function(result) {
                if(result=='1') {
                    $("#password1").next(".error-input").fadeIn().html("- Le mot de passe n'est pas correct.");
					valid=false;
                }
            }
        });

        return valid;
    });
});