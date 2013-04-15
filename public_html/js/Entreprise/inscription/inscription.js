$(function(){
    $('#conditions-popup').dialog({
        autoOpen: false,
        width: 700,
        buttons: {
            "Accepter": function() {
                $(this).dialog("close");
            },
            "Refuser": function() {
                $(this).dialog("close");
            }
        }
    });

    $('#condi').click(function(){
        $('#conditions-popup').dialog('open');
        return false;
    });

    $("#signup").submit(function(){

        valid = true;

        if($("#nomentreprise").val() == ""){
            $("#nomentreprise").next(".error-input").fadeIn().text("- Veuillez entrer le nom de l'entreprise.");
            valid = false;
        }
        else
        {
            $("#nomentreprise").next(".error-input").fadeOut();
        }
        
        if(!$("#nomentreprise").val().match(/^conne|merde|pede|pédé|pedes|pédés|encul|bougnoul|connard|couille|salope|putain|trouduk|enfoiré|tapette|baltringue|grognasse|pédale|pouffiasse|pétasse|enflure|bordel|batard|poufiasse|emmerde|niquer|fiotte|filsdepute|racaille|grognasse|pourriture|branleur|saleporc|ducon|mangemerde|enculé|trouduc|nique|violeur|pédophile|pedophile|nazi|hitler|sarko|baiseur|défonce|defoncer|défoncer|niquer$/i)) {
        }
        else {
            $("#nomentreprise").next(".error-input").fadeIn().html("- Merci de ne pas mettre de grossiereté.");
            valid = false;
        }

        if($("#password2").val() == ""){
            $("#password2").next(".error-input").fadeIn().text("- Veuillez entrer votre mot de passe.");
            valid = false;
        }
        else if(!$("#password2").val().match(/^[a-z0-9A-Z._-]+$/)) {
            $("#password2").next(".error-input").fadeIn().text("- Le mot de passe n'est pas au format valide.");
            valid = false;
        }
        else if($("#password2").val().length > 40 || $("#password2").val().length < 2) {
            $("#password2").next(".error-input").fadeIn().text("- La taille du mot de passe doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }
        else
        {
            $("#password2").next(".error-input").fadeOut();
        }

        if($("#confirmation").val() == ""){
            $("#confirmation").next(".error-input").fadeIn().text("- Veuillez entrer votre mot de passe.");
            valid = false;
        }
        else if(!$("#confirmation").val().match(/^[a-z0-9A-Z._-]+$/)) {
            $("#confirmation").next(".error-input").fadeIn().text("- Le mot de passe n'est pas au format valide.");
            valid = false;
        }
        else if($("#confirmation").val().length > 40 || $("#confirmation").val().length < 2) {
            $("#confirmation").next(".error-input").fadeIn().text("- La taille du mot de passe doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }
        else if($("#confirmation").val() != $("#password2").val()) {
            $("#confirmation").next(".error-input").fadeIn().text("- Les mots de passe doivent être indentiques.");
            valid = false;
        }
        else
        {
            $("#confirmation").next(".error-input").fadeOut();
        }

        if($("#email").val() == ""){
            $("#email").next(".error-input").fadeIn().text("- Veuillez entrer un email.");
            valid = false;
        }
        else if(!$("#email").val().match(/^[a-z0-9._-]+@[a-z0-9.-]{2,}[.][a-z]{2,4}$/)) {
            $("#email").next(".error-input").fadeIn().text("- L'email n'est pas au format valide.");
            valid = false;
        }
        else
        {
            $("#email").next(".error-input").fadeOut();
        }

        if($("#email").val().length < 5){
            $("#email").next(".error-input").fadeIn().text("- L'email est trop court.");
            valid = false;
        }

        if($("#email").val().length > 70){
            $("#email").next(".error-input").fadeIn().text("- L'email est trop long.");
            valid = false;
        }

        // Pour les conditions
        var $conditions = $('input[name=conditions]');
        if($conditions.is(':checked')) {
            $conditions.next('.error-input').fadeOut();
        }
        else {
            $conditions.next('.error-input').fadeIn().text('- Vous devez accepter les conditions d\'utilisation.');
            valid = false;
        }

        // Pour le captcha
        if($("#captcha").val() != ""){
            valid = false;
        }


        // La partie AJAX
        $.ajax({
            type: 'POST',
            url: 'js/Ajax-PHP/inscription.php',
            data: $(this).serialize(),
            async: false,
            success:
            function(result) {
                if(result=='1') {
                    $("#email").next(".error-input").fadeIn().html("- L'email est déjà pris.");
                    valid=false;
                }
            }
        });

        return valid;
    });
});