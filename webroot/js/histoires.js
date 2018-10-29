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
var couleur_fond = "#ffe4bd";
var bordure_fond = "#e5c89c";

//Ajuster la vidéo au chargement pour qu'elle occupe toute la div consacrée
var largeur = $(".corps_scroll2:eq(0)").width();
var hauteur = $(".corps_scroll2:eq(0)").height();

//Au chargement on affiche par défaut la vidéo d'introduction
var titre = $(".corps_scroll span:eq(1)").html();
var lien = $(".corps_scroll span:eq(2)").html();
$(".entetes_scroll:eq(1) span").html(titre);

$('<iframe id="video" width="' + largeur + '" height="' + hauteur + '" frameborder="0" src="' + lien + '" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').appendTo(".corps_scroll2:eq(0)");
	
//Comportement des lignes de sorts qui se mettent en surbrillance et en gros quand on passe le curseur dessus
$(".corps_scroll div").on("mouseover",function(){
	mettre_en_evidence_ligne_scroll($(this));
});

$(".corps_scroll div").on("mouseout",function(){
	enlever_evidence_ligne_scroll($(this));
});

$(".corps_scroll div").on("click",function(){
	if(!$(this).hasClass("p0")) //Si c'est une div cliquable on supprimer la vidéo et recharche la nouvelle puis on la considère comme lue
	{
		$("#video").remove();
		titre = $(this).children("span:eq(1)").html();
		lien = $(this).children("span:eq(2)").html();
		var id_histoire = $(this).children("span:eq(3)").html();
		$(".entetes_scroll:eq(1) span").html(titre);
		
		$('<iframe id="video" width="' + largeur + '" height="' + hauteur + '" src="' + lien + '" frameborder="0" allowfullscreen></iframe>').appendTo(".corps_scroll2:eq(0)");
		
		if($(this).hasClass("fond_beige_clair")) //Si c'était une histoire non lu enlève le surlignage et la notif
		{
			$(this).removeClass("fond_beige_clair");
			var notif = parseInt($("#notif_histoires").html()) - 1;
			if(notif==0)
			{
				$("#notif_histoires").remove();
				$("#notif_histoires_ico").remove();
			}
			else
				{$("#notif_histoires").html(notif);}
		}
		
		$.ajax({
			url: '/app/controllers/ajax.php',
			type :'POST',
			data: 'id_histoire=' + id_histoire,
			dataType: 'html',
			success: function(code_html, statut){
				//On utilise le code html sur notre page
			},
			error: function(resultat, statut, erreur){
				//Erreur est une chaine de caractère à afficher au joueur
			},
			complete: function(resultat, statut){
				//Pas très intéressant
			}
		});
	}
});


//Infobulle pour notifier le joueur du niveau nécessaire pour débloquer l'histoire
$('<span id="info_bulle"></span>').appendTo("body");
$("#info_bulle").css("text-align", "center").css("background-color", couleur_fond).css("border","2px solid " + bordure_fond).css("padding", "5px 10px").css("-moz-border-radius", "10px").css("-moz-border-radius", "10px").css("-webkit-border-radius", "10px").css("border-radius", "10px");

$("#info_bulle").hide();

$(".bloquees").on("mouseover", function(){
	var nom = $(this).children("span:eq(0)").html();
	if(nom != "Introduction")
	{
		var num_chapitre = parseInt(nom.substr(9));
		if(num_chapitre <= 5)
			{var info = "Niveau " + (num_chapitre)*4 + " requis";}
		else if(num_chapitre > 5)
			{var info = "Niveau " + (num_chapitre-1)*5 + " requis";}
	}
	var position = $(this).offset();
	$('#info_bulle').html(info);
	$("#info_bulle").css("position", "absolute").css("top", position.top + 40).css("left", position.left + 0.5*$(this).width() - 0.5*$("#info_bulle").width());
	$("#info_bulle").show();
});

$(".bloquees").on("mouseout", function(){
	$("#info_bulle").hide();
});

function afficher(objet)
{
	objet.show("clip", 500);
}

function disparaitre(objet)
{
	objet.hide("clip", 500);
}






});