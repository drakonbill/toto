/*
jQuery drop down plugin
Copyright (C) 2009 Schwartz Pierre

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
*/

(function($) {
	//Définition du plugin
	$.fn.imgDropDown = function(options) {	
		
		// définition des paramètres par défaut
		var defaults = {
		    title: "",
		    callback: null,
			id: null
		};	
		// mélange des paramètres fournis et par défaut
		var opts = $.extend(defaults, options);		
				
		// création d'une liste
		function createList(f){
			// créer la première zone, affichant l'option sélectionnée
			var cell = $("<div class='dropdownCell' id='" + opts.id + "'>" + opts.title + "</div>");
			
			// créer la seconde zone, affichant toutes les options
			var dropdown = $("<div class='dropdownPanel'></div>");				
						
			$(this).find("li").each(function(){
				dropdown.append($("<div class='dropdownOpt'></div")
					.click(onSelect)
					.attr("value", $(this).attr("value"))
					.append($(this).html())		
					.hover(function(){$(this).addClass("dropdownOptSelected");},
						   function(){$(this).removeClass("dropdownOptSelected");})
				);
			});

			// on masque la zone déroulante
			dropdown.hide();
			$.data(cell, "visible", false);
			
			// on remplace la balise ul par notre liste personnalisée
			$(this).after(dropdown);
			$(this).after(cell);
			$(this).remove();
			
			// on positionne l'évènement de déroulage de la liste
			var hasOn = false;
			cell.click(function(){		
				// si la liste est déroulée
				if ($.data(cell, "visible")){
					dropdown.slideUp("fast");
					$.data(cell, "visible", false);
				}else{
					dropdown.slideDown("fast");
					$.data(cell, "visible", true);
				}
			});
			
			$("div.dropdownPanel, div#" + opts.id + "").live('mouseover', function() {
				hasOn = true;
			});
			
			$("div.dropdownPanel, div#" + opts.id + "").live('mouseout', function() {
				hasOn = false;
			});
			
			$("html").live('click', function() {
				if(!hasOn) {
					if ($.data(cell, "visible")){
						dropdown.slideUp("fast");
						$.data(cell, "visible", false);
					}
				}
			});
			
			// fonction appelée à chaque sélection d'un élément
			function onSelect(){			
				cell.html($(this).html());
				cell.attr("value", $(this).attr("value"));
				dropdown.slideUp("fast");
				
				$.data(cell, "visible", false);
				
				// appel d'une fonction personnalisée
				if (opts.callback)
					opts.callback($(this));
			}				
		}
			
		// création d'une liste déroulante personnalisée pour tous les éléments de l'objet jQuery
		$(this).each(createList);	

		// interface fluide
		return $(this);
	};
})(jQuery);
