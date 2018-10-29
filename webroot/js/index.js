// JavaScript Document

var hauteur_elem_base;
var chosenElement;
var title, description, strength, weakness;
var element_choisi = false;
var connected = {}; //Players connected on chat
var playerIdConnexion; //Connexion to chat
var currentChannel = "all"; //Chat channel
var chatActive = false;
	var selectedMonsterId = 0;
var iconFocused;

function choix_element(){
		if(!$(".choix_element").length)	{
			$('<div class="choix_element"></div>').appendTo("body");
			$(".choix_element").css("z-index", "99");
			$('<span class="consigne mb4 p3 g">Quelle magie veux-tu pratiquer ?</span>').appendTo(".choix_element");
			$('<span><img class="element_principal" src="/webroot/img/elements/feu.png" /></span>').appendTo(".choix_element");
			$('<span><img class="element_principal" src="/webroot/img/elements/eau.png" /></span>').appendTo(".choix_element");
			$('<span><img class="element_principal" src="/webroot/img/elements/vent.png" /></span>').appendTo(".choix_element");
			$('<span><img class="element_principal" src="/webroot/img/elements/terre.png" /></span>').appendTo(".choix_element");
			$('<span id="descr_elem" class="ib mh4 mb4 l90 justif"></span>').appendTo(".choix_element");
			hauteur_elem_base = $(".element_principal:eq(0)").css("height");
		}
		$(".element_principal:eq(0)").on("mouseover", function(){ //Clic sur le feu
			title = "<span class='g ib l100 rouge align_centre mb2 p2'>Le Feu - Nombres et Calculs</span>";
			description = "<span class='ib l100 mb4'>Une magie brutale et instantanée. Pour s'entraîner à la magie du Feu, il n'y a pas de meilleur moyen que de <span class='g'>manipuler des nombres</span>.<br>C'est ta puissance de calcul en combat qui te permettra de carboniser tes ennemis.</span>";
			strength = "<span class='ib l100'>Fort contre : <span class='g vert'>La Terre</span></span>";
			weakness = "<span class='ib l100'>Faible contre : <span class='g bleu'>L\'Eau</span></span>";
		});

		$(".element_principal:eq(1)").on("mouseover", function(){ //Clic sur l'eau
			title = "<span class='g ib l100 bleu align_centre mb2 p2'>L'Eau - Gestion de données et Fonctions</span>";
			description = "<span class='ib l100 mb4'>Telle une marée montante, la puissance de la magie de l'Eau augmente progressivement pour engloutir ses ennemis.<br>Pour manier cette magie, il te faudra t'entraîner à <span class='g'>analyser rapidement les données</span> et tendances en combat afin de les utiliser à ton avantage.</span>";
			strength = "<span class='ib l100'>Fort contre : <span class='g rouge'>Le Feu</span></span>";
			weakness = "<span class='ib l100'>Faible contre : <span class='g jaune'>Le Vent</span></span>";
		});

		$(".element_principal:eq(2)").on("mouseover", function(){ //Clic sur le vent
			title = "<span class='g ib l100 jaune align_centre mb2 p2'>Le Vent - Espace et Géométrie</span>";
			description = "<span class='ib l100 mb4'>La magie du Vent est à l'image d'une violente bourrasque: aussi impressionante que rapide et élégante. Ses adeptes la comparent à une danse.<br>Pour utiliser tout son potentiel, il te faudra devenir expert"+e+" en <span class='g'>maîtrise de la géométrie et de l'espace</span>.</span>";
			strength = "<span class='ib l100'>Fort contre : <span class='g bleu'>L'Eau</span></span>";
			weakness = "<span class='ib l100'>Faible contre : <span class='g vert'>La Terre</span></span>";
		});

		$(".element_principal:eq(3)").on("mouseover", function(){ //Clic sur la terre
			title = "<span class='g ib l100 vert align_centre mb2 p2'>La Terre - Grandeurs et Mesures</span>";
			description = "<span class='ib l100 mb4'>La magie de la Terre est profonde et solide. Elle puise toute sa force des entrailles de ce monde et c'est un lien que tu devras entretenir.<br>Ton entraînement consistera à mieux <span class='g'>comprendre les forces et grandeurs</span> impliquées pour développer cette connexion.</span>";
			strength = "<span class='ib l100'>Fort contre : <span class='g jaune'>Le Vent</span></span>";
			weakness = "<span class='ib l100'>Faible contre : <span class='g rouge'>Le Feu</span></span>";
		});

		$(".element_principal").on("mouseover", function(){
			$("#user_info").hide();
			$('#descr_elem').html(title+description+strength+weakness);
			chosenElement = $(this).attr("src").replace(/^.{1,}\/(.{3,5})\.png/, '$1');
			$(".element_principal").removeAttr("style");
			$(this).css("height", hauteur_elem_base + 10).css("border", "2px #e0c399 solid").css("background-color", "#ffe4bd").css("-moz-border-radius", "10px").css("-webkit-border-radius", "10px").css("border-radius", "10px");
			element_choisi = true;
			$(".valider_choix_elem").show();
			$("#valider_icone").show();
		});

		$(".element_principal").on("click", function(){
			if(element_choisi){
				assignChallenge(chosenElement);
			}
		});
}


function popUpDisplay(){
	if(playerAssignedChallenges > 0 && playerTuto != "fini")	{
		$(location).attr('href',"/app/controllers/accueil_defi.php?tuto=next");
	} else if(playerAssignedChallenges > 0 && playerTuto == "fini")	{
		$(location).attr('href',"/app/controllers/accueil_defi.php");
	}	else if(playerUnassignedChallenges > 0)	{
		choix_element();
	} else {
		$(location).attr('href',"/app/controllers/profil.php?id="+playerId+"&tab=challenges");
	}
}

function assignChallenge(elmnt){
	$.ajax({url: '/app/controllers/ajax.php',
	   type: 'POST',
	   data: 'assignChallenge='+elmnt,
	   dataType: 'html',
	   success: function (result, status) {
		 $(location).attr('href',"/app/controllers/accueil_defi.php");
	   },
	   error: function (result, status, error) {
		 $("<div title='Désolé...'>Erreur du serveur : peux-tu réessayer ?</div>").dialog({
		 		resizable: false,
		 		modal: true,
	 		});
	   },
	   complete: function (result, status) {
	   }
	});
}

function participateFight(){
	var idFight = parseInt(iconFocused.attr("idFight"));
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_participer=' + idFight,
		dataType: 'html',
		success: function(code_html, statut){
			prets = code_html;
			if(prets == "yourTurn") {
				var iconTemp = "gaming";
				var titleTemp = "A ton tour de combattre !";
			} else if(prets == "prets") {
				var iconTemp = "check";
				var titleTemp = "Tous les joueurs sont prêts mais ce n'est pas ton tour";
			} else {
				var iconTemp = "wait";
				var titleTemp = "En attente des autres participants";
			}
			iconFocused.hide("pulsate", "swing", 500, function(){
				iconFocused.attr("src", "/webroot/img/icones/"+iconTemp+".png").load(function() {
					iconFocused.parent(".monstre_index").attr("title", titleTemp);
					iconFocused.show("pulsate", 500);
				});
			});
		},
		error: function(resultat, statut, erreur){
		},
		complete: function(resultat, statut){
		}
	});
}

function declineFight(){
	var idFight = parseInt(iconFocused.attr("idFight"));
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_refuser=' + idFight,
		dataType: 'html',
		success: function(code_html, statut){
			$("#monstre_"+selectedMonsterId+"_info").hide("pulsate", "swing", 500);
			$("#chrono_"+selectedMonsterId).hide("pulsate", "swing", 500);
			iconFocused.parent(".monstre_index").hide("pulsate", "swing", 500);
		},
		error: function(resultat, statut, erreur){
		},
		complete: function(resultat, statut){
		}
	});
}


function resetMessage(){
	$("#message").removeClass("i rouge g");
	if(chatActive){
		$("#message").val("Exprime toi ici ! 😉");
		$("#message").addClass("i");
	} else {
		$("#message").val("Le chat ne fonctionne pas, le port 8080 de ta connexion doit être bloqué.");
		$("#message").addClass("rouge g");
	}
}

function startTyping(){
	$("#message").val("");
	$("#message").removeClass("i rouge g");
}

//Fonctions de contrôle du format des données
function msg_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[\s\S\r]{1,200}$/);
	if(ok == null && valeur_champ== "")
	{
		enlever_erreur(objet);
		return false;
	}
	else if(ok == null)
	{
		erreur_saisie(objet);
		return false;
	}
	else
	{
		enlever_erreur(objet);
		return true;
	}
}

//Fonctions de formatage des champs pour gérer les erreurs
function erreur_saisie(objet){
	objet.attr("title", "Message trop long !");
	objet.tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});
	objet.tooltip("open");
	objet.css("border", "2px solid #f00");
}

function enlever_erreur(objet){
	if(objet.attr("title") == "Message trop long !"){
		objet.tooltip("destroy");
	}
	objet.attr("title", "");
	objet.css("border", "2px solid #1c9500");
}


function placePlayer(idConnexion){
	var rand_title = math.pickRandom(["T\'as la classe non ?", "Oui, oui, c'est toi !", "Cette tête te rappelle quelque chose non ?", "Quel beau gosse !"]);
	if(rand_title == "Quel beau gosse !" && playerSexe == "fille"){
		rand_title = "Quelle beauté !";
	}
	$('<a class="gris avatars_joueurs" id="joueur_'+playerId+'" title="'+rand_title+'" href="/app/controllers/profil.php?id='+playerId+'" data-connected="'+idConnexion+'"><img  src="'+playerFullPortrait+'" /></a>').appendTo("body").hide();
	$("#joueur_"+playerId).tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});
	$("#joueur_"+playerId).css("left", playerPosX);
	$("#joueur_"+playerId).css("bottom", playerPosY);
	$("#joueur_"+playerId).draggable();
	$("#joueur_"+playerId).show("pulsate", {easing:"swing"}, 1000);
}

function channelFilter(playerData){
	if(playerData.channel == "none" || currentChannel == "none"){
		return true;
	} else if(playerData.channel == "nearLevels" || currentChannel == "nearLevels"){
		if(Math.abs(playerData.level - playerNiveau) > 3){
			return true;
		}
	} else {
		return false;
	}
}

function updateConnected(){
	//Remove the disconnected players
	$(".avatars_joueurs").each(function(){
		var idTemp = parseInt($(this).attr("data-connected"));
		if(idTemp != playerIdConnexion && (connected[idTemp] == undefined || channelFilter(connected[idTemp])) ){
			$(this).hide("pulsate", {easing:"swing"}, 1000, function(){
				$(this).remove();
			});
		}
	});
	//Add the newly connected players
	$.each(connected, function(index, value) {
		if(value.id == playerId && !$("#joueur_" + value.id).length){
			placePlayer(index);
		} else if(!$("#joueur_" + value.id).length && !channelFilter(connected[index])){
			$('<a class="gris avatars_joueurs" id="joueur_'+value.id+'" title="'+value.pseudo+' - niv.'+value.level+'" href="/app/controllers/profil.php?id='+value.id+'" data-connected="'+index+'"><img  src="'+value.portrait+'" /></a>').appendTo("body").hide();
			$("#joueur_"+value.id).tooltip({
				show: {
						effect: "slideDown",
						delay: 250
					}
			});
			$("#joueur_"+value.id).css("left", value.posX);
			$("#joueur_"+value.id).css("bottom", value.posY);
			$("#joueur_"+value.id).draggable();
			$("#joueur_"+value.id).show("pulsate", {easing:"swing"}, 1000);
		}
	});
}

function newNotification(){
	if(!$("#chatNotifications").length){
		$('<div id="chatNotifications" class="p5 g fond_beige_clair blanc align_centre">1</div>').appendTo("body").hide();
		$("#chatNotifications").css("position", "absolute").css("-moz-border-radius","50%").css("-webkit-border-radius","50%").css("border-radius","50%");
		$("#chatNotifications").css("left", $("#chatDisplay").offset().left + 0.6*$("#chatDisplay").width()).css("top",$("#chatDisplay").offset().top + 0.6*$("#chatDisplay").height());
		$("#chatNotifications").css("width", $("#chatNotifications").height()).css("border", "4px solid #fd874b");
		$("#chatNotifications").show("clip", {easing:"swing"}, 250);
	} else {
		var notif = parseInt($("#chatNotifications").html());
		notif ++;
		$("#chatNotifications").hide("clip", {easing:"swing"}, 250, function(){
			$("#chatNotifications").html(notif);
			$("#chatNotifications").show("clip", {easing:"swing"}, 250);
		});
	}
}


$(window).load(function(){

	//if ($("#chatDisplay").length)	{
	if(playerTuto == "fini") {

	if(window.location.hostname == "localhost"){
		var socket = new WebSocket('ws://'+window.location.hostname+':8080');
	} else {
           if (location.protocol != 'https:') {
            var socket = new WebSocket('ws://'+window.location.hostname+'/websocket');
          }
          else {
            var socket = new WebSocket('wss://'+window.location.hostname+'/websocket');
          }
	}

	socket.onopen = function(e) {
		chatActive = true;
		resetMessage();
	};

	socket.onclose = function(e) {
		chatActive = false;
		resetMessage();
	};

	socket.onmessage = function(e) {
		var message = JSON.parse(e.data);

		if(message.type == "connected"){
			//Send to server current player's data
			var newPlayer = {};
			newPlayer.id = playerId;
			newPlayer.pseudo = playerPseudo;
			newPlayer.portrait = playerFullPortrait;
			newPlayer.level = playerNiveau;
			newPlayer.posX = playerPosX;
			newPlayer.posY = playerPosY;
			newPlayer.channel = "all";
			newPlayer.idConnexion = message.idConnexion;
			playerIdConnexion = message.idConnexion;
			connected[message.idConnexion] = newPlayer;
			socket.send(JSON.stringify(newPlayer));
		} else if(message.content != undefined) {
			//For chat messages
			while(message.content.match(/\n/)){
				message.content = message.content.replace(/\n/, "<br>");
			}
			if(message.senderId == playerId){
				$("<div class='ib l80 mh4 align_haut align_droite'><span class='l100 align_droite'><span class='ib i p0'>"+message.time+" --</span><span class='ib g mg2'>Toi</span></span><span class='align_droite'>"+message.content+"</span></div>").appendTo($("#chatContent"));
				$("<img class='ib l17' title='Niv. "+message.senderLevel+"' src='"+message.senderAvatar+"'/>").appendTo($("#chatContent")).tooltip({
					show: {
						effect: "slideDown",
						delay: 250
					}
				});
			} else if(!channelFilter(connected[message.connexion])) {
				$("<img class='ib l17' title='Niv. "+message.senderLevel+"' src='"+message.senderAvatar+"'/>").appendTo($("#chatContent")).tooltip({
					show: {
						effect: "slideDown",
						delay: 250
					}
				});
				$("<div class='ib l80 mh4 align_haut'><span class='l100 align_gauche'><span class='ib i p0'>"+message.time+" --</span><span class='ib g mg2'> "+message.sender+"</span></span><span class='align_gauche'>"+message.content+"</span></div>").appendTo($("#chatContent"));
				if(!$("#chatContent").is(":visible")){
					newNotification();
				}
			}
			if(playerVolumeInterface == 1){
				$("#son_dialogue").trigger("play");
			}
			$("#chatContent").scrollTop(10000);
		} else {
			//Server send to all players the currently connected players
			connected = message;
			updateConnected();
		}

	};
	if ($("#chatDisplay").length)	{

	  $("#chatDisplay").css("position","absolute").css("right", "15%").css("top","20%").css("width", "5%").css("cursor", "pointer");
		$("#chatContent").css("position","absolute").css("right", "1%").css("top",$("#chatDisplay").offset().top + 1.1*$("#chatDisplay").height()).css("width", "23%");
		$("#message").css("width", $("#chatContent").width()).css("position","absolute").css("right", "1%").css("top",$("#barre_menu_droite").offset().top - 1.3*$("#message").height()).css("z-index", 85);
		resize_textarea($("#message"));
		$("#chatContent").css("height", $("#message").offset().top-1.1*$("#chatContent").offset().top).css("z-index", 85);

		$("#chatContent").hide();
		$("#message").hide();
		$("#selectFilter").selectmenu({
	    change: function(){
				var newChannel = {};
				currentChannel = $(this).val();
				newChannel.newChannel = currentChannel;
				newChannel.idPlayer = playerIdConnexion;
				socket.send(JSON.stringify(newChannel));
	    }
	  });
		$("#selectFilter-button").css("position","absolute").css("left", $("#chatDisplay").offset().left + 1.2*$("#chatDisplay").width()).css("width", "10%");
		$("#selectFilter-button").css("top",$("#chatDisplay").offset().top + 0.5*$("#chatDisplay").height() - 0.5*$("#selectFilter-button").height());

		$("#chatDisplay").on("mouseover", function () {
	     	$("#chatDisplay").attr("src", "/webroot/img/icones/chat_bubbles_hover.png");
	    });
		$("#chatDisplay").on("mouseout", function () {
	     	$("#chatDisplay").attr("src", "/webroot/img/icones/chat_bubbles.png");
	    });

		$("#chatDisplay").on("click", function () {
	     	$("#chatContent").toggle("clip", {easing: "easeInOutCubic"}, 500, function(){
				$("#chatContent").scrollTop(10000);
			});
			$("#message").toggle("clip", {easing: "easeInOutCubic"}, 500);
			if($("#chatNotifications").length){
				$("#chatNotifications").remove();
			}
	    });

		$("#message").on("focus", function () {
	     	if($("#message").val() == "Exprime toi ici ! 😉" || $("#message").val() == "Le chat ne fonctionne pas, nous sommes probablement en train de le réparer :)"){
				startTyping();
			}
	    });

		$("#message").on("blur", function () {
	     	if($("#message").val() == ""){
				resetMessage();
			}
	    });

		$("#message").on("keyup",function(){
			msg_valide($(this));
		});

		var altPressed = false;
		$(document).on("keydown", function (event) {
		  var key = event.which || event.keyCode;
		  	/* Bug with firefox, to uncomment maybe later
		  	//Enter + Alt Keys
			if (key == 13 && altPressed){
				$("#message").val($("#message").val() + "\n")
			} */
			//Enter Key
			if (key == 13 && !altPressed && msg_valide($("#message")) ){
				event.preventDefault();
				var message = {};
				message.sender = playerPseudo;
				message.senderId = playerId;
				message.connexion = playerIdConnexion;
				message.senderAvatar = playerFullPortrait;
				message.senderLevel = playerNiveau;
				message.content = $("#message").val();
				var currentTime = new Date();
				var hour = currentTime.getHours();
				if(hour < 10){
					var hour = "0"+hour;
				}
				var minutes = currentTime.getMinutes();
				if(minutes < 10){
					var minutes = "0"+minutes;
				}
				message.time = hour + "h" + minutes;
				socket.send(JSON.stringify(message));
				$("#message").val("");
			}
			/* Bug with firefox, to uncomment maybe later
			//Alt Key
			if (key == 18){
				altPressed = true;
			} */
		});

		$(document).on("keyup", function (event) {
		  var key = event.which || event.keyCode;
			//Alt Key
			if (key == 18){
				altPressed = false;
			}
		});
		}
  }

  if ($("#lancer_defi").length)
  {
    $("#lancer_defi").on("click", function () {
     	popUpDisplay();
    });

	$("#bulle_index").on("click", function () {
     	popUpDisplay();
    });
  }

	if ($("#nouveau_monstre_multi").length) {
    $("#nouveau_monstre_multi").hide();
	}
	if ($("#spellsToBuy").length){
    $("#spellsToBuy").hide();
	}
	if ($("#monstre_tuto_suite").length){
    $("#monstre_tuto_suite").hide();
	}
	if ($("#msg_end_fight").length) {
    $("#msg_end_fight").hide();
	}

  if ($("#bilan_saison").length) {
    $("#fermer_bilan_saison").on("mouseover", function () {
      $(this).attr("src", "/webroot/img/icones/refuser_selec.png");
    });

    $("#fermer_bilan_saison").on("mouseout", function () {
      $(this).attr("src", "/webroot/img/icones/refuser.png");
    });

    $("#fermer_bilan_saison").on("click", function () {
      $("#bilan_saison").hide("clip", 500);
    });
  } else if ($("#nouveau_monstre_multi").length) {
    $("#nouveau_monstre_multi").hide();
    $("#nouveau_monstre_multi").css("position", "absolute").css("top", "20%").css("width", "40%").css("left", "30%").css("z-index", "1000").css("cursor", "pointer");
    $("#nouveau_monstre_multi").show("clip", {easing: "swing"}, 1000, function () {
	  if(playerVolumeSoundEffects == 1){
      	$("#son_evenement").trigger("play");
	  }
    });
		createCloseImg($("#nouveau_monstre_multi"));
    $("#nouveau_monstre_multi").on("click", function () {
      $(this).hide("clip", {easing: "swing"}, 500);
    });
  } else if ($("#spellsToBuy").length){
    $("#spellsToBuy").hide();
    $("#spellsToBuy").css("position", "absolute").css("top", "20%").css("width", "40%").css("left", "30%").css("z-index", "1000").css("cursor", "pointer");
    $("#spellsToBuy").show("clip", {easing: "swing"}, 1000, function () {
			if(playerVolumeSoundEffects == 1){
			  $("#son_evenement").trigger("play");
			}
    });
		$("#bulle_index").hide();
		$("#lancer_defi").hide();
  } else if ($("#monstre_tuto_suite").length){
    $("#monstre_tuto_suite").hide();
    $("#monstre_tuto_suite").css("position", "absolute").css("top", "20%").css("width", "40%").css("left", "30%").css("z-index", "1000").css("cursor", "pointer");
    $("#monstre_tuto_suite").show("clip", {easing: "swing"}, 1000, function () {
		if(playerVolumeSoundEffects == 1){
		  $("#son_evenement").trigger("play");
		}
    });
		createCloseImg($("#monstre_tuto_suite"));
    $("#monstre_tuto_suite").on("click", function () {
      $(this).hide("clip", {easing: "swing"}, 500);
    });
  }	else if ($("#msg_end_fight").length) {
    $("#msg_end_fight").hide();
    $("#msg_end_fight").css("position", "absolute").css("top", "20%").css("width", "40%").css("left", "30%").css("z-index", "1000").css("cursor", "pointer");
    $("#msg_end_fight").show("clip", {easing: "swing"}, 1000, function () {
	  if(playerVolumeSoundEffects == 1){
      	$("#son_evenement").trigger("play");
	  }
    });
		createCloseImg($("#msg_end_fight"));
    $("#msg_end_fight").on("click", function () {
      $(this).hide("clip", {easing: "swing"}, 500);
    });
  }

	if ($("#spotTraining").length) {
    $("#spotTraining").hide();
    $("#spotTraining").css("position", "absolute").css("top", $("#lancer_defi").offset().top + $("#lancer_defi").height()).css("left", $("#lancer_defi").offset().left + 0.5*$("#lancer_defi").width() - 0.5*$("#spotTraining").width());
		$("#spotTraining").css("z-index", "150").css("cursor", "pointer");
    $("#spotTraining").show("pulsate", {easing: "swing"}, 1000, function () {
    });
  }

	$("#notEnoughFights").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
			"Je reviendrai demain": function(){
				$(this).dialog("close");
			}
		}
	});

	$("#participateFight").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
			"Accepter": function(){
				$(this).dialog("close");
				participateFight();
			},
			"Refuser": function(){
				$(this).dialog("close");
				declineFight();
			}
		}
	});

	$(".monstre_index").on("click", function(event){
		event.preventDefault();
		selectedMonsterId = parseInt($(this).attr("id").match(/\w+_(\d+)/)[1]);
		var iconType = "";
		if($(this).children(".monsterIcon").length){
			iconType = $(this).children(".monsterIcon").attr("src").match(/(\w+).png/)[1];
			if(iconType == "notification"){
				iconFocused = $(this).children(".monsterIcon");
				$("#participateFight").dialog("open");
			} else if(iconType == "gaming"){
				$(location).attr('href', $(this).children(".monsterIcon").attr("dest"));
			} else if(iconType == "gaming" && playerNb_combats <= 0){
				$("#notEnoughFights").dialog("open");
			}
		}
		if(iconType != "notification" && iconType != "gaming") {
			$(location).attr('href', "/app/controllers/prepa_combats.php?idm=" + selectedMonsterId);
		}
	});


});
