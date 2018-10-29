// JavaScript Document

//Variables de couleurs
var gris = "#666";
var gris_fonce = "#352c2d";
var marron = "#ce9d62";
var rouge = "#FF0000";
var bleu = "#0080FF";
var jaune = "#B7B700";
var vert = "#00A452";
var violet = "#7171FF";
var violet_clair = "#AAF";
var beige = "#ffe4bd";
var blanc = "#FFF";
var jaune_clair = "#FFFFC6";
var rouge_pastel = "#ac090a";
var bleu_pastel = "#05a2d9";
var jaune_pastel = "#FEE996";
var jaune_pastel_bis = "#e2b603";
var vert_pastel = "#38bd48";

if($("#playerId").length){
	var playerId = parseInt($("#playerId").html());
	var playerPseudo = $("#playerPseudo").html();
	var playerSexe = $("#playerSexe").html();
	var e = $("#e").html();
	var playerClasse = $("#playerClasse").html();
	var playerEmail = $("#playerEmail").html();
	var playerEmail_parent = $("#playerEmail_parent").html();
	var playerNiveau = parseInt($("#playerNiveau").html());
	var playerXp = parseInt($("#playerXp").html());
	var playerPyrs_feu = parseInt($("#playerPyrs_feu").html());
	var playerPyrs_eau = parseInt($("#playerPyrs_eau").html());
	var playerPyrs_vent = parseInt($("#playerPyrs_vent").html());
	var playerPyrs_terre = parseInt($("#playerPyrs_terre").html());
	var playerElement = $("#playerElement").html();
	var playerElement_article = $("#playerElement_article").html();
	var playerElement_article2 = $("#playerElement_article2").html();
	var playerProfil_elem = $("#playerProfil_elem").html();
	var playerPrestige = parseInt($("#playerPrestige").html());
	var playerPm_joueur = parseInt($("#playerPm_joueur").html());
	var playerEndu_joueur = parseInt($("#playerEndu_joueur").html());
	var playerNb_combats = parseInt($("#playerNb_combats").html());
	var playerTuto = $("#playerTuto").html();
	var playerImg_joueur = $("#playerImg_joueur").html();
	var playerPortrait = $("#playerPortrait").html();
	var playerFullPortrait = $("#playerFullPortrait").html();
	var playerTuteur = $("#playerTuteur").html();
	var playerUnassignedChallenges = parseInt($("#playerUnassignedChallenges").html());
	var playerAssignedChallenges = parseInt($("#playerAssignedChallenges").html());
	var playerTotalChallenges = playerUnassignedChallenges + playerAssignedChallenges;
	var playerSoloMonsters = parseInt($("#playerSoloMonsters").html());
	var playerMultiMonsters = parseInt($("#playerMultiMonsters").html());
	var playerTotalMonsters = playerSoloMonsters + playerMultiMonsters;
	//var playerBulles_daide_vues = $("#playerBulles_daide_vues").html();
	var playerAdvanced_description = parseInt($("#playerAdvanced_description").html());
	var playerVolumeMusic = parseInt($("#playerVolumeMusic").html());
	var playerVolumeSoundEffects = parseInt($("#playerVolumeSoundEffects").html());
	var playerVolumeInterface = parseInt($("#playerVolumeInterface").html());
	var playerPosX = $("#playerPosX").html();
	var playerPosY = $("#playerPosY").html();
	var playerGameLimitation = parseInt($("#playerGameLimitation").html());
	var playerFirstConnexion = parseInt($("#playerFirstConnexion").html());
	var playerExistingPayment = parseInt($("#playerExistingPayment").html());
	var tutoFinishedToday = parseInt($("#tutoFinishedToday").html());
	var playerTotalSpells = parseInt($("#playerTotalSpells").html());
	var timeSlot = $("#timeSlot").html();
	if(timeSlot != "NoTimeSlot"){
		timeSlot = timeSlot.split(",");
	}
	switch(playerSexe){
	 case "gars" :
		 var e = "";
		 var hf = "homme";
		 break;
	 case "fille" :
		 var e = "e";
		 var hf = "fille";
		 break;
 	}
}

function mettre_en_evidence_ligne_scroll(objet)
{
	objet.css('background-color', '#FFFFC6').css("color", gris_fonce).css('cursor', 'pointer');
}

function enlever_evidence_ligne_scroll(objet)
{
	objet.removeAttr("style");
}

function display_clip(object){
	object.show("clip", 500);
}

function hide_clip(object){
	object.hide("clip", 500);
}

function superpose_portrait(target, lvl, elem, portrait){
	if(lvl == undefined){
		var lvl = playerNiveau;
	}
	if(elem == undefined){
		var elem = playerElement;
	}
	if(portrait == undefined){
		var portrait = playerPortrait;
	}
	levelBackground = ornmentLvl(lvl);
	var src_background = "/webroot/img/cadres_avatars/cadres_ronds/"+elem+"_fond.png";
	$("<img src='"+src_background+"' />").appendTo(target).css("position", "absolute").css("bottom", "0").css("left", "0").css("width", "100%");
	$("<img src='"+portrait+"' />").appendTo(target).css("position", "absolute").css("bottom", "0").css("left", "0").css("width", "100%").load(function() {
	    $(target).css("height", $(target+" img:eq(1)").height());
		if(levelBackground > 0){
			var src_ornments = "/webroot/img/cadres_avatars/cadres_ronds/"+elem+"_niv_"+levelBackground+".png";
			$("<img src='"+src_ornments+"' />").appendTo(target).css("position", "absolute").css("width", "130%").css("bottom", "-15%").css("left", "-15%");
		}
	});
}

function complete_portrait(target, lvl, elem){
	if(lvl == undefined){
		var lvl = playerNiveau;
	}
	if(elem == undefined){
		var elem = playerElement;
	}
	levelBackground = ornmentLvl(lvl);
	var src_background = "/webroot/img/cadres_avatars/cadres_ronds/"+elem+"_fond.png";
	$("<img src='"+src_background+"' />").appendTo("body").css("position", "absolute").css("top", top).css("left", left).css("width", width).load(function() {
		var width = target.width();
		var height = target.height();
		var top = target.offset().top;
		var left = target.offset().left;
		if(levelBackground > 0){
			var src_ornments = "/webroot/img/cadres_avatars/cadres_ronds/"+elem+"_niv_"+levelBackground+".png";
			$("<img src='"+src_ornments+"' />").appendTo("body").css("position", "absolute").css("width", 1.3*width).css("top", top-0.15*height).css("left", left-0.15*width);
		}
	});
}

function ornmentLvl(lvl){
	if(lvl>=40){
		return 5;
	} else if(lvl>=30){
		return 4;
	} else if(lvl>=20){
		return 3;
	} else if(lvl>=10){
		return 2;
	} else if(lvl>=5){
		return 1;
	} else {
		return 0;
	}
}

//Fonctions de formatage des champs pour gérer les erreurs
function erreur_saisie(objet){
	objet.css("border", "2px solid #f00");
}

function enlever_erreur(objet){
	objet.css("border", "2px solid #1c9500");
}

//Fonctions de contrôle du format des données
function titre_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[\s\S\r]{10,50}$/);
	if(ok == null)
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

function descriptif_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[\s\S\r]{20,500}$/);
	if(ok == null)
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

function descriptif_long_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[\s\S\r]{20,2000}$/);
	if(ok == null)
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

var resize_history = {};
//Script pour appliquer un padding à la textarea sans modifier ses dimensions
function resize_textarea(object){
	if(resize_history[object.attr("name")] == undefined){
		resize_history[object.attr("name")] = true;
		object.css("padding", "5px 10px"); //On rajoute 2px en haut et en bas et 5 à gauche et à droite
		object.css("width", object.width() - 20); //On diminue sa largeur de 10px
		object.css("height", object.height() - 10); //On diminue sa hauteur de 4px
	}
};

var bugReportTries = 0;
function report_bug(code, answerUser, answerReal){
	bugReportTries = 0;
	$("#feedback").hide();
	display_processing_div();
	var capture = {};
	var answerUserEsc = answerUser.toString();
	while(answerUserEsc.match(/<img src=".{0,}\/\w+.png">/)) {
		answerUserEsc = answerUserEsc.replace(/<img src=".{0,}\/(\w+.png)">/, '$1');
	}
	var answerRealEsc = answerReal.toString();
	while(answerRealEsc.match(/<img src=".{0,}\/\w+.png">/)) {
		answerRealEsc = answerRealEsc.replace(/<img src=".{0,}\/(\w+.png)">/, '$1');
	}
	html2canvas(document.body, {
		onrendered: function(canvas) {
			capture.img = canvas.toDataURL( "image/png" );
			capture.data = {
				'image' : capture.img,
				'playerId' : playerId,
				'descriptif_bug' : $("textarea[name=descriptif_bug]").val(),
				'page_courante' : $("input[name=page_courante]").val(),
				'challenge' : code,
				'answerUser' : answerUserEsc,
				'answerReal' : answerRealEsc
			};
			send_bug(capture);
		}
	});
}

function send_bug(capture){
	bugReportTries ++;
	$.ajax({
		url: "/app/controllers/envoi_demails.php",
		data: capture.data,
		type: 'POST',
		success: function( result ) {
			$("#processing").hide();
			$("#feedback").show();
			$("#confirm_bug").removeClass("rouge").addClass("vert").html("Un mail vient d'être envoyé à l'équipe Navadra avec ta capture d'écran, merci !");
			$("textarea[name=descriptif_bug]").val("");
		},
		error: function(request, status, err) {
			if(bugReportTries > 3){
				$("#processing").hide();
				$("#feedback").show();
				$("#confirm_bug").removeClass("vert").addClass("rouge").html("Désolé, ta connexion semble trop lente pour envoyer le rapport de bug.");
				console.log("Requête :" + request);
				console.log("Statut :" + status);
				console.log("Erreur :" + err);
			} else {
				send_bug(capture); //Try again
			}
		},
		complete: function (result, status) {

		}
	});
}

function display_processing_div()
{
	if(!$("#processing").length){
		$('<div id="processing" class="bulle_daide"></div>').appendTo("body");
		$("#processing").css("position", "absolute").css("left", "35%").css("top", "2%").css("width","30%").css("vertical-align","top");
		$("#processing").html("<span class='ib l20'><img class='l50' src='/webroot/img/icones/loading.gif' /></span><span class='ib l80'><span class='ib l100 p1 g mb2'>Capture d'écran en cours...</span><span class='ib l100'>Ne quitte pas la page s'il te plait.</span></span>");
	}
	else{
		$("#processing").show();
	}
}

function createCloseImg(object, animate){
	$('<img id="closeIndexPopUp" title="Fermer la fenêtre" class="img_80" src="/webroot/img/icones/refuser.png">').appendTo(object).load(function() {
		$("#closeIndexPopUp").tooltip({
			show: {
				effect: "slideDown",
				delay: 250
			}
		});
		$("#closeIndexPopUp").css("position", "absolute").css("top", -0.4*$("#closeIndexPopUp").height()).css("right", -0.4*$("#closeIndexPopUp").height()).on("click", function () {
			object.hide("clip", {easing: "swing"}, 500,function(){
				if(animate == true){
					$("#annonce_fin_defi").switchClass("annonce_fin_defi", "annonce_fin_defi_cachee", 500);
					setTimeout(function(){
						$("#annonce_fin_defi").hide();
						if(playerVolumeSoundEffects == 1){
							$("#son_fin_defi").trigger("play");
						}
						change_level(levelBeforeChallenge, levelAfterChallenge)
					}, 500);
				}
			});
		});
	});
}

function displayEndFreePeriod(limitReached, endChallenge){
	if(limitReached){
		$("#warningBlur").show();
		display_clip($("#warningMessage"));
		$(".hideMessage").on("click", function(){
			window.open('https://www.navadra.com/jeu-payant/');
			hide_clip($("#warningMessage"));
			setTimeout(function(){
				$("#warningBlur").hide();
				if(endChallenge && playerTuto == "fini" && (playerAssignedChallenges + playerUnassignedChallenges > 0)){
					$("#bulle_bas").show("pulsate", {easing: "swing"}, 500);
					$("#lancer_defi_bas").show("pulsate", {easing: "swing"}, 500);
				}
			}, 500);
		});
		$(".subscriptionFormulas").on("click", function(){
			if(typeof(mixpanel) != "undefined" && endChallenge) {
					mixpanel.track("LookFormulas");
			}
			window.open('https://www.navadra.com/pass/');
		});
		if(typeof(mixpanel) != "undefined" && endChallenge) {
				mixpanel.track("DisplayEndFreePeriod");
				mixpanel.track("EmailParentSentAuto1");
		}
	} else if(endChallenge) {
		if(playerTuto == "fini" && (playerAssignedChallenges + playerUnassignedChallenges > 0) ){
			$("#bulle_bas").show("pulsate", {easing: "swing"}, 500);
			$("#lancer_defi_bas").show("pulsate", {easing: "swing"}, 500);
		}
	}
}
