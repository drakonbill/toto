$( document ).ready(function() {

    // For the first name of the member
    $('#first-name').editable(function(value, settings) { 
       
        /* console.log(this);
        console.log(value);
        console.log(settings);
        return(value); */
        
        $.ajax({
            type: 'POST',
            url: '/ajax/modifyFirstName',
            data: "first-name="+value,
            success:
            function(result) {
             //  alert(result); 
            }
        });
    }, { 
        type    : 'text',
        submit  : 'Modifier',
        cancel  : 'Annuler',
        width   : '200px',
    });
    
});