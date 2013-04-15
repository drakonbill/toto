$(function(){
    $('label[for=ville]').show();
    $('select[name="ville"]').show();
    $("#cp").next(".error-input").hide();

    $.ajax({
        type: 'POST',
        url: 'js/Ajax-PHP/inscription/inscription2.php',
        data: $("#cp").serialize(),
        dataType: 'json',
        success:
        function(json) {
            if(json != null) {
                $('label[for=ville]').fadeIn();
                $('select[name="ville"]').fadeIn();
                $("#cp").next(".error-input").fadeOut();

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
                $("#cp").next(".error-input").fadeIn().text("- Le code postal est inconnu.");
            }
        }
    });
	$("input").uniform();
    $("#cp").keyup(function(){
        if($("#cp").val().length == 5) {

            $.ajax({
                type: 'POST',
                url: 'js/Ajax-PHP/inscription2.php',
                data: $(this).serialize(),
                dataType: 'json',
                success:
                function(json) {
                    if(json != null) {
                        $('label[for=ville]').fadeIn();
                        $('select[name="ville"]').fadeIn();
                        $("#cp").next(".error-input").fadeOut();
                        $('br#ville').show();

                        var $selectVille = $('select[name="ville"]');
                        $selectVille.empty();

                        for(var key in json) {
                            var ville = json[key];
                            $selectVille.append('<option value="'+ville+'">'+json[key]+'</option>');
                        }
                    }
                    if(json == null) {
                        $('label[for=ville]').fadeOut();
                        $('select[name="ville"]').fadeOut(function(){
                            $('br#ville').hide();
                        });

                        $("#cp").next(".error-input").fadeIn().text("- Le code postal est inconnu.");
                    }
                }
            });
        }
    });
            
    $("#changerinfos").submit(function(){
		valid = true;
        if($("#pseudo").val() == ""){
            $("#pseudo").next(".error-input").fadeIn().text("- Veuillez entrer votre pseudo.");
            valid = false;
        }
        else if(!$("#pseudo").val().match(/^[a-z0-9A-Z._-]+$/)) {
            $("#pseudo").next(".error-input").fadeIn().html("- Le pseudo n'est pas au format valide.");
            valid = false;
        }
        else
        {
            $("#pseudo").next(".error-input").fadeOut();
        }


        if(!$("#pseudo").val().match(/^conne|merde|pede|pédé|pedes|pédés|encul|bougnoul|connard|couille|salope|putain|trouduk|enfoiré|tapette|baltringue|grognasse|pédale|pouffiasse|pétasse|enflure|bordel|batard|poufiasse|emmerde|niquer|fiotte|filsdepute|racaille|grognasse|pourriture|branleur|saleporc|ducon|mangemerde|enculé|trouduc|nique|violeur|pédophile|pedophile|nazi|hitler|sarko|baiseur|défonce|defoncer|défoncer|niquer$/i)) {
        }
        else {
            $("#pseudo").next(".error-input").fadeIn().html("- Merci de ne pas mettre de grossiereté.");
            valid = false;
        }

        if($("#pseudo").val().length > 40){
            $("#pseudo").next(".error-input").fadeIn().text("- La taille du pseudo doit être comprise entre 2 et 40 caractères.");
            valid = false;
        }

        if($("#pseudo").val().length < 2){
            $("#pseudo").next(".error-input").fadeIn().text("- La taille du pseudo doit être comprise entre 2 et 40 caractères.");
            valid = false;
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

        if($("#cp").val() == ""){
            $("#cp").next(".error-input").fadeIn().text("- Veuillez entrer un code postal.");
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
            $("#cp").next(".error-input").fadeIn().text("- Le code postal n'est pas au format valide.");
            valid = false;
        }


        var day = $("#day").val();
        var month = $("#month").val();
        var year = $("#year").val();
        var age = 16;
        var mydate = new Date();
        mydate.setFullYear(year, month-1, day);

        var currdate = new Date();
        currdate.setFullYear(currdate.getFullYear() - age);
        if ((currdate - mydate) < 0){
            $("#year").next(".error-input").fadeIn().text("- Vous devez avoir au moins 16 ans pour vous inscrire.");
            valid = false;
        }
        else if ((currdate - mydate) > 0){

            $("#year").next(".error-input").fadeOut();
        }
		
        $.ajax({
            type: 'POST',
            url: 'js/Ajax-PHP/changerinfos.php',
            data: $(this).serialize(),
			async: false,
            success:
            function(result) {
                if(result=='1') {
                    $("#pseudo").next(".error-input").fadeIn().html("- Le pseudo est déjà pris.");
					valid = false;
                }
                if(result=='2') {
                    $("#email").next(".error-input").fadeIn().html("- L'email est déjà pris.");
					valid = false;
                }

                if(result=='123'){
                    $("#pseudo").next(".error-input").fadeIn().html("- Le pseudo est déjà pris.");
                    $("#email").next(".error-input").fadeIn().html("- L'email est déjà pris.");
					valid = false;
                }
            }
        });
        
        return valid;
    });
});