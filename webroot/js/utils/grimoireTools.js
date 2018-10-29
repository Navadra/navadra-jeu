function showPossibleUpgrades(element){
	$("#upgradeSpells div").css('visibility', 'hidden');
	$("#costUpgrades span").css('visibility', 'hidden');
	$("#sorts_" + element + " .icones_sorts_grimoire").each(function(index){
		if($(this).attr("src").match(/_nb.png/) == null){
			var numTemp = parseInt($(this).parent('div').attr("id"));
			var lvlTemp = parseInt($("#niveau_" + numTemp).html());
			var nameTemp = $("#nom_" + numTemp).html();
			var costTemp = parseInt($("#cout_" + numTemp).html());
			var elementTemp = $("#element_" + numTemp).html();
			var colorTemp = couleur_sort_lettres(elementTemp);
			$(".numUpgrade:eq("+index+")").html(numTemp);
			$(".lvlUpgrade:eq("+index+")").html(lvlTemp);
			$(".nameUpgrade:eq("+index+")").html(nameTemp);
			$("#upgradeSpells div:eq("+index+")").unbind("mouseover").on("mouseover", function(){
				displayDescription($(this), $(this).children(".numUpgrade").html(), $(this).children(".lvlUpgrade").html(), $(this).children(".nameUpgrade").html());
			})
			//$("#upgradeSpells img:eq("+index+")").attr('src', $(this).attr("src")).load(function(){
			//Problem with safari on .load();
				$("#upgradeSpells img:eq("+index+")").attr('src', $(this).attr("src"));
				$("#upgradeSpells div:eq("+index+")").css('visibility', 'visible');
				if($("#fleche_tuto").length && nb_sorts == 0)	{
					if(etape == 1){
						etape++;
						changer_txt_bulle(msg_tuteur());
						block_instructions = false;
					}
				}
			//});
			$("#costUpgrades span:eq("+index+")").removeClass("rouge bleu vert jaune").addClass(colorTemp).html(costTemp+'<img class="mg4 img_15" src="/webroot/img/icones/pyrs_'+ elementTemp + '.png"/>').css('visibility', 'visible');
		}
	});
}


function displayDescription(object, num, lvl, name){
	var descriptionTemp = get_theoretical_spell_description(num, lvl, name );
	$("#spellDescription").html(descriptionTemp);
	var xPos = object.offset().left + 0.5*object.width() - 0.5*$("#spellDescription").width();
	var yPos = object.offset().top + object.height() + 10;
	$("#spellDescription").css("top", yPos).css("left", xPos).show();
}


function couleur_sort(elmt){
	switch(elmt){
		case "feu":
			var couleur = rouge;
			break;
		case "eau":
			var couleur = bleu;
			break;
		case "vent":
			var couleur = jaune;
			break;
		case "terre":
			var couleur = vert;
			break;
	}
	return couleur;
}

function couleur_sort_lettres(elmt){
	switch(elmt){
		case "feu":
			var couleur = "rouge";
			break;
		case "eau":
			var couleur = "bleu";
			break;
		case "vent":
			var couleur = "jaune";
			break;
		case "terre":
			var couleur = "vert";
			break;
	}
	return couleur;
}
