$(function(){

    $("#changersecrete").submit(function(){

        valid = true;
		
		if($("#reponsesecrete").val() == ""){
            $("#reponsesecrete").next(".error-input").fadeIn().text("- Une réponse secrète doit être saisie.");
            valid = false;
        }   
		else
        {
            $("#reponsesecrete").next(".error-input").fadeOut();
        }
		
		if($("#questionsecrete").val() == ""){
	     $("#questionsecrete").next(".error-input").fadeIn().text("- Une question secrète doit être saisie.");
            valid = false;
        }
		else
        {
            $("#questionsecrete").next(".error-input").fadeOut();
        }
		
		if($("#questionsecrete").val() != "" && $("#questionsecrete").val().length < 15){
            $("#questionsecrete").next(".error-input").fadeIn().text("- La question secrète dontenir au moins 15 caractères.");
            valid = false;
        }
		
		if($("#reponsesecrete").val() != "" && $("#reponsesecrete").val().length < 5){
            $("#reponsesecrete").next(".error-input").fadeIn().text("- La réponse secrète dontenir au moins 5 caractères.");
            valid = false;
        }
		
        // Pour le captcha
        if($("#captcha").val() != ""){
            valid = false;
        }

        return valid;
    });
});