$(document).ready(function() {
    
// AJAX FUNCTIONS

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
                        return result;
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
    
        function birthajax(value, settings) {
        console.log(this);
        console.log(value);
        console.log(settings);

        $.ajax({
            type: 'POST',
            url: '/ajax/modifyBirth',
            data: "birth=" + value,
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
    
        function situationproajax(value, settings) {
        console.log(this);
        console.log(value);
        console.log(settings);

        $.ajax({
            type: 'POST',
            url: '/ajax/modifySituationpro',
            data: "situationpro=" + value,
            success:
                    function(result) {
                        //  alert(result); 
                    }
        });
    }
    
// JEDITABLE PART

// FIRST-NAME OF THE MEMBER

    firstname = $("#first-name").text();

    $('#first-name').editable(function(value, settings) {

        $(".form-validation-firstname").html("");

        if (value.length < 3) {
            $(".form-validation-firstname").html("Prénom trop court. <br/>");
            return(firstname);
        }
        else if (value.length > 45) {
            $(".form-validation-firstname").html("Prénom trop long. <br/>");
            return(firstname);
        }
        else {

            firstnameajax(value, settings);

            firstname = value;
            return(firstname);
        }
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

// BIRTH OF THE MEMBER

    birth = $("#birth").text();

    $('#birth').editable(function(value, settings) {

        $(".form-validation-birth").html("");
        
        var date = value.split("/"); 
        var day = date[0];
        var month = date[1];
        var year = date[2];
        
        if (day === "00") {
            $(".form-validation-birth").html("Le jour ne doit pas être nul. <br/>");
            return(birth);
        }
        else if (month === "00") {
            $(".form-validation-birth").html("Le mois ne doit pas être nul. <br/>");
            return(birth);
        }
        else if (year === "0000") {
            $(".form-validation-birth").html("L'année ne doit pas être nul. <br/>");
            return(birth);
        }
        else if (day >= "31") {
            $(".form-validation-birth").html("Le jour doit exister. <br/>");
            return(birth);
        }
        else if (month >= "12") {
            $(".form-validation-birth").html("Ce mois n'existe pas. Vous venez d'une autre planète ? <br/>");
            return(birth);
        }
        else if (year >= "2000") {
            $(".form-validation-birth").html("Désolé mais là, ça fait un peu jeune. <br/>");
            return(birth);
        }
        else if (year <= "1900") {
            $(".form-validation-birth").html("Désolé mais là, ... Bref. REFUS ! <br/>");
            return(birth);
        }
        else {

            birthajax(value, settings);

            birth = value;
            return(birth);
        }
    }, {
        type: 'masked',
        mask: "99/99/9999",
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
        data: "{'Non communiquée':'Non communiquée', 'Collège':'Collège', 'Lycée':'Lycée', 'Bac+1':'Bac+1', 'Bac+2':'Bac+2', 'Bac+3':'Bac+3', 'Bac+4':'Bac+4', 'Bac+5':'Bac+5', 'Bac+6':'Bac+6', 'Employé':'Employé', 'Freelance':'Freelance', 'Sans emploi':'Sans emploi'}",
        type: 'select',
        submit: 'Modifier',
        cancel: 'Annuler',
        width: '200px',
    });

});
