// JavaScript Document

$(function(){

//Attribution de leurs caractéristiques aux widgets jQuery
$("#sexe").buttonset();

$("#classe").buttonset();


//Fonctions de contrôle du format des données
function nom_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[a-zàéèïîëçù'A-Z0-9 -@_]{3,30}$/);
	if(valeur_champ == "") //Si le champ est vide
	{
		enlever_erreur(objet);
		return true;
	}
	else if(ok == null) //Si le champ n'est pas valide
	{
		erreur_saisie(objet);
		return false;
	}
	else //Si le champ est valide
	{
		enlever_erreur(objet);
		return true;
	}
}

function pseudo_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[a-zàéèïîëçù'A-Z0-9 -@_]{3,15}$/);
	if(valeur_champ == "") //Si le champ est vide
	{
		enlever_erreur(objet);
		return true;
	}
	else if(ok == null) //Si le champ n'est pas valide
	{
		erreur_saisie(objet);
		return false;
	}
	else //Si le champ est valide
	{
		enlever_erreur(objet);
		return true;
	}
}

function cacher(objet)
{
	objet.effect("pulsate", {times: 2}, 1000, function(){
		objet.hide()
	});
}

function afficher(objet)
{
	objet.show();
	objet.effect("pulsate", {times: 2}, 1000);
}

function afficher_rapide(objet)
{
	objet.show("clip", 250);
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

//Controle de tous les champs lorsque l'utilisateur clique sur "Valider"
$("input[name=valider]").on("click",function(event){
	event.preventDefault();
	$("input[name=pseudo]").val($("input[name=pseudo]").val().substr(0,1).toUpperCase() + $("input[name=pseudo]").val().substr(1,$("input[name=pseudo]").val().length).toLowerCase());
	$("input[name=nom]").val($("input[name=nom]").val().toUpperCase());
	$("input[name=prenom]").val($("input[name=prenom]").val().substr(0,1).toUpperCase() + $("input[name=prenom]").val().substr(1,$("input[name=prenom]").val().length).toLowerCase());
	//Vérification que tous les champs soit corrects
	var ok = pseudo_valide($("input[name=pseudo]")) * nom_valide($("input[name=nom]")) * nom_valide($("input[name=prenom]"));
	if($("#departement").val() != "" && !departement_valide($("#departement"))){
		ok = 0;
	}
	if($("#nomCollege").val() != "" && !college_existant($("#nomCollege"))){
		ok = 0;
	}
	if(ok!=0)
	{
		$("form").submit();
	}
});

$(".icone_form_droite").on("click",function(event){
	event.preventDefault();
	$("input[name=pseudo]").val($("input[name=pseudo]").val().substr(0,1).toUpperCase() + $("input[name=pseudo]").val().substr(1,$("input[name=pseudo]").val().length).toLowerCase());
	$("input[name=nom]").val($("input[name=nom]").val().toUpperCase());
	$("input[name=prenom]").val($("input[name=prenom]").val().substr(0,1).toUpperCase() + $("input[name=prenom]").val().substr(1,$("input[name=prenom]").val().length).toLowerCase());
	//Vérification que tous les champs soit corrects
	var ok = pseudo_valide($("input[name=pseudo]")) * nom_valide($("input[name=nom]")) * nom_valide($("input[name=prenom]"));
	if($("#departement").val() != "" && !departement_valide($("#departement"))){
		ok = 0;
	}
	if($("#nomCollege").val() != "" && !college_existant($("#nomCollege"))){
		ok = 0;
	}
	if(ok!=0)
	{
		$("form").submit();
	}
});


//Comportement des lignes de joueurs qui se mettent en surbrillance et en gros quand on passe le curseur dessus
$(".corps_scroll div").on("mouseover",function(){
	mettre_en_evidence_ligne_scroll($(this));
});

$(".corps_scroll div").on("mouseout",function(){
	enlever_evidence_ligne_scroll($(this));
});

var id_contact ;
var id_joueur_actif = parseInt($("#id_joueur_actif").html());
$("#controles_contact").hide();
$(".corps_scroll div").on("click",function(e){
	$("#controles_contact").hide();
	var souris_x = e.pageX;
	var souris_y = e.pageY;
	$(".corps_scroll div").removeClass("fond_beige_clair");
	$(this).addClass("fond_beige_clair");
	id_contact = parseInt($(this).attr("id"));
	if(id_contact == id_joueur_actif)
	{
		$("#ajouter_contact").hide();
		$("#supprimer_contact").hide();
		$("#envoyer_message").hide();
	}
	else
	{
		$("#envoyer_message").show();
		if($(this).hasClass("ajouter"))
		{
			$("#ajouter_contact").show();
			$("#supprimer_contact").hide();
		}
		else if($(this).hasClass("supprimer"))
		{
			$("#ajouter_contact").hide();
			$("#supprimer_contact").show();
		}
	}
	$("#controles_contact").css("top", souris_y + 30).css("left", souris_x - 0.5*$("#controles_contact").width());
	afficher_rapide($("#controles_contact"));
});

//Comportement des images de la barre de contrôle qui apparait
$("#controles_contact img").css("cursor", "pointer");

$("#voir_contact").on("mouseover", function(){
	$(this).attr("src", "/webroot/img/icones/oeil_selec.png");
});

$("#voir_contact").on("mouseout", function(){
	$(this).attr("src", "/webroot/img/icones/oeil.png");
});

$("#voir_contact").on("click", function(){
	$(location).attr('href',"/app/controllers/profil.php?id="+id_contact);
});

$("#ajouter_contact").on("mouseover", function(){
	$(this).attr("src", "/webroot/img/icones/plus_selec.png");
});

$("#ajouter_contact").on("mouseout", function(){
	$(this).attr("src", "/webroot/img/icones/plus.png");
});

$("#ajouter_contact").on("click", function(){
	ajouter_contact();
});

$("#supprimer_contact").on("mouseover", function(){
	$(this).attr("src", "/webroot/img/icones/refuser_selec.png");
});

$("#supprimer_contact").on("mouseout", function(){
	$(this).attr("src", "/webroot/img/icones/refuser.png");
});

$("#supprimer_contact").on("click", function(){
	supprimer_contact();
});

$("#envoyer_message").on("mouseover", function(){
	$(this).attr("src", "/webroot/img/icones/messages_selec.png");
});

$("#envoyer_message").on("mouseout", function(){
	$(this).attr("src", "/webroot/img/icones/messages_normal.png");
});

$("#envoyer_message").on("click", function(){
	$(location).attr('href',"/app/controllers/messages.php?id="+id_contact);
});


function ajouter_contact()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_contact=' + id_contact,
		dataType: 'html',
		success: function(code_html, statut){
			//On utilise le code html sur notre page
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			$("#ajouter_contact").effect("pulsate", {easing : "swing"}, 250, function(){
					$("#ajouter_contact").hide();
					$("#"+id_contact).switchClass("ajouter", "supprimer");
					$("#supprimer_contact").show();
					$("#supprimer_contact").effect("pulsate", {easing : "swing"}, 250);
			});
		}
	});
}


function supprimer_contact()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_contact_suppr=' + id_contact,
		dataType: 'html',
		success: function(code_html, statut){
			//On utilise le code html sur notre page
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			$("#supprimer_contact").effect("pulsate", {easing : "swing"}, 250, function(){
					$("#supprimer_contact").hide();
					$("#"+id_contact).switchClass("supprimer", "ajouter");
					$("#ajouter_contact").show();
					$("#ajouter_contact").effect("pulsate", {easing : "swing"}, 250);
			});
		}
	});
}




});
