// JavaScript Document

//Attribution de leurs caractéristiques aux widgets jQuery
//$("#classe").buttonset();
$("#volume_music").button();
$("#volume_sound_effects").button();
$("#volume_interface").button();
//$("#activer_bulles_daides").button();
$("#advanced_spell_description").button();
$("#confirmationWindow").hide();
$("#closeConfirmationWindow").on("click", function(){
	hide_clip($("#confirmationWindow"));
	hide_clip($("#info_input"));
	$("#warningBlur").hide();
})

var landing_tab = $("#get_parameters").html();

$(".settings").on("click", function(){
	change_settings();
});

$("#missing_info").dialog({
	autoOpen: false,
}).hide();
$("#wrong_info").dialog({
	autoOpen: false,
}).hide();

if($("#completeProfile").length){
	$("#completeProfile").dialog({
		autoOpen: true,
		resizable: false,
		modal: true,
		buttons: {
			"Ça marche !": function(){
				$(this).dialog("close");
			}
		}
	});
}

if($("#sameEmail").length){
	$("#sameEmail").button();
	$("#sameEmail").on("click", function(event){
		event.preventDefault();
		if($("input[name=email]").parent("p").is(":visible")){
			$("#sameEmailInfo").dialog("open");
		} else {
			$("#sameEmail").prop("checked", false).button("refresh");
			display_clip($("input[name=email]").parent("p"));
		}
	});
}

$("#sameEmailInfo").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Oui": function(){
			$("#sameEmail").prop("checked", true).button("refresh");
			$(this).dialog("close");
			hide_clip($("input[name=email]").parent("p"));
			if($("input[name=email_parent]").val() == ""){
				$("input[name=email_parent]").val($("input[name=email]").val());
			}
			email_parent_valide($("input[name=email_parent]"));
		},
		"Non": function(){
			$(this).dialog("close");
		}
	}
});

if($("#completeEmail").length){
	$("#completeEmail").dialog({
		autoOpen: true,
		resizable: false,
		modal: true,
		buttons: {
			"Mon adresse email": function(){
				$(this).dialog("close");
				$("input[name=email]").css("background-color", "#b5ed8a");
				email_valide($("input[name=email]"));
				$("input[name=email]").focus();
			},
			"Celle d'un de mes parents": function(){
				if(playerClasse == "Prof"){
					$(this).dialog("close");
					$("input[name=email]").css("background-color", "#b5ed8a");
					email_valide($("input[name=email]"));
					$("input[name=email]").focus();
				} else {
					$("#sameEmail").prop("checked", true).button("refresh");
					hide_clip($("input[name=email]").parent("p"));
					if($("input[name=email_parent]").val() == ""){
						$("input[name=email_parent]").val($("input[name=email]").val());
					}
					email_parent_valide($("input[name=email_parent]"));
					$("input[name=email_parent]").css("background-color", "#b5ed8a");
					setTimeout(function(){
						$("input[name=email_parent]").focus();
					}, 1000);
					$(this).dialog("close");
				}
			}
		}
	});
}

if($("#confirmEmail").length){
	$("#confirmEmail").dialog({
		autoOpen: true,
		resizable: false,
		modal: true,
		buttons: {
			"OK": function(){
				$(this).dialog("close");
			},
			"Renvoyer": function(){
				$(this).dialog("close");
				$.ajax({
					url: '/app/controllers/ajax.php',
					type :'POST',
					data: 'sendEmailActivationLink=ok',
					dataType: 'html',
					success: function(resultat, statut){
						console.log(resultat);
					},
					error: function(resultat, statut, erreur){
						console.log(resultat);
					},
					complete: function(resultat, statut){
						console.log(resultat);
					}
				});
			},
			"Pas reçu l'email": function(){
				$(this).dialog("close");
				$.ajax({
					url: '/app/controllers/ajax.php',
					type :'POST',
					data: 'dontReceiveEmail=ok',
					dataType: 'html',
					success: function(resultat, statut){
						if(resultat == "alreadyAlerted"){
							$("#dontReceiveEmail").html("Tu as déjà envoyé un message aux administrateurs et devrais recevoir rapidement une réponse sur ton adresse email <span class='g'>(" + playerEmail + ")</span>.");
						}
						$("#dontReceiveEmail").dialog("open");
					},
					error: function(resultat, statut, erreur){
						console.log(resultat);
					},
					complete: function(resultat, statut){
						console.log(resultat);
					}
				});
			},
			"Changer mon email": function(){
				$(this).dialog("close");
				$("input[name=email]").focus().select();
			}
		}
	});
}

if($("#dontReceiveEmail").length){
	$("#dontReceiveEmail").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
			"OK": function(){
				$(this).dialog("close");
			}
		}
	});
}


function change_settings(){
	var settings = {};
	$(".settings").each(function(){
		if($(this).prop('checked') == true) {
			settings[$(this).attr("id")] = 1;
		} else {
			settings[$(this).attr("id")] = 0;
		}
	});
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'volume_music='+ settings.volume_music+'&volume_sound_effects='+ settings.volume_sound_effects+'&volume_interface='+ settings.volume_interface+'&advanced_spell_description='+ settings.advanced_spell_description,
		dataType: 'html',
		success: function(resultat, statut){
			console.log(resultat);
		},
		error: function(resultat, statut, erreur){
			console.log(erreur);
		},
		complete: function(resultat, statut){
			console.log(resultat);
		}
	});
}


//Changement de l'apparence des chevrons gauches quand l'utilisateur les survole
$("#chevron1").on("mouseover", function(){
	$(this).attr("src", "/webroot/img/icones/chevron1bis.png");
});
$("#chevron1").on("mouseout", function(){
	$(this).attr("src", "/webroot/img/icones/chevron1.png");
});


//Changement de l'apparence des chevrons droits quand l'utilisateur les survole
$("#chevron2").on("mouseover", function(){
	$(this).attr("src", "/webroot/img/icones/chevron2bis.png");
});
$("#chevron2").on("mouseout", function(){
	$(this).attr("src", "/webroot/img/icones/chevron2.png");
});

//Détermination des paramètres d'initiation (variables sexe et classe ainsi que l'apparition ou pas de la div question_secrete
var photo_modele = $("input[name=avatar_entier]").val();
if(photo_modele.match(/gars/) != null)
	{var sexe = "gars";}
else
	{var sexe = "fille";}
var classe = playerClasse;

//Changement des parties du corps du personnage en cliquant sur les chevrons ou en choisissant le sexe du joueur
var nom_partie = "";
var modele = 1000000 + photo_modele.match(/[0-9]{1}/)[0] - 1;
var cheveux = 0;
var yeux = 0;
var peau = 0;
initialiser_variables_customisation();

function initialiser_variables_customisation() //Permet de récupérer les bonnes couleurs de cheveux, yeux et peau du personnage existant
{
	var regexp_modele = /[0-9]{1}_([a-z]+)_([a-z]+)_([a-z]+).png/.exec(photo_modele);
	var cheveux_txt = RegExp.$1;
	var yeux_txt = RegExp.$2;
	var peau_txt = RegExp.$3;
	switch(cheveux_txt)
	{
		case "blond" :
			cheveux = 100;
			break;
		case "roux" :
			cheveux = 101;
			break;
		case "brun" :
			cheveux = 102;
			break;
		case "noir" :
			cheveux = 103;
			break;
	}
	switch(yeux_txt)
	{
		case "bleu" :
			yeux = 100;
			break;
		case "vert" :
			yeux = 101;
			break;
		case "marron" :
			yeux = 102;
			break;
		case "noir" :
			yeux = 103;
			break;
	}
	switch(peau_txt)
	{
		case "occ" :
			peau = 100;
			break;
		case "asi" :
			peau = 101;
			break;
		case "met" :
			peau = 102;
			break;
		case "noi" :
			peau = 103;
			break;
	}
}


$("#chevron1").on("click", function(){
	//On vérifie que l'utilisateur a déjà choisi un sexe
	if(sexe != "")
	{
		modele--;
		changer_modele();
	}
});

$("#chevron2").on("click", function(){
	//On vérifie que l'utilisateur a déjà choisi un sexe
	if(sexe != "")
	{
		modele++;
		changer_modele();
	}
});

function changer_modele()
{
	$("#modele_avatar").css("visibility", "hidden");
	$("#icone_chargement").removeClass("cache");
	var mod_modele = modele % 8 + 1;
	if(cheveux == 0)
	{
		cheveux = 100;
		//cheveux = entier_aleatoire(100, 103);
	}
	cheveux_txt = determiner_txt("cheveux", cheveux);
	if(yeux == 0)
	{
		yeux = 100;
		//yeux = entier_aleatoire(100, 103);
	}
	yeux_txt = determiner_txt("yeux", yeux);
	if(peau == 0)
	{
		peau = 100;
		//peau = entier_aleatoire(100, 103);
	}
	peau_txt = determiner_txt("peau", peau);

	$("#modele_avatar").attr("src", "/webroot/img/avatars/"+sexe+"_"+mod_modele+"_"+cheveux_txt+"_"+yeux_txt+"_"+peau_txt+".png").load(function() {
		$("#icone_chargement").addClass("cache");
		$("#modele_avatar").css("visibility", "visible");
	});
	$("input[name=avatar_entier]").val("/webroot/img/avatars/"+sexe+"_"+mod_modele+"_"+cheveux_txt+"_"+yeux_txt+"_"+peau_txt+".png");
	$("input[name=avatar_tete]").val("/webroot/img/avatars/tete_"+sexe+"_"+mod_modele+"_"+cheveux_txt+"_"+yeux_txt+"_"+peau_txt+".png");
}

var taille_base  = $(".case_coul:eq(0)").width();
$(".case_coul").on("click", function(){
	switch($(this).attr("id"))
	{
		case "cheveux_blond" :
			cheveux = 100;
			reinitialiser_cheveux(taille_base);
			break;
		case "cheveux_roux" :
			cheveux = 101;
			reinitialiser_cheveux(taille_base);
			break;
		case "cheveux_brun" :
			cheveux = 102;
			reinitialiser_cheveux(taille_base);
			break;
		case "cheveux_noir" :
			cheveux = 103;
			reinitialiser_cheveux(taille_base);
			break;
		case "yeux_bleu" :
			yeux = 100;
			reinitialiser_yeux(taille_base);
			break;
		case "yeux_vert" :
			yeux = 101;
			reinitialiser_yeux(taille_base);
			break;
		case "yeux_marron" :
			yeux = 102;
			reinitialiser_yeux(taille_base);
			break;
		case "yeux_noir" :
			yeux = 103;
			reinitialiser_yeux(taille_base);
			break;
		case "peau_occ" :
			peau = 100;
			reinitialiser_peau(taille_base);
			break;
		case "peau_asi" :
			peau = 101;
			reinitialiser_peau(taille_base);
			break;
		case "peau_met" :
			peau = 102;
			reinitialiser_peau(taille_base);
			break;
		case "peau_noi" :
			peau = 103;
			reinitialiser_peau(taille_base);
			break;
	}
	$(this).css("height", taille_base + 5).css("width", taille_base + 5);
	$(this).css("border", "3px solid #1c9500");
	if(sexe != "")
	{
		changer_modele();
	}
});

function reinitialiser_cheveux(taille_cible)
{
	$("#cheveux_blond").css("height", taille_cible).css("width", taille_cible);
	$("#cheveux_blond").css("border", "2px solid #e5c89c");
	$("#cheveux_roux").css("height", taille_cible).css("width", taille_cible);
	$("#cheveux_roux").css("border", "2px solid #e5c89c");
	$("#cheveux_brun").css("height", taille_cible).css("width", taille_cible);
	$("#cheveux_brun").css("border", "2px solid #e5c89c");
	$("#cheveux_noir").css("height", taille_cible).css("width", taille_cible);
	$("#cheveux_noir").css("border", "2px solid #e5c89c");
}

function reinitialiser_yeux(taille_cible)
{
	$("#yeux_bleu").css("height", taille_cible).css("width", taille_cible);
	$("#yeux_bleu").css("border", "2px solid #e5c89c");
	$("#yeux_vert").css("height", taille_cible).css("width", taille_cible);
	$("#yeux_vert").css("border", "2px solid #e5c89c");
	$("#yeux_marron").css("height", taille_cible).css("width", taille_cible);
	$("#yeux_marron").css("border", "2px solid #e5c89c");
	$("#yeux_noir").css("height", taille_cible).css("width", taille_cible);
	$("#yeux_noir").css("border", "2px solid #e5c89c");
}

function reinitialiser_peau(taille_cible)
{
	$("#peau_occ").css("height", taille_cible).css("width", taille_cible);
	$("#peau_occ").css("border", "2px solid #e5c89c");
	$("#peau_asi").css("height", taille_cible).css("width", taille_cible);
	$("#peau_asi").css("border", "2px solid #e5c89c");
	$("#peau_met").css("height", taille_cible).css("width", taille_cible);
	$("#peau_met").css("border", "2px solid #e5c89c");
	$("#peau_noi").css("height", taille_cible).css("width", taille_cible);
	$("#peau_noi").css("border", "2px solid #e5c89c");
}

//Fonctions de contrôle du format des données
function nom_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[a-zàéèïîëçù'A-Z0-9 -@_]{3,30}$/);
	if(ok == null  && valeur_champ != "")
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

function pseudo_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[a-zàéèïîëçù'A-Z0-9 -@_]{3,15}$/);
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

function mdp_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[\wàéèïîëçù._-]{6,30}$/);
	if(ok == null && valeur_champ != "")
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

function mdp_identiques(objet1, objet2){
	mdp1 = objet1.val();
	mdp2 = objet2.val();
	if(mdp1 != mdp2)
	{
		erreur_saisie(objet1);
		erreur_saisie(objet2);
		objet1.attr("title","Les mots de passe doivent être identiques.");
		objet2.attr("title","Les mots de passe doivent être identiques.");
		return false;
	}
	else
	{
		enlever_erreur(objet1);
		enlever_erreur(objet2);
		objet1.attr("title","Entre 6 et 30 caractères : lettres, chiffres et ponctuation (.!?_-).");
		objet2.attr("title","Entre 6 et 30 caractères : lettres, chiffres et ponctuation (.!?_-).");
		return true;
	}
}

function email_valide(objet){
	valeur_champ = objet.val();
	if(classe != "Prof") {
		var ok = regexEmail(valeur_champ, true);
	} else {
		var ok = regexEmail(valeur_champ, false);
	}
	if(ok !== true  && valeur_champ != "" && objet.parent("p").is(":visible") )
	{
		objet.attr("title",ok);
		erreur_saisie(objet);
		return false;
	}
	else
	{
		enlever_erreur(objet);
		return true;
	}
}

function email_parent_valide(objet){
	if(objet.length){
		valeur_champ = objet.val();
		var ok = regexEmail(valeur_champ, true);
		if(ok !== true && valeur_champ != "")
		{
			objet.attr("title",ok);
			erreur_saisie(objet);
			return false;
		}
		else
		{
			enlever_erreur(objet);
			return true;
		}
	} else {
		return true;
	}
}

function emails_differents(objet1, objet2){
	if(objet1.length && objet2.length){
		email1 = objet1.val();
		email2 = objet2.val();
		if(email1 != "" && email2 != "" && email1 == email2 && objet1.parent("p").is(":visible"))
		{
			objet1.attr("title","Les 2 adresses emails doivent être différentes !");
			objet2.attr("title","Les 2 adresses emails doivent être différentes !");
			erreur_saisie(objet1);
			erreur_saisie(objet2);
			return false;
		}
		else
		{
			enlever_erreur(objet1);
			enlever_erreur(objet2);
			objet1.attr("title","Au format adresse email classique.");
			objet2.attr("title","Au format adresse email classique.");
			return true;
		}
	} else {
		return true;
	}
}

function emailsIdentical(objet1, objet2){
	email1 = objet1.val().toLowerCase();
	email2 = objet2.val().toLowerCase();
	if(email1 != email2 )	{
		erreur_saisie(objet1);
		return false;
	}	else {
		enlever_erreur(objet1);
		return true;
	}
}


//Déclencheurs lorsque l'utilisateur remplit les champs de texte
$("input[name=nom]").on("keyup",function(){
	nom_valide($(this));
});

$("input[name=prenom]").on("keyup",function(){
	nom_valide($(this));
});

$("input[name=pseudo]").on("keyup",function(){
	pseudo_valide($(this));
});

$("input[name=mdp]").on("keyup",function(){
	mdp_valide($(this));
});

$("input[name=mdp_new]").on("keyup",function(){
	if(mdp_valide($(this)) == true)
	{
		mdp_identiques($("input[name=mdp_new]"), $("input[name=mdp2_new]"))
	}
});

$("input[name=mdp2_new]").on("keyup",function(){
	if(mdp_valide($(this)) == true)
	{
		mdp_identiques($("input[name=mdp_new]"), $("input[name=mdp2_new]"))
	}
});

$("input[name=email]").on("keyup",function(event){
	var key = event.which || event.keyCode;
	//Tab Key
	if (key != 9){
		if(email_valide($(this)))	{
			emails_differents($("input[name=email]"), $("input[name=email_parent]"))
		}
	}
});

if($("input[name=email_parent]").length){
	$("input[name=email_parent]").on("keyup",function(event){
		var key = event.which || event.keyCode;
		//Tab Key
		if (key != 9){
			if(email_valide($(this)))
			{
				emails_differents($("input[name=email]"), $("input[name=email_parent]"))
			}
		}
	});
}

$("input[name=confirmEmail]").on("keyup",function(event){
	var key = event.which || event.keyCode;
	//Tab Key
	if (key != 9){
		emailsIdentical($(this), $("input[name=email]"));
	}
});

$("input[name=confirmEmailParent]").on("keyup",function(event){
	var key = event.which || event.keyCode;
	//Tab Key
	if (key != 9){
		emailsIdentical($(this), $("input[name=email_parent]"));
	}
});

$(".confirmEmails").on("click",function(event){
	var ok = 1;
	if($("#confirmationWindow span:eq(1)").is(":visible")){
		ok = ok * emailsIdentical($("input[name=confirmEmail]"), $("input[name=email]"));
	}
	if(ok != 0 && $("#confirmationWindow span:eq(3)").is(":visible")){
		ok = ok * emailsIdentical($("input[name=confirmEmailParent]"), $("input[name=email_parent]"));
	}
	if(ok != 0){
		hide_clip($("#confirmationWindow"));
		hide_clip($("#info_input"));
		$("#warningBlur").hide();
		showPage2();
	}
});



//Déclencheur lorsque l'utilisateur choisi sa classe (pour s'assurer qu'il l'a remplie)
/*
$("input[name=classe]").on("click",function(){
	classe = $(this).val();
}); */

//Passage d'une page à l'autre
$(".precedent").on("click", function(){
	if(landing_tab != "avatar"){
		$("#partie2").hide();
		$("#partie1").show();
	}
});

//Controle de tous les champs lorsque l'utilisateur clique sur "Suivant"
$(".suivant").on("click", function(){
	//On reformate les différents champs de texte
	$("input[name=nom]").val($("input[name=nom]").val().toUpperCase());
	$("input[name=prenom]").val($("input[name=prenom]").val().substr(0,1).toUpperCase() + $("input[name=prenom]").val().substr(1,$("input[name=prenom]").val().length).toLowerCase());
	$("input[name=email]").val($("input[name=email]").val().toLowerCase());
	if($("input[name=email_parent]").length){
		$("input[name=email_parent]").val($("input[name=email_parent]").val().toLowerCase());
	}

	//Vérification que tous les champs soit corrects (excepté les champs mot de passe)
	var ok = nom_valide($("input[name=nom]"));
	if(ok != 0){
		ok = ok * nom_valide($("input[name=prenom]"));
	}
	if(ok != 0){
		ok = ok * mdp_valide($("input[name=mdp]")) * mdp_valide($("input[name=mdp_new]")) * mdp_valide($("input[name=mdp2_new]")) * mdp_identiques($("input[name=mdp_new]"), $("input[name=mdp2_new]"));
	}
	if(ok != 0){
		ok = ok * departement_valide($("#departement"));
	}
	if(ok != 0){
		ok = ok * college_valide($("#departement"), $("#nomCollege"));
	}
	if(ok != 0){
		ok = ok * email_valide($("input[name=email]"));
	}
	if(ok != 0){
		ok = ok * email_parent_valide($("input[name=email_parent]"));
	}
	if(ok != 0){
		ok = ok * emails_differents($("input[name=email]"), $("input[name=email_parent]"));
	}
	if(ok !=0) {
		confirmEmail();
	} else {
		$("#wrong_info").dialog("open");
	}
});

//Controle de tous les champs lorsque l'utilisateur clique sur "Valider"
$("input[name=valider]").on("click",function(event){
	event.preventDefault();
	//On reformate les différents champs de texte
	$("input[name=pseudo]").val($("input[name=pseudo]").val().substr(0,1).toUpperCase() + $("input[name=pseudo]").val().substr(1,$("input[name=pseudo]").val().length).toLowerCase());
	//Vérification que tous les champs soit corrects
	var ok = pseudo_valide($("input[name=pseudo]"));
	if(ok !=0)
	{
		$("form").submit();
	}
	else if( $("input[name=pseudo]").val() == "")
	{
		$("#missing_info").dialog("open");
	}
	else
	{
		$("#wrong_info").dialog("open");
	}
});

$(".icone_form_droite").on("click",function(event){
	event.preventDefault();
	//On reformate les différents champs de texte
	$("input[name=pseudo]").val($("input[name=pseudo]").val().substr(0,1).toUpperCase() + $("input[name=pseudo]").val().substr(1,$("input[name=pseudo]").val().length).toLowerCase());
	//Vérification que tous les champs soit corrects
	var ok = pseudo_valide($("input[name=pseudo]"));
	if(ok !=0)
	{
		$("form").submit();
	}
	else if( $("input[name=pseudo]").val() == "")
	{
		$("#missing_info").dialog("open");
	}
	else
	{
		$("#wrong_info").dialog("open");
	}
});

//Fonctions utiles pour la génération de l'avatar
function texte_aleatoire(array) //Génère un texte aléatoire parmi un array de plusieurs string
{
	var taille = array.length;
	var rand = entier_aleatoire(0, taille - 1)
	return array[rand];
}

function entier_aleatoire(minimum, maximum) //Entier relatif aléatoire entre minimum et maximum
{
	return Math.floor(Math.random() * (maximum - minimum + 1 )) + minimum
}

function determiner_txt(partie, valeur)
{
	var modulo = valeur % 4;
	switch(partie)
	{
		case "cheveux" :
			if(modulo == 0)
				{return "blond";}
			if(modulo == 1)
				{return "roux";}
			if(modulo == 2)
				{return "brun";}
			if(modulo == 3)
				{return "noir";}
			break;
		case "yeux" :
			if(modulo == 0)
				{return "bleu";}
			if(modulo == 1)
				{return "vert";}
			if(modulo == 2)
				{return "marron";}
			if(modulo == 3)
				{return "noir";}
			break;
		case "peau" :
			if(modulo == 0)
				{return "occ";}
			if(modulo == 1)
				{return "asi";}
			if(modulo == 2)
				{return "met";}
			if(modulo == 3)
				{return "noi";}
			break;
	}
}

if($("#send_email_parent").length){
	$("#send_email_parent_loading").hide();
	$("#send_email_parent").on("click", function(){
		var ok = email_valide($("input[name=email]")) * email_parent_valide($("input[name=email_parent]"));
		if(ok != 0 && emails_differents($("input[name=email]"), $("input[name=email_parent]"))==1) {
			$("#send_email_parent").hide();
			$("#send_email_parent_loading").show();
			sendEmailParent();
		}
	})
}

function confirmEmail(){
	$("input[name=email]").val($("input[name=email]").val().toLowerCase());
	if(playerClasse == "Prof") {
		if(playerEmail == $("input[name=email]").val()){
			showPage2();
		} else {
			$("#confirmationWindow span:eq(0)").html("Confirmez votre email :");
			$("#confirmationWindow input:eq(0)").attr("title", "L'adresse doit correspondre à l'email que vous venez de renseigner.");
			$("#confirmationWindow span:eq(2)").hide();
			$("#confirmationWindow span:eq(3)").hide();
			$("#confirmationWindow").show();
			$("#warningBlur").show();
			if($("#confirmationWindow span:eq(1)").is(":visible")){
				$("#confirmationWindow span:eq(1) input").focus();
			} else {
				$("#confirmationWindow span:eq(3) input").focus();
			}
		}
	} else {
		$("input[name=email_parent]").val($("input[name=email_parent]").val().toLowerCase());
		if( (playerEmail == $("input[name=email]").val() || !$("input[name=email]").parent("p").is(":visible")) && playerEmail_parent == $("input[name=email_parent]").val()){
			showPage2();
		} else {
			if(playerEmail == $("input[name=email]").val() || !$("input[name=email]").parent("p").is(":visible")){
				$("#confirmationWindow span:eq(0)").hide();
				$("#confirmationWindow span:eq(1)").hide();
			}
			if(playerEmail_parent == $("input[name=email_parent]").val()) {
				$("#confirmationWindow span:eq(2)").hide();
				$("#confirmationWindow span:eq(3)").hide();
			}
			$("#confirmationWindow").show();
			$("#warningBlur").show();
			if($("#confirmationWindow span:eq(1)").is(":visible")){
				$("#confirmationWindow span:eq(1) input").focus();
			} else {
				$("#confirmationWindow span:eq(3) input").focus();
			}
		}
	}
}

function showPage2(){
	if(!$("input[name=email]").parent("p").is(":visible")){
		$("input[name=email]").val($("input[name=email_parent]").val());
	}
	$("#partie1").hide();
	$("#partie2").show();
	$("#info_input").hide();
	var x_avatar = $("#modele_avatar").offset().left - $(".fond:eq(0)").offset().left;
	var y_avatar = $("#modele_avatar").offset().top - $(".fond:eq(0)").offset().top;
	var haut_avatar = $("#modele_avatar").height();
	var larg_avatar = $("#modele_avatar").width();
	$("#icone_chargement").css("width", "50px").css("position", "absolute").css("top", y_avatar + 0.5*(haut_avatar - 50)).css("left", x_avatar + 0.5*(larg_avatar - 50));
}


function sendEmailParent(){
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'sendEmailParent='+ $("input[name=email_parent]").val() + '&newEmailPlayer='+ $("input[name=email]").val(),
		dataType: 'html',
		success: function(resultat, statut){
			if(resultat != "ok") {
				$("#msgEmailParent").switchClass("msg_conf", "msg_erreur").html("<span class='g'>Tu as déjà envoyé un email à tes parents aujourd'hui. Réessaie demain !</span>");
				var typeTemp = "TooMuchEmailsParent";
			}	else if(playerGameLimitation == 1){
				$("#msgEmailParent").switchClass("msg_erreur", "msg_conf").html("<span class='g'>Email envoyé à tes parents.<br></span>Tu disposeras à nouveau de la version complète du jeu dès qu'ils auront choisi un Pass Navadra.");
				var typeTemp = "InvitationToPay";
			} else {
				$("#msgEmailParent").switchClass("msg_erreur", "msg_conf").html("<span class='g'>Tes progrès ont été envoyé à tes parents par email (à "+ $("input[name=email_parent]").val() +").</span>");
				var typeTemp = "ReportProgress";
			}
			if(typeof(mixpanel) != "undefined") {
				mixpanel.track("EmailParentSentPlayer", {
					"type ":  typeTemp
				});
		  }
		},
		error: function(resultat, statut, erreur){
			$("#msgEmailParent").html("Oups, on dirait que l'envoi d'email n'a pas fonctionné... Réessaie d'envoyer le mail dans quelques instants.");
			$("#send_email_parent").show();
			console.log("Resultat : "+ resultat);
			console.log("Statut : "+ statut);
			console.log("Erreur : "+ erreur);
		},
		complete: function(resultat, statut){
			$("#send_email_parent_loading").hide();
		}
	});
}

$(function(){
	if(landing_tab == "avatar"){
		$("#partie1").hide();
		$("#info_input").hide();
		var x_avatar = $("#modele_avatar").offset().left - $(".fond:eq(0)").offset().left;
		var y_avatar = $("#modele_avatar").offset().top - $(".fond:eq(0)").offset().top;
		var haut_avatar = $("#modele_avatar").height();
		var larg_avatar = $("#modele_avatar").width();
		$("#icone_chargement").css("width", "50px").css("position", "absolute").css("top", y_avatar + 0.5*(haut_avatar - 50)).css("left", x_avatar + 0.5*(larg_avatar - 50));
	} else {
		$("#partie2").hide();
	}

	if($("input[name=email_parent]").length && $("input[name=email_parent]").val() == $("input[name=email]").val() && $("input[name=email_parent]").val() != ""){
		$("input[name=email]").parent("p").hide();
	}

	$(document).on("keydown", function (event) {
	  var key = event.which || event.keyCode;
		if (key == 13){ //Enter Key
			event.preventDefault();
			if($("#partie1").is(":visible") && !$("#confirmationWindow").is(":visible")){
				$(".suivant:eq(0)").click();
			} else if($("#confirmationWindow").is(":visible")){
				$(".confirmEmails:eq(0)").click();
			}	else if($("#partie2").is(":visible")){
				$("input[name=valider]").click();
			}
		}
	});

});
