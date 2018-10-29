// JavaScript Document

$(function(){

if($("#portrait_footer").length) //Si et seulement si l'utilisateur est connecté
{

	if(playerTuto == "fini") //Si et seulement si l'utilisateur est sorti du tuto
	{
		//TETE AVATAR
		$("#portrait_footer").on("mouseover", function(){
			//$(this).attr("src", "/webroot/img/icones/grimoire_selec.png");
			$(this).css("width", $(this).width() + 5);
		});

		$("#portrait_footer").on("mouseout", function(){
			//$(this).attr("src", "/webroot/img/icones/grimoire_normal.png");
			$(this).css("width", $(this).width() - 5);
		});

		//PRESTIGE
		$("#icone_prestige").on("mouseover", function(){
			$(this).attr("src", "/webroot/img/icones/prestige_selec.png");
			$(this).css("width", $(this).width() + 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge - 2);
		});

		$("#icone_prestige").on("mouseout", function(){
			$(this).attr("src", "/webroot/img/icones/prestige.png");
			$(this).css("width", $(this).width() - 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge + 2);
		});

		//GRIMOIRE
		$("#icone_grimoire").on("mouseover", function(){
			$(this).attr("src", "/webroot/img/icones/grimoire_selec.png");
			$(this).css("width", $(this).width() + 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge - 5);
		});

		$("#icone_grimoire").on("mouseout", function(){
			$(this).attr("src", "/webroot/img/icones/grimoire_normal.png");
			$(this).css("width", $(this).width() - 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge + 5);
		});

		//LISTE COMBATS
		$("#icone_combats").on("mouseover", function(){
			$(this).attr("src", "/webroot/img/icones/liste_combats_selec.png");
			$(this).css("width", $(this).width() + 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge - 5);
		});

		$("#icone_combats").on("mouseout", function(){
			$(this).attr("src", "/webroot/img/icones/liste_combats_normal.png");
			$(this).css("width", $(this).width() - 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge + 5);
		});

		//HISTOIRES
		/*
		$("#icone_histoires").on("mouseover", function(){
			$(this).attr("src", "/webroot/img/icones/histoires_selec.png");
			$(this).css("width", $(this).width() + 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge - 5);
		});

		$("#icone_histoires").on("mouseout", function(){
			$(this).attr("src", "/webroot/img/icones/histoires_normal.png");
			$(this).css("width", $(this).width() - 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge + 5);
		}); */

		//MESSAGES
		$("#icone_messages").on("mouseover", function(){
			$(this).attr("src", "/webroot/img/icones/messages_selec.png");
			$(this).css("width", $(this).width() + 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge - 5);
		});

		$("#icone_messages").on("mouseout", function(){
			$(this).attr("src", "/webroot/img/icones/messages_normal.png");
			$(this).css("width", $(this).width() - 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge + 5);
		});

		//CONTACTS
		$("#icone_contacts").on("mouseover", function(){
			$(this).attr("src", "/webroot/img/icones/contacts_selec.png");
			$(this).css("width", $(this).width() + 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge - 5);
		});

		$("#icone_contacts").on("mouseout", function(){
			$(this).attr("src", "/webroot/img/icones/contacts_normal.png");
			$(this).css("width", $(this).width() - 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge + 5);
		});

		//RECHERCHE
		$("#icone_recherche").on("mouseover", function(){
			$(this).attr("src", "/webroot/img/icones/recherche_selec.png");
			$(this).css("width", $(this).width() + 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge - 5);
		});

		$("#icone_recherche").on("mouseout", function(){
			$(this).attr("src", "/webroot/img/icones/recherche_normal.png");
			$(this).css("width", $(this).width() - 5);
			var marge = parseInt($(this).parent().parent().parent().css("margin-top").replace(/px/,""));
			$(this).parent().parent().parent().css("margin-top", marge + 5);
		});

		$("#icone_deco").parent().on("click", function(event){
			event.preventDefault();
			$(location).attr('href', "/app/controllers/deconnexion.php");
		});


	}

//Info-bulle title
$("img").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

$("#xp_joueur").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

$("#portrait_footer").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

if($("#closeWindow").length){
	$("#closeWindow").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/refuser_selec.png");
	});

	$("#closeWindow").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/refuser.png");
	});
}


//Script pour déco automatique au bout de 20 min d'inactivité
/*
setTimeout(function(){
	$(location).attr('href', "/app/controllers/deconnexion.php");
}, 20*60*1000);
*/

  //GESTION DU FEEDBACK TESTEURS s'il existe
	$("#feedback").hide();

}

//Son lorsqu'on clique sur un élément activable
$('a').on("click", function(){
	if(playerVolumeInterface == 1){
		$("#son_clic").trigger("play");
	}
});

$('.form_droite').on("click", function(){
	if(playerVolumeInterface == 1){
		$("#son_clic").trigger("play");
	}
});

});

//Lève les rideaux

$(window).load(function(){

	if($("#warningMessage").length){
		$("#warningBlur").hide();
		$("#warningMessage").hide();
	}

if($("#portrait_footer").length){ //Si et seulement si l'utilisateur est connecté
	superpose_portrait("#portrait_footer");
	initiateGuidedTour();

	//Gestion des problèmes d'affichage des icones du footer qui dépassent de l'écran
	if($("#pyrs_joueur").offset().top + $("#pyrs_joueur").height() + 10 > $(window).height())
	{
		$("#barre_menu_gauche").css("height", $("#barre_menu_gauche").height() + 10);
		$("#barre_menu_droite").css("height", $("#barre_menu_gauche").height() + 10);
	}

	if($("#barre_menu_droite img:eq(0)").offset().top + $("#barre_menu_droite img:eq(0)").height() > $(window).height())
	{
		$("#barre_menu_droite img").css("height", $(window).height() - $("#barre_menu_droite img:eq(0)").offset().top - 10);
		$("#barre_menu_droite img").removeClass("l100");
	}

	$('#xp_joueur').css("height", Math.max(parseInt($('#xp_joueur').css("height")), $(window).height() * 0.085));
	$('#xp_joueur').css("top", $('#fond_niveau_joueur').offset().top - $('#xp_joueur').height() - 10);

	//BARRE XP
	var pourcent_xp = parseInt($("#xp_joueur").html());
	$("#xp_joueur").html("").removeClass("cache");
	plot1 = $.jqplot('xp_joueur', [[[1,pourcent_xp]]], {
				stackSeries: true,
				captureRightClick: false,
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					shadowAngle: 0,
					rendererOptions: {
						barDirection: 'vertical',
						barWidth: 200,
						highlightMouseDown: true,
						highlightMouseOver: true,
						shadowOffset: 0,
					},
					pointLabels: {show: false, formatString: '%d'}
				},
				legend: {
					show: false
				},
				grid: {
					drawGridLines: false,
					gridLineColor: '#d6f9fe',
					background: '#d6f9fe',
					borderWidth: 0,
					shadow: false,
				},
				gridPadding: {
					top: 0,
					bottom: 0,
					left: 0,
					right: 0
				},
				axesDefaults: {
					show: false,
					showTicks: false,
					showTickMarks: false,
				},
				axes: {
					xaxis: {
						show: false,
						showTicks: false,
						showTickMarks: false,

					},
					yaxis: {
						show: false,
						showTicks: false,
						showTickMarks: false,
						max: 100,
						min: 0,

					},
				},
				animate : false,
				seriesColors: ["#04b6d2"]
	});

	function afficher_notif(icone_notif, notif, icone){
		icone_notif.hide().removeClass("cache");
		notif.hide().removeClass("cache");
		var pos = icone.offset();
		var hauteur_icone = icone.height();
		var hauteur_notif = icone_notif.height();
		var largeur_icone = icone.width();
		var largeur_notif = icone_notif.width();
		var x = pos.left + largeur_icone - 0.8*largeur_notif;
		var y = pos.top + hauteur_icone - 0.8*hauteur_notif;
		icone_notif.css("left", x).css("top", y).show();
		notif.css("left", x).css("top", y + 4).css("width", largeur_notif).show();
	}

	function afficher_petite_notif(icone_notif, notif, icone){
		icone_notif.hide().removeClass("cache");
		notif.hide().removeClass("cache");
		var pos = icone.offset();
		var hauteur_icone = icone.height();
		var hauteur_notif = icone_notif.height();
		var largeur_icone = icone.width();
		var largeur_notif = icone_notif.width();
		var x = pos.left + largeur_icone - 0.4*largeur_notif;
		var y = pos.top + hauteur_icone - 0.4*hauteur_notif;
		icone_notif.css("left", x).css("top", y).show();
		notif.css("left", x).css("top", y + 2).css("width", largeur_notif).show();
	}
	/*
	if($("#feedback").length){
		function afficher_notif_feedback(icone_notif, notif, feedback){
			icone_notif.hide().removeClass("cache");
			notif.hide().removeClass("cache");
			var pos = feedback.offset();
			var hauteur_feedback = feedback.height();
			var hauteur_notif = icone_notif.height();
			var largeur_feedback = feedback.width();
			var largeur_notif = icone_notif.width();
			var x = pos.left + largeur_feedback - 0.4*largeur_notif;
			var y = pos.top + hauteur_feedback - 0.4*hauteur_notif;
			icone_notif.css("left", x).css("top", y).show();
			notif.css("left", x).css("top", y + 4).css("width", largeur_notif).show();
		}
	} */

	//NOTIFICATIONS
	if($("#notif_grimoire").length)//S'il y a des notifications grimoire
	{
		var icone_notif_grimoire = $('<img src="/webroot/img/icones/icone_notif.png" id="notif_grimoire_ico" class="img_30 cache icone_notifications">').appendTo('body').load(function(){
			afficher_notif(icone_notif_grimoire, $("#notif_grimoire"), $("#icone_grimoire"));
		});
	}

	if($("#notif_combats").length)//S'il y a des notifications combats
	{
		var icone_notif_combats = $('<img src="/webroot/img/icones/icone_notif.png" id="notif_combats_ico" class="img_30 cache icone_notifications">').appendTo('body').load(function(){
			afficher_notif(icone_notif_combats, $("#notif_combats"), $("#icone_combats"));
		});
	}

	/*
	if($("#notif_histoires").length)//S'il y a des notifications histoires
	{
		var icone_notif_histoires = $('<img src="/webroot/img/icones/icone_notif.png" id="notif_histoires_ico" class="img_30 cache icone_notifications">').appendTo('body').load(function(){
			afficher_notif(icone_notif_histoires, $("#notif_histoires"), $("#icone_histoires"));
		});
	} */

	if($("#notif_messages").length)//S'il y a des notifications messages
	{
		var icone_notif_messages = $('<img src="/webroot/img/icones/icone_notif.png" id="notif_messages_ico" class="img_30 cache icone_notifications">').appendTo('body').load(function(){
			afficher_notif(icone_notif_messages, $("#notif_messages"), $("#icone_messages"));
		});
	}

	if($("#notif_parametres").length)//S'il y a des notifications parametres
	{
		var icone_notif_parametres = $('<img src="/webroot/img/icones/icone_notif.png" id="notif_parametres_ico" class="img_20 cache icone_notifications">').appendTo('body').load(function(){
			afficher_petite_notif(icone_notif_parametres, $("#notif_parametres"), $("#icone_parametres"));
		});
	}

	if(timeSlot != "NoTimeSlot"){
		$('<div id="limitationNotification" class="p2 g align_centre" title="Ton professeur a créé un créneau pour jouer en illimité.">ILLIMITÉ</div>').appendTo("body");
		$("#limitationNotification").css("position", "absolute").css("-moz-border-radius","14px").css("-webkit-border-radius","14px").css("border-radius","14px").css("padding", "2px 10px 2px 10px").css("background-color", "#de7300").css("color", "#ffdc00");
		$("#limitationNotification").css("right", "-4px").css("top",$("#fond_param_deco").offset().top + $("#fond_param_deco").height() - 4);
		$("#limitationNotification").css("width", $("#chatNotifications").height()).css("border", "4px solid #702600").css("cursor", "pointer");
		$("#limitationNotification").tooltip({
			show: {
				effect: "slideDown",
				delay: 250
			}
		});
	} else if(playerGameLimitation == 1 && window.location.pathname != "/app/controllers/parametres.php"){
		/*
		$('<div id="limitationNotification" class="p2 g align_centre">JEU LIMITÉ</div>').appendTo("body");
		$("#limitationNotification").css("position", "absolute").css("-moz-border-radius","14px").css("-webkit-border-radius","14px").css("border-radius","14px").css("padding", "2px 10px 2px 10px").css("background-color", "#de7300").css("color", "#ffdc00");
		$("#limitationNotification").css("right", "-4px").css("top",$("#fond_param_deco").offset().top + $("#fond_param_deco").height() - 4);
		$("#limitationNotification").css("width", $("#chatNotifications").height()).css("border", "4px solid #702600").css("cursor", "pointer");
		$("#limitationNotification").on("click", function(){
			displayEndFreePeriod(true, false);
		}) */
	}

	if(screen.width === window.innerWidth && window.innerHeight == screen.height) {
    $('<div id="fullScreen" class="g align_centre">Presse "F11" pour quitter le Plein Ecran</div>').appendTo("body").hide();
	} else {
		$('<div id="fullScreen" class="g align_centre">Presse "F11" pour passer en Plein Ecran</div>').appendTo("body").show();
	}
	$("#fullScreen").css("position", "absolute").css("-moz-border-radius","14px").css("-webkit-border-radius","14px").css("border-radius","14px").css("padding", "2px 10px 2px 10px").css("background-color", "#5b000c").css("color", "#f2ae00");
	$("#fullScreen").css("left", 0.90*$("#barre_menu_gauche").width()).css("bottom","-4px").css("z-index", "-1");
	$("#fullScreen").css("width", 1.1*$("#barre_menu_droite").offset().left - $("#barre_menu_gauche").width()).css("border", "4px solid #fed100");

	$(window).on('resize', function(){
    if(screen.width === window.innerWidth && window.innerHeight == screen.height){
			$("#fullScreen").html('Presse "F11" pour quitter le Plein Ecran').hide();
    } else {
			$("#fullScreen").html('Presse "F11" pour passer en Plein Ecran').show();
		}
  });

	/*
	if($("#feedback").length && $("#notif_ameliorations").length)//S'il y a des notifications d'améliorations
	{
		var icone_notif_feedback = $('<img src="/webroot/img/icones/icone_notif.png" id="notif_ameliorations_ico" class="img_30 cache icone_notifications">').appendTo('body').load(function(){
			$("#notif_ameliorations").css("position", "absolute").css("z-index", "99");
			icone_notif_feedback.css("position", "absolute").css("z-index", "98");
			afficher_notif_feedback(icone_notif_feedback, $("#notif_ameliorations"), $("#suggestion"));
		});
	} */

}
  if(playerVolumeInterface == 1){
		$('#son_chargement').prop("volume", 0.2);
  	$("#son_chargement").trigger("play");
  }
  $("#rideau_haut").hide("slide",{easing:"easeInExpo", direction: "up"}, "slow");
  $("#rideau_bas").hide("slide",{easing:"easeInExpo", direction: "down"}, "slow", function(){
	 if($("#fleche_tuto").length){
		$("#fleche_tuto").effect("pulsate", {easing: "swing", times: 8}, 2000);
	}
	 if($("#bilan_saison").length) {
	 	$("#bilan_saison").show("clip",{easing:"swing"}, 500, function(){
			if(playerVolumeSoundEffects == 1){
				$("#son_fin_saison").trigger("play");
			}
		});
	 }
  });

});
