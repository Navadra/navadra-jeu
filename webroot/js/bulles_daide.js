// JavaScript Document

//Initilisation du focus
$('<img id="fleche_gauche" class="img_50" src="/webroot/img/icones/fleche.png">').appendTo("body").css("position", "absolute").css("z-index", 1300).hide();
$('<img id="fleche_gauche2" class="img_50" src="/webroot/img/icones/fleche.png">').appendTo("body").css("position", "absolute").css("z-index", 1300).hide();
$('<img id="fleche_droite" class="img_50" src="/webroot/img/icones/fleche2.png">').appendTo("body").css("position", "absolute").css("z-index", 1300).hide();
function focus_bulle_daide_gauche(objet)
{
	var pos_x = objet.offset().left;
	var pos_y = objet.offset().top;
	var larg = objet.width();
	var haut = objet.height();
	$("#fleche_gauche").css("height", haut*2);
	var larg_fleche = $("#fleche_gauche").width();
	var haut_fleche = $("#fleche_gauche").height();
	$("#fleche_gauche").css("top", pos_y + 0.5*haut - 0.5*haut_fleche).css("left", pos_x - larg_fleche);
	$("#fleche_gauche").show("scale", {easing: "swing", percent: 400}, 1000, function(){
		$("#fleche_gauche").effect("pulsate", {easing: "swing"}, 1000, function(){
		});
	});
}

function focus_bulle_daide_gauche2(objet)
{
	var pos_x = objet.offset().left;
	var pos_y = objet.offset().top;
	var larg = objet.width();
	var haut = objet.height();
	$("#fleche_gauche2").css("height", haut*2);
	var larg_fleche = $("#fleche_gauche2").width();
	var haut_fleche = $("#fleche_gauche2").height();
	$("#fleche_gauche2").css("top", pos_y + 0.5*haut - 0.5*haut_fleche).css("left", pos_x - larg_fleche);
	$("#fleche_gauche2").show("scale", {easing: "swing", percent: 400}, 1000, function(){
		$("#fleche_gauche2").effect("pulsate", {easing: "swing"}, 1000, function(){
		});
	});
}

function focus_bulle_daide_droite(objet)
{
	var pos_x = objet.offset().left;
	var pos_y = objet.offset().top;
	var larg = objet.width();
	var haut = objet.height();
	$("#fleche_droite").css("height", haut*2);
	var larg_fleche = $("#fleche_droite").width();
	var haut_fleche = $("#fleche_droite").height();
	$("#fleche_droite").css("top", pos_y + 0.5*haut - 0.5*haut_fleche).css("left", pos_x + larg);
	$("#fleche_droite").show("scale", {easing: "swing", percent: 400}, 1000, function(){
		$("#fleche_droite").effect("pulsate", {easing: "swing"}, 1000, function(){
		});
	});
}

function focus_bulle_daide_gauche_taille_standard(objet)
{
	var pos_x = objet.offset().left;
	var pos_y = objet.offset().top;
	var larg = objet.width();
	var haut = objet.height();
	$("#fleche_gauche").css("height", $(window).height()*0.3);
	var larg_fleche = $("#fleche_gauche").width();
	var haut_fleche = $("#fleche_gauche").height();
	$("#fleche_gauche").css("top", pos_y + 0.5*haut - 0.5*haut_fleche).css("left", pos_x - larg_fleche);
	$("#fleche_gauche").show("scale", {easing: "swing", percent: 400}, 1000, function(){
		$("#fleche_gauche").effect("pulsate", {easing: "swing"}, 1000, function(){
		});
	});
}

function focus_bulle_daide_droite_taille_standard(objet)
{
	var pos_x = objet.offset().left;
	var pos_y = objet.offset().top;
	var larg = objet.width();
	var haut = objet.height();
	$("#fleche_droite").css("height", $(window).height()*0.3);
	var larg_fleche = $("#fleche_droite").width();
	var haut_fleche = $("#fleche_droite").height();
	$("#fleche_droite").css("top", pos_y + 0.5*haut - 0.5*haut_fleche).css("left", pos_x + larg);
	$("#fleche_droite").show("scale", {easing: "swing", percent: 400}, 1000, function(){
		$("#fleche_droite").effect("pulsate", {easing: "swing"}, 1000, function(){
		});
	});
}

$("#bulle_daide").css("position", "absolute").css("z-index", "1500").css("cursor", "pointer");
var adresse = $("#bulle_daide").html();
var numero = 0;
var chargement_page = true;
var bulle;
function recuperer_bulle_daide()
{
	$.ajax({
			url: '/app/controllers/ajax.php',
			type :'POST',
			data: 'bulle_daide='+ numero +'&adresse=' + adresse,
			dataType: 'json',
			success: function(resultat, statut){
				bulle = resultat; //On récupère le joueur
				if(chargement_page == false && ( (bulle.adresse == "index" && (bulle.numero == 4 || bulle.numero == 6 || bulle.numero == 8) ) || ( bulle.adresse == "prepa_combats" && (bulle.numero == 3) ) ) )
				{
					//Dans certain cas particuliers, la prochaine bulle ne s'affiche qu'au prochain chargement de la page
				}
				else
				{
					configuration_bulle();
				}
				chargement_page = false;
			},
			error: function(resultat, statut, erreur){
				if (status == "timeout") {
					// timeout -> reload the page and try again
					console.log("timeout");
					recuperer_bulle_daide();
				}
			}
		});
}

function configuration_bulle()
{
	switch(bulle.adresse)
	{
		case "index" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 mb2 g'>Bonjour "+playerPseudo+", un petit coup de pouce pour te repérer sur Navadra ?</span><span class='ib l100'>Ces bulles d'aides te permettront de comprendre un peu mieux le fonctionnement du jeu.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_droite_taille_standard($("#bulle_daide"));
					break;
				case 2 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g'>Tu peux activer/désactiver à tout moment ces bulles d'aides en te rendant sur la page Paramètres (petite icône en haut à droite de l'écran).</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche($("#icone_parametres"));
					break;
				case 3 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='g ib l100 mb2'>Chaque jour, tu pourras t'entraîner avec 3 défis différents<br></span><span class='ib l100'>Pourquoi seulement 3 ? Parce que c'est bien plus efficace de s'entraîner un peu mais régulièrement que beaucoup en une seule fois.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_droite($("#lancer_defi"));
					break;
				case 4 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='g ib l100 mb2'>Sur la carte de Navadra, tu pourras voir le portrait de ton avatar.</span><span class='ib l100'>Si jamais il te gêne pour attaquer un monstre, clic dessus et tout en maintenant le clic enfoncé, déplace-le ailleurs.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche($("#avatar_index"));
					break;
				case 5 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='g ib l100 mb2'>Si d'autres joueurs sont connectés, le portrait de leur avatar sera également visible.</span><span class='ib l100'>Tu peux filtrer les joueurs visibles grâce à la liste déroulante. Comme pour ton avatar, tu peux également les déplacer s'ils gênent ou cliquer dessus pour voir leur Profil.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche($("#filtre_avatars"));
					break;
				case 6 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='g ib l100 mb2'>Chaque jour, tu pourras combattre 3 petits monstres.<br>(reconnaissables au chiffre 1 sur leur portrait)</span><span class='ib l100'>Ce sont les monstres les plus fréquents sur Navadra et tu devrais pouvoir t'en sortir seul"+e+"…<br>De temps en temps, tu repéreras de plus gros monstres. Ces derniers te demanderont de t'allier avec d'autres aventuriers. Certains se combattent à 2-3 et d'autres à 4-5.<br><br>Des rumeurs existent sur la présence de monstres LEGENDAIRES tellement puissants qu'il faudrait être entre 6 et 10 pour en venir à bout. Mais ce ne sont sûrement que des rumeurs...</span>");
					afficher_bulle_daide();
					break;
				case 7 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='g ib l100 mb2'>Tu auras plus de chance de découvrir un gros monstre en te connectant après 18h : sur Navadra, les monstres sortent plus facilement après la tombée de la nuit.</span><span class='ib l100'>Ça tombe bien, s'entraîner avant de se coucher est plus efficace !<br><br>Enfin, les gros monstres sont difficiles à traquer et si tu n'engages pas le combat avec tes potes avant la fin du compte à rebours, ils disparaîtront dans la nature...</span>");
					afficher_bulle_daide();
					break;
				case 8 :
					if(playerTotalChallenges == 0 && playerSoloMonsters == 0){
						$("#bulle_daide").css("bottom", "45%").css("width", "40%").css("left", "30%");
						$("#bulle_daide").html("<span class='g ib l100 mb2'>Tu en veux encore ?</span><span class='ib l100 mb2'>Les monstres et les défis sont limités mais tu peux t'entraîner autant que tu veux sur des défis que tu as déjà rencontrés.</span><span class='ib l100'>Pour ça, il te suffit de te rendre sur ton <a class='rouge_fonce' href='/app/controllers/profil.php?id="+playerId+"&tab=challenges'><span class='g under'>Profil dans l'onglet Défi.</span></a></span>");
						afficher_bulle_daide();
					}
					break;
			}
			break;
		case "grimoire" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("bottom", "2%").css("width", "70%").css("left", "15%");
					$("#bulle_daide").html("<span class='g ib l100 mb2'>Tu t'es déjà demandé à quoi servait le bouton \"Réinitialiser\" ?</span><span class='ib l100'>Il te permet de te faire rembourser toutes les Pyrs que tu as dépensées pour apprendre tes sorts. Bien entendu, tu oublies tous tes sorts par la même occasion, faudrait pas abuser non plus...<br><br>Le coût de la réinitialisation augmente à chaque fois mais ne dépassera jamais un total de 100 Pyrs.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_droite_taille_standard($("#reinitialiser_sort"));
					break;
			}
			break;
		case "prepa_combats" :
			switch(bulle.numero)
			{
				case 1 :
					if($("#monter").length && $("#info_nb_chasseurs").html() != "Affrontable seul")
					{
						$("#bulle_daide").css("bottom", "20%").css("width", "30%").css("left", "35%");
						$("#bulle_daide").html("<span class='ib l100 mb2 g'>Ah je vois qu'aujourd'hui tu as décidé de te frotter à un monstre plus puissant que toi...</span><span class='ib l100'>J'apprécie ton goût pour les défis tu sais !</span>");
						afficher_bulle_daide();
					}
					break;
				case 2 :
					if($("#monter").length && $("#info_nb_chasseurs").html() != "Affrontable seul")
					{
						$("#bulle_daide").css("bottom", "20%").css("width", "30%").css("left", "35%");
						$("#bulle_daide").html("<span class='ib l100 mb2 g'>Comme tu peux le voir ce n'est pas recommandé de l'affronter seul, ce serait même plutôt suicidaire…</span><span class='ib l100'>Je te suggère fortement d'appeler d'autres aventuriers à la rescousse !</span>");
						afficher_bulle_daide();
						focus_bulle_daide_gauche($(".icone_form_gauche:eq(0)"));
					}
					break;
				case 3 :
					if($("#monter").length && $("#nb_chasseurs").html() != "1 chasseur de monstres") //Admin sur le combat plus plusieurs chasseurs
					{
						$("#bulle_daide").css("bottom", "20%").css("width", "30%").css("left", "35%");
						$("#bulle_daide").html("<span class='ib l100 g mb2'>La colonne \"Etat\" de permet de savoir si les autres aventuriers sont prêts.</span><span class='ib l100'>Tout le monde doit être prêt pour pouvoir commencer le combat.</span>");
						afficher_bulle_daide();
						focus_bulle_daide_gauche_taille_standard($("#state_contact"));
					}
					break;
				case 4 :
					if($("#monter").length && $("#nb_chasseurs").html() != "1 chasseur de monstres")
					{
						$("#bulle_daide").css("bottom", "20%").css("width", "30%").css("left", "35%");
						$("#bulle_daide").html("<span class='ib l100 g mb2'>Les aventuriers que tu as invités pourront répondre à ton invitation et accéder au combat depuis leur Liste de Combat.</span><span class='ib l100'>C'est aussi depuis cette liste que tu peux voir rapidement si tout le monde est prêt.</span>");
						afficher_bulle_daide();
						focus_bulle_daide_gauche_taille_standard($("#icone_combats"));
					}
					break;
				case 5 :
					if($("#monter").length && $("#nb_chasseurs").html() != "1 chasseur de monstres")
					{
						$("#bulle_daide").css("bottom", "20%").css("width", "30%").css("left", "35%");
						$("#bulle_daide").html("<span class='ib l100 g mb2'>Comme c'est toi qui est le chef du combat, c'est à toi que reviens la responsabilité de l'organiser.</span><span class='ib l100'>Concrètement, ça signifie que c'est toi qui décides de l'ordre dans lequel vous allez affronter ce monstre.</span>");
						afficher_bulle_daide();
					}
					break;
				case 6 :
					if($("#monter").length && $("#nb_chasseurs").html() != "1 chasseur de monstres")
					{
						$("#bulle_daide").css("bottom", "20%").css("width", "30%").css("left", "35%");
						$("#bulle_daide").html("<span class='ib l100 g mb2'>Le premier aventurier commencera le combat et sera remplacé par le suivant si le monstre le bat.</span><span class='ib l100'>Pour modifier l'ordre, sélectionne un aventurier dans la liste et clique sur les flèches haut ou bas.</span>");
						focus_bulle_daide_droite($("#descendre"));
						afficher_bulle_daide();
					}
					break;
				case 7 :
					if($("#monter").length && $("#nb_chasseurs").html() != "1 chasseur de monstres")
					{
						$("#bulle_daide").css("bottom", "20%").css("width", "30%").css("left", "35%");
						$("#bulle_daide").html("<span class='ib l100 g'>Une fois satisfait, c'est au premier aventurier sur la liste de démarrer le combat !</span>");
						afficher_bulle_daide();
					}
					break;
			}
			break;
		case "inviter_contacts" :
			switch(bulle.numero)
			{
				case 1 :
					if(!$(".contacts").length)
					{
						$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
						$("#bulle_daide").html("<span class='ib l100 g mb2'>Evidemment si tu n'as rencontré aucun autre aventurier, ça va être compliqué d'en appeler à la rescousse…</span><span class='ib l100'>Va faire un tour dans l'onglet Recherche et reviens faire ce combat quand tu auras un contact !</span>");
						focus_bulle_daide_gauche_taille_standard($("#icone_recherche"));
						afficher_bulle_daide();
					}
					if($(".contacts").length)
					{
						$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
						$("#bulle_daide").html("<span class='ib l100 g mb2'>Bonne nouvelle tu as au moins un contact sur Navadra, c'est plus pratique pour combattre à plusieurs...</span>");
						afficher_bulle_daide();
					}
					break;
				case 2 :
					if($(".contacts").length)
					{
						$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
						$("#bulle_daide").html("<span class='ib l100 mb2 g'>A toi de choisir avec qui tu souhaites faire équipe.</span><span class='ib l100'>Pour envoyer un appel à l'aide à un aventurier, clique sur l'icône <img class='img_30' src='/webroot/img/icones/plus.png'/> à gauche du contact en question.<br><br>Une fois que tu as lancé toutes tes invitations, clique sur \"Retour\" pour revenir au combat.</span>");
						afficher_bulle_daide();
						focus_bulle_daide_gauche_taille_standard($(".form_gauche:eq(0)"));
					}
					break;
			}
			break;
		case "liste_combats" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g mb2'>Tu pourras voir ici la liste des invitations que tu as envoyées à tes contacts ou que tu as reçues.</span><span class='ib l100'>Si une ligne apparait en surbrillance c'est qu'une action de ta part est requise (soit accepter/refuser une invitation soit combattre puisque c'est ton tour).</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche_taille_standard($("#liste_invitations"));
					break;
				case 2 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g'>Tu pourras également voir l'historique de tes combats de la saison en cours (une saison = un mois).</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche_taille_standard($("#liste_combats_passes"));
					break;
			}
			break;
		case "contacts" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g mb2'>Tu pourras consulter ici la liste de tes contacts.</span><span class='ib l100'>Pour supprimer quelqu'un de cette liste, il te suffit de cliquer sur la croix rouge à côté de son nom.<br><br>Pour ajouter un contact, tu as 2 options...</span>");
					afficher_bulle_daide();
					break;
				case 2 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g mb2'>Première solution : les joueurs connectés apparaissent sur l'ile.</span><span class='ib l100'>Clique sur l'un d'eux pour accéder à son profil, puis clique sur \"Ajouter aux contacts\"</span>");
					afficher_bulle_daide();
					break;
				case 3 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g mb2'>Deuxième solution : l'onglet recherche te permet de trouver d'autres joueurs selon certains critères.</span><span class='ib l100'>Tu pourras alors les ajouter à tes contacts ou leur envoyer un message.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche_taille_standard($("#icone_recherche"));
					break;
			}
			break;
		case "classement" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 mb2 g'>C'est ici que tu pourras consulter le classement des joueurs sur Navadra.</span><span class='ib l100'>Une seule chose compte pour ce classement : <span class='g'>le Prestige </span>!</span>");
					afficher_bulle_daide();
					break;
				case 2 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g'>Le classement de la semaine permet de voir la progression en Prestige.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche_taille_standard($("#semaine"));
					break;
				case 3 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g'>Quant au classement global, c'est le total de Prestige gagné depuis le début de la Saison (une saison = un mois) !</span>");
					afficher_bulle_daide();
					$("#global").click();
					focus_bulle_daide_gauche_taille_standard($("#global"));
					break;
			}
			break;
		case "entrainement" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("bottom", "25%").css("width", "40%").css("left", "40%");
					$("#bulle_daide").html("<span class='ib l100 g mb2'>L'entraînement te permet de travailler un défi en temps illimité.</span><span class='ib l100'>Bien entendu, il ne te permet pas d'obtenir de récompense : faut pas rêver !<br><br>En revanche, ça reste très pratique pour t'échauffer avant le vrai défi.</span>");
					afficher_bulle_daide();
					break;
			}
			break;
		case "histoires" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("bottom", "5%").css("width", "70%").css("left", "15%");
					$("#bulle_daide").html("<span class='g ib l100 mb2'>En progressant dans le jeu tu débloqueras des cinématiques qui t'en apprendrons plus sur les personnages, leur histoire et celle de Navadra.</span><span class='ib l100'>Si une ligne est en surbrillance, c'est que tu n'as pas encore vu la vidéo associée.<br><br>Pas de chance, les vidéos ne sont pas encore prêtes donc il n'y a pas grand chose à voir pour l'instant…<br><br>Ce sera pour la prochaine version !</span>");
					afficher_bulle_daide();
					break;
			}
			break;
		case "messages" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g mb2'>Dans l'onglet gauche, tu as la liste de tes conversation existantes.</span><span class='ib l100'>Si tu cliques sur l'une d'entre elle, cela charge son contenu dans l'onglet de droite.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche_taille_standard($("#conversation_list"));
					break;
				case 2 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g'>Tu peux aussi démarrer une nouvelle conversation en cliquant sur l'icône \"Nouvelle conversation\" et en choisissant un de tes contacts (si tu en as).</span>");
					afficher_bulle_daide();
					focus_bulle_daide_droite($("#nouvelle_conv"));
					break;
				case 3 :
					$("#bulle_daide").css("bottom", "5%").css("width", "40%").css("left", "30%");
					$("#bulle_daide").html("<span class='ib l100 g'>Pour envoyer un message, saisis ton texte dans le champ \"Message\" et clique sur \"Envoyer\".</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche_taille_standard($("#send_mesage"));
					break;
			}
			break;
		case "parametres" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("top", "10%").css("width", "30%").css("left", "35%");
					$("#bulle_daide").html("<span class='g ib l100 mb2'>C'est ici que tu pourras changer les informations relatives à ton compte mais aussi activer/désactiver les bulles d'aides.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_gauche_taille_standard($("#activer_bulles_daides"));
					break;
			}
			break;
		case "recherche" :
			switch(bulle.numero)
			{
				case 1 :
					$("#bulle_daide").css("bottom", "20%").css("width", "30%").css("left", "35%");
					$("#bulle_daide").html("<span class='g ib l100 mb2'>Tu pourras chercher ici des joueurs en filtrant selon certains critères (nom, prénom, classe, etc.).</span><span class='ib l100'>Tu peux aussi ne choisir aucun critère pour afficher la liste de tous les joueurs.<br><br>Lorsque tu as sélectionné les critères qui t'intéressent clique sur le bouton Recherche pour afficher le résultat.</span>");
					afficher_bulle_daide();
					focus_bulle_daide_droite_taille_standard($("#launch_search"));
					break;
			}
			break;
	}
}

$("#bulle_daide").hide();
function afficher_bulle_daide()
{
	$("<span class='mh1 g ib l100 i p0'>-- Clique ici pour fermer --</span>").appendTo($("#bulle_daide"));
	$("#bulle_daide").show("clip", {easing: "swing"}, 500);
	if(playerVolumeSoundEffects == 1){
		$("#son_bulles_daide").trigger("play");
	}
}

$("#bulle_daide").on("click", function(){
	masquer_bulle_daide();
});

function masquer_bulle_daide()
{
	$("#bulle_daide").hide("clip", {easing: "swing"}, 500);
	$("#fleche_droite").hide();
	$("#fleche_gauche").hide();
	$("#fleche_gauche2").hide();
	numero = bulle.numero;
	recuperer_bulle_daide();
}

if(adresse == "accueil_defi")
{
	var click_jouer = false;
	//Lorsque le joueur clique sur Jouer
	$(".jouer").unbind("click").on("click", function(event){
		event.preventDefault();
		if($(".contact:eq(1)").length) //Si le joueur a au moins un contact on le propose d'en défier 1
		{
			$("#defier_contact").hide();
			$("#defier_contact").show("clip", {easing: "swing"}, 500);
			configuration_bulle();
		}
		else if(click_jouer == false) //Si le joueur n'a aucun contact, on lance le défi
		{
			click_jouer = true;
			$(location).attr('href', "/app/controllers/new_defi.php");
		}
	});
}
else if(adresse == "grimoire")
{
	$(".icones_sorts_grimoire").on("click", function(){
		configuration_bulle();
	});
}

recuperer_bulle_daide();


$(window).load(function(){

});
