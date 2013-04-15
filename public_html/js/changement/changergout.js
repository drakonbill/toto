
function FirstMaj(texte) {
var t = new Array();
	for(j=0 ; j < texte.length ;j++) {
		if(j == 0) t[j] = texte.substr(j,1).toUpperCase();
		else t[j] = texte.substr(j,1).toLowerCase();
	}
	return t.join('');
}

function RechercheTableau(Tableau,valeur)
{
	for(var i=0; i < Tableau.length; i++)
	{
		if(Tableau[i] == valeur)
			return true;
	}
	return false;
}

$(function(){$("input:checkbox, input:radio, input:file, input:text, input:submit").uniform();
    var tabContainers = $('div.tabs > div');
    
    $('div.tabs ul.tabNavigation a').click(function () {
        tabContainers.hide().filter(this.hash).fadeIn();
        
        $('div.tabs ul.tabNavigation a').removeClass('selected');
		$('div.tabs ul.tabNavigation li').removeClass('selected');
        $(this).addClass('selected');
        
        return false;
    }).filter(':first').click();
	
    $('label[for=type]').hide();
    $('select[name="type"]').hide();
	$('br#type').hide();
    $("#type").next(".error-input").hide();
	
    $('#passion').hide();
	$('br#passion').hide();
    $("#passion").next(".error-input").hide();
	$("#listepassion").next(".error-input").hide();
	
	var cpt=0;
	var cpt2=0;
	//Variable contiendra le texte de la sélection
	var categorieselection="";
	$("#categorie").change(function(){
		if($("#categorie").val()!="")
		{	
			$.ajax({
			type: 'POST',
			url: 'js/Ajax-PHP/cryptage/cryptage.php',
			data: "crpt="+crpt,
			success:
			function(result) {
				 //while(){}
				 $("#type").empty();
				 $("#type").append("<option value=\"\">-- Sélectionner --</option>");
				 var jsObject = JSON.parse(result);
				 cpt = 0;
				 categorieselection=$("#categorie option:selected").text();
				 for(var i in jsObject['type'+$("#categorie").val()])
				 {
					if(jsObject['type'+$("#categorie").val()][i]!="")
					{
						$("#type").append("<option value="+jsObject['type'+$("#categorie").val()][i]+">"+FirstMaj(jsObject['type'+$("#categorie").val()][i])+"</option>");
						cpt++;
					}
				 }
				 if(cpt!=0)
					$("#type").next(".error-input").text("");
					
				$('label[for=type]').fadeIn();
				$('select[name="type"]').fadeIn();
				$('br#type').fadeIn();
				}
			});
		}
		else
		{
			$('label[for=type]').fadeOut();
			$('select[name="type"]').fadeOut();
			$("#type").next(".error-input").fadeOut(function(){
				$('br#type').hide();
			});
		}
			$('label[for=passion]').fadeOut();
			$('#passion').fadeOut();
			$("#passion").next(".error-input").fadeOut(function(){
				$('br#passion').hide();
				$("#passion").empty();
			});
			$("#listepassion").next(".error-input").fadeOut();
	});
	
	$("#type").change(function(){
			if($("#type").val()!="")
			{
				if(cpt!=0)
				{
					$("#passion").html("<label for='passion'></label><input type='text' id='"+$("#type").val()+$("#categorie").val()+"' name='passion' value=''/><a href='#' id='ajouter'>Ajouter</a>");
					$('label[for=passion]').text(FirstMaj($("#type").val())+" : ");
					$('label[for=passion]').fadeIn();
					$('#passion').fadeIn();
					$("#"+$("#type").val()+$("#categorie").val()+"").autocomplete(
					  "js/Ajax-PHP/changement/changergout.php",
					  {
							extraParams:{type:$("#type").val(),categorie:$("#categorie").val()},
							selectFirst:true,
							lineSeparator:"|",
							minChars:2,
							cacheLength:10,
							matchSubset:5,
							onItemSelect:selectItem,
							onFindValue:findValue,
							formatItem:formatItem,
							autoFill:false
						}
					);
					$('br#passion').fadeIn();
					$("#passion").next(".error-input").text("");
				}
			}
			else
			{
				$('label[for=passion]').fadeOut();
				$('input[name="passion"]').fadeOut();
				$("#passion").next(".error-input").fadeOut(function(){
					$('br#passion').hide();
					$("#passion").html("");
					$("label[for=passion]").empty();
				});
				$("#listepassion").next(".error-input").fadeOut();
			}
	});
	//Variable contiendra le texte de l'ajout
	var ajout= new Array();
	//Variable contiendra le texte de l'ajout visuel
	var ajoutvisuel="";
	//Tableau contiendra les passions
	var ajoutPassion = new Array();
	$("#listepassion").hide();
	$("#ajouter").live('click',function(){
		valid=true;
		if($("#categorie").val() == ""){
            $("#categorie").next(".error-input").fadeIn().text("- Veuillez sélectionner une catégorie.");
            valid = false;
        }
        else
        {
            $("#categorie").next(".error-input").fadeOut();
        }
		
		if($("#type").val() == ""){
            $("#type").next(".error-input").fadeIn().text("- Veuillez sélectionner un type.");
            valid = false;
        }
        else
        {
            $("#type").next(".error-input").fadeOut();
			
			if($("input[name='passion']").val() == ""){
				$("#passion").next(".error-input").fadeIn().text("- Veuillez saisir une passion.");
				valid = false;
			}
			else
			{
				$("#passion").next(".error-input").fadeOut();
			}
        }
		
		if(valid == true)
		{
			if(!RechercheTableau(ajoutPassion,$("#categorie").val()+$("#type").val()+$("input[name='passion']").val()))
			{
				ajout.push($("#categorie").val()+"|"+$("#type").val()+"|"+$("input[name='passion']").val());
				ajoutvisuel+=categorieselection+" ("+$("#type").val()+") : "+$("input[name='passion']").val()+"<br/>";
				ajoutPassion.push($("#categorie").val()+$("#type").val()+$("input[name='passion']").val());
				$("#listepassion").html("Passions ajoutées : <br/>"+ajoutvisuel);
				$("#listepassion").fadeIn();
				$("#listepassion").next(".error-input").fadeOut();
			}
			else
			{
				$("#listepassion").next(".error-input").html("Cette passion a déjà été ajoutée");
				$("#listepassion").next(".error-input").fadeIn();
			}
		}
		
	});
	
    $("#enregistrer").live("click",function(){

        valid = true;

        // Pour le pseudo
        if(ajout.length == 0){
            $("#erreurpassion").html("- Ajoutez d'abord les passions pour les enregistrer").fadeIn();
            valid = false;
        }
		else
		{
			$("#erreurpassion").fadeOut();
			$.ajax({
			type: 'POST',
			url: 'js/Ajax-PHP/changement/enregistrergout.php',
			data: "ajout="+ajout,
			success:
			function(result) {
					alert(result);
					if(result == 1)
						$("#listepassion").html("Ces passions ont déjà été ajoutées").fadeIn();
				}
			});
		}

    });
});