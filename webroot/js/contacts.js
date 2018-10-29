// JavaScript Document

$(function(){

//Variables de couleurs
var gris = "#666";
var rouge = "#FF0000";
var bleu = "#0080FF";
var jaune = "#B7B700";
var vert = "#00A452";
var violet = "#7171FF";
var violet_clair = "#AAF";
var blanc = "#FFF";

var id_contact ;
if($("#id_monstre")){var id_monstre = parseInt($("#id_monstre").html());}
var nbre_chasseurs = nbre_contacts_invites() + 1;

function afficher_rapide(objet)
{
	objet.show("clip", 250);
}

	
//Comportement des lignes de joueurs qui se mettent en surbrillance et en gros quand on passe le curseur dessus
$(".corps_scroll div").on("mouseover",function(){
	mettre_en_evidence_ligne_scroll($(this));
});

$(".corps_scroll div").on("mouseout",function(){
	enlever_evidence_ligne_scroll($(this));
});


$("#controles_contact").hide();
$(".corps_scroll div").on("click",function(e){
	if(cacher_controles == false)
	{
		$("#controles_contact").hide();
		var souris_x = e.pageX;
		var souris_y = e.pageY;
		$(".corps_scroll div").removeClass("fond_beige_clair");
		$(this).addClass("fond_beige_clair");
		id_contact = parseInt($(this).attr("id"));
		$("#envoyer_message").show();
		if($("#"+id_contact+"_icone").hasClass("inviter"))
		{
			$("#ajouter_contact").show();
			$("#supprimer_contact").hide();
		}
		else if($("#"+id_contact+"_icone").hasClass("deja_invite"))
		{
			$("#ajouter_contact").hide();
			$("#supprimer_contact").show();
		}
		else if($("#"+id_contact+"_icone").hasClass("suppr"))
		{
			$("#ajouter_contact").hide();
			$("#supprimer_contact").show();
		}
		$("#controles_contact").css("top", souris_y + 30).css("left", souris_x - 0.5*$("#controles_contact").width());
		afficher_rapide($("#controles_contact"));
	}
	else
	{
		$("#controles_contact").hide();
	}
});

$(".suppr").on("click", function(event){
	event.preventDefault();
	cacher_controles = true;
	id_contact  = parseInt($(this).attr("id"));
	var pseudo = $("#"+id_contact+"_pseudo").html();
	$("#confirm_suppr").html("Veux-tu vraiment supprimer " + pseudo +" de tes contacts ? Tu pourras toujours le rajouter par la suite.");
	$("#confirm_suppr").dialog("open");
});

var cacher_controles = false;
$(".inviter").on("click", function(event){
	event.preventDefault();
	cacher_controles = true;
	id_contact  = parseInt($(this).attr("id"));
	if($(this).hasClass("inviter"))
	{
		inviter_contact();
	}
	else
	{
		annuler_invitation();
	}
});

$(".deja_invite").on("click", function(event){
	event.preventDefault();
	cacher_controles = true;
	id_contact  = parseInt($(this).attr("id"));
	if($(this).hasClass("inviter"))
	{
		inviter_contact();
	}
	else
	{
		annuler_invitation();
	}
	$("#controles_contact").hide();
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
	inviter_contact();
});

$("#supprimer_contact").on("mouseover", function(){
	$(this).attr("src", "/webroot/img/icones/refuser_selec.png");
});

$("#supprimer_contact").on("mouseout", function(){
	$(this).attr("src", "/webroot/img/icones/refuser.png");
});

$("#supprimer_contact").on("click", function(){
	if($("#"+id_contact+"_icone").hasClass("suppr"))
	{
		var pseudo = $("#"+id_contact+"_pseudo").html();
		$("#controles_contact").hide();
		$("#confirm_suppr").html("Veux-tu vraiment supprimer " + pseudo +" de tes contacts ? Tu pourras toujours le rajouter par la suite.");
		$("#confirm_suppr").dialog("open");
	}
	else if($("#"+id_contact+"_icone").hasClass("suppr"))
	{
		annuler_invitation();
	}
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

//Dialogue et fonction pour supprimer

$("#confirm_suppr").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Supprimer": function(){
			$(this).dialog("close");
			cacher_controles = false;
			supprimer_contact();
		},
		"Laisse moi réfléchir": function(){
			$(this).dialog("close");
			cacher_controles = false;
		}
	}
});

$("#trop_chasseurs").dialog({
	autoOpen: false,
	resizable: false,
});

function supprimer_contact()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_contact_suppr=' +  id_contact ,
		dataType: 'html',
		success: function(code_html, statut){
			//On utilise le code html sur notre page
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			$("#" + id_contact+"_contact").hide("blind", 500); //On cache la ligne une fois le contact supprimé
		}
	});
}


function inviter_contact()
{
	if(nbre_chasseurs < 12)
	{
		$.ajax({
			url: '/app/controllers/ajax.php',
			type :'POST',
			data: 'id_monstre=' + id_monstre + '&id_contact_inviter=' + id_contact ,
			dataType: 'html',
			success: function(code_html, statut){
				//On utilise le code html sur notre page
			},
			error: function(resultat, statut, erreur){
				//Erreur est une chaine de caractère à afficher au joueur
	
			},
			complete: function(resultat, statut){
				nbre_chasseurs ++;
				$("#"+id_contact+"_icone").hide("explode", "swing", 500, function(){
					$("#"+id_contact+"_icone").attr("src", "/webroot/img/icones/check.png");
					$("#"+id_contact+"_icone").switchClass("inviter", "deja_invite");
					$("#"+id_contact+"_icone").show("explode", 500);
					cacher_controles = false;
				});
				
				$("#ajouter_contact").effect("pulsate", {easing : "swing"}, 250, function(){
					$("#ajouter_contact").hide();
					$("#supprimer_contact").show();
					$("#supprimer_contact").effect("pulsate", {easing : "swing"}, 250);
				});
			}
		});
	}
	else
	{
		$("#trop_chasseurs").html("Tu ne crois pas que 12 chasseurs c'est suffisant pour venir à bout d'un monstre ?<br>Faudrait pas exagérer non plus !");
		$("#trop_chasseurs").dialog("open");
	}
}

function annuler_invitation()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_monstre=' + id_monstre + '&id_combattant_suppr=' + id_contact + '&msg=non',
		dataType: 'html',
		success: function(code_html, statut){
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			nbre_chasseurs --;
			$("#"+id_contact+"_icone").hide("explode", "swing", 500, function(){
				$("#"+id_contact+"_icone").attr("src", "/webroot/img/icones/plus.png");
				$("#"+id_contact+"_icone").switchClass("deja_invite", "inviter");
				$("#"+id_contact+"_icone").show("explode", 500);
				cacher_controles = false;
			});
				
			$("#supprimer_contact").effect("pulsate", {easing : "swing"}, 250, function(){
				$("#supprimer_contact").hide();
				$("#ajouter_contact").show();
				$("#ajouter_contact").effect("pulsate", {easing : "swing"}, 250);
			});
		}
	});
}

function nbre_contacts_invites()
{
	var nbre_contacts = 0;
	$(".deja_invite").each(function(){
		nbre_contacts ++;
	});
	return nbre_contacts;
}


});