$(function(){
    
    $("#ajouterContact").live('click',function(){
        $.ajax({
            type: 'POST',
            url: '/ajax/addContactProfil',
            data: "iddumembre="+iddumembre+"&action=1",
            success:
            function(result) {
                if(result=="1")
                    $("#addcontact").html("<a href='javascript:void()' id='cancelContact' class='details-btn'>Annuler ma demande</a>");
                else if(result=="2")
                    $("#addcontact").html("<a href='javascript:void()' id='retirerContact' class='details-btn'>Retirer de mes contacts</a>");
            }
        });
    });
	
    $("#retirerContact").live('click',function(){
        $.ajax({
            type: 'POST',
            url: '/ajax/addContactProfil',
            data: "iddumembre="+iddumembre+"&action=2",
            success:
            function(result) {
                if(result=="2")
                    $("#addcontact").html("<a href='javascript:void()' id='ajouterContact' class='details-btn'>Ajouter comme ami</a>");

            }
        });
    });
    
    $("#cancelContact").live('click',function(){
        $.ajax({
            type: 'POST',
            url: '/ajax/addContactProfil',
            data: "iddumembre="+iddumembre+"&action=3",
            success:
            function(result) {
                if(result=="3")
                    $("#addcontact").html("<a href='javascript:void()' id='ajouterContact' class='details-btn'>Ajouter comme ami</a>");

            }
        });
    });
	
    $("#refuserContact").live('click',function(){
        $.ajax({
            type: 'POST',
            url: '/ajax/addContactProfil',
            data: "iddumembre="+iddumembre+"&action=4",
            success:
            function(result) {
                if(result=="4")
                    $("#addcontact").html("<a href='javascript:void()' id='ajouterContact' class='details-btn'>Ajouter comme ami</a>");
            }
        });
    });
	
});