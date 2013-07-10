
var resizeimg;
var x1, x2, y1, y2, w, h;
var image_width, image_height;

function loadInputValue() {
	var inputvalue = new Array();

	function loadInputValueBlur(obj, i) {
		$(obj).live('blur', function() {		
			if($(obj).val() == "" || $(obj).val() == inputvalue[i]) {
				$(obj).val(inputvalue[i]);
			}
		});
	}

	function loadInputValueFocus(obj, i) {
		$(obj).live('focus', function() {		
			if($(obj).val() == inputvalue[i]) {
				$(obj).val("");
			}
		});
	}
	
	$("input.contentinput").each(function() {
		inputvalue.push($(this).val());
	});

	var i=0;
	$("input.contentinput").each(function() {
		loadInputValueBlur(this, i);
		loadInputValueFocus(this, i);
		i++;
	});
}

function formatItem(row) {
	return "<span class='autocomplete_title'>"+row[1]+"</span>"+(row[2]!=""?"<img src='"+row[2]+"' alt='"+row[0]+"' />":"")+row[0];
}

function selectItem(row) {
	$("input#categorievalue").val(row.getElementsByTagName("span")[0].innerText);
}

function preview(img, selection) {  
    var scaleX = 75 / selection.width;  
    var scaleY = 75 / selection.height;   

    $("div.imgResize > img").css({  
        width: Math.round(scaleX * $('img#photo').width()) + "px",  
        height: Math.round(scaleY * $('img#photo').height()) + "px",  
        marginLeft: "-" + Math.round(scaleX * selection.x1) + "px",  
        marginTop: "-" + Math.round(scaleY * selection.y1) + "px"  
    });  

    x1=selection.x1;  
    y1=selection.y1;  
    x2=selection.x2;  
    y2=selection.y2;  
    w=selection.width;  
    h=selection.height;  
}

function delete_passion(idpassion) {
	$.ajax({
			type: 'GET',
			url: '/ajax/deletePassion',
			data: "idpassion="+idpassion,
			success:
				function(result) {
					result = result.split(";");
					if(result[0] == "1") {
						if($("> li", $("li#passion"+result[1]).parent()).length == 1) {
							$("li#passion"+result[1]).parent().prev("h3").remove();
							$("li#passion"+result[1]).parent().remove();
                                                        if($("#content_passion").html() == "") $("#content_passion").html("Aucune passion liée pour le moment");
						}
						else {
							$("li#passion"+result[1]).remove();
						}
					}
				}
	});
}

$(document).ready(function(){
	
	loadInputValue();

	
	$("input#passionvalue").autocomplete(
	  "/ajax/choicePassion",
	  {
			minChars:2,
			cacheLength:10,
			onItemSelect:selectItem,
			onFindValue:findValue,
			formatItem:formatItem,
			autoFill:false,
			matchSubset: 0
		}
	);
	
	$("a#contentvalid").live('click', function() {
		if($("input#passionvalue").val() != "" && $("input#passionvalue").val() != "Saisissez le nom d'une de vos passions" && $("input#passionvalue").val().length > 2) {
			$("#erreurpassion").fadeOut();
			$.ajax({
			type: 'POST',
			url: '/ajax/registerPassionProfil',
			data: "passion="+$("input#passionvalue").val()+"&categorie="+$("input#categorievalue").val(),
			success:
				function(result) {alert(result);
                                        result = JSON.parse(result);
					if(result[0] == "1") {
						popup = $("div.popup-othermini");
						popup.html("<div class='contentdetail'><h2>Ajout d'une passion</h2><span class='information'>La passion n'est pour l'instant pas présente, vous pouvez utiliser le formulaire ci-dessous pour l'ajouter.</span></div>");
						popup.append("<div class='contentdetail noverflow'><h2>Passion à ajouter : "+$("input#passionvalue").val()+"</h2><label class='contentinput'>Catégorie * : </label><ul id='listecategorie'></ul><label class='contentinput'>Image de cette passion : </label><input type='file' name='filepassion' id='filepassion' /><br/><div class='loading'></div><a id='addpassion' class='contentvalid contentvalidright' href='javascript:void();'>Ajouter cette passion</a><hr class='clear'/></div><div class='error contentdetail'></div>");
						
						$.ajax({
						type: 'GET',
						url: '/ajax/categoryPassion',
						data: "mode=categorie",
						success:
							function(result) {
								$("ul#listecategorie").html(result);
								$("ul#listecategorie").imgDropDown({title:"-- Sélectionner une catégorie --",id:"listecategorie"});
							}
						});
						
						$("#filepassion").change(function() {
							
							if(typeof(resizeimg) != 'undefined')
								resizeimg.cancelSelection();
								
							$("div.loading").html("<img src='/css/images/loader-autocomplete.gif'/>");
							$(this).upload('/ajax/registerImagePassion', function(res) {
								if(res.substring(0,2) == "1;")
								{
									image = res.split(";");
									$("div.loading").empty();
									$("div.error").fadeOut("slow");
									$("div.loading").html("<div class='contentimgnoResize'><label class='contentinput'>Image d'origine :</label><img id='photo' src='"+image[1]+"' alt='icone' /></div>");
									$("div.loading").append("<div class='contentimgResize'><label class='contentinput'>Aperçu :</label><div class='imgResize'><img src='' /></div></div><hr class='clear' />");
									$("div.imgResize > img").attr("src",image[1]);
									
									var img = $("img#photo")[0]; // Get my img elem
									
									$("<img/>") // Make in memory copy of image to avoid css issues
									.attr("src", $(img).attr("src"))
									.load(function() {
										image_width = this.width;   // Note: $(this).width() will not
										image_height = this.height; // work for in memory images.

										resizeimg = $('img#photo').imgAreaSelect({
											onSelectEnd: preview,
											onInit: preview,
											aspectRatio: '1:1',
											zIndex: 1001,
											instance: true,
											handles: true,
											persistent: true,
											x1: 0, y1: 0, x2: 50, y2: 50,
											minWidth: 25,
											minHeight: 25,
											parent: "div.popup"
										});
									});	
								}
								else
								{
									$("div.error").fadeIn().html(res);
									$("div.loading").empty();
								}
							}, 'php');
						});
						
						$("a#addpassion").click(function() {  
							if($("div#listecategorie").val() == "" || parseInt($("div#listecategorie").val()) != $("div#listecategorie").val()) {
								$("div.error").append("Veuillez sélectionner une catégorie");
								$("div.error").fadeIn();
							}
							else {
								$("div.error").fadeOut();
								$("div.error").empty();

								if(typeof($('img#photo').attr('src')) != "undefined") {
									if(typeof(x1) != "undefined" && typeof(y1) != "undefined" && typeof(x2) != "undefined" && typeof(y2) != "undefined" && typeof(w) != "undefined" && typeof(h) != "undefined" && typeof($('img#photo')) != "undefined" && typeof($('img#photo')) != "undefined" && parseInt(x1) == x1 && parseInt(x2) == x2 && parseInt(y1) == y1 && parseInt(y2) == y2 && parseInt(w) == w && parseInt(h) == h && parseInt($('img#photo').height()) == $('img#photo').height() && parseInt($('img#photo').width()) == $('img#photo').width()) {
										$.ajax({
											type: 'POST',
											url: '/ajax/registerPassionProfil',
											data: "mode=enregistrericone&passion="+$("input#passionvalue").val()+"&categorie="+$("div#listecategorie").val()+"&x1="+x1+"&y1="+y1+"&x2="+x2+"&y2="+y2+"&w="+w+"&h="+h+"&image="+image[2]+"&imageheight="+$('img#photo').height()+"&imagewidth="+$('img#photo').width(),
											success:
												function(result) {
                                                                                                        result = JSON.parse(result);
													if(result[0] == "ok") {
														disable(popup);
														if($("#content_passion").text() == "Aucune passion liée pour le moment") $("#content_passion").html("");
                                                                                                                result = result[1].split(";");
                                                                                                                if($("h3."+result[0]).length == 0)
                                                                                                                    $("#content_passion").prepend("<h3 class='title_underline'>"+result[0]+"</h3><ul class='listepassions cat"+result[4]+"'><li id='passion"+result[2]+"'><img src='"+result[3]+"' alt='"+result[1]+"'/>"+result[1]+"<br/><a href='javascript:void()' onclick='delete_passion("+result[2]+")'>Supprimer</a></li></ul>");
                                                                                                                else
                                                                                                                    $("ul.cat"+result[4]).prepend("<li id='passion"+result[2]+"'><img src='"+result[3]+"' alt='"+result[1]+"'/>"+result[1]+"<br/><a href='javascript:void()' onclick='delete_passion("+result[2]+")'>Supprimer</a></li>");
													}
													else {
														$("div.error").append(result[1]);
														$("div.error").fadeIn();
													}
												}
										});
									}
									else {
										$("div.error").append("Veuillez recadrer la partie de l'image que vous souhaitez");
										$("div.error").fadeIn();
									}
								}
								else {

									$.ajax({
										type: 'POST',
										url: '/ajax/registerPassionProfil',
										data: "mode=enregistrergout&passion="+$("input#passionvalue").val()+"&categorie="+$("div#listecategorie").val(),
										success:
											function(result) {
                                                                                                result = JSON.parse(result);
												if(result[0] == "ok") {
													disable(popup);
													if($("#content_passion").text() == "Aucune passion liée pour le moment") $("#content_passion").html("");
                                                                                                        result = result[1].split(";");
                                                                                                        if($("h3."+result[0]).length == 0)
                                                                                                            $("#content_passion").prepend("<h3 class='title_underline'>"+result[0]+"</h3><ul class='listepassions cat"+result[4]+"'><li id='passion"+result[2]+"'><img src='"+result[3]+"' alt='"+result[1]+"'/>"+result[1]+"<br/><a href='javascript:void()' onclick='delete_passion("+result[2]+")'>Supprimer</a></li></ul>");
                                                                                                        else
                                                                                                            $("ul.cat"+result[4]).prepend("<li id='passion"+result[2]+"'><img src='"+result[3]+"' alt='"+result[1]+"'/>"+result[1]+"<br/><a href='javascript:void()' onclick='delete_passion("+result[2]+")'>Supprimer</a></li>");
												}
												else {
													$("div.error").append(result[1]);
													$("div.error").fadeIn();
												}
											}
									});
								}
							}
						});  
						
						$("body").prepend("<div class='background-mini'></div>");
						$('.background-mini').css({
							"opacity" : "0.6"
						}).show();
						centrer(popup);
						$(popup).show();
						
						$('.background-mini').live('click', function() {
							$(popup).hide();
							$(".background-mini").remove();
						});
						
						$(window).resize(function(){ 
							centrer($(popup));
						}); 
					}
					else if(result[0] == "ok") {
						if($("#content_passion").html() == "Aucune passion liée pour le moment") $("#content_passion").html("");
                                                result = result[1].split(";");
                                                if($("h3."+result[0]).length == 0)
                                                    $("#content_passion").prepend("<h3 class='title_underline'>"+result[0]+"</h3><ul class='listepassions cat"+result[4]+"'><li id='passion"+result[2]+"'><img src='"+result[3]+"' alt='"+result[1]+"'/>"+result[1]+"<br/><a href='javascript:void()' onclick='delete_passion("+result[2]+")'>Supprimer</a></li></ul>");
                                                else
                                                    $("ul.cat"+result[4]).prepend("<li id='passion"+result[2]+"'><img src='"+result[3]+"' alt='"+result[1]+"'/>"+result[1]+"<br/><a href='javascript:void()' onclick='delete_passion("+result[2]+")'>Supprimer</a></li>");

					}
					else {
						$("div.error").append(result[1]);
						$("div.error").fadeIn();	
					}
				}
			});
		}
		else {
			msgerreur="Veuillez saisir une passion";
		}
	});
	
}); 