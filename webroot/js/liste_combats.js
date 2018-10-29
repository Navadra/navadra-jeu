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

var id_combat = 0;
var nb_combats = parseInt($("#nb_combats_restants").html());
var prets = "";

	
//Comportement des lignes de joueurs qui se mettent en surbrillance et en gros quand on passe le curseur dessus
$(".corps_scroll div").on("mouseover",function(){
	mettre_en_evidence_ligne_scroll($(this));
});

$(".corps_scroll div").on("mouseout",function(){
	enlever_evidence_ligne_scroll($(this));
});


//Comportement des boites de dialogue
$("#plus_assez_combats").dialog({
	autoOpen: false,
	resizable: false
});

$("#participer_combat").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Participer": function(){
			$(this).dialog("close");
			$("#accepter_"+ id_combat).switchClass("cliquable", "neutre").unbind("mouseover mouseout click").css("height", "30px");
			participer_combat();
		},
		"Laisse moi réfléchir": function(){
			$(this).dialog("close");
		}
	}
});

$("#refuser_combat").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Refuser": function(){
			$(this).dialog("close");
			refuser_combat();
		},
		"Laisse moi réfléchir": function(){
			$(this).dialog("close");
		}
	}
});



//Comportement des boutons accepter/refuser
$(".cliquable").on("mouseover", function(){
	$(this).css("height", "40px");
});

$(".cliquable").on("mouseout", function(){
	$(this).css("height", "30px");
});

$(".accepter").on("click", function(event){
	event.preventDefault();
	/*
	if(nb_combats == 0)
	{
		$("#plus_assez_combats").html("Tu as déjà fais tes 7 combats journaliers, reviens demain !");
		$("#plus_assez_combats").dialog("open");
	}
	else if(nb_combats > 0)
	{
		var nb_combats_apres = nb_combats - 1;
		if(nb_combats_apres == 0)
			{$("#participer_combat").html("Ça comptera comme un des combats que tu as le droit de faire aujourd'hui.<br>Il ne te restera plus de combats pour ce jour.");}
		else if(nb_combats_apres == 1)
			{$("#participer_combat").html("Ça comptera comme un des combats que tu as le droit de faire aujourd'hui.<br>Il te restera " + nb_combats_apres + " combat pour ce jour.");}
		else
			{$("#participer_combat").html("Ça comptera comme un des combats que tu as le droit de faire aujourd'hui.<br>Il te restera " + nb_combats_apres + " combats pour ce jour.");}
		$("#participer_combat").dialog("open");
	} */
	id_combat = parseInt($(this).parent().parents().attr("id"));
	$("#accepter_"+ id_combat).switchClass("cliquable", "neutre").unbind("mouseover mouseout click").css("height", "30px");
	participer_combat();	
});

$(".refuser").on("click", function(event){
	event.preventDefault();
	id_combat = parseInt($(this).parent().parents().attr("id"));
	$("#refuser_combat").html("Tu ne pourras plus changer d'avis par la suite sauf si l'organisateur te réinvite.");
	$("#refuser_combat").dialog("open");	
});


//Fonctions pour traiter la participation / le refus d'un combat
function participer_combat()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_participer=' + id_combat,
		dataType: 'html',
		success: function(code_html, statut){
			prets = code_html;
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			$("#" + id_combat).removeClass("fond_beige_clair", 500, "swing"); //On enleve la surbrillance sur la ligne
			/*
			nb_combats --;
			$("#info_combats").html("(Combats restants aujourd'hui : " + nb_combats + ")");
			if(nb_combats == 0)
				{$("#info_combats").switchClass("gris", "rouge");}
			*/
			$("#refuser_"+ id_combat).hide("explode", "swing", 500, function(){
				diminuer_notif();
				if(prets == "prets")
				{
					$("#prets_"+ id_combat).hide("pulsate", "swing", 500, function(){
						$("#prets_"+ id_combat).attr("src", "/webroot/img/icones/check.png");
						$("#prets_"+ id_combat).show("pulsate", 500);
					});
				}
			});
		}
	});
}


function refuser_combat()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_refuser=' + id_combat,
		dataType: 'html',
		success: function(code_html, statut){
			prets = code_html;
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			$("#" + id_combat).hide("blind", "swing", 500, function(){ //On cache la ligne entière
				diminuer_notif();
			});
		}
	});
}


function diminuer_notif()
{
	var notif = parseInt($("#notif_combats").html()) - 1;
	if(notif==0)
	{
		$("#notif_combats").remove();
		$("#notif_combats_ico").remove();
	}
	else
	{
		$("#notif_combats").html(notif);
	}
}




});