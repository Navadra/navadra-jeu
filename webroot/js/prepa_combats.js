//Variables
var id_contact_select = 0;
var id_combattant_suppr = 0;
var id_monstre = parseInt($("#id_monstre").html());
var id_joueur = parseInt($("#id_joueur").html());
var id_combat = parseInt($("#id_combat").html());
var gain_prestige = "";
var perte_prestige = "";
var nb_combats = parseInt($("#nb_combats_restants").html());
nb_combats = 10;
var selectedPlayers = [];
$("#list_fighting_players .ligne_scroll").each(function(index, element){
	selectedPlayers.push(parseInt($(this).attr("id")));
});

$(function(){

//Gestion de l'affichage du profil élémentaire
$("#tableau_profil").hide();
$("#tableau_profil").removeClass('cache');
var position = $("#monstre").offset();
$("#tableau_profil").css("top", position.top + 200).css("left", position.left + 0.5*$("#monstre").width() - 0.5*$("#tableau_profil").width());

$("#tabs_fight_preparation").tabs();
$("#tabs_fight_preparation").css("position", "absolute").css("top", "5%").css("left", "10%").css("z-index", "99");
$("#tabs_fight_preparation").hide();
select_potential_players();
activate_line_players();

if($(".team_portraits").length)
{
	$(".team_portraits").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});
}

//Highlight player lines
$(".potentialPartner").on("mouseover",function(){
	if(!$(this).hasClass("fond_beige_clair")){
		mettre_en_evidence_ligne_scroll($(this));
	}
});

$(".potentialPartner").on("mouseout",function(){
	if(!$(this).hasClass("fond_beige_clair")){
		enlever_evidence_ligne_scroll($(this));
	}
});

//Select players lines
function select_potential_players(){
	$(".potentialPartner").removeClass("fond_beige_clair");
	$.each(selectedPlayers, function(index, value){
		$("#contact_"+value).addClass("fond_beige_clair").css("cursor", "pointer");
		$("#research_"+value).addClass("fond_beige_clair").css("cursor", "pointer");
	});
}

function select_focus_player(object){
	$("#list_fighting_players .ligne_scroll").removeClass("fond_beige_clair");
	if(object != undefined){
		object.addClass("fond_beige_clair");
	}
}

//Selecting players to join the fight
$(".potentialPartner").on("click",function(){
	var idTemp = parseInt($(this).attr("id").match(/\w+_(\d+)/)[1]);
	if(selectedPlayers.indexOf(idTemp) != -1){
		var indexTemp = selectedPlayers.indexOf(idTemp);
		selectedPlayers.splice(indexTemp, 1);
	} else {
		selectedPlayers.push(idTemp);
		enlever_evidence_ligne_scroll($(this));
		$(this).css("cursor", "pointer");
	}
	select_potential_players();
});

$(".back").on("click", function(){
	selectedPlayers = [];
	$("#list_fighting_players .ligne_scroll").each(function(index, element){
		selectedPlayers.push(parseInt($(this).attr("id")));
	});
	select_potential_players();
	$("#tabs_fight_preparation").hide();
});

$(".invite_players").on("click", function(){
	var selectedPanel = $("#tabs_fight_preparation div.ui-tabs-panel[aria-hidden='false']")[0].id;
	if(selectedPanel == "automatic"){
		selectedPlayers = [playerId];
		var nb_automatically_invited = parseInt($("#nb_automatically_invited").val());
		for(var i=0;i<nb_automatically_invited;i++){
			var newId = $("#research_list .potentialPartner:eq("+i+")").attr("id");
			newId = parseInt(newId.match(/\w+_(\d+)/)[1]);
			selectedPlayers.push(newId);
		};
		select_potential_players();
	}
	invite_contact();
});

$(".open_invite").on("click", function(){
	$("#tabs_fight_preparation").show();
});



//Affichage du bonus/malus du joueur en survolant le monstre
$("#monstre").on("mouseover", function(){
	$(this).css("cursor", "pointer");
	afficher($("#tableau_profil"));
});

$("#monstre").on("mouseout", function(){
	$("#tableau_profil").hide();
});


function afficher(objet){
	objet.show("clip", 500);
}

function disparaitre(objet){
	objet.hide("clip", 500);
}

//Comportement des lignes de joueurs qui se mettent en surbrillance et en gros quand on passe le curseur dessus
function activate_line_players(){
	$("#list_fighting_players .ligne_scroll").on("mouseover",function(){
		mettre_en_evidence_ligne_scroll($(this));
	});

	$("#list_fighting_players .ligne_scroll").on("mouseout",function(){
		enlever_evidence_ligne_scroll($(this));
	});

	//Comportement de sélection de ligne
	$("#list_fighting_players .ligne_scroll").on("click",function(){
		if(id_contact_select != parseInt($(this).attr("id"))) //Si ce n'est pas déjà le joueur sélectionné
		{
			id_contact_select = parseInt($(this).attr("id"));
			$("#voir_profil" ).attr("href", "/app/controllers/profil.php?id=" + id_contact_select);
			select_focus_player($(this));
		}
		else //Si le joueur est déjà sélectionné
		{
			id_contact_select = 0;
			$("#voir_profil" ).attr("href", "#");
			select_focus_player();
		}
	});


	//Comportement de l'image suppr
	$(".suppr").on("mouseover", function(){
		$(this).css("height", "30px");
	});

	$(".suppr").on("mouseout", function(){
		$(this).css("height", "20px");
	});


	//Mécanisme de suppression d'un invité
	$(".suppr").on("click", function(){
		id_combattant_suppr = parseInt($(this).parent().parents().attr("id"));
		supprimer_contact(false);
	});

	$("#confirm_suppr").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
			"Supprimer": function(){
				$(this).dialog("close");
				supprimer_contact(true);
			},
			"Laisse moi réfléchir": function(){
				$(this).dialog("close");
			}
		}
	});
}


function invite_contact()
{
	if(selectedPlayers.length <= 12)
	{
		$.ajax({
			url: '/app/controllers/ajax.php',
			type :'POST',
			data: 'id_monstre=' + id_monstre + '&id_invited_players=' + JSON.stringify(selectedPlayers),
			dataType: 'html',
			success: function(code_html, statut){
				$("#list_fighting_players").html(code_html);
			},
			error: function(resultat, statut, erreur){
				//Erreur est une chaine de caractère à afficher au joueur
			},
			complete: function(resultat, statut){
				activate_line_players();
				$("#tabs_fight_preparation").hide();
				$("#gain_prestige").html('+'+$("#prestigeEarned").html()+'<br /><img class="img_20" src="/webroot/img/icones/prestige.png"/>').effect("pulsate", 500);
				$("#perte_prestige").html($("#prestigeLost").html()+'<br /><img class="img_20" src="/webroot/img/icones/prestige.png"/>').effect("pulsate", 500);
				var chasseurs_prets = tous_prets();
				if(chasseurs_prets == true && nbre_chasseurs_restants() == 1) //On enleve une notif si l'orga passe d'un combat à 2 personnes prêtes à un combat tout seul
				{
					diminuer_notif();
				}
				nb_chasseurs = selectedPlayers.length;
				if(nb_chasseurs == 1)
				{
					var nb_chasseur_txt = "1 chasseur de monstres";
					$(".spell_choice:eq(0)").removeClass("cache");
					$(".spell_choice:eq(1)").removeClass("cache");
				}
				else
				{
					var nb_chasseur_txt = nb_chasseurs + " chasseurs de monstres";
				}
				$("#nb_chasseurs").hide("pulsate", "swing", 500, function(){
					$("#nb_chasseurs").html(nb_chasseur_txt);
					$("#nb_chasseurs").show("pulsate", 500);
				});
			}
		});
	}
	else
	{
		$("#too_many_players").html("Tu ne crois pas que 12 chasseurs c'est suffisant pour venir à bout d'un monstre ?<br>Faudrait pas exagérer non plus !");
		$("#too_many_players").dialog("open");
	}
}

function supprimer_contact(msg)
{
	if(selectedPlayers.indexOf(id_combattant_suppr) != -1){
		if(msg)	{
			var data_ajax = 'id_monstre=' + id_monstre + '&id_combattant_suppr=' + id_combattant_suppr + '&msg=' + "oui";
		}	else	{
			var data_ajax = 'id_monstre=' + id_monstre + '&id_combattant_suppr=' + id_combattant_suppr + '&msg=' + "non";
		}
		$.ajax({
			url: '/app/controllers/ajax.php',
			type :'POST',
			data: data_ajax,
			dataType: 'html',
			success: function(code_html, statut){
				var prestige = code_html.split(",");
				gain_prestige = '+' + parseInt(prestige[0]) + '<br /><img class="img_20" src="/webroot/img/icones/prestige.png"/>';
				perte_prestige = parseInt(prestige[1]) + '<br /><img class="img_20" src="/webroot/img/icones/prestige.png"/>';
				var indexTemp = selectedPlayers.indexOf(id_combattant_suppr);
				selectedPlayers.splice(indexTemp, 1);
				select_potential_players();
			},
			error: function(resultat, statut, erreur){
				//Erreur est une chaine de caractère à afficher au joueur
			},
			complete: function(resultat, statut){
				var chasseurs_prets = tous_prets();
				$("#" + id_combattant_suppr).hide("blind", "swing", 500, function(){ //On cache la ligne une fois le contact supprimé puis on la supprime
					$("#" + id_combattant_suppr).remove();
					if(chasseurs_prets == true && nbre_chasseurs_restants() == 1) //On enleve une notif si l'orga passe d'un combat à 2 personnes prêtes à un combat tout seul
					{
						diminuer_notif();
					}
				});
				nb_chasseurs --;
				if(nb_chasseurs == 1)
				{
					var nb_chasseur_txt = "1 chasseur de monstres";
					$(".spell_choice:eq(0)").removeClass("cache");
					$(".spell_choice:eq(1)").removeClass("cache");
				}
				else
				{
					var nb_chasseur_txt = nb_chasseurs + " chasseurs de monstres";
				}
				$("#nb_chasseurs").hide("pulsate", "swing", 500, function(){
					$("#nb_chasseurs").html(nb_chasseur_txt);
					$("#nb_chasseurs").show("pulsate", 500);
				});
				$("#gain_prestige").hide("pulsate", "swing", 500, function(){
					$("#gain_prestige").html(gain_prestige);
					$("#gain_prestige").show("pulsate", 500);
				});
				$("#perte_prestige").hide("pulsate", "swing", 500, function(){
					$("#perte_prestige").html(perte_prestige);
					$("#perte_prestige").show("pulsate", 500);
				});
			}
		});
	}
}


//Mécanisme pour monter un joueur dans l'ordre de combat une fois qu'il a été sélectionné
$("#monter").on("click", function(){
	if( id_contact_select != 0 && id_contact_select != parseInt($(".corps_scroll div:eq(0)").attr("id")) ) //Vérifie qu'il y a bien un contact sélectionné et que ce ne soit pas le premier
	{
		monter_contact();
	}
});

function monter_contact()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_monstre=' + id_monstre + '&id_combattant_monter=' + id_contact_select,
		dataType: 'html',
		success: function(resultat, statut){
			var prochain_a_jouer = resultat;
			if(prochain_a_jouer == "oui")
			{
				$(".spell_choice:eq(0)").removeClass("cache");
				$(".spell_choice:eq(1)").removeClass("cache");
			}
			else
			{
				$(".spell_choice:eq(0)").addClass("cache");
				$(".spell_choice:eq(1)").addClass("cache");
			}
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			$("#" + id_contact_select).prev().before($("#" + id_contact_select)); //On bouge la ligne d'un cran vers le haut
		}
	});
}

//Mécanisme pour descendre un joueur dans l'ordre de combat une fois qu'il a été sélectionné
$("#descendre").on("click", function(){
	if( id_contact_select != 0 && id_contact_select != parseInt($(".corps_scroll div").last().attr("id")) ) //Vérifie qu'il y a bien un contact sélectionné et que ce ne soit pas le dernier
	{
		descendre_contact();
	}
});

function descendre_contact()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_monstre=' + id_monstre + '&id_combattant_descendre=' + id_contact_select,
		dataType: 'html',
		success: function(resultat, statut){
			var prochain_a_jouer = resultat;
			if(prochain_a_jouer == "oui")
			{
				$(".spell_choice:eq(0)").removeClass("cache");
				$(".spell_choice:eq(1)").removeClass("cache");
			}
			else
			{
				$(".spell_choice:eq(0)").addClass("cache");
				$(".spell_choice:eq(1)").addClass("cache");
			}
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			$("#" + id_contact_select).next().after($("#" + id_contact_select)); //On bouge la ligne d'un cran vers le bas
		}
	});
}


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

$("#lancer_combat").dialog({
	autoOpen: false,
	resizable: false
});

$("#confirm_combat_solo").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"C'est pas grave": function(){
			$(this).dialog("close");
			var count_spells = parseInt($("#count_spells").html());
			if( count_spells > 0) {
				$(location).attr('href',"/app/controllers/combats_decks.php?id=" + id_combat);
			}
			else {
				$(location).attr('href', "/app/controllers/combattre.php?id=" + id_combat + '&s=0');
			}
		},
		"Laisse moi réfléchir": function(){
			$(this).dialog("close");
			$(".open_invite").effect("pulsate", {easing: "swing"}, 2000);
		}
	}
});

$("#too_many_players").dialog({
	autoOpen: false,
	resizable: false
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
	$("#accepter_"+ id_joueur).switchClass("cliquable", "neutre").unbind("mouseover mouseout click").css("height", "20px");
	participer_combat();
});

$(".refuser").on("click", function(event){
	event.preventDefault();
	$("#refuser_combat").html("Tu ne pourras plus changer d'avis par la suite sauf si l'organisateur te réinvite.");
	$("#refuser_combat").dialog("open");
});


//Fonctions pour traiter la participation / le refus d'un combat
function participer_combat()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_monstre_participer=' + id_monstre,
		dataType: 'html',
		success: function(code_html, statut){
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			$("#refuser_"+ id_joueur).hide("explode", "swing", 500, function(){
				diminuer_notif();
				$("#etat_"+ id_joueur).hide("pulsate", "swing", 500, function(){
					$("#etat_"+ id_joueur).attr("src", "/webroot/img/icones/check.png");
					$("#etat_"+ id_joueur).show("pulsate", 500);
				});
			});
		}
	});
}


function refuser_combat()
{
	$.ajax({
		url: '/app/controllers/ajax.php',
		type :'POST',
		data: 'id_monstre_refuser=' + id_monstre,
		dataType: 'html',
		success: function(code_html, statut){
		},
		error: function(resultat, statut, erreur){
			//Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function(resultat, statut){
			diminuer_notif();
			$("#accepter_"+ id_joueur).hide("explode", "swing", 500, function(){
				$(location).attr('href',"/app/controllers/liste_combats.php");
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
	{$("#notif_combats").html(notif);}
}

var nb_chasseurs = parseInt($("#nb_chasseurs").html());
var nb_chasseurs_recommandes = parseFloat($("#nb_chasseurs_recommandes").html());
var nb_chasseurs_recommandes_txt = nb_chasseurs_recommandes;
switch(nb_chasseurs_recommandes)
{
	case 2.5 :
		nb_chasseurs_recommandes_txt = "2 à 3";
		break;
	case 4.5 :
		nb_chasseurs_recommandes_txt = "4 à 5";
		break;
	case 8 :
		nb_chasseurs_recommandes_txt = "8";
		break;
}
//Si le joueur tente de cliquer sur Attaquer
if($(".spell_choice")) {
	$(".spell_choice").on("click", function(event){
		event.preventDefault();
		if(!tous_prets())	{
			$("#lancer_combat").html("Chaque joueur a reçu une notification pour participer au combat.<br><br>Tant que tout le monde n'a pas accepté (icône <img class='img_20' src='/webroot/img/icones/check.png'/>), tu ne peux pas lancer le combat.<br><br>Tu recevras une notification lorsque tout le monde sera prêt!");
			$("#lancer_combat").dialog("open");
		}
		else if(nb_combats <= 0) {
			$("#plus_assez_combats").html("Tu as déjà fais tes 5 combats journaliers. Reviens demain !");
			$("#plus_assez_combats").dialog("open");
		}
		else if(nb_chasseurs + 1 < nb_chasseurs_recommandes)	{
			$("#confirm_combat_solo").html("Normalement, il faut "+nb_chasseurs_recommandes_txt+ " chasseurs pour venir à bout de ce monstre !");
			$("#confirm_combat_solo").dialog("open");
		}
		else {
			var count_spells = parseInt($("#count_spells").html());
			if( count_spells > 0) {
				$(location).attr('href', "/app/controllers/combats_decks.php?id=" + id_combat);
			}
			else {
				$(location).attr('href', "/app/controllers/combattre.php?id=" + id_combat + '&s=0');
			}
		}
	});
}

function tous_prets()
{
	var ok = 0;
	$(".pret").each(function(){
		if($(this).attr("src") != "/webroot/img/icones/check.png" && $(this).attr("src") != "/webroot/img/icones/gaming.png") {
			ok ++;
		}
	});
	if(ok == 0)
		{return true;}
	else
		{return false;}
}

function nbre_chasseurs_restants()
{
	var nbre_chasseurs = 0;
	$(".pret").each(function(){
		nbre_chasseurs ++;
	});
	return nbre_chasseurs;
}

});

$(window).load(function(){

	if($("#chrono").length) //Chrono pour les monstres multijoueurs
	{
		var temps_restant = parseInt($("#chrono").html());
		var heures = Math.floor(temps_restant/60);
		var minutes = temps_restant - heures*60;
		if(heures < 10)
		{
			heures = "0" + heures;
		}
		if(minutes < 10)
		{
			minutes = "0" + minutes;
		}
		var chrono = heures + "h" + minutes + "min";
		$("#chrono").html(chrono);
		$("#chrono").css("top", $(".info_monstre_flow:eq(0)").offset().top - 0.8*$("#chrono").height());
		$("#chrono").css("left", $("#monstre").offset().left + $("#monstre").width()/2 - 0.5*$("#chrono").width());
		setInterval(function(){
			temps_restant -= 1;
			if(temps_restant > 0)
			{
				var heures = Math.floor(temps_restant/60);
				var minutes = temps_restant - heures*60;
				if(heures < 10)
				{
					heures = "0" + heures;
				}
				if(minutes < 10)
				{
					minutes = "0" + minutes;
				}
				var chrono = heures + "h" + minutes + "min";
				$("#chrono").html(chrono);
			}
			else if(temps_restant <= 0)
			{
				$(location).attr('href', '/index.php');
			}
		}, 1000*60);
	}

	if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1){ //Détection de Safari
		var decalage = parseInt($(".info_monstre_flow").css("left"));
		$(".info_monstre_flow").css("left", -1.8*decalage);
	}

	if(tutoFinishedToday == 1){
		$(".open_invite").effect("pulsate", {easing: "swing", times: 20}, 10000);
	}

});
