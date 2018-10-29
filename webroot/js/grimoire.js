//On récupère les Pyrs du joueur
var pyrs_feu = parseInt($("input[name=pyrs_feu]").val());
var pyrs_eau = parseInt($("input[name=pyrs_eau]").val());
var pyrs_vent = parseInt($("input[name=pyrs_vent]").val());
var pyrs_terre = parseInt($("input[name=pyrs_terre]").val());
var pseudo = $("input[name=pseudo]").val();
var niveau_joueur = parseInt($("input[name=niveau]").val());
var element_joueur = $("input[name=element]").val();
var element_joueur_brut = element_joueur;
var couleur_elem = couleur_sort(element_joueur);
element_joueur = element_joueur.substr(0,1).toUpperCase() + element_joueur.substr(1,element_joueur.length).toLowerCase();
if(element_joueur=="Eau")
	{element_joueur = "de l'" + element_joueur;}
else if(element_joueur=="Terre")
	{element_joueur = "de la " + element_joueur;}
else
	{element_joueur = "du " + element_joueur;}
var ancien_tuteur = $("input[name=ancien_tuteur]").val();
var nouveau_tuteur = $("input[name=nouveau_tuteur]").val();
var nb_sorts = parseInt($("input[name=nb_sorts]").val());
var nb_sorts_debloques = parseInt($("input[name=nb_sorts_debloques]").val());
var niv_pour_nouveau_sort = parseInt($("input[name=niv_pour_nouveau_sort]").val());
var staysGrimoire = false;

//Gestion des animations
var anim = 0;
var etape = 1; //Variable pour le changement d'élément

//Gestion du descriptif qui s'affiche pour apporter des précisions sur les catégories des sorts
$('<span id="descriptif" class="descriptif_sort p0"></span>').appendTo("body");
$("#descriptif").css("z-index", "1500").hide();

function changer_descriptif_categorie(descr) {
	$("#descriptif").hide();
	$("#descriptif").html(descr);
	$("#descriptif").css("position", "absolute").css("top", "7%").css("width", "30%").css("right", "2%");
	$("#descriptif .l80").css("padding-top", "1%").css("vertical-align", "top");
	afficher($("#descriptif"));
}

$('<span id="descriptif_forces_faiblesses" class="bulle_daide align_centre"></span>').appendTo("body");
$("#descriptif_forces_faiblesses").css("z-index", "2").css("position", "absolute").css("top", "2%").css("left", "15%").css("width", "20%").hide();

function reinitialiser_animations() {
	if(anim != 0) {
		clearTimeout(anim);
		anim = 0;
	}
	$("#displaySpellBought").hide();
}

//Comportement des dialog de confirmation
$("#confirm_apprendre").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Apprendre": function(){
			$(this).dialog("close");
			staysGrimoire = true;
			$("form").submit();
		},
		"Laisse moi réfléchir": function(){
			$(this).dialog("close");
		}
	}
});

$("#confirm_reinitialiser").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Réinitialiser": function(){
			$(this).dialog("close");
			staysGrimoire = true;
			$("form").submit();
			//choix_element();
		},
		"Laisse moi réfléchir": function(){
			$(this).dialog("close");
		}
	}
});

$("#info_reinitialiser").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
        "Ça marche !": function() {
          $( this ).dialog( "close" );
        }
	}
});

//Au chargement de la page masque ce qui est lié à l'apprentissage des sorts
$("#spell_display_area span").hide(); //Descriptif sorts
$("#spell_display_area").show();
$("#validate_learning").hide(); //Bouton valider
$("#error_msg_spells").hide(); //Message informant qu'il n'y a pas assez de Pyrs pour acheter le sort

var element_a_afficher;
var haut_base_elem = $("#elements_grimoire img").height();

$("#elements_grimoire img").each(function(index){
	$(this).tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});
	element_a_afficher = $(this).attr("id");
	var categoryTemp =  element_a_afficher.substr(0,1).toUpperCase() + element_a_afficher.substr(1,element_a_afficher.length).toLowerCase();
	if(element_a_afficher  == "feu"){
		$(this).tooltip( "option", "tooltipClass", "g rouge" );
		categoryTemp = "de " + categoryTemp;
	} else if(element_a_afficher  == "eau"){
		$(this).tooltip( "option", "tooltipClass", "g bleu" );
		categoryTemp = "d'" + categoryTemp;
	} else if(element_a_afficher  == "vent"){
		$(this).tooltip( "option", "tooltipClass", "g jaune" );
		categoryTemp = "de " + categoryTemp;
	} else if(element_a_afficher  == "terre"){
		$(this).tooltip( "option", "tooltipClass", "g vert" );
		categoryTemp = "de " + categoryTemp;
	}
	$(this).tooltip( "option", "position", { at: "center top-20" } );
	if((element_a_afficher == "feu" && playerPyrs_feu > 0) || (element_a_afficher == "eau" && playerPyrs_eau > 0) || (element_a_afficher == "vent" && playerPyrs_vent > 0) || (element_a_afficher == "terre" && playerPyrs_terre > 0) ){
		$(this).tooltip( "option", "content", "Clique pour afficher les sorts " + categoryTemp );
	} else {
		$(this).tooltip( "option", "content", "Tu n'as pas de Pyrs " + categoryTemp );
	}
});

$("#upgradeSpells div").css('visibility', 'hidden').css("cursor", "pointer");
$("#upgradeSpells div").on("mouseout", function(){
	$("#spellDescription").hide();
});
$("#costUpgrades span").css('visibility', 'hidden');
//When click on an element
$("#elements_grimoire img").on("click", function(){
	if($("#displayMsg").is(":visible")){
		$("#displayMsg").hide();
	}
	element_a_afficher = $(this).attr("id");
	if(playerTuto == "fini" || (element_a_afficher == "feu" && playerPyrs_feu > 0) || (element_a_afficher == "eau" && playerPyrs_eau > 0) || (element_a_afficher == "vent" && playerPyrs_vent > 0) || (element_a_afficher == "terre" && playerPyrs_terre > 0) ){
		//On réinitialise les icones éléments et on masque les sorts
		$("#elements_grimoire img").removeAttr("style");
		$("#sorts_feu").hide();
		$("#sorts_eau").hide();
		$("#sorts_vent").hide();
		$("#sorts_terre").hide();

		//On masque le descriptif sorts
		$("#spell_display_area span").hide();
		$("#validate_learning").hide();
		$("#error_msg_spells").hide();
		$("#descriptif").hide();

		//On affiche les sorts de l'élément en question et on le met en forme
		$("#sorts_" + element_a_afficher).show();
		$(this).css("background-color","#FFFFC6").css("height", haut_base_elem - 4).css("border", "2px #e0c399 solid");
		$(this).css("-moz-border-radius", "10px").css("-webkit-border-radius", "10px").css("border-radius", "10px");
		$(this).css("padding", "5px");

		//On affiche les forces/faiblesses de l'élément
		changer_descriptif_force_faiblesse('descriptif_forces_faiblesses', element_a_afficher);
		showPossibleUpgrades(element_a_afficher);
	}
});


var hauteur_base = $(".icones_sorts_grimoire").height();
$("#spellDescription").hide();
$(".icones_sorts_grimoire").on("mouseover", function(){
	var num = parseInt($(this).parent('div').attr("id"));
	var nom = $("#nom_" + num).html();
	var niveau = parseInt($("#niveau_" + num).html());
	if($(this).attr("src").match(/_nb.png/) == null){
		displayDescription($(this), num, niveau-1, nom);
	} else {
		displayDescription($(this), num, niveau, nom);
	}
});

$(".icones_sorts_grimoire").on("mouseout", function(){
	$("#spellDescription").hide();
});

var formSubmitted = false;
//Comportement des icones de sorts lorsqu'on clique dessus
$(".learnableSpells").on("click", function(){
	if(!formSubmitted){
		formSubmitted = true;
		var num = parseInt($(this).parent().children(".numUpgrade").html());
		reinitialiser_animations();
		$("input[name=num_sort]").val(num);
		staysGrimoire = true;
		$("form").submit();
	}
});

//Lorsque l'utilisateur clique sur "Apprendre"
$("#validate_learning").on("click",function(event){
	event.preventDefault();
	//$("#confirm_apprendre").dialog("open");
	staysGrimoire = true;
	$("form").submit();
});


//Lorsque l'utilisateur clique sur "Réinitialiser ses sorts"
$("#reinitialiser_sort").on("click",function(event){
	event.preventDefault();
	$("input[name=num_sort]").val("");
	$("#spell_display_area span").hide(); //Descriptif sorts
	$("#validate_learning").hide(); //Bouton valider
	$("#error_msg_spells").hide(); //Message informant qu'il n'y a pas assez de Pyrs pour acheter le sort

	//Chargement du message de réinitialisation
	var cout_reinitialisation = $("input[name=reinitialiser]").val().split(",");
	var cout_feu = parseInt(cout_reinitialisation[0]);
	var cout_eau = parseInt(cout_reinitialisation[1]);
	var cout_vent = parseInt(cout_reinitialisation[2]);
	var cout_terre = parseInt(cout_reinitialisation[3]);
	if(pyrs_feu>=cout_feu && pyrs_eau>=cout_eau && pyrs_vent>=cout_vent && pyrs_terre>=cout_terre)
	{
		$("#confirm_reinitialiser").html("Veux-tu vraiment oublier tous tes sorts et récupérer toutes les Pyrs que tu as dépensé pour les obtenir ?<br />Cela te coûtera :" + affichage_cout_reinitialisation(cout_feu, cout_eau, cout_vent, cout_terre) + "<br />Ce coût augmentera à chaque fois que tu oublies tous tes sorts.");
		$("#confirm_reinitialiser").dialog("open");
	}
	else
	{
		$("#info_reinitialiser").html("Cela te coûterait" + affichage_cout_reinitialisation(cout_feu, cout_eau, cout_vent, cout_terre) + " pour pouvoir réinitialiser tous tes sorts.<br />Va faire quelques défis pour obtenir les Pyrs manquantes et reviens plus tard !");
		$("#info_reinitialiser").dialog("open");
	}
});

$(".icone_form_gauche").on("click",function(event){
	event.preventDefault();
	$("input[name=num_sort]").val("");
	$("#spell_display_area span").hide(); //Descriptif sorts
	$("#validate_learning").hide(); //Bouton valider
	$("#error_msg_spells").hide(); //Message informant qu'il n'y a pas assez de Pyrs pour acheter le sort

	//Chargement du message de réinitialisation
	var cout_reinitialisation = $("input[name=reinitialiser]").val().split(",");
	var cout_feu = parseInt(cout_reinitialisation[0]);
	var cout_eau = parseInt(cout_reinitialisation[1]);
	var cout_vent = parseInt(cout_reinitialisation[2]);
	var cout_terre = parseInt(cout_reinitialisation[3]);
	if(pyrs_feu>=cout_feu && pyrs_eau>=cout_eau && pyrs_vent>=cout_vent && pyrs_terre>=cout_terre)
	{
		$("#confirm_reinitialiser").html("Veux-tu vraiment oublier tous tes sorts et récupérer toutes les Pyrs que tu as dépensé pour les obtenir ?<br />Cela te coûtera :" + affichage_cout_reinitialisation(cout_feu, cout_eau, cout_vent, cout_terre) + "<br />Ce coût augmentera à chaque fois que tu oublies tous tes sorts.");
		$("#confirm_reinitialiser").dialog("open");
	}
	else
	{
		$("#info_reinitialiser").html("Cela te coûterait" + affichage_cout_reinitialisation(cout_feu, cout_eau, cout_vent, cout_terre) + " pour pouvoir réinitialiser tous tes sorts.<br />Va faire quelques défis pour obtenir les Pyrs manquantes et reviens plus tard !");
		$("#info_reinitialiser").dialog("open");
	}
});


//Affichage du choix des éléments lors d'une réinitialisation
function choix_element()
{
	if(!$(".choix_element_grim").length)
	{
		$('<div class="choix_element_grim"></div>').appendTo("body");
		$('<span class="consigne mb2 p1 g">Quel élément choisis-tu ?</span>').appendTo(".choix_element_grim");
		$('<span><img class="element_principal" src="/webroot/img/elements/feu.png" /></span>').appendTo(".choix_element_grim");
		$('<span><img class="element_principal" src="/webroot/img/elements/eau.png" /></span>').appendTo(".choix_element_grim");
		$('<span><img class="element_principal" src="/webroot/img/elements/vent.png" /></span>').appendTo(".choix_element_grim");
		$('<span><img class="element_principal" src="/webroot/img/elements/terre.png" /></span>').appendTo(".choix_element_grim");
		$('<span id="descr_elem" class="ib mh4 mb4 l90 justif"></span>').appendTo(".choix_element_grim");

		$('<span class="consigne mb2 p1 g">Quel élément secondaire choisis-tu ?</span>').appendTo(".choix_element_grim");
		$('<span><img class="element_secondaire" src="/webroot/img/elements/feu.png" /></span>').appendTo(".choix_element_grim");
		$('<span><img class="element_secondaire" src="/webroot/img/elements/eau.png" /></span>').appendTo(".choix_element_grim");
		$('<span><img class="element_secondaire" src="/webroot/img/elements/vent.png" /></span>').appendTo(".choix_element_grim");
		$('<span><img class="element_secondaire" src="/webroot/img/elements/terre.png" /></span>').appendTo(".choix_element_grim");

		$('<div class="bouton form_droite2 valider_choix_elem"><a class="blanc" href="#">Choisir</a></div>').appendTo(".choix_element_grim");
		$('<a href="#"><img id="valider_icone" class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>').appendTo(".choix_element_grim");
	}
		$(".element_principal:eq(0)").on("click", function(){ //Clic sur le feu
			$('#descr_elem').html('<span class="g"><font color="'+ rouge +'">Le Feu</font></span> : une magie très offensive, tes sorts sont là pour venir à bout de ton adversaire le plus vite possible grâce à leur puissance brutale, quitte à te blesser un peu au passage. L’essentiel est de triompher !<br><br>Fort contre : <span class="g"><font color="'+ vert +'">La Terre</font></span><br>Faible contre : <span class="g"><font color="'+ bleu +'">L\'Eau</font></span>');
		});

		$(".element_principal:eq(1)").on("click", function(){ //Clic sur l'eau
			$('#descr_elem').html('<span class="g"><font color="'+ bleu +'">L\'Eau</font></span> : une magie plutôt défensive. Tes sorts te protégeront efficacement mais contrairement à la Terre, tu n’attendras pas la fin du combat pour commencer à riposter. Et ton adversaire ne le verra pas venir…<br><br>Fort contre : <span class="g"><font color="'+ rouge +'">Le Feu</font></span><br>Faible contre : <span class="g"><font color="'+ jaune +'">Le Vent</font></span>');
		});

		$(".element_principal:eq(2)").on("click", function(){ //Clic sur le vent
			$('#descr_elem').html('<span class="g"><font color="'+ jaune +'">Le Vent</font></span> : une magie plutôt offensive. Tes sorts sont faits pour infliger des dégâts mais, à l’inverse du Feu, ils le font avec précision et élégance. Un adepte de la magie du Vent peut réussir à vaincre rapidement son adversaire sans avoir pris un seul coup.<br><br>Fort contre : <span class="g"><font color="'+ bleu +'">L\'Eau</font></span><br>Faible contre : <span class="g"><font color="'+ vert +'">La Terre</font></span>');
		});

		$(".element_principal:eq(3)").on("click", function(){ //Clic sur la terre
			$('#descr_elem').html('<span class="g"><font color="'+ vert +'">La Terre</font></span> : une magie très défensive. Tes sorts seront là pour te soigner, te protéger et si ton adversaire ne réussit pas à franchir rapidement tes protections, alors tu es sûr'+e+' de l’emporter !<br><br>Fort contre : <span class="g"><font color="'+ jaune +'">Le Vent</font></span><br>Faible contre : <span class="g"><font color="'+ rouge +'">Le Feu</font></span>');
		});

		choix_element_principal();
		choix_element_secondaire();

		$(".valider_choix_elem").on("click", function(){if(element_principal_choisi && element_secondaire_choisi){$("form").submit();}});
		$("#valider_icone").on("click", function(){if(element_principal_choisi && element_secondaire_choisi){$("form").submit();}});
}

var element_principal_choisi = false;
function choix_element_principal()
{
	$(".element_principal").on("click", function(){
		var elmnt = $(this).attr("src").replace(/^.{1,}\/(.{3,5})\.png/, '$1');
		$("input[name=element1]").val(elmnt);
		$(".element_principal").css("height", "50px").css("border", "").css("background-color", "");
		$(this).css("height", "60px").css("border", "2px #e0c399 solid").css("background-color", "#ffe4bd").css("-moz-border-radius", "10px").css("-webkit-border-radius", "10px").css("border-radius", "10px");
		element_principal_choisi = true;
	});
}

var element_secondaire_choisi = false;
function choix_element_secondaire()
{
	$(".element_secondaire").on("click", function(){
		var elmnt = $(this).attr("src").replace(/^.{1,}\/(.{3,5})\.png/, '$1');
		$("input[name=element2]").val(elmnt);
		$(".element_secondaire").css("height", "50px").css("border", "").css("background-color", "");
		$(this).css("height", "60px").css("border", "2px #e0c399 solid").css("background-color", "#ffe4bd").css("-moz-border-radius", "10px").css("-webkit-border-radius", "10px").css("border-radius", "10px");
		element_secondaire_choisi = true;
	});
}


function animation_achat(){
	if($("#displaySpellBought").length){
		$("#displaySpellBought").hide();
		$("#displaySpellBought").show("scale", {percent: 20, easing: "swing"}, 500, function(){
			if(playerVolumeSoundEffects == 1){
				$("#son_nouveau_sort").trigger("play");
			}
			anim = setTimeout(function(){
				anim = 0;
				$("#displaySpellBought").hide("scale", {percent: 20, easing: "swing"}, 500);
			}, 2500);
		});
		$("#"+ $("#spellBoughtElement").html()).click();
	}
}

function animation_chgt_elem()
{
	if($("input[name=chgt_elem]").val() == "oui" && !$("#fleche_tuto").length) //Si cet apprentissage fait changer le joueur d'affinité élémentaire
	{
		var timer = pulser($("#tuteur_cote"));
		pulser($("#bulle_bas"));
		anim = setTimeout(function(){
			pulser($("#tuteur_cote"));
			afficher_etape();
			$('<img id="instr_precedent" alt="" src="/webroot/img/icones/chevron1.png" />').appendTo("#commandes_grimoire");
			$('<img id="instr_suivant" alt="" src="/webroot/img/icones/chevron2.png" />').appendTo("#commandes_grimoire");
			commandes_fonctionnelles();
			anim = 0;
		}, timer);
	}
}

function afficher_etape()
{
	switch(etape)
	{
		case 1:
			afficher_txt_bulle("QUOI ?! En apprenant ce sort, tu viens de faire pencher ton affinité avec les éléments vers la magie <font color ='" + couleur_elem + "'>" + element_joueur + "</font> !");
			break;
		case 2:
			if(ancien_tuteur == "Katillys" || ancien_tuteur == "Namuka")
				{afficher_txt_bulle("Il est hors de question que je m'abaisse à enseigner la magie <font color ='" + couleur_elem + "'>" + element_joueur + "</font> !");}
			else
				{afficher_txt_bulle("Il est hors de question que je m'abaisse à enseigner la magie <font color ='" + couleur_elem + "'>" + element_joueur + "</font> !");}
			break;
		case 3:
			afficher_txt_bulle("Bref,  sympa de t'avoir connu " + pseudo + " mais ne compte pas sur moi pour la suite, ciao !");
			break;
		case 4:
			afficher_txt_bulle("J'imagine que tu sais ce que tu fais...");
			$("#tuteur_cote").attr("src", "/webroot/img/personnages/" + ancien_tuteur.toLowerCase() + "_portrait.png");
			break;
		case 5:
			var timer = pulser($("#tuteur_cote"));
			anim = setTimeout(function(){
				$("#tuteur_cote").attr("src", "/webroot/img/personnages/" + nouveau_tuteur.toLowerCase() + "_portrait.png");
				afficher_txt_bulle("Salut " + pseudo + " ! Je m'appelle " + nouveau_tuteur + ". J'ai senti un tournant dans ton affinité avec les éléments, on dirait que tu t'orientes maintenant vers la magie <font color ='" + couleur_elem + "'>" + element_joueur + "</font>.");
				}, timer);
			break;
		case 6:
			afficher_txt_bulle("Je peux sentir que tu es sur Navadra depuis un moment déjà, parfait. Il n'y a donc pas besoin de reprendre ton entrainement de 0.");
			break;
		case 7:
			afficher_txt_bulle("Pour être honnête, c'est pas mon truc d'aider les aventuriers mais j'ai senti le potentiel qu'il y avait en toi et ce serait dommage, au nom de la magie <font color ='" + couleur_elem + "'>" + element_joueur + "</font>, de ne pas l'utiliser.");
			break;
		case 8:
			afficher_txt_bulle("Allez, on aura tout le temps de causer plus tard, je te laisse choisir tes sorts !");
			break;
		case 9:
			$("#commandes_grimoire").hide();
			break;
	}
}

function afficher_txt_bulle(nouveau_texte) //Petite animation pour la bulle
{
	$("#bulle_bas").hide("blind", 500);
	setTimeout(function(){
		$("#txt_bulle").html(nouveau_texte);
		$("#bulle_bas").show("blind", 500);
		}, 500);
}

//Donner leurs propriétés aux boutons suivants et précédents
function commandes_fonctionnelles()
{
	$("#instr_precedent").on("mouseover",function(){
		$("#instr_precedent").attr("src","/webroot/img/icones/chevron1bis.png");
	});

	$("#instr_precedent").on("mouseout",function(){
		$("#instr_precedent").attr("src","/webroot/img/icones/chevron1.png");
	});

	$("#instr_precedent").on("click",function(){
		if(etape > 1)
		{
			etape--;
			afficher_etape();
		}
	});

	$("#instr_suivant").on("mouseover",function(){
		$("#instr_suivant").attr("src","/webroot/img/icones/chevron2bis.png");
	});

	$("#instr_suivant").on("mouseout",function(){
		$("#instr_suivant").attr("src","/webroot/img/icones/chevron2.png");
	});

	$("#instr_suivant").on("click",function(){
		if(etape < 10)
		{
			etape++;
			afficher_etape();
		}
	});
}


//Fonctions utilitaires permettant de formater une partie des informations à afficher
function affichage_cout_sort(element, cout)
{
	var cout_tot = "Veux-tu vraiment apprendre ce sort ?<br />Cela te coûtera :";
	if(element == "feu")
	{
		cout_tot += " <font color='" + rouge + "'>" + cout + "</font> <img class='cout_sort' src='/webroot/img/icones/pyrs_feu.png'/>";
	}
	else if(element == "eau")
	{
		cout_tot += " <font color='" + bleu + "'>" + cout + "</font> <img class='cout_sort' src='/webroot/img/icones/pyrs_eau.png'/>";
	}
	else if(element == "vent")
	{
		cout_tot += " <font color='" + jaune + "'>" + cout + "</font> <img class='cout_sort' src='/webroot/img/icones/pyrs_vent.png'/>";
	}
	else if(element == "terre")
	{
		cout_tot += " <font color='" + vert + "'>" + cout + "</font> <img class='cout_sort' src='/webroot/img/icones/pyrs_terre.png'/>";
	}
	return cout_tot;
}

function affichage_cout_reinitialisation(feu, eau, vent, terre)
{
	var cout_tot = "";
	if(feu != 0)
	{
		cout_tot += " <font color='" + rouge + "'>" + feu + "</font> <img class='cout_sort' src='/webroot/img/icones/pyrs_feu.png'/>";
	}
	if(eau != 0)
	{
		cout_tot += " <font color='" + bleu + "'>" + eau + "</font> <img class='cout_sort' src='/webroot/img/icones/pyrs_eau.png'/>";
	}
	if(vent != 0)
	{
		cout_tot += " <font color='" + jaune + "'>" + vent + "</font> <img class='cout_sort' src='/webroot/img/icones/pyrs_vent.png'/>";
	}
	if(terre != 0)
	{
		cout_tot += " <font color='" + vert + "'>" + terre + "</font> <img class='cout_sort' src='/webroot/img/icones/pyrs_terre.png'/>";
	}
	return cout_tot;
}

function pulser_afficher(objet)
{
	objet.show("pulsate", {times: 1}, 1000);
}

function pulser(objet)
{
	objet.effect("pulsate", {times: 2}, 1000);
	return 1000;
}

function pyrs_suffisantes(element, cout)
{
	switch(element)
	{
		case "feu" :
			if(pyrs_feu >= cout)
				{return true;}
			else
				{return false;}
			break;
		case "eau" :
			if(pyrs_eau >= cout)
				{return true;}
			else
				{return false;}
			break;
		case "vent" :
			if(pyrs_vent >= cout)
				{return true;}
			else
				{return false;}
			break;
		case "terre" :
			if(pyrs_terre >= cout)
				{return true;}
			else
				{return false;}
			break;
	}
}


$(window).load(function(){ //Permet de gérer les problèmes d'affichages liés aux ratios d'écran bizarres
	//Si le tuteur empiète sur les icones de sort
	if($("#tuteur_cote").length)
	{
		if($("#tuteur_cote").offset().left + $("#tuteur_cote").width() > $(".icones_sorts_grimoire:eq(0)").offset().left)
		{
			$("#tuteur_cote").css("left", "0%"); //On enlève le décalage du tuteur
			if($("#tuteur_cote").offset().left + $("#tuteur_cote").width() > $(".icones_sorts_grimoire:eq(0)").offset().left) //Si le tuteur empiète encore sur les icones de sort
			{
				$("#tuteur_cote").css("width", $(".icones_sorts_grimoire:eq(0)").offset().left - $("#tuteur_cote").offset().left).css("height", "auto"); //On rétréci le tuteur
			}
		}
	}
	$("#sorts_feu").hide();
	$("#sorts_eau").hide();
	$("#sorts_vent").hide();
	$("#sorts_terre").hide();
	if(window.location.search.match(/elem/) != null){
		element_a_afficher = window.location.search.match(/elem=(\w+)/) [1];
		//On affiche les sorts de l'élément en question et on le met en forme
		$("#sorts_" + element_a_afficher).show();
		$("#"+ element_a_afficher).css("background-color","#FFFFC6").css("height", haut_base_elem - 4).css("border", "2px #e0c399 solid");
		$("#"+ element_a_afficher).css("-moz-border-radius", "10px").css("-webkit-border-radius", "10px").css("border-radius", "10px");
		$("#"+ element_a_afficher).css("padding", "5px");

		//On affiche les forces/faiblesses de l'élément
		changer_descriptif_force_faiblesse('descriptif_forces_faiblesses', element_a_afficher);
		showPossibleUpgrades(element_a_afficher);
		$("#displayMsg").hide();
	}

	//Gestion des animations lorsque l'utilisateur vient d'apprendre un sort
	if($("input[name=achat]").val() == "oui") {
		animation_achat();
	}

	if($("#spellsToBuy").length) {
		$("#spellsToBuy").css("position", "absolute").css("width", "80%").css("left", "10%").css("bottom", "1%");
	}



});
