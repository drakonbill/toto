$(function(){
    
    $("#ajouterContact").live('click',function(){
		 $.ajax({
        type: 'POST',
        url: 'js/Ajax-PHP/contact/ajouterContact.php',
        data: "iddumembre="+iddumembre+"&idmoi="+idmoi+"&action=1",
        success:
        function(result) {
            if(result=="1")
			 $("#contacts").html("En attente de confirmation");
			 alert(result);
        }
    });
    });
	
	 $("#retirerContact").live('click',function(){
		 $.ajax({
        type: 'POST',
        url: 'js/Ajax-PHP/contact/ajouterContact.php',
        data: "iddumembre="+iddumembre+"&idmoi="+idmoi+"&action=2",
        success:
        function(result) {
			if(result=="2")
			 $("#contacts").html("<a href='#ajouterContact' id='ajouterContact'>Ajouter ce contact</a>");
            alert(result);
        }
    });
    });
	
	 $("#refuserContact").live('click',function(){
		 $.ajax({
        type: 'POST',
        url: 'js/Ajax-PHP/contact/ajouterContact.php',
        data: "iddumembre="+iddumembre+"&idmoi="+idmoi+"&action=4",
        success:
        function(result) {
            if(result=="4")
			 $("#contacts").html("<a href='#ajouterContact' id='ajouterContact'>Ajouter ce contact</a>");
        }
    });
    });
	
});