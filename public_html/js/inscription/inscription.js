$(function(){


    $('label[for=ville]').hide();
    $('select[name="ville"]').hide();
    $("#cp").next(".error-input").hide();
    $("br#ville").hide();

    if($.browser.msie)
    {
        $("legend,.hometitle").dropShadow({
            left:0,
            top:0,
            blur:1,
            opacity:1
        });
        $("#signup").dropShadow({
            left:0,
            top:0,
            blur:4,
            opacity:0.5
        });
    }
		
    $(".hideform").hide();
		
    validvisuelpseudo = false;
    $("#pseudo2").bind("change keyup", function(){
        // Pour le pseudo
        if($("#pseudo2").val() == ""){
            $("#pseudo2").next(".error-input").fadeIn("slow").text("- Veuillez entrer votre pseudo.");
        }
        else
        {
            validvisuelpseudo=true;
            $("#pseudo2").next(".error-input").fadeOut("slow");
        }
    });
    validvisuelpassword = false;
    $("#password2").bind("change keyup",function(){
        if($("#password2").val() == ""){
            $("#password2").next(".error-input").fadeIn("slow").text("- Veuillez entrer votre mot de passe.");
        }
        else
        {
            validvisuelpassword=true;
            $("#password2").next(".error-input").fadeOut("slow");
        }
    });
    validvisuelconfirmation = false;
    $("#confirmation").bind("change keyup",function(){
        if($("#confirmation").val() == ""){
            $("#confirmation").next(".error-input").fadeIn("slow").text("- Veuillez entrer votre mot de passe.");
        }
        else if($("#confirmation").val() != $("#password2").val()){
            $("#confirmation").next(".error-input").fadeIn("slow").text("- Les mots de passe doivent être indentiques.");
        }
        else
        {
            validvisuelconfirmation=true;
            $("#confirmation").next(".error-input").fadeOut("slow");
        }
    });
    validvisuelemail = false;
    $("#email").bind("change keyup",function(){
        if($("#email").val() == ""){
            $("#email").next(".error-input").fadeIn("slow").text("- Veuillez entrer un email.");
        }
        else
        {
            validvisuelemail=true;
            $("#email").next(".error-input").fadeOut("slow");
        }
    });
	
    $("#email,#password2,#confirmation,#pseudo2,#pays").bind("change keyup",function(){
        if(validvisuelpseudo == true && validvisuelpassword == true && validvisuelconfirmation == true && validvisuelemail == true){
            $(".hideform").fadeIn("slow");
            $("input[type=checkbox],input[type=radio]").uniform();
        }	
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
                            if (ville.length > 15) {
                                $("#signup").css({
                                    width: "400px"
                                });            
                            }
                            $selectVille.append('<option value="'+ville+'">'+json[key]+'</option>');
                        }
                    }
                    if(json == null) {
                        $('label[for=ville]').fadeOut();
                        $('select[name="ville"]').fadeOut();
                        $("#cp").next(".error-input").fadeIn("slow").text("- Le code postal est inconnu.");
                        $("br#ville").hide();
                        $("#signup").css({
                            width: "376px"
                        });
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


    $('#conditions-popup').dialog({
        autoOpen: false,
        width: 500,
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

        // Pour le pseudo
        if($("#pseudo2").val() == ""){
            $("#pseudo2").next(".error-input").fadeIn("slow").text("- Veuillez entrer votre pseudo.");
            valid = false;
        }
        else if(!$("#pseudo2").val().match(/^[a-z0-9A-Z._-]+$/)) {
            $("#pseudo2").next(".error-input").fadeIn("slow").html("- Le pseudo n'est pas au format valide.");
            valid = false;
        }
        else if($("#pseudo2").val().length > 40 || $("#pseudo2").val().length < 2){
            $("#pseudo2").next(".error-input").fadeIn("slow").text("- La taille du pseudo doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }
        else
        {
            $("#pseudo2").next(".error-input").fadeOut();
        }
        
        if(!$("#pseudo2").val().match(/^conne|merde|pede|pédé|pedes|pédés|encul|bougnoul|connard|couille|salope|putain|trouduk|enfoiré|tapette|baltringue|grognasse|pédale|pouffiasse|pétasse|enflure|bordel|batard|poufiasse|emmerde|niquer|fiotte|filsdepute|racaille|grognasse|pourriture|branleur|saleporc|ducon|mangemerde|enculé|trouduc|nique|violeur|pédophile|pedophile|nazi|hitler|sarko|baiseur|défonce|defoncer|défoncer|niquer$/i)) {
        }
        else {
            $("#pseudo2").next(".error-input").fadeIn("slow").html("- Merci de ne pas mettre de grossiereté.");
            valid = false;
        }

        // Pour le mot de passe
        if($("#password2").val() == ""){
            $("#password2").next(".error-input").fadeIn("slow").text("- Veuillez entrer votre mot de passe.");
            valid = false;
        }
        else if(!$("#password2").val().match(/^[a-z0-9A-Z._-]+$/)) {
            $("#password2").next(".error-input").fadeIn("slow").text("- Le mot de passe n'est pas au format valide.");
            valid = false;
        }
        else if($("#password2").val().length > 40 || $("#password2").val().length < 2){
            $("#password2").next(".error-input").fadeIn("slow").text("- La taille du mot de passe doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }
        else
        {
            $("#password2").next(".error-input").fadeOut();
        }

        if($("#confirmation").val() == ""){
            $("#confirmation").next(".error-input").fadeIn("slow").text("- Veuillez entrer votre mot de passe.");
            valid = false;
        }
        else if($("#confirmation").val() != $("#password2").val()){
            $("#confirmation").next(".error-input").fadeIn("slow").text("- Les mots de passe doivent être indentiques.");
            valid = false;
        }
        else if(!$("#confirmation").val().match(/^[a-z0-9A-Z._-]+$/)) {
            $("#confirmation").next(".error-input").fadeIn("slow").text("- Le mot de passe n'est pas au format valide.");
            valid = false;
        }
        else if($("#confirmation").val().length > 40 || $("#confirmation").val().length < 2){
            $("#confirmation").next(".error-input").fadeIn("slow").text("- La taille du mot de passe doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }
        else
        {
            $("#confirmation").next(".error-input").fadeOut();
        }

        if($("#email").val() == ""){
            $("#email").next(".error-input").fadeIn("slow").text("- Veuillez entrer un email.");
            valid = false;
        }
        else if(!$("#email").val().match(/^[a-z0-9._-]+@[a-z0-9.-]{2,}[.][a-z]{2,4}$/)) {
            $("#email").next(".error-input").fadeIn("slow").text("- L'email n'est pas au format valide.");
            valid = false;
        }
        else if($("#email").val().length < 5 || $("#email").val().length > 70){
            $("#email").next(".error-input").fadeIn("slow").text("- L'email est trop court.");
            valid = false;
        }
        else
        {
            $("#email").next(".error-input").fadeOut();
        }
		
        if(valid == true)
        {

            // Pour les conditions
            var $conditions = $('input[name=conditions]');
            if($conditions.is(':checked')) {
                $conditions.next('.error-input').fadeOut();
            }
            else {
                $conditions.next('.error-input').fadeIn("slow").text('- Vous devez accepter les conditions d\'utilisation.');
                valid = false;
            }

            // Pour le captcha
            if($("#captcha").val() != ""){
                valid = false;
            }

            // Pour le code postal
            if($("#cp").val() == ""){
                $("#cp").next(".error-input").fadeIn("slow").text("- Veuillez entrer un code postal.");
                valid = false;
            }
            else
            {
                $("#cp").next(".error-input").fadeOut();
            }

            if(($("#cp").val()  >= 1) && ($("#cp").val()  <= 99999)){
            }
            else
            {
                $("#cp").next(".error-input").fadeIn("slow").text("- Le code postal n'est pas au format valide.");
                valid = false;
            }

            var day = $("#Jour").val();
            var month = $("#month").val();
            var year = $("#year").val();
            var age = 16;

            if($("#day").val() == "none"){
                $("#year").next(".error-input").fadeIn("slow").text("- Aucun jour n'a été sélectionné.");
                valid = false;
            }
            else if($("#month").val() == "none"){
                $("#year").next(".error-input").fadeIn("slow").text("- Aucun mois n'a été sélectionné.");
                valid = false;
            }
            else if($("#year").val() == "none"){
                $("#year").next(".error-input").fadeIn("slow").text("- Aucune année n'a été sélectionnée.");
                valid = false;
            }
            else
            {
                $("#year").next(".error-input").fadeOut();
            }
			
            var mydate = new Date();
            mydate.setFullYear(year, month-1, day);

            var currdate = new Date();
            currdate.setFullYear(currdate.getFullYear() - age);
            if ((currdate - mydate) < 0){
                $("#year").next(".error-input").fadeIn("slow").text("- Vous devez avoir au moins 16 ans pour vous inscrire.");
                valid = false;
            }
            else if ((currdate - mydate) > 0){
			
                $("#year").next(".error-input").fadeOut();
            }

            // La partie AJAX
            $.ajax({
                type: 'POST',
                url: 'js/Ajax-PHP/inscription/inscription.php',
                data: $('#form').serialize(),
                async: false,
                success:
                function(result) {
                    if(result=='1') {
                        $("#pseudo2").next(".error-input").fadeIn("slow").html("- Le pseudo est déjà pris.");
                        valid=false;
                    }
                    if(result=='2') {
                        $("#email").next(".error-input").fadeIn("slow").html("- L'email est déjà pris.");
                        valid=false;
                    }

                    if(result=='123'){
                        $("#pseudo2").next(".error-input").fadeIn("slow").html("- Le pseudo est déjà pris.");
                        $("#email").next(".error-input").fadeIn("slow").html("- L'email est déjà pris.");
                        valid=false;
                    }
                }
            });
        }
        return valid;
    });
});
