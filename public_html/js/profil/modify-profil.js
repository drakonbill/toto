$(document).ready(function() {

    function firstnameajax(value, settings) {
        console.log(this);
        console.log(value);
        console.log(settings);

        $.ajax({
            type: 'POST',
            url: '/ajax/modifyFirstName',
            data: "first-name=" + value,
            success:
                    function(result) {
                        // alert(result); 
                    }
        });
    }

    function pseudoajax(value, settings) {
        console.log(this);
        console.log(value);
        console.log(settings);

        $.ajax({
            type: 'POST',
            url: '/ajax/modifyPseudo',
            data: "pseudo=" + value,
            success:
                    function(result) {
                        if (result === "alreadytake") {
                            $(".form-validation-pseudo").html("Pseudo déjà pris. <br/>");
                            return result;
                        }

                        if (result === "adminformat") {
                            $(".form-validation-pseudo").html("Les pseudos : Mister- et Miss- sont réservés. <br/>");
                            return result;
                        }
                    }
        });
        
    }

    function situationajax(value, settings) {
        console.log(this);
        console.log(value);
        console.log(settings);

        $.ajax({
            type: 'POST',
            url: '/ajax/modifySituation',
            data: "situation=" + value,
            success:
                    function(result) {
                        //  alert(result); 
                    }
        });
    }

    function preferanceajax(value, settings) {
        console.log(this);
        console.log(value);
        console.log(settings);

        $.ajax({
            type: 'POST',
            url: '/ajax/modifyPreferance',
            data: "preferance=" + value,
            success:
                    function(result) {
                        //  alert(result); 
                    }
        });
    }

    function sexeajax(value, settings) {

        console.log(this);
        console.log(value);
        console.log(settings);


        $.ajax({
            type: 'POST',
            url: '/ajax/modifySexe',
            data: "sexe=" + value,
            success:
                    function(result) {
                        // alert(result);
                    }
        });
    }

    $('#first-name').editable(function(value, settings) {
        firstnameajax(value, settings);
        return(value);
    }, {
        type: 'text',
        submit: 'Modifier',
        cancel: 'Annuler',
        width: '200px',
    });

// PSEUDO OF THE MEMBER

    pseudo = $("#pseudo").text();

    $('#pseudo').editable(function(value, settings) {

        $(".form-validation-pseudo").html("");

        if (value.length <= 3) {
            $(".form-validation-pseudo").html("Pseudo trop court. <br/>");
            return(pseudo);
        }
        else if (value.length > 45) {
            $(".form-validation-pseudo").html("Pseudo trop long. <br/>");
            return(pseudo);
        }
        else {

            pseudoajax(value, settings);

            pseudo = value;
            return(pseudo);
        }
    }, {
        type: 'text',
        submit: 'Modifier',
        cancel: 'Annuler',
        width: '200px',
    });

    $('#situation1').editable(function(value, settings) {
        situationajax(value, settings);
        return(value);
    }, {
        data: "{'Non communiquée':'Non communiquée', 'Célibataire':'Célibataire','Couple':'Couple','Marié':'Marié'}",
        type: 'select',
        submit: 'Modifier',
        cancel: 'Annuler',
        width: '200px',
    });

    $('#situation2').editable(function(value, settings) {
        situationajax(value, settings);
        return(value);
    }, {
        data: "{'Non communiquée':'Non communiquée', 'Célibataire':'Célibataire','Couple':'Couple','Mariée':'Mariée'}",
        type: 'select',
        submit: 'Modifier',
        cancel: 'Annuler',
        width: '200px',
    });

    $('#preferance').editable(function(value, settings) {
        preferanceajax(value, settings);
        return(value);
    }, {
        data: "{'Non communiqué':'Non communiqué', 'Homme':'Homme', 'Femme':'Femme', 'Homme et Femme':'Homme et Femme' }",
        type: 'select',
        submit: 'Modifier',
        cancel: 'Annuler',
        width: '200px',
    });

    $('#sexe').editable(function(value, settings) {
        sexeajax(value, settings);
        return(value);
    }, {
        data: "{'Homme':'Homme', 'Femme':'Femme'}",
        type: 'select',
        submit: 'Modifier',
        cancel: 'Annuler',
        width: '200px',
    });

    $('#situationpro').editable(function(value, settings) {
        situationproajax(value, settings);
        return(value);
    }, {
        data: "{'Non communiqué':'Non communiqué', 'Homme':'Homme', 'Femme':'Femme', 'Homme et Femme':'Homme et Femme' }",
        type: 'select',
        submit: 'Modifier',
        cancel: 'Annuler',
        width: '200px',
    });

});
