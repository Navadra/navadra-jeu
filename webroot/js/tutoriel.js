// JavaScript Document
var player = {};
var etape_tuto = $("#etape_tuto").html();
var tuteur = $("#nom_tuteur").html();
var sexe = $("#sexe_joueur").html();
var pseudo = $("#pseudo").html();
var click_play = false;
var block_instructions = false;
var ajaxRunning = false;
if($("#id_monstre").length){var id_monstre = parseInt($("#id_monstre").html());}
if($("#previous_training").length){var previous_training = $("#previous_training").html();}
if($("#id_combat").length){var id_combat = parseInt($("#id_combat").html());}
if($("#nb_prestige").length){var nb_prestige = parseInt($("#nb_prestige").html());}
if($("#winLastFight").length){var win = $("#winLastFight").html();}
if($("#prestigeLastFight").length){var prestigeLastFight = parseInt($("#prestigeLastFight").html());}
if($("#challengeElement").length){var selectedElement = $("#challengeElement").html();}
switch(selectedElement){
	case "fire":
		var selectedElementArticle1 = "de Feu";
		var selectedElementArticle2 = "du Feu";
		var frenchElement = "feu";
		break;
	case "water":
		var selectedElementArticle1 = "d'Eau";
		var selectedElementArticle2 = "de l'Eau";
		var frenchElement = "eau";
		break;
	case "wind":
		var selectedElementArticle1 = "de Vent";
		var selectedElementArticle2 = "du Vent";
		var frenchElement = "vent";
		break;
	case "earth":
		var selectedElementArticle1 = "de Terre";
		var selectedElementArticle2 = "de la Terre";
		var frenchElement = "terre";
		break;
}

$.ajax({url: '/app/controllers/ajax.php',
   type: 'POST',
   data: 'caracs_joueur=get',
   dataType: 'json',
   success: function (result, status) {
     player = result;
   },
   error: function (result, status, error) {
     //Erreur est une chaine de caractère à afficher au joueur
   },
   complete: function (result, status) {
   }
});

var instructions_vues = "non";
if($("#instructions_vues").length){instructions_vues = $("#instructions_vues").html();}

var monstre_tuto = "non";
if($("#monstre_tuto").length){monstre_tuto = "oui";}

var nouveau_tuteur = $("#new_mentor").html();
if(tuteur != nouveau_tuteur)
	{var changement = true;}
else
	{var changement = false;}

//Spécificités de l'étape actuelle du tuto
switch(etape_tuto){
	case "index_1" :
		var derniere_etape = 1;
		var page_suivante = "/app/controllers/combattre.php?tuto=next&id="+id_combat;
		var objet_bulle = $("#bulle_index");
		break;
	case "combattre_2" :
		var derniere_etape = 1;
		var page_suivante = "/index.php?tuto=next";
		var objet_bulle = $("#bulle_combat");
		break;
	case "index_3" :
		var derniere_etape = 2;
		var page_suivante = "/app/controllers/accueil_defi.php?tuto=next";
		var objet_bulle = $("#bulle_index");
		break;
	case "accueil_defi_4" :
		if(changement)
			{var derniere_etape = 3;}
		else
			{var derniere_etape = 1;}
		var page_suivante = "/app/controllers/new_defi.php?tuto=next";
		var objet_bulle = $("#bulle_bas");
		break;
	case "fin_defi_5" :
		var derniere_etape = 2;
		var page_suivante = "/app/controllers/grimoire.php?tuto=next";
		var objet_bulle = $("#bulle_bas");
		break;
	case "grimoire_6" :
		var derniere_etape = 2;
		var page_suivante = "#";
		var objet_bulle = $("#bulle_bas");
		break;
	case "grimoire_7" :
		var derniere_etape = 1;
		var page_suivante = "/index.php?tuto=next";
		var objet_bulle = $("#bulle_bas");
		break;
	case "index_8" :
		var derniere_etape = 2;
		var page_suivante = "/app/controllers/prepa_combats.php?tuto=next&idm="+id_monstre;
		var objet_bulle = $("#bulle_index");
		break;
	case "prepa_combats_9" :
		var derniere_etape = 2;
		var page_suivante = "/app/controllers/combats_decks.php?tuto=next&id="+id_combat;
		var objet_bulle = $("#bulle_bas");
		break;
	case "combats_decks_10" :
		var derniere_etape = 2;
		var page_suivante = "/app/controllers/combattre.php?tuto=next&id="+id_combat;
		var objet_bulle = $("#bulle_bas");
		break;
	case "combattre_11" :
		var derniere_etape = 2;
		var page_suivante = "/index.php?tuto=next";
		var objet_bulle = $("#bulle_combat");
		break;
	case "index_12" :
		if(win=="yes")
			{var derniere_etape = 3;}
		else if(win=="no")
			{var derniere_etape = 5;}
		var page_suivante = "/index.php?tuto=next&help=active";
		var objet_bulle = $("#bulle_index");
		break;
}

//Initialisation des étapes
$("<div id='mise_en_evidence'></div>").appendTo("body").hide();
function mettre_en_evidence(objet, adjustedHeight){
	$("#mise_en_evidence").hide();
	if(adjustedHeight == undefined)	{
		adjustedHeight = 0;
	}
	var pos_x = objet.offset().left;
	var pos_y = objet.offset().top - 0.5*adjustedHeight;
	var larg = objet.width();
	var haut = objet.height() + adjustedHeight;
	$("#mise_en_evidence").css("width", larg + 10).css("height", haut + 10);
	$("#mise_en_evidence").css("top", pos_y - 5).css("left", pos_x - 5);
	$("#mise_en_evidence").show("scale", {easing: "swing", percent: 200}, 1000, function(){
		$("#mise_en_evidence").effect("pulsate", {easing: "swing"}, 1000, function(){
		});
	});
}

function agrandir_mise_en_evidence(objet){
	$("#mise_en_evidence").hide();
	var pos_x = objet.offset().left;
	var pos_y = objet.offset().top;
	var larg = objet.width();
	var haut = objet.height();
	$("#mise_en_evidence").css("width", larg + 50).css("height", haut + 10);
	$("#mise_en_evidence").css("left", pos_x - 25).css("top", pos_y - 5);
	$("#mise_en_evidence").show("scale", {easing: "swing", percent: 200}, 1000, function(){
		$("#mise_en_evidence").effect("pulsate", {easing: "swing"}, 1000, function(){
		});
	});
}

var etape = 1;
if(previous_training != undefined && previous_training == "yes"){
	etape = derniere_etape;
}
if(sexe == "gars"){
	var e = "";
	var hf = "homme";
	var hunter = "chasseur";
} else if(sexe == "fille"){
	var e = "e";
	var hf = "fille";
	var hunter = "chasseuse";
}
if(tuteur == "Sivem" || tuteur == "Leorn") {
	var e_t = "";
} else if(tuteur == "Namuka" || tuteur == "Katillys") {
	var e_t = "e";
}
var animationRunning = false;


//Donner leurs propriétés aux boutons suivants et précédents
$("#tuto_precedent").on("mouseover",function(){
	$("#tuto_precedent").attr("src","/webroot/img/icones/chevron1bis.png");
});

$("#tuto_precedent").on("mouseout",function(){
	$("#tuto_precedent").attr("src","/webroot/img/icones/chevron1.png");
});

$("#tuto_precedent").on("click",function(){
	if(etape > 1 && block_instructions==false)
	{
		etape--;
		changer_txt_bulle(msg_tuteur());
	}
});

$("#tuto_suivant").on("mouseover",function(){
	$("#tuto_suivant").attr("src","/webroot/img/icones/chevron2bis.png");
});

$("#tuto_suivant").on("mouseout",function(){
	$("#tuto_suivant").attr("src","/webroot/img/icones/chevron2.png");
});

$("#tuto_suivant").on("click",function(){
	if(etape < derniere_etape && block_instructions==false && animationRunning==false)	{
		animationRunning = true;
		etape++;
		if(etape_tuto == "accueil_defi_4" && changement && etape==2)	{
			change_mentor();
		}	else {
			changer_txt_bulle(msg_tuteur());
		}
	}
	else if(etape == derniere_etape && block_instructions==false && animationRunning==false) { //Si c'est la dernière étape envoie vers la prochaine étape du tuto en changeant l'étape du joueur
		if(etape_tuto == "combattre_2" || etape_tuto == "combattre_11")	{
			animationRunning = true;
			$("#tuteur_combat").hide();
			$("#bulle_combat").hide();
			$("#commandes_tuto_combat").hide();
			$("#fleche_tuto").hide();
			$("#countdown").show();
			$("#son_musique_combat")[0].volume = 0.75;
			if(playerVolumeMusic == 1){
    			$("#son_musique_combat").trigger("play");
			}
			fight_initialization();
		}	else if(etape_tuto != "grimoire_6" && etape_tuto != "prepa_combats_9" && etape_tuto !="combats_decks_10")	{
			animationRunning = true;
			executer_ajax();
		}
	}
});

if(monstre_tuto == "oui"){
	$("#monstre_tuto").on("click", function(event){
		event.preventDefault();
		executer_ajax();
	});
}

function msg_tuteur(){
	switch(etape_tuto){
		case "index_1" :
			switch(etape)	{
				case 1 :
					var msg = "Un monstre t'a repéré !<br>Pas le temps de discuter, prépare-toi à défendre chèrement ta vie...";
					$("#monstre_" + id_monstre).on("click", function(event){
							event.preventDefault();
							executer_ajax();
					});
					$("#monstre_" + id_monstre).effect("pulsate", {easing: "swing"}, 2000);
					$("#monstre_" + id_monstre + "_info").effect("pulsate", {easing: "swing"}, 2000);
					/*
					$("#monstre_" + id_monstre).effect("pulsate", {easing: "swing"}, 1000,function(){
						$("#fleche_tuto").show();
					});
					reposition_arrow($("#monstre_" + id_monstre));*/
					$("#commandes_tuto_index").hide();
					block_instructions = true;
					break;
			}
			break;
		case "combattre_2" :
			switch(etape)	{
				case 1 :
					var msg = "Bon eh bien c'est le moment de vérité comme on dit.<br>Bonne chance et à tout à l'heure... peut-être !";
					$("#son_musique_combat").trigger("pause");
					$("#player_combo_img").hide();
					$("#player_combo_bar").hide();
					break;
			}
			break;
		case "index_3" :
			switch(etape)	{
				case 1 :
					var msg = "Pas mal pour un étranger, je ne m'attendais pas à te voir revenir !<br>Ce monstre était particulièremment faible mais ce ne sera pas toujours le cas...";
					break;
				case 2 :
					var msg = "Pour te préparer pour tes prochains combats, il est grand temps de commencer ton entraînement.<br>Viens me parler quand tu es prêt"+e+".";
					$('<img id="lancer_defi" src="/webroot/img/icones/play_inverse.png" />').appendTo('body').load(function(){
						reposition_arrow($("#lancer_defi"));
						$("#lancer_defi").on("click", function (event) {
							event.preventDefault();
							popUpDisplay();
							reposition_arrow($(".choix_element"), 20);
						});
						$("#bulle_index").on("click", function (event) {
							event.preventDefault();
							popUpDisplay();
							reposition_arrow($(".choix_element"), 20);
						});
						$("#commandes_tuto_index").hide();
					});
					break;
			}
			break;
		case "accueil_defi_4" :
			if(!changement)	{
				switch(etape)	{
					case 1 :
						var msg = "Si tu te sens déjà affuté"+e+" mentalement tu peux directement commencer le défi et sinon, un peu d'entraînement avant ne te fera pas de mal.<br>On y va ?";
						reposition_arrow($(".form_droite2"), 30);
						position_images();
						//activate_play_button();
						$("#commandes_tuto_bas").hide();
						block_instructions = true;
						break;
				}
			}
			else if(changement)	{
				switch(etape)	{
					case 1 :
						if(tuteur == "Namuka")
							{var msg = "QUOI, TU N'AS PAS CHOISI LE FEU ?! C'est un scandale !<br>Je te laisse donc te débrouiller tout"+e+" seul"+e+", ciao !";}
						if(tuteur == "Katillys")
							{var msg = "QUOI, TU N'AS PAS CHOISI L'EAU ?! C'est un scandale !<br>Je te laisse donc te débrouiller tout"+e+" seul"+e+", ciao !";}
						if(tuteur == "Sivem")
							{var msg = "QUOI, TU N'AS PAS CHOISI LE VENT ?! C'est un scandale !<br>Je te laisse donc te débrouiller tout"+e+" seul"+e+", ciao !";}
						if(tuteur == "Leorn")
							{var msg = "QUOI, TU N'AS PAS CHOISI LA TERRE ?! C'est un scandale !<br>Je te laisse donc te débrouiller tout"+e+" seul"+e+", ciao !";}
						hide_challenge_infos();
						restablish_mentor();
						break;
					case 2 :
						if(nouveau_tuteur == "Namuka")
							{var msg = "Salut "+pseudo+", alors comme ça la magie du Feu t'intéresse ?<br>Je m’appelle Namuka et je suis l’ancienne cheffe de la tribu Shakor, passée maître dans la magie du Feu.";}
						if(nouveau_tuteur == "Katillys")
							{var msg = "Salut "+pseudo+", alors comme ça la magie de l'Eau t'intéresse ?<br>Je m’appelle Katillys et je suis une ancienne Membre du Conseil de la tribu des Lyréens, passée maître dans la magie de l’Eau.";}
						if(nouveau_tuteur == "Sivem")
							{var msg = "Salut "+pseudo+", alors comme ça la magie du Vent t'intéresse ?<br>Je m’appelle Sivem et je suis l’ancien chef de la tribu des Ataliis, passée maître dans la magie du Vent.";}
						if(nouveau_tuteur == "Leorn")
							{var msg = "Salut "+pseudo+", alors comme ça la magie de la Terre t'intéresse ?<br>Je m’appelle Leorn et je suis l’ancien chef de la tribu des Keodenns, passée maître dans la magie de la Terre.";}
						display_challenge_infos();
						break;
					case 3 :
						var msg = "Si tu te sens déjà affuté"+e+" mentalement tu peux directement commencer le défi et sinon, un peu d'entraînement ne te fera pas de mal.<br>On y va ?";
						reposition_arrow($(".form_droite2"), 30);
						//activate_play_button();
						$("#commandes_tuto_bas").hide();
						block_instructions = true;
						break;
				}
			}
			break;
		case "fin_defi_5" :
			switch(etape)	{
				case 1 :
					if(playerPyrs_feu > 0){
						var msg = "Tu viens de pratiquer la magie du Feu, tu récoltes donc des Pyrs de Feu (<img class='img_20' src='/webroot/img/icones/pyrs_feu.png' />).<br>C'est grâce à elles que tu vas pouvoir apprendre des sorts de Feu et devenir plus puissant"+e+" !";
						mettre_en_evidence($("#pyrs_feu"));
					} else if(playerPyrs_eau > 0){
						var msg = "Tu viens de pratiquer la magie de l'Eau, tu récoltes donc des Pyrs d'Eau (<img class='img_20' src='/webroot/img/icones/pyrs_eau.png' />).<br>C'est grâce à elles que tu vas pouvoir apprendre des sorts d'Eau et devenir plus puissant"+e+" !";
						mettre_en_evidence($("#pyrs_eau"));
					} else if(playerPyrs_vent > 0){
						var msg = "Tu viens de pratiquer la magie du Vent, tu récoltes donc des Pyrs de Vent (<img class='img_20' src='/webroot/img/icones/pyrs_vent.png' />).<br>C'est grâce à elles que tu vas pouvoir apprendre des sorts de Vent et devenir plus puissant"+e+" !";
						mettre_en_evidence($("#pyrs_vent"));
					} else if(playerPyrs_terre > 0){
						var msg = "Tu viens de pratiquer la magie de la Terre, tu récoltes donc des Pyrs de Terre (<img class='img_20' src='/webroot/img/icones/pyrs_terre.png' />).<br>C'est grâce à elles que tu vas pouvoir apprendre des sorts de Terre et devenir plus puissant"+e+" !";
						mettre_en_evidence($("#pyrs_terre"));
					}
					break;
				case 2 :
					var msg = "Tu t'es montré"+e+" digne d'être un"+e+" vrai"+e+" "+hunter+" de monstres ! Je t'offre donc mon vieux grimoire (<img class='img_20' src='/webroot/img/icones/grimoire_normal.png' />) grâce auquel j'ai appris à maîtriser la magie.";
					$("#mise_en_evidence").hide();
					reposition_arrow($("#icone_grimoire"));
					$("#link_grimoire").attr("href", "app/controllers/grimoire.php");
					$("#icone_grimoire").attr("src", "/webroot/img/icones/grimoire_normal.png");
					$("#icone_grimoire").attr("title", "Ce grimoire sent le grenier de ta grand-mère.");
					activate_grimoire_button();
					$("#commandes_tuto_bas").hide();
					block_instructions = true;
					break;
			}
			break;
		case "grimoire_6" :
			switch(etape)	{
				case 1 :
					var msg = "N'ayant que des Pyrs "+selectedElementArticle1+" pour l'instant, tu dois donc choisir la magie dévastatrice "+selectedElementArticle2+" !";
					$("#commandes_tuto_bas").hide();
					reposition_arrow($("#"+frenchElement));
					block_instructions = true;
					break;
				case 2 :
					switch(selectedElement)	{
						case "fire" :
							var spellNum = 1;
							break;
						case "water" :
							var spellNum = 11;
							break;
						case "wind" :
							var spellNum = 15;
							break;
						case "earth" :
							var spellNum = 25;
							break;
					}
					var msg = "Pour commencer simplement, choisis l'attaque de base "+selectedElementArticle2+" : "+$("#nom_"+spellNum).html()+".";
					$(".learnableSpells").each(function(index){
						if($(this).css("visibility") != "hidden"){
							reposition_arrow($(this));
							return false;
						}
					})
					break;
			}
			break;
		case "grimoire_7" :
			switch(etape)	{
				case 1 :
					var msg = "Voilà une bonne chose de faite, allons explorer l'île pour se divertir un peu...";
					$("#icone_index").attr("src", "/webroot/img/icones/home.png");
					$("#icone_index").attr("title", "Revenir sur l'île");
					reposition_arrow_top($("#icone_index"), 0, 0, true);
					activate_home_button();
					$("#commandes_tuto_bas").hide();
					break;
			}
			break;
		case "index_8" :
			switch(etape)	{
				case 1 :
					var msg = "Plutôt que de te cacher dans ton coin en attendant qu'un monstre ne te tombe dessus, je te propose d'aller au devant du danger !";
					break;
				case 2 :
					if(tuteur == "Namuka")
						{var msg = "Tiens, regarde moi ce vilain serpent... Il devrait parfaitement faire l'affaire!";}
					if(tuteur == "Katillys")
						{var msg = "Tiens, regarde moi ce vilain serpent... Il devrait parfaitement faire l'affaire!";}
					if(tuteur == "Sivem")
						{var msg = "Tiens, regarde moi ce vilain serpent... Il devrait parfaitement faire l'affaire!";}
					if(tuteur == "Leorn")
						{var msg = "Tiens, regarde moi ce vilain rapace... Il devrait parfaitement faire l'affaire!";}
					$("#commandes_tuto_index").hide();
					$("#fleche_tuto").hide();
					$("#monstre_" + id_monstre).on("click", function(event){
						event.preventDefault();
						executer_ajax();
					});
					$("#monstre_" + id_monstre + "_info").show("scale", {easing: "swing", percent: 100}, 1000);
					$("#monstre_" + id_monstre).show("scale", {easing: "swing", percent: 100}, 1000, function(){
						$("#monstre_" + id_monstre + "_info").effect("pulsate", {easing: "swing"}, 1000);
						$("#monstre_" + id_monstre).effect("pulsate", {easing: "swing"}, 1000,function(){
							$("#fleche_tuto").show();
							reposition_arrow($("#monstre_" + id_monstre));
						});
					});
					block_instructions = true;
					break;
			}
			break;
		case "prepa_combats_9" :
			switch(etape)	{
				case 1 :
					var msg = "Avant le combat, tu peux voir combien tu gagneras de Prestige (<img class='img_20' src='/webroot/img/icones/prestige.png'/>) si tu gagnes et combien tu en perdras si tu perds.";
					mettre_en_evidence($("#info_prestige"));
					break;
				case 2 :
					var msg = "Passons au choix des sorts maintenant.";
					$("#mise_en_evidence").hide();
					reposition_arrow($(".form_droite2:eq(0)"), 0.15*$(".form_droite2:eq(0)").width(), 0.2*$(".form_droite2:eq(0)").height());
					$("#commandes_tuto_bas").hide();
					activate_spell_choice();
					break;
			}
			break;
		case "combats_decks_10" :
			switch(etape)	{
				case 1 :
					var msg = "Déplace le sort que tu as appris dans la zone encadrée pour pouvoir l’utiliser en combat.";
					reposition_arrow($(".icones_sorts_deck:eq(0)"), 30);
					$("#commandes_tuto_bas").hide();
					showDragAndDrop();
					block_instructions = true;
					break;
				case 2 :
					var msg = "Pour l'instant tu ne connais qu'un sort donc assez discuté et engage le combat !";
					reposition_arrow($(".form_droite2:eq(0)"), 0.15*$(".form_droite2:eq(0)").width(), 0.2*$(".form_droite2:eq(0)").height());
					$("#commandes_tuto_bas").hide();
					activate_attack();
					break;
			}
			break;
		case "combattre_11" :
			switch(etape)	{
				case 1 :
					var msg = "Prêt"+e+" ? Ah attends, j'ai une vision…<br>Un groupe d'aventuriers est en train de combattre un DRAGON !!!";
					break;
				case 2 :
					var msg = "Ça s'annonce EPIQUE, il faut que je voie ça !<br>Désolé"+e_t+" et … bonne chance !";
					break;
			}
			break;
		case "index_12" :
			if(win=="yes"){
				switch(etape)	{
					case 1 :
						var msg = "Waouh, tu as gagné ? Incroyable ! Tu viens donc de récolter "+prestigeLastFight+" points de Prestige (<img class='img_20' src='/webroot/img/icones/prestige.png'/>).<br>C'est avec le Prestige que tu gagneras des places dans le classement des chasseurs de monstres.";
						mettre_en_evidence($("#icone_prestige"));
						break;
					case 2 :
						var msg = "Je te conseille de faire 1 ou 2 défis en plus et apprendre de nouveaux sorts avant de combattre encore mais tu fais comme tu veux...";
						$("#mise_en_evidence").hide();
						break;
					case 3 :
						var msg = "<span class='g'>Une dernière chose</span> : à tout moment, si tu es perdu"+e+" dans Navadra, clique sur la <span class='g'>boussole</span> (<img class='img_20' src='/webroot/img/icones/boussole.png'/>), elle saura te guider sur chaque écran du jeu.";
						reposition_arrow_left($("#boussole"));
						$("#commandes_tuto_index").hide();
						break;
				}
			}
			else if(win=="no"){
				switch(etape)	{
					case 1 :
						var msg = "Ça arrive même aux meilleurs de se faire mettre K.O par un monstre ! Tu n'as pas gagné de Prestige (<img class='img_20' src='/webroot/img/icones/prestige.png'/>) cette fois ci.<br>C'est avec le Prestige que tu gagneras des places dans le classement des chasseurs de monstres.";
						mettre_en_evidence($("#icone_prestige"));
						break;
					case 2 :
						var msg = "Je te conseille de faire 1 ou 2 défis en plus et apprendre de nouveaux sorts avant de combattre encore mais tu fais comme tu veux...";
						$("#mise_en_evidence").hide();
						break;
					case 3 :
						var msg = "<span class='g'>Une dernière chose</span> : à tout moment, si tu es perdu"+e+" dans Navadra, clique sur la <span class='g'>boussole</span> (<img class='img_20' src='/webroot/img/icones/boussole.png'/>), elle saura te guider sur chaque écran du jeu.";
						reposition_arrow_left($("#boussole"));
						$("#commandes_tuto_index").hide();
						break;
				}
			}
			break;
		}
return msg;
}

function apparition_txt_bulle(texte){
	if(playerVolumeSoundEffects == 1){
		$("#son_tuto").trigger("play");
	}
	objet_bulle.hide().removeClass("cache");
	$("#txt_bulle").html(texte);
	objet_bulle.show("blind", 500);
}

function changer_txt_bulle(nouveau_texte) {
	if(playerVolumeSoundEffects == 1){
		$("#son_tuto").trigger("play");
	}
	objet_bulle.hide("blind", {easing: "swing"}, 500, function(){
		$("#txt_bulle").html(nouveau_texte);
		objet_bulle.show("blind", {easing: "swing"}, 500, function(){
			animationRunning = false;
		});
	})
	/*
	objet_bulle.hide("blind", 500);
	setTimeout(function(){
		$("#txt_bulle").html(nouveau_texte);
		objet_bulle.show("blind", 500);
	}, 500); */
}

function executer_ajax() {
	console.log("executer_ajax " + page_suivante);
	if (page_suivante != "#") {
		//window.location.replace( page_suivante );
		window.location.href = page_suivante;
  }
}

function hide_challenge_infos()
{
	$(".fond:eq(0)").hide();
	$("#accomplissement_joueur").hide();
	$(".entrainement").hide();
	$(".jouer").hide();
	$("#progress_graph").hide();
	$("#playerAvatar").hide();
	$("#lock").hide();
	$("#question_mark").hide();
}

function display_challenge_infos()
{
	$(".fond:eq(0)").show();
	$("#accomplissement_joueur").show("pulsate", {easing: "swing"}, 750);
	$(".entrainement").show("pulsate", {easing: "swing"}, 750);
	$(".jouer").show("pulsate", {easing: "swing"}, 750);
	$("#progress_graph").show("pulsate", {easing: "swing"}, 750);
	$("#playerAvatar").show("pulsate", {easing: "swing"}, 750);
	$("#lock").show("pulsate", {easing: "swing"}, 750);
	$("#question_mark").show("pulsate", {easing: "swing"}, 750);
	position_images();
}

//Prevent from clicking on "Jouer"
$(".jouer").unbind("click").on("click", function(event){
	event.preventDefault();
	if(click_play == false)	{
		click_play = true;
		executer_ajax();
	}
});

function activate_play_button()
{
	$(".jouer").on("click", function(event){
		event.preventDefault();
		if(click_play == false)
		{
			click_play = true;
			executer_ajax();
		}
	});
}

//Prevent from clicking on grimoire button
$("#icone_grimoire").unbind("click").on("click", function(event){
	event.preventDefault();
});

function activate_grimoire_button(){
	$("#icone_grimoire").on("click", function(event){
		event.preventDefault();
		executer_ajax();
	});
}

function activate_home_button(){
	$("#icone_index").on("click", function(event){
		event.preventDefault();
		executer_ajax();
	});
	$("#closeWindow").on("click", function(event){
		event.preventDefault();
		executer_ajax();
	});
}

$(".learnableSpells").unbind("click").on("click", function(){
	if(!formSubmitted){
		formSubmitted = true;
		var num = parseInt($(this).parent().children(".numUpgrade").html());
		reinitialiser_animations();
		$("input[name=num_sort]").val(num);
		staysGrimoire = true;
		$("form").attr("action", "/app/controllers/grimoire.php?tuto=next").submit();
	}
});

function activate_spell_choice(){
	$(".spell_choice").on("click", function(event){
		event.preventDefault();
		executer_ajax();
	});
}

function showDragAndDrop(){
	$("#elements_spells_selection img").unbind("click");
	var xStart = $(".icones_sorts_deck:eq(0)").offset().left;
	var yStart = $(".icones_sorts_deck:eq(0)").offset().top;
	var xArrow = $("#fleche_tuto").offset().left;
	var yArrow = $("#fleche_tuto").offset().top;
	var xEnd = $("#sortable_spells").offset().left + 0.5*$("#sortable_spells_parent").width() - 0.5*$(".icones_sorts_deck:eq(0)").width();
	var yEnd = $("#sortable_spells").offset().top + 0.1*$("#sortable_spells").height();
	var destination = {top: yEnd, left: xEnd};
	var newSpellIcon = $(".icones_sorts_deck:eq(0)").clone().appendTo("body");
	newSpellIcon.css("position", "absolute").detach().appendTo("body").hide();
	setInterval(function () {
		if($("#fleche_tuto").offset().left == xArrow && $("#fleche_tuto").offset().top == yArrow){
			newSpellIcon.css("top", yStart).css("left", xStart).show();
			newSpellIcon.animate(destination, 3000, "easeInExpo", function () {
				newSpellIcon.delay(1000).fadeOut(400);
			});
		}
	}, 4500);
}

//Prevent from clicking too soon on attack
$("#spells_start_fight").attr("onclick", "");
activate_attack();

function activate_attack(){
	$("#spells_start_fight").on("click", function(event){
		event.preventDefault();
		executer_ajax();
	});
}

if(etape_tuto != "index_1" && etape_tuto != "fin_defi_5"){
	placer_fleche();
};


function placer_fleche(){
	var pos_x = $("#tuto_suivant").offset().left;
	var pos_y = $("#tuto_suivant").offset().top;
	var larg = $("#tuto_suivant").width();
	var haut = $("#tuto_suivant").height();
	$("<img id='fleche_tuto' class='img_100' src='/webroot/img/icones/fleche2.png' />").appendTo("body");
	var haut_fleche = $("#fleche_tuto").height();
	$("#fleche_tuto").css("position", "absolute").css("top", pos_y + 0.5*(haut - haut_fleche)).css("left", pos_x + larg).css("z-index", 2);
}

function place_second_arrow(target){
	var pos_x = target.offset().left;
	var pos_y = target.offset().top;
	var larg = target.width();
	var haut = target.height();
	if(!$("#fleche_tuto2").length){
		$("<img id='fleche_tuto2' class='img_100' src='/webroot/img/icones/fleche2.png' />").appendTo("body");
	}
	var haut_fleche = $("#fleche_tuto2").height();
	$("#fleche_tuto2").css("position", "absolute").css("top", pos_y + 0.5*(haut - haut_fleche)).css("left", pos_x + larg).css("z-index", 2);
}

function reposition_arrow(target, leftOffset, topOffset, infinite){
	if($("#fleche_tuto").attr("src") == "/webroot/img/icones/fleche2.png"){
		positionningArrow(target, "right", leftOffset, topOffset, infinite);
	} else {
		$("#fleche_tuto").attr("src", "/webroot/img/icones/fleche2.png").load(function(){
			positionningArrow(target, "right", leftOffset, topOffset, infinite);
		});
	}
}

function reposition_arrow_left(target, leftOffset, topOffset, infinite){
	if($("#fleche_tuto").attr("src") == "/webroot/img/icones/fleche.png"){
		positionningArrow(target, "left", leftOffset, topOffset, infinite);
	} else {
		$("#fleche_tuto").attr("src", "/webroot/img/icones/fleche.png").load(function(){
			positionningArrow(target, "left", leftOffset, topOffset, infinite);
		});
	}
}

function reposition_arrow_top(target, leftOffset, topOffset, infinite){
	if($("#fleche_tuto").attr("src") == "/webroot/img/icones/fleche4.png"){
		positionningArrow(target, "top", leftOffset, topOffset, infinite);
	} else {
		$("#fleche_tuto").attr("src", "/webroot/img/icones/fleche4.png").load(function(){
			positionningArrow(target, "top", leftOffset, topOffset, infinite);
		});
	}
}

function positionningArrow(target, position, leftOffset, topOffset, infinite){
	var pos_x = target.offset().left;
	var pos_y = target.offset().top;
	var larg = target.width();
	var haut = target.height();
	var arrowHeight = $("#fleche_tuto").height();
	var arrowWidth = $("#fleche_tuto").width();
	if(leftOffset == undefined) {
		leftOffset = 0;
	}
	if(topOffset == undefined) {
		topOffset = 0;
	}
	if(position == "right"){
		var destinationX = pos_x + larg + leftOffset;
		var destinationY = pos_y + 0.5*(haut - arrowHeight) + topOffset;
	} else if(position == "left"){
		var destinationX = pos_x - arrowWidth + leftOffset;
		var destinationY = pos_y + 0.5*(haut - arrowHeight) + topOffset;
	} else if(position == "top"){
		var destinationX = pos_x + 0.5*(larg - arrowWidth) + leftOffset;
		var destinationY = pos_y + haut + 10 + topOffset;
	}
	$("#fleche_tuto").css("position", "absolute").css("top", destinationY).css("left", destinationX);
	var blinkDuration = 2000;
	if(infinite == true){
		blinkDuration = 30000;
	}
	$("#fleche_tuto").effect("pulsate", {easing: "swing", times: blinkDuration/200}, blinkDuration);
}

function change_mentor(){
	$("#txt_bulle").html("...");
	var new_src = "/webroot/img/personnages/" + nouveau_tuteur.toLowerCase() +"_portrait.png";
	if($("#tuteur_cote").attr("src") != new_src){
		$("#tuteur_cote").hide("pulsate", {easing: "swing"}, 750, function(){
			$("#tuteur_cote").attr("src", new_src);
			$("#tuteur_cote").show("pulsate", {easing: "swing"}, 750, function(){
				changer_txt_bulle(msg_tuteur());
			});
		});
	}
}

function restablish_mentor()
{
	var new_src = "/webroot/img/personnages/" + tuteur.toLowerCase() +"_portrait.png";
	if($("#tuteur_cote").attr("src") != new_src)
	{
		$("#tuteur_cote").attr("src", new_src);
	}
}

function afficher_tuto(objet){
	objet.show("clip", 500);
}


$(window).load(function(){
	if($(".icones_sorts_combat").length){
		var activation = 0;
		$(".icones_sorts_combat").on("click", function(){
			if(activation == 0){
				activation ++;
				$("#tuteur_combat").hide();
				$("#bulle_combat").hide();
				$("#commandes_tuto_combat").hide();
				$("#fleche_tuto").hide();
				$("#countdown").show();
				$("#son_musique_combat")[0].volume = 0.75;
				if(playerVolumeMusic == 1){
	    			$("#son_musique_combat").trigger("play");
				}
				fight_initialization();
			}
		});
	}

	//Prevent from clicking too soon on spell choice
	$(".spell_choice").unbind("click").on("click", function(event){
		event.preventDefault();
		executer_ajax();
	});

	if(etape_tuto == "fin_defi_5"){
		$("#bulle_bas").hide();
		$("#commandes_tuto_bas").hide();
		$("#annonce_fin_defi").unbind("click");
		$("#annonce_fin_defi").on("click", function(){
			$("#annonce_fin_defi").switchClass("annonce_fin_defi", "annonce_fin_defi_cachee", 500);
			setTimeout(function(){
				$("#annonce_fin_defi").hide();
				if(playerVolumeSoundEffects == 1){
					$("#son_fin_defi").trigger("play");
				}
				change_level(levelBeforeChallenge, levelAfterChallenge);
				setTimeout(function(){
					apparition_txt_bulle(msg_tuteur());
					setTimeout(function(){
						$("#commandes_tuto_bas").show();
						placer_fleche();
						$("#fleche_tuto").effect("pulsate", {easing: "swing"}, 1000);
					}, 3000);
				}, 2500);
			}, 500);
		});
	} else	{
		apparition_txt_bulle(msg_tuteur());
	}

});
