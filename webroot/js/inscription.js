var source = "unknown"; //For Mixpanel

$("#sexe").buttonset();

$("#classe").buttonset();

$("#no").button();

//$("#noProf").button().prop("checked", false).button("refresh");

$("#no").on("click", function(event){
	event.preventDefault();
	if($("input[name=]").parent("p").is(":visible")){
		$("#no").prop("checked", true).button("refresh");
		$("label[for='no']").html("<span class=\"ui-button-text\">J'ai une adresse </span>");
		hide_clip($("input[name=]").parent("p"));
	} else {
		$("#no").prop("checked", false).button("refresh");
		$("label[for='no']").html("<span class=\"ui-button-text\">Je n'ai pas d'adresse  !</span>");
		display_clip($("input[name=]").parent("p"));
	}
});

$("#confirmationWindow").hide();
$("#warningBlur").hide();
$("#closeConfirmationWindow").on("click", function(){
	hide_clip($("#confirmationWindow"));
	hide_clip($("#info_input"));
	$("#warningBlur").hide();
})

/*
$("#noProf").on("click", function(event){
	event.preventDefault();
	$("#noProfInfo").dialog("open");
}); */

$("#noCode").button();

$("#noCode").on("click", function(event){
	event.preventDefault();
	if($("input[name=codeClassroom]").parent("p").is(":visible")){
		$("#noCode").prop("checked", true).button("refresh");
		$("label[for='noCode']").html("<span class=\"ui-button-text\">J'ai le code de la classe</span>");
		hide_clip($("input[name=codeClassroom]").parent("p"));
	} else {
		$("#noCode").prop("checked", false).button("refresh");
		$("label[for='noCode']").html("<span class=\"ui-button-text\">Je n'ai pas de code de classe!</span>");
		display_clip($("input[name=codeClassroom]").parent("p"));
	}
});


$("#chevron1").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

$("#chevron2").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

$(".case_coul").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

if(sexe == ""){
	$("#chevron1").attr("title", "Il faudrait déjà savoir si tu es un gars ou une fille !");
	$("#chevron2").attr("title", "Il faudrait déjà savoir si tu es un gars ou une fille !");
	$(".case_coul").attr("title", "Il faudrait déjà savoir si tu es un gars ou une fille !");
}

$("#confirm_question_secrete").html("Tu n'as pas renseigné d'adresse  valide, on te propose donc de répondre à une question secrète pour pouvoir retrouver ton mot de passe si tu l'oublies. <br />Le plus sûr serait quand même de renseigner une adresse  mais on te laisse choisir.");
$("#confirm_question_secrete").dialog({
	autoOpen: false,
}).removeClass("cache");

$("#missing_info").dialog({
	autoOpen: false,
}).hide();
$("#missing_class").dialog({
	autoOpen: false,
}).hide();
$("#missing_sex").dialog({
	autoOpen: false,
}).hide();
$("#wrong_info").dialog({
	autoOpen: false,
}).hide();

$("#info_contact").dialog({
	autoOpen: false,
});

/*
$("#noProfInfo").dialog({
	autoOpen: false,
}); */


$("#bouton_contact").on("click", function(){
	$("#info_contact").dialog("open");
});

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


//Changement des parties du corps du personnage en cliquant sur les chevrons ou en choisissant le sexe du joueur
var modele = 0;
var cheveux = 0;
var yeux = 0;
var peau = 0;

//Détermination des paramètres d'initiation (variables sexe et classe ainsi que l'apparition ou pas de la div question_secrete
$("input[name=code_prof]").parent("p").hide();
$("input[name=email]").parent("p").hide();
if($("#fille").attr("checked") == "checked")
	{var sexe = "fille";}
else if($("#gars").attr("checked") == "checked")
	{var sexe = "gars";}
else
	{var sexe = "";}
if($("#6").attr("checked") == "checked")
	{var classe = "6°";}
else if($("#5").attr("checked") == "checked")
	{var classe = "5°";}
else if($("#4").attr("checked") == "checked")
	{var classe = "4°";}
else if($("#3").attr("checked") == "checked")
	{var classe = "3°";}
else if($("#teacher").attr("checked") == "checked"){
	var classe = "Prof";
	$("input[name=code_prof]").parent("p").show();
}
else
	{var classe = "";}
if(classe == "Prof"){
	$("#no").prop("checked", false).button("refresh");
	$("label[for='no']").hide();
	$("#noCode").prop("checked", false).button("refresh");
	$("label[for='noCode']").hide();
} else if(classe != "Prof"){
	//$("label[for='noProf']").hide();
}
if($("#no").prop("checked")){
	$("input[name=]").parent("p").hide();
}
$("input[name=codeClassroom]").parent("p").hide();

var modele_avatar = $("#modele_avatar").attr("src");
modele = 10000 - 1 + parseInt(modele_avatar.match(/(\d+)_\w+_\w+_\w+.png/)[1]);
switch(modele_avatar.match(/\d+_(\w+)_\w+_\w+.png/)[1]){
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
switch(modele_avatar.match(/\d+_\w+_(\w+)_\w+.png/)[1]){
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
switch(modele_avatar.match(/\d+_\w+_\w+_(\w+).png/)[1]){
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
	}
	cheveux_txt = determiner_txt("cheveux", cheveux);
	if(yeux == 0)
	{
		yeux = 100;
	}
	yeux_txt = determiner_txt("yeux", yeux);
	if(peau == 0)
	{
		peau = 100;
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

$("input[name=sexe]").on("click",function(){
	sexe = $(this).val();
	$("#chevron1").removeAttr("title");
	$("#chevron2").removeAttr("title");
	$(".case_coul").removeAttr("title");
	changer_modele();
});


//Fonctions de contrôle du format des données
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
	var ok = valeur_champ.match(/^[\wàéèïîëçù.!?_-]{6,30}$/);
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

function mdp_identiques(objet1, objet2){
	mdp1 = objet1.val();
	mdp2 = objet2.val();
	if(mdp1 != "" && mdp2 != "" && mdp1 != mdp2)
	{
		erreur_saisie(objet1);
		erreur_saisie(objet2);
		objet1.attr("title","Les mots de passe doivent être identiques.");
		objet2.attr("title","Les mots de passe doivent être identiques.");
		return false;
	}
	else if (mdp1 == "" || mdp2 == "")
	{
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

function code_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[A-Z0-9]{4}$/);
	var erreur = ok == null && $("input[name=codeClassroom]").is(':visible');
	if (erreur) {
		erreur_saisie(objet);
		return false;
	}
	else {
		enlever_erreur(objet);
		return true;
	}
}

function _valide(objet){
	valeur_champ = objet.val();
	if(classe != "Prof") {
		var ok = regex(valeur_champ, true);
	} else {
		var ok = regex(valeur_champ, false);
	}
	if(ok !== true && $("input[name=]").is(':visible'))
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

/*
function s_differents(objet1, objet2){
	1 = objet1.val();
	2 = objet2.val();
	if(1 != "" && 2 != "" && 1 == 2)
	{
		erreur_saisie(objet1);
		erreur_saisie(objet2);
		objet1.attr("title","Les 2 adresses s doivent être différentes !");
		objet2.attr("title","Les 2 adresses s doivent être différentes !");
		return false;
	}
	else
	{
		enlever_erreur(objet1);
		enlever_erreur(objet2);
		objet1.attr("title","Au format adresse  classique.");
		objet2.attr("title","Au format adresse  classique. Facultatif pour l'instant : tu auras 15 jours pour la renseigner.");
		return true;
	}
}

function sIdentical(objet1, objet2){
	1 = objet1.val().toLowerCase();
	2 = objet2.val().toLowerCase();
	if(1 != 2 )	{
		erreur_saisie(objet1);
		return false;
	}	else {
		enlever_erreur(objet1);
		return true;
	}
} */

function rep_secrete_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[a-zàéèïîëçù'A-Z -]{3,30}$/);
	if(ok == null && valeur_champ!= "")
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

function radio_choisi(nom){
	switch(nom)
	{
		case "sexe" :
			if(sexe=="")
			{
				//$("input[name=sexe]:first").click();
				return false;
			}
			else
			{
				return true;
			}
			break;
		case "classe" :
			if(classe=="")
			{
				//$("input[name=classe]:first").click();
				return false;
			}
			else
			{
				return true;
			}
			break;
	}
}

//Déclencheurs lorsque l'utilisateur remplit les champs de texte
$("input[name=pseudo]").on("keyup",function(){
	pseudo_valide($(this));
});

$("input[name=mdp]").on("keyup",function(){
	mdp_valide($(this));
});

/*
$("input[name=]").on("keyup",function(){
	var key = event.which || event.keyCode;
	//Tab Key
	if (key != 9){
		_valide($(this))
	}
}); */

$("input[name=codeClassroom]").on("keyup",function(){
	code_valide($(this))
});

$("input[name=confirm]").on("keyup",function(event){
	var key = event.which || event.keyCode;
	//Tab Key
	if (key != 9){
		sIdentical($(this), $("input[name=]"));
	}
});

/*
$(".confirms").on("click",function(event){
	var ok = sIdentical($("input[name=confirm]"), $("input[name=]"));
	if(ok != 0){
		hide_clip($("#confirmationWindow"));
		hide_clip($("#info_input"));
		$("#warningBlur").hide();
		showPage2();
	}
}); */


//Déclencheur lorsque l'utilisateur choisi sa classe (pour s'assurer qu'il l'a remplie)
$("input[name=classe]").on("click",function(){
	classe = $(this).val();
	if(classe == "Prof"){
		$("input[name=code_prof]").parent("p").show();
	} else {
		$("input[name=code_prof]").parent("p").hide();
	}
});

$("#partie2").hide();
//Passage d'une page à l'autre
$(".precedent").on("click", function(){
	$("#partie2").hide();
	$("#partie1").show();
});

//Controle de tous les champs lorsque l'utilisateur clique sur "Suivant" et affichage question secrète si pas d'
$(".suivant").on("click", function(){
	nextStep();
});


//Controle de tous les champs lorsque l'utilisateur clique sur "Valider"
$("input[name=valider]").on("click",function(event){
	event.preventDefault();
	nextStep();
});

$(".icone_form_droite").on("click",function(event){
	event.preventDefault();
	nextStep();
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

function nextStep(){
	if($("#partie1").is(":visible")){
		//On reformate les différents champs de texte
		$("input[name=pseudo]").val($("input[name=pseudo]").val().substr(0,1).toUpperCase() + $("input[name=pseudo]").val().substr(1,$("input[name=pseudo]").val().length).toLowerCase());
		$("input[name=email]").val($("input[name=email]").val().toLowerCase());
		var ok = 1;
		if(!pseudo_valide($("input[name=pseudo]"))){
			ok = 0;
		} else if (!mdp_valide($("input[name=mdp]"))) {
			ok = 0;
		} else if (!code_valide($("input[name=codeClassroom]"))) {
			ok = 0;
		} else if (classe == "") {
			ok = 0;
		}
		if(ok !=0) {
			confirm();
		}	else if( $("input[name=pseudo]").val() == ""
				|| $("input[name=mdp]").val() == ""
				|| ($("input[name=codeClassroom]").val() == "" && !$('#noCode').is(':checked')) )	{
			$("#missing_info").dialog("open");
		}	else if (!radio_choisi("classe"))	{
			$("#missing_class").dialog("open");
		}	else{
			$("#wrong_info").dialog("open");
		}
	} else if($("#partie2").is(":visible")){
		var ok = radio_choisi("sexe");
		if(ok==true) {
			$("form").submit();
		}	else if (!radio_choisi("sexe"))	{
			$("#missing_sex").dialog("open");
		}	else {
			$("#wrong_info").dialog("open");
		}
	}
}

$("#loading").hide();
function sendReminder(){
	$("#mobileDisplay .label").hide();
	$("#mobileDisplay .input").hide();
	$("#loading").show();
	$.ajax({
			url: '/app/controllers/ajax_disconnected.php',
			type :'POST',
			data: 'sendReminder=' + $("#mobileDisplay input[name=Reminder]").val(),
			dataType: 'html',
			success: function(result, statut){
				if(result == "Sent"){
					$("#loading").hide();
					$("#confirmReminder").removeClass("rouge vert").addClass("vert").html(" envoyé !");
					$(".sendReminder").hide();
				} else if(result == "wrong"){
					$("#loading").hide();
					$("#confirmReminder").removeClass("rouge vert").addClass("rouge").html("Désolé mais cet  n'est pas valide.");
				}
			},
			error: function(resultat, statut, erreur){
				$("#loading").hide();
				$("#confirmReminder").removeClass("rouge vert").addClass("rouge").html("Désolé, problème de connexion Internet !<br>Réessaie dans quelques instants...");
			},
			complete: function(resultat, statut){
			}
	});
}

$(".sendReminder").on("click",function(event){
	event.preventDefault();
	if(_valide($("input[name=]"))){
		sendReminder();
	}
});

$(document).on("keydown", function (event) {
	var key = event.which || event.keyCode;
	//Enter Key
	if (key == 13){
		event.preventDefault();
		if($("#partie1").is(":visible") && !$("#confirmationWindow").is(":visible")){
			$(".suivant:eq(0)").click();
		} else if($("#confirmationWindow").is(":visible")){
			$(".confirms:eq(0)").click();
		}	else if($("#partie2").is(":visible")){
			$("input[name=valider]").click();
		}
	}
});

function confirm(){
	showPage2();
	/*
	$("input[name=]").val($("input[name=]").val().toLowerCase());
	if(classe == "Prof") {
		$("#confirmationWindow span:eq(0)").html("Confirmez votre  :");
		$("#confirmationWindow input").attr("title", "L'adresse doit correspondre à l' que vous venez de renseigner.");
	}
	if( !$("input[name=]").parent("p").is(":visible") ){
			showPage2();
	} else {
		$("#confirmationWindow").show();
		$("#warningBlur").show();
		$("#confirmationWindow span:eq(1) input").focus();
	}
	*/
}

function showPage2(){
	$("#partie1").hide();
	$("#partie2").show();
	$("#info_input").hide();
	var x_avatar = $("#modele_avatar").offset().left - $(".fond:eq(0)").offset().left;
	var y_avatar = $("#modele_avatar").offset().top - $(".fond:eq(0)").offset().top;
	var haut_avatar = $("#modele_avatar").height();
	var larg_avatar = $("#modele_avatar").width();
	$("#icone_chargement").css("width", "50px").css("position", "absolute").css("top", y_avatar + 0.5*(haut_avatar - 50)).css("left", x_avatar + 0.5*(larg_avatar - 50));
}

/*
if(typeof(mixpanel) != "undefined"){
	if(mixpanel.get_property('utm_source') != "-" && mixpanel.get_property('utm_campaign') != "-"){
		mixpanel.track("signUp");
	}
}*/
