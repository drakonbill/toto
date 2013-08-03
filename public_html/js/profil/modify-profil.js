$( document ).ready(function() {
    
    var errors = [], ok = [], i;
     
    function firstnameajax(value, settings){
        console.log(this);
        console.log(value);
        console.log(settings);
        
        $.ajax({
            type: 'POST',
            url: '/ajax/modifyFirstName',
            data: "first-name="+value,
            success:
            function(result) {
            // alert(result); 
            }
        });
    }
    
    
    
    function pseudoajax(value, settings){
        
        console.log(this);
        console.log(value);
        console.log(settings);
        
        $.ajax({
            type: 'POST',
            url: '/ajax/modifyPseudo',
            data: "pseudo="+value,
            success:
            function(result) {
            //  alert(result); 
            }
        });
        
    }
    
    function situationajax(value, settings){
        console.log(this);
        console.log(value);
        console.log(settings);
        
        $.ajax({
            type: 'POST',
            url: '/ajax/modifySituation',
            data: "situation="+value,
            success:
            function(result) {
            //  alert(result); 
            }
        });
    }
    
    function preferanceajax(value, settings){
        console.log(this);
        console.log(value);
        console.log(settings);
        
        $.ajax({
            type: 'POST',
            url: '/ajax/modifyPreferance',
            data: "preferance="+value,
            success:
            function(result) {
            //  alert(result); 
            }
        });
    }
    
    function sexeajax(value, settings){
        console.log(this);
        console.log(value);
        console.log(settings);
        
        $.ajax({
            type: 'POST',
            url: '/ajax/modifySexe',
            data: "sexe="+value,
            success:
            function(result) {
            //  alert(result); 
            }
        });
    }
    
    $('#first-name').editable(function(value, settings) { 
        firstnameajax(value, settings);
        return(value);
    }, { 
        type    : 'text',
        submit  : 'Modifier',
        cancel  : 'Annuler',
        width   : '200px',
    });
    
    if(value.length < 3){
        errors.push(['.general-error', 'Pseudo trop court.']);
    }
    else {
        $('#pseudo').editable(function(value, settings) { 
            pseudoajax(value, settings);
            return(value);
        }, { 
            type    : 'text',
            submit  : 'Modifier',
            cancel  : 'Annuler',
            width   : '200px',
        }); 
    }
        
    $('#situation1').editable(function(value, settings) { 
        situationajax(value, settings);
        return(value);
    }, { 
        data    : "{'Non communiquée':'Non communiquée', 'Célibataire':'Célibataire','Couple':'Couple','Marié':'Marié'}",
        type    : 'select',
        submit  : 'Modifier',
        cancel  : 'Annuler',
        width   : '200px',
    });
    
    $('#situation2').editable(function(value, settings) { 
        situationajax(value, settings);
        return(value);
    }, { 
        data    : "{'Non communiquée':'Non communiquée', 'Célibataire':'Célibataire','Couple':'Couple','Mariée':'Mariée'}",
        type    : 'select',
        submit  : 'Modifier',
        cancel  : 'Annuler',
        width   : '200px',
    });
    
    $('#preferance').editable(function(value, settings) { 
        preferanceajax(value, settings);
        return(value);
    }, { 
        data    : "{'Non communiqué':'Non communiqué', 'Homme':'Homme', 'Femme':'Femme', 'Homme et Femme':'Homme et Femme' }",
        type    : 'select',
        submit  : 'Modifier',
        cancel  : 'Annuler',
        width   : '200px',
    });
    
    $('#sexe').editable(function(value, settings) { 
        sexeajax(value, settings);
        return(value);
    }, { 
        data    : "{'Homme':'Homme', 'Femme':'Femme'}",
        type    : 'select',
        submit  : 'Modifier',
        cancel  : 'Annuler',
        width   : '200px',
    });
    
});
