//Variables declaration
var helpModeActive = false;
if($("#boussole").length){
	var largeur_boussole = $("#boussole").width();
}

function initiateGuidedTour(){
	if($("#boussole").length){
		$("#boussole").on("mouseover", function(){
			$(this).css("width", $(this).width() + 10).css("cursor", "pointer");
		});

		$("#boussole").on("mouseout", function(){
			if($("#boussole").width() > largeur_boussole)	{
				$(this).css("width", $(this).width() - 10);
			}
		});

		$("#boussole img").on("click", function(){
			if(helpModeActive){
				switchHelpMode("hide");
			} else {
				$("#helpModeInfo").dialog("open");
			}
		});
	}

	$("#helpModeInfo").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
			"J'ai compris": function(){
				if(playerTuto == "index_12"){
					executer_ajax();
				} else {
					$(this).dialog("close");
					switchHelpMode("show");
				}
			}
		},
		close: function( event, ui ) {
			if(playerTuto == "index_12"){
				window.location.href = "/index.php?tuto=next";
			}
		}
	});

	$("#warningBlur").hide();
	$("#helpBubble").hide();

	if(playerTuto == "fini" && window.location.pathname.match(/index.php/) != null && window.location.search.match(/help=active/) != null){
		switchHelpMode("show");
	}
}


function switchHelpMode(state){
	if(state == "show"){
		helpModeActive = true;
		$("#boussole img").attr("src", "/webroot/img/icones/boussoleClose.png");
		$("#warningBlur").show();
		$("#boussole").css("z-index", 105);
		activateHelp($("#icone_index"), "<span class='g ib l100 mb1'>Retourner sur l'île</span>", "bottom", "right");
		activateHelp($("#icone_parametres"), "<span class='g ib l100 mb1'>Paramètres</span>Pour changer tes informations personnelles et ton avatar.", "bottom", "right");
		activateHelp($("#icone_deco"), "<span class='g ib l100 mb1'>Se déconnecter</span>", "bottom", "right");
		activateHelp($("#icone_grimoire"), "<span class='g ib l100 mb1'>Grimoire</span>Pour apprendre de nouveaux sorts et voir tes sorts actuels.", "top", "middle");
		activateHelp($("#icone_combats"), "<span class='g ib l100 mb1'>Liste des invitations et combats passés</span>Pour répondre aux invitations des autres joueurs et voir ton tableau de chasse.", "top", "middle");
		activateHelp($("#icone_messages"), "<span class='g ib l100 mb1'>Messages</span>Pour discuter avec les autres joueurs.", "top", "middle");
		activateHelp($("#icone_contacts"), "<span class='g ib l100 mb1'>Contacts</span>Pour consulter la liste de tes contacts.", "top", "middle");
		activateHelp($("#icone_recherche"), "<span class='g ib l100 mb1'>Recherche de joueurs</span>Pour ajouter des joueurs à tes contacts et les inviter plus facilement en combats.", "top", "right");
		activateHelp($("#portrait_footer"), "<span class='g ib l100 mb1'>Profil</span>Pour admirer tes trophées, tes progrès dans les défis, tes parrainages, etc.", "top", "left");
		activateHelp($("#icone_prestige"), "<span class='g ib l100 mb1'>Prestige & Classement</span>Pour voir où tu te situes par rapport aux autres chasseurs de monstres.", "top", "middle");
		if(window.location.pathname.match(/index.php/) != null){
			activateHelp($("#chatDisplay"), "<span class='g ib l100 mb1'>Chat</span>Pour discuter en temps réel avec les autres joueurs.", "bottom", "middle");
			activateHelp($("#bulle_index"), "<span class='g ib l100 mb1'>S'entrainer</span>Chaque jour, tu pourras t'entrainer sur 3 nouveaux défis.<br>C'est comme ça que tu gagneras des niveaux.", "bottom", "middle");
			activateHelp($("#lancer_defi"), "<span class='g ib l100 mb1'>S'entrainer</span>Chaque jour, tu pourras t'entrainer sur 3 nouveaux défis.<br>C'est comme ça que tu gagneras des niveaux.", "bottom", "middle");
			$(".monstre_index").each(function(){
					activateHelp($(this), "<span class='g ib l100 mb1'>Combattre</span>Chaque jour, tu pourras combattre 3 petits monstres et peut-être quelques gros monstres en plus.<br>C'est comme ça que tu gagneras du Prestige.", "top", "middle");
			})
		} else if(window.location.pathname.match(/profil.php/) != null){
			activateHelp($("#avatar_profil"), "<span class='g ib l100 mb1'>Modifier ton avatar</span>", "bottom", "middle");
			activateHelp($("#tabs_profile ul li:eq(0)"), "<span class='g ib l100 mb1'>Onglet par défaut du Profil</span>", "bottom", "middle");
			activateHelp($("#tabs_profile ul li:eq(1)"), "<span class='g ib l100 mb1'>Suivre tes progrès dans les défis</span>", "bottom", "middle");
			activateHelp($("#tabs_profile ul li:eq(2)"), "<span class='g ib l100 mb1'>La liste des trophées que tu as débloqués</span>", "bottom", "middle");
			activateHelp($("#tabs_profile ul li:eq(3)"), "<span class='g ib l100 mb1'>Pour parrainer d'autres joueurs sur Navadra</span>", "bottom", "middle");
			activateHelp($("#fire_challenges img"), "<span class='g ib l100 mb1'>Affiche ta progression dans les défis du Feu</span>", "bottom", "middle");
			activateHelp($("#water_challenges img"), "<span class='g ib l100 mb1'>Affiche ta progression dans les défis de l'Eau</span>", "bottom", "middle");
			activateHelp($("#wind_challenges img"), "<span class='g ib l100 mb1'>Affiche ta progression dans les défis du Vent</span>", "bottom", "middle");
			activateHelp($("#earth_challenges img"), "<span class='g ib l100 mb1'>Affiche ta progression dans les défis de la Terre</span>", "bottom", "middle");
		} else if(window.location.pathname.match(/grimoire.php/) != null){
			activateHelp($("#reinitialiser_sort"), "<span class='g ib l100 mb1'>Pour oublier tes sorts, récupérer tes Pyrs et choisir de nouveaux sorts.</span>", "top", "middle");
			activateHelp($(".icone_form_gauche"), "<span class='g ib l100 mb1'>Pour oublier tes sorts, récupérer tes Pyrs et choisir de nouveaux sorts.</span>", "top", "middle");
			activateHelp($("input[name=valider]"), "<span class='g ib l100 mb1'>Pour apprendre le sort sélectionné.</span>", "top", "middle");
			activateHelp($(".icone_form_droite"), "<span class='g ib l100 mb1'>Pour apprendre le sort sélectionné.</span>", "top", "middle");
			activateHelp($("#feu"), "<span class='g ib l100 mb1'>Affiche les sorts de Feu</span>", "bottom", "middle");
			activateHelp($("#eau"), "<span class='g ib l100 mb1'>Affiche les sorts d'Eau</span>", "bottom", "middle");
			activateHelp($("#vent"), "<span class='g ib l100 mb1'>Affiche les sorts du Vent</span>", "bottom", "middle");
			activateHelp($("#terre"), "<span class='g ib l100 mb1'>Affiche les sorts de Terre</span>", "bottom", "middle");
		} else if(window.location.pathname.match(/liste_combats.php/) != null){
			$(".cliquable").each(function(){
				if($(this).hasClass("accepter")){
					activateHelp($(this), "<span class='g ib l100 mb1'>Accepter de participer à ce combat</span>", "bottom", "middle");
				} else if($(this).hasClass("refuser")){
					activateHelp($(this), "<span class='g ib l100 mb1'>Refuser de participer à ce combat</span>", "bottom", "middle");
				}
			});
		} else if(window.location.pathname.match(/recherche.php/) != null){
			activateHelp($(".form_droite"), "<span class='g ib l100 mb1'>Lancer la recherche avec les critères sélectionnés</span>", "bottom", "middle");
			activateHelp($(".icone_form_droite"), "<span class='g ib l100 mb1'>Lancer la recherche avec les critères sélectionnés</span>", "bottom", "middle");
		} else if(window.location.pathname.match(/combats_decks.php/) != null){
			activateHelp($(".form_droite2"), "<span class='g ib l100 mb1'>Démarrer le combat avec les critères sélectionnés</span>", "bottom", "middle");
			activateHelp($(".icone_form_droite2"), "<span class='g ib l100 mb1'>Démarrer le combat avec les critères sélectionnés</span>", "bottom", "middle");
			activateHelp($(".form_gauche"), "<span class='g ib l100 mb1'>Sélectionne automatiquement les sorts les plus efficaces contre ce monstre</span>", "bottom", "middle");
			activateHelp($(".icone_form_gauche"), "<span class='g ib l100 mb1'>Sélectionne automatiquement les sorts les plus efficaces contre ce monstre</span>", "bottom", "middle");
			activateHelp($("#feu"), "<span class='g ib l100 mb1'>Affiche tes sorts de Feu</span>", "bottom", "middle");
			activateHelp($("#eau"), "<span class='g ib l100 mb1'>Affiche tes sorts d'Eau</span>", "bottom", "middle");
			activateHelp($("#vent"), "<span class='g ib l100 mb1'>Affiche tes sorts du Vent</span>", "bottom", "middle");
			activateHelp($("#terre"), "<span class='g ib l100 mb1'>Affiche tes sorts de Terre</span>", "bottom", "middle");
			activateHelp($(".icones_sorts_remove"), "<span class='g ib l100 mb1'>Clique sur la croix pour désélectionner ce sorts</span>", "top", "middle");
		} else if(window.location.pathname.match(/accueil_defi.php/) != null){
			activateHelp($(".form_droite2"), "<span class='g ib l100 mb1'>Lancer le défi</span>Tu n'as le droit qu'à un seul essai aujourd'hui sur ce défi.", "bottom", "middle");
			activateHelp($(".icone_form_droite2"), "<span class='g ib l100 mb1'>Lancer le défi</span>Tu n'as le droit qu'à un seul essai aujourd'hui sur ce défi.", "bottom", "middle");
			activateHelp($(".form_gauche"), "Ne te rapportera pas d'expérience mais permet de t'entraîner avant le vrai défi.", "bottom", "middle");
			activateHelp($(".icone_form_gauche"), "Ne te rapportera pas d'expérience mais permet de t'entraîner avant le vrai défi.", "bottom", "middle");
		} else if(window.location.pathname.match(/fin_defi.php/) != null){
			activateHelp($("#lancer_defi_bas"), "<span class='g ib l100 mb1'>Choisir un autre défi s'il t'en reste</span>", "top", "middle");
			activateHelp($("#bulle_bas"), "<span class='g ib l100 mb1'>Choisir un autre défi s'il t'en reste</span>", "top", "middle");
		}
	} else if(state == "hide"){
		helpModeActive = false;
		$("#boussole img").attr("src", "/webroot/img/icones/boussole.png");
		$("#warningBlur").hide();
		$("#boussole").removeAttr('style');
		$(".activatedHelp").css("z-index", "").removeClass("activatedHelp");
	}
}

function activateHelp(object, description, vertical, horizontal){
	if(object.css("position") == "static"){
			object.css("position", "relative");
	}
	object.css("z-index", 105).addClass("activatedHelp");
	if(object.attr("title") != undefined && object.attr("title") != ""){
		object.tooltip( "disable" );
	}
	object.unbind("mouseover mouseout")
	object.on("mouseover", function(){
		displayHelp($(this), description, vertical, horizontal);
	});
	object.on("mouseout", function(){
		$("#helpBubble").hide();
	});
}

function displayHelp(object, description, vertical, horizontal){
	$("#helpBubble").html(description);
	if(vertical == "top"){
		var yPos = object.offset().top - $("#helpBubble").height() - 30;
	} else if(vertical == "bottom"){
		var yPos = object.offset().top + object.height() + 30;
	}
	if(horizontal == "left"){
		var xPos = object.offset().left;
	} else if(horizontal == "middle"){
		var xPos = object.offset().left + 0.5*object.width() - 0.5*$("#helpBubble").width();
	} else if(horizontal == "right"){
		var xPos = object.offset().left + object.width() - $("#helpBubble").width();
	}
	$("#helpBubble").css("top", yPos).css("left", xPos);
	$("#helpBubble").show("clip", {easing: "swing"}, 500);
}
