$(function(){
    $('.equal-heights').each(function(){
        $(this).find('.equal').equalHeights();
    })

    $('input[type=checkbox]').each(function(){
        $(this).after('<label class="cb-replace" for="' + $(this).attr('id') + '"></label>');
        $(this).css({
            position:'absolute', 
            left : '-10000px'
        })
    }).click(function(){
        $(this).next()[this.checked ? 'addClass' : 'removeClass']('cb-active');
    })

    $('.slider').nivoSlider();

    $('.designed').each(function(){
        var wa = $(this).width() - 20 - $(this).find('.designed-in').width();
        $(this).find('.designed-line').width(wa / 2);
    })

    $('.popup').fancybox({
        padding : 0,
        wrapCSS : 'popup-box'
    });

    $('#register-form').bind('submit', validateRegisterForm1);

    $('#actualites-pagination a').click(function(){
        if($(this).hasClass('active')) return false;
        $(this).parents('ul').find('a.active').each(function(){
            $($(this).attr('href')).hide();
            $(this).removeClass('active');
        });
        $(this).addClass('active');
        $($(this).attr('href')).show();
        return false;
    })

    $('#slurpee-in').each(function(){
        var w = $(this).find('.slurpee-sep').length * 39 + $(this).find('.slurpee-col').length * 150
        $(this).width(w);
    })
    $('#slurpee .slurpee-sep').height($('#slurpee').height());
    $('#slurpee-left').mouseenter(function(){
        slurpee_start('left');
    }).mouseleave( slurpee_stop );
    $('#slurpee-right').mouseenter(function(){
        slurpee_start('right');
    }).mouseleave( slurpee_stop );

    top_resize();
    $(window).resize(function(){
        top_resize();
    }).load(function(){
        top_resize();
    })
})

function top_resize(){
    var cw = $('#header-left-in2').width();
    var ew = $('#left-menu > li').width();
    var num = $('#left-menu > li').length
    var mr = Math.floor((cw - ew * num) / (num - 1)) - 1;
    $('#left-menu > li').not(':last').css({
        marginRight : mr + 'px'
    })
    $('#right-menu > li').css({
        marginRight : mr + 'px'
    })

    $('#inside-column-wrap').css({
        height : 'auto'
    });
    var h = $('#inside-main').height();
    $('#inside-column-wrap').height(h - 125);


}

var form_down = false;
// Premier passage dans la batterie de test
var result_form1 = false;

function validateRegisterForm1(){
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var errors = [], ok = [], i;
    

    if($('#register-email').val() == ''){
        errors.push(['#register-email', 'Email vide']);
    }
    else {
        
        if(!re.test($('#register-email').val())){
            errors.push(['#register-email', 'Email invalide']);
        }
        else{
            ok.push('#register-email');
        }

    }
    
    if($('#register-confirmation-email').val() == ''){
        errors.push(['#register-confirmation-email', 'Confirmation de l\'email vide']);
    }
    else {
        
        if(!re.test($('#register-confirmation-email').val())){
            errors.push(['#register-confirmation-email', 'Email invalide']);
        }
        else{
            ok.push('#register-confirmation-email');
        }
    }
      
    if($("#register-confirmation-password").val() != $("#register-password").val()){
        errors.push(['#register-confirmation-password', 'Les mots de passe doivent être identiques.']);
    }
    else {
        ok.push('#register-confirmation-password');
    }
    
    if($("#register-confirmation-email").val() != $("#register-email").val()){
        errors.push(['#register-confirmation-email', 'Les email doivent être identiques.']);
    }
    else {
        ok.push('#register-confirmation-email');
    }
    
    if(!$("#register-pseudo").val().match(/^[a-z0-9A-Z._-]+$/)) {
        errors.push(['#register-pseudo', 'Format du pseudo invalide']);
    }
    else{
        ok.push('#register-pseudo');
    }
    
    if($('#register-pseudo').val() == ''){
        errors.push(['#register-pseudo', 'Pseudo vide']);
    }
    else{
        if($("#register-pseudo").val().length > 40 || $("#register-pseudo").val().length < 2){
            errors.push(['#register-pseudo', 'La taille du pseudo doit être entre 2 et 4 caractères']);
        }
        else{
            ok.push('#register-pseudo');
        }
    }
    
    if(!$("#register-pseudo").val().match(/^conne|merde|pede|pédé|pedes|pédés|encul|bougnoul|connard|couille|salope|putain|trouduk|enfoiré|tapette|baltringue|grognasse|pédale|pouffiasse|pétasse|enflure|bordel|batard|poufiasse|emmerde|niquer|fiotte|filsdepute|racaille|grognasse|pourriture|branleur|saleporc|ducon|mangemerde|enculé|trouduc|nique|violeur|pédophile|pedophile|nazi|hitler|sarko|baiseur|défonce|defoncer|défoncer|niquer$/i)) {
        ok.push('#register-pseudo');
    }
    else {
        errors.push(['#register-pseudo', 'Pas de grossièretés dans le pseudo']);
    }
    
    if(!$("#register-password").val().match(/^[a-z0-9A-Z._-]+$/)) {
        errors.push(['#register-password', 'Format du mot de passe invalide']);
    }
    else{
        ok.push('#register-password');
    }
    
    if($('#register-password').val() == ''){
        errors.push(['#register-password', 'Mot de passe vide']);
    }
    else{
        if($("#register-password").val().length > 40 || $("#register-password").val().length < 2){
            errors.push(['#register-password', 'La taille du mot de passe doit être entre 2 et 4 caractères']);
        }
        else{
            ok.push('#register-password');
        }
    }
    
    if($('#register-confirmation-password').val() == ''){
        errors.push(['#register-confirmation-password', 'Mot de passe vide']);
    }
    else{
        if($("#register-confirmation-password").val().length > 40 || $("#register-confirmation-password").val().length < 2){
            errors.push(['#register-confirmation-password', 'La taille du mot de passe doit être entre 2 et 4 caractères']);
        }
        else{
            ok.push('#register-confirmation-password');
        }
    }
    
    for(i = 0; i < ok.length; i++){
        $(ok[i]).parent().find('.form-validation').removeClass('validation-error').addClass('validation-ok').html('OK');
    }
    for(i = 0; i < errors.length; i++){
        $(errors[i][0]).parent().find('.form-validation').html(errors[i][1]).removeClass('validation-ok').addClass('validation-error');
    }
    
    
    ok = errors.length == 0;
	
    $("#register-code-postal").keyup(function(){
        if($("#register-code-postal").val().length == 5) {
            $.ajax({
                type: 'POST',
                url: 'ajax/inscription2',
                data: "cp="+$("#register-code-postal").val(),
                dataType: 'json',
                success:
                function(json) {
                    if(json != null) {
                        $('label[for=register-ville]').fadeIn("slow");
                        $('select[name="register-ville"]').fadeIn("slow");
                        $("#register-code-postal").parent().find('.form-validation').removeClass('validation-error').addClass('validation-ok').html('OK');

                        var $selectVille = $('select[name="register-ville"]');
                        $selectVille.empty();

                        for(var key in json) {
                            var ville = json[key];
                            $selectVille.append('<option value="'+ville+'">'+json[key]+'</option>');
                        }
                    }
                    if(json == null) {
                        $('label[for=register-ville]').fadeOut();
                        $('select[name="register-ville"]').fadeOut();
                        $("#register-code-postal").parent().find('.form-validation').html("Le code postal est inconnu").removeClass('validation-ok').addClass('validation-error');
                    }
                }
            });
        }
        else
        {
            $('label[for=register-ville]').fadeOut();
            $('select[name="register-ville"]').fadeOut();
            $("#register-code-postal").parent().find('.form-validation').html("Le code postal est inconnu").removeClass('validation-ok').addClass('validation-error');
        }
    });
	
    if(form_down) {
       
        var errors = [], ok = [], i;

        // Conditions d'utilisation
        var $conditions = $('input[name=accepte]');
        if($conditions.is(':checked')) {
            ok.push('#register-accepte');
        }
        else {
            errors.push(['#register-accepte', 'Vous devez accepter les conditions d\'utilisation']);
        }
			
        // Sexe
        var $sexe = $('input[name=etes]');
        if($sexe.is(':checked')) {
            ok.push('#register-etes-2');
        }
        else {
            errors.push(['#register-etes-2', 'Vous devez indiquer votre sexe']);
        }
        
        
        // Code Postal
        if($('#register-code-postal').val() == ''){
            errors.push(['#register-code-postal', 'Code Postal vide']);
        }
        else{
            ok.push('#register-code-postal');
        }
        
        if(($("#register-code-postal").val()  >= 1) && ($("#register-code-postal").val()  <= 99999)){
            ok.push('#register-code-postal');
        }
        else
        {
            errors.push(['#register-code-postal', 'Le code postal doit avoir 5 chiffres']);
        }
			
			 
        
        // Date de naissance
        var day = $("#register-jour").val();
        var month = $("#register-mois").val();
        var year = $("#register-annee").val();
        var age = 13;
            
        var mydate = new Date();
        mydate.setFullYear(year, month-1, day);

        var currdate = new Date();
        currdate.setFullYear(currdate.getFullYear() - age);
        if ((currdate - mydate) < 0){
            errors.push(['#register-annee', 'Vous devez avoir 13 ans pour l\'inscription']);
        }
        else if ((currdate - mydate) > 0){
            ok.push('#register-annee');
        }
        
        // La partie de vérification AJAX
        // Vérification du deuxième formulaire
        for(i = 0; i < ok.length; i++){
            $(ok[i]).parent().find('.form-validation').removeClass('validation-error').addClass('validation-ok').html('OK');
        }
        for(i = 0; i < errors.length; i++){
            $(errors[i][0]).parent().find('.form-validation').html(errors[i][1]).removeClass('validation-ok').addClass('validation-error');
        }
			
        if(errors.length == 0) {

            $.ajax({
                type: 'POST',
                url: 'ajax/inscription/',
                data: "register-pseudo="+$("#register-pseudo").val()+"&register-email="+$("#register-email").val(),
                async: false,
                success:
                function(result) {
                    if(result=='1') {
                        $("#register-pseudo").parent().find('.form-validation').fadeIn("slow").html("Le pseudo est déjà pris.").removeClass('validation-ok').addClass('validation-error');
                    }
                    else if(result=='2') {
                        $("#register-email").parent().find('.form-validation').fadeIn("slow").html("L'email est déjà pris.").removeClass('validation-ok').addClass('validation-error');
                    }
                    else if(result=='123'){
                        $("#register-pseudo").parent().find('.form-validation').fadeIn("slow").html("Le pseudo est déjà pris.").removeClass('validation-ok').addClass('validation-error');
                        $("#register-email").parent().find('.form-validation').fadeIn("slow").html("L'email est déjà pris.").removeClass('validation-ok').addClass('validation-error');
                    }
                    else {
                        result_form1=true;
                    }
                }
            });
        }
			
        
    }
	
    // ACTIVATION DU DEUXIEME PARTIE DU FORMULAIRE
    if(ok && !form_down){
        //$('#register-form').unbind('submit', validateRegisterForm1);
        //$('#register-form').bind('submit', validateRegisterForm2);
        $('.register-form-part2').slideDown();
        form_down = true;
        
    }
        
    return result_form1;
    
}

var slurpee_interval = null, slurpee_dir;
function slurpee_stop(){
    if(slurpee_interval){
        clearInterval(slurpee_interval);
        slurpee_interval = null;
    }
}
function slurpee_start(dir){
    slurpee_dir = dir;
    slurpee_interval = setInterval(slurpee_scroll, 10);
}
function slurpee_scroll(){
    var o = $('#slurpee-in');
    var ml = parseInt(o.css('marginLeft')) || 0;
    var iw = o.width();
    var ow = $('#slurpee-oh').width();
    if(iw < ow){
        return;
    }
    var max_ml = 0, min_ml = ow - iw;
    ml += slurpee_dir == 'left' ? 3 : -3;
    ml = Math.max(min_ml, Math.min(max_ml, ml));
    o.css({
        marginLeft : ml + 'px'
    });
}