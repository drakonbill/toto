function accepterContact(iddumembre)
{
	 $.ajax({
        type: 'POST',
        url: 'js/Ajax-PHP/contact/contact.php',
        data: "iddumembre="+iddumembre+"&idmoi="+idmoi+"&action=1",
        success:
        function(result) {
            if(result=="1")
			 $("#contacts").html("En attente de confirmation");
			 alert(result);
			 location.reload();
        }
    });
}

function refuserContact(iddumembre)
{
	 $.ajax({
        type: 'POST',
        url: 'js/Ajax-PHP/contact/contact.php',
        data: "iddumembre="+iddumembre+"&idmoi="+idmoi+"&action=2",
        success:
        function(result) {
            if(result=="2")
			 $("#contacts").html("En attente de confirmation");
			 alert(result);
			 location.reload();
        }
    });
}

function retirerContact(iddumembre)
{
	 $.ajax({
        type: 'POST',
        url: 'js/Ajax-PHP/contact/.php',
        data: "iddumembre="+iddumembre+"&idmoi="+idmoi+"&action=3",
        success:
        function(result) {
            if(result=="3")
			 $("#contacts").html("En attente de confirmation");
			 alert(result);
			 location.reload();
        }
    });
}

function annulerContact(iddumembre)
{
	 $.ajax({
        type: 'POST',
        url: 'js/Ajax-PHP/contact/.php',
        data: "iddumembre="+iddumembre+"&idmoi="+idmoi+"&action=4",
        success:
        function(result) {
            if(result=="4")
			 $("#contacts").html("En attente de confirmation");
			 alert(result);
			 location.reload();
        }
    });
}

$(function(){

   $("#repondre").click(function(){

		valid = true;
		
		if($("#message").val() == ""){
			$("textarea#message").html("- Veuillez saisir un message.");
			valid = false;
		}
		else
		{
			$("textarea#message").html("");
		}
		
		if(valid==true)
		{
			$.ajax({
				type: 'POST',
				url: 'js/Ajax-PHP/messagerie/messagerie.php',
				data: 'message='+$('textarea#message').val()+'&sujet='+sujet+'&idrecepteur='+idrecepteur+'&idmoi='+idmoi,
				success:
				function(result) {
					if(result=='1') {
						$("textarea#message").html("- Veuillez saisir un message.");
					}
					location.reload();
				}
			});
		}
	});
	
	$("#delete").click(function(){

		$.ajax({
			type: 'POST',
			url: 'js/Ajax-PHP/messagerie/messagerie.php',
			data: 'idrecepteur='+idrecepteur+'&idmoi='+idmoi+'&action=delete',
			success:
			function(result) {
				if(result=='1') {
				}
				alert(result);
		
			}
		});
	});
	
});