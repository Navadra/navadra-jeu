// JavaScript Document
var challengeNotion = $("#challengeNotion").html();
var levelBeforeChallenge = parseFloat($("#levelBeforeChallenge").html());
var levelAfterChallenge = parseFloat($("#levelAfterChallenge").html());
var congratulations = parseFloat($("#congratulations").html());
var challengeTries = parseFloat($("#challengeTries").html()) - 1;
var levelUp = $("#levelUp").html();
var newLevel = parseFloat($("#newLevel").html());
var diagram;
var playerAvatar;
var limitReached = false;
if($("#displayEndFreePeriod").length){
	limitReached = true;
}


//Avoid double click on "Play"
$(".jouer").on("click", function (event) {
   event.preventDefault();
	if (click_jouer == false) //Si le joueur n'a aucun contact, on lance le défi
    {
      click_jouer = true;
      $(location).attr('href', "/app/controllers/new_defi.php");
    }
});

function initiate_styles_diagram(){
	// Load the fonts
	Highcharts.createElement('link', {
	   href: 'https://fonts.googleapis.com/css?family=Dosis:400,600',
	   rel: 'stylesheet',
	   type: 'text/css'
	}, null, document.getElementsByTagName('head')[0]);

	Highcharts.theme = {
	   colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
		  "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
	   chart: {
		  backgroundColor: null,
		  style: {
			 fontFamily: "Dosis, sans-serif"
		  }
	   },
	   title: {
		  style: {
			 fontSize: '16px',
			 fontWeight: 'bold',
			 textTransform: 'uppercase'
		  }
	   },
	   tooltip: {
		  borderWidth: 0,
		  backgroundColor: 'rgba(219,219,216,0.8)',
		  shadow: false
	   },
	   legend: {
		  itemStyle: {
			 fontWeight: 'bold',
			 fontSize: '13px'
		  }
	   },
	   xAxis: {
		  gridLineWidth: 1,
		  labels: {
			 style: {
				fontSize: '12px'
			 }
		  }
	   },
	   yAxis: {
		  minorTickInterval: 'auto',
		  title: {
			 style: {
				textTransform: 'uppercase'
			 }
		  },
		  labels: {
			 style: {
				fontSize: '12px'
			 }
		  }
	   },
	   plotOptions: {
		  candlestick: {
			 lineColor: '#404048'
		  }
	   },


	   // General
	   background2: '#F0F0EA'

	};

	// Apply the theme
	Highcharts.setOptions(Highcharts.theme);
}

function initiate_bars_diagram(title, legend, series){
	initiate_styles_diagram()
	diagram = new Highcharts.Chart({

		chart: {
			renderTo: 'progress_graph',
			animation: false
		},

		title: {
			text: ""
		},

		xAxis: {
			categories: legend,
			minorTickLength: 0,
			tickLength: 0,
			lineColor: 'transparent',
			gridLineColor: 'transparent'
		},

		yAxis: {
		   title: "",
		   lineWidth: 0,
		   minorGridLineWidth: 0,
		   lineColor: 'transparent',
		   gridLineColor: 'transparent',
		   labels: {
			   enabled: false
		   },
		   minorTickLength: 0,
		   tickLength: 0
		},

		plotOptions: {
			series: {
				point: {
					events: {

						drag: function (e) {
						},
						drop: function () {

						},
						click: function (e) {
						}
					}
				},
				stickyTracking: false
			},
			column: {
				stacking: false
			},
			line: {
				cursor: 'ns-resize'
			}
		},

		tooltip: {
			yDecimals: 1,
			enabled: false
		},

		credits: {
			enabled: false
		},

		series: series

	});
}

function color_bars(level){
	for(var i = 1;i<= 5;i++)	{
		diagram.series[0].data[i-1].update({ color: '#7cb5ec' });
	}
	if(level < 5)	{
		//diagram.series[0].data[4].update({ color: '#CFD0D1' });
	}
	if(level == 6)	{
		for(var i = 1;i<= 5;i++)	{
			diagram.series[0].data[i-1].update({ color: '#73EB73' });
		}
		$("<img id='check1' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check1"), 1);
		});
		$("<img id='check2' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check2"), 2);
		});
		$("<img id='check3' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check3"), 3);
		});
		$("<img id='check4' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check4"), 4);
		});
		$("<img id='check5' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check5"), 5);
		});
	}	else if(level > 0)	{
		diagram.series[0].data[level-1].update({ color: '#c80505' });
	}
}

function position_images(level){
	playerAvatar = $("#playerAvatar");
	playerAvatar.css("position", "absolute");
	if(level < 5)	{
		/*
		$("<img id='lock' class='img_30' src='/webroot/img/icones/lock.png' />").appendTo("body").load(function(){
			$("#lock").css("position", "absolute");
			$("#lock").css("top", $("#progress_graph").offset().top + 0.25*$("#progress_graph").height() + diagram.series[0].data[4].plotY);
			var offsetX = 0.02*$("#progress_graph").width() + 0.96*$("#progress_graph").width()*4.5/5 ;
			$("#lock").css("left", $("#progress_graph").offset().left + offsetX - 0.5*$("#lock").width());
		}); */
	}
	if(challengeTries == 0)	{
		playerAvatar.css("top", $("#progress_graph").offset().top + 0.1*$("#progress_graph").height());
		playerAvatar.css("left", $("#progress_graph").offset().left + 0.1*$("#progress_graph").width());
		$("<img id='question_mark' class='img_30' src='/webroot/img/icones/question_mark.png' />").appendTo("body").load(function(){
			$("#question_mark").css("position", "absolute");
			$("#question_mark").css("left", playerAvatar.offset().left + playerAvatar.width() - 1.1*$("#question_mark").width() );
			$("#question_mark").css("top", playerAvatar.offset().top + playerAvatar.height() - 1.1*$("#question_mark").height() );
		});
	}
	else if (level < 6 && level > 0)	{
		playerAvatar.css("top", $("#progress_graph").offset().top + diagram.series[0].data[level-1].plotY - 0.5*playerAvatar.height());
		var offsetX = 0.02*$("#progress_graph").width() + 0.96*$("#progress_graph").width()*(level-0.5)/5 ;
		playerAvatar.css("left", $("#progress_graph").offset().left + offsetX - 0.5*playerAvatar.width());
	}
	else if (level == 6)	{
		playerAvatar.hide();
	}
}

function change_level(start, end){
	if(end == 5) //Remove lock
	{ /*
		$("#lock").effect("pulsate", {easing: "swing"}, 500, function(){
			$("#lock").hide();
			diagram.series[0].data[4].update({ color: '#7cb5ec' });
		}); */
	}
	if(end < 6) //Move player icon
	{
		var endX = $("#progress_graph").offset().left + 0.02*$("#progress_graph").width() + 0.96*$("#progress_graph").width()*(end-0.5)/5 - 0.5*playerAvatar.width();
		var endY = $("#progress_graph").offset().top + diagram.series[0].data[end-1].plotY - 0.5*playerAvatar.height();
		var effect = {top: endY, left: endX};
		playerAvatar.effect("pulsate", {easing: "swing"}, 500, function () {
			if($("#question_mark").length){$("#question_mark").hide();};
			playerAvatar.animate(effect, 1500, "easeInOutCirc", function () {
				color_bars(levelAfterChallenge);
				if(playerTuto == "fini"){
					playerAvatar.effect("pulsate", {easing: "swing"}, 500, function(){
						displayEndFreePeriod(limitReached, true);
					});
				} else {
					displayEndFreePeriod(limitReached, true);
				}
			});
		});
	}
	else if(end == 6) //Final animation for cleaning challenge
	{
		var checkLoaded = 0;
		playerAvatar.hide();
		$("<img id='check1' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			$("#check1").hide();
			position_check($("#check1"), 1);
			checkLoaded ++;
			animate_check(checkLoaded);
		});
		$("<img id='check2' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			$("#check2").hide();
			position_check($("#check2"), 2);
			checkLoaded ++;
			animate_check(checkLoaded);
		});
		$("<img id='check3' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			$("#check3").hide();
			position_check($("#check3"), 3);
			checkLoaded ++;
			animate_check(checkLoaded);
		});
		$("<img id='check4' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			$("#check4").hide();
			position_check($("#check4"), 4);
			checkLoaded ++;
			animate_check(checkLoaded);
		});
		$("<img id='check5' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			$("#check5").hide();
			position_check($("#check5"), 5);
			checkLoaded ++;
			animate_check(checkLoaded);
		});
	}
}

function animate_check(loaded){
	if(loaded == 5){
		$("#check1").show("pulsate", {easing: "swing"}, 500, function () {
			diagram.series[0].data[0].update({ color: '#73EB73' });
			$("#check2").show("pulsate", {easing: "swing"}, 500, function () {
				diagram.series[0].data[1].update({ color: '#73EB73' });
				$("#check3").show("pulsate", {easing: "swing"}, 500, function () {
					diagram.series[0].data[2].update({ color: '#73EB73' });
					$("#check4").show("pulsate", {easing: "swing"}, 500, function () {
						diagram.series[0].data[3].update({ color: '#73EB73' });
						$("#check5").show("pulsate", {easing: "swing"}, 500, function () {
							diagram.series[0].data[4].update({ color: '#73EB73' });
							display_ultime_mastery();
						});
					});
				});
			});
		});
	}
}

function position_check(object, level){
	object.css("position", "absolute");
	var offsetY = $("#progress_graph").offset().top + diagram.series[0].data[level-1].plotY - 0.5*object.height();
	var offsetX = $("#progress_graph").offset().left + 0.02*$("#progress_graph").width() + 0.96*$("#progress_graph").width()*(level-0.5)/5 - 0.5*object.width();
	object.css("top", offsetY).css("left", offsetX);
}

function display_ultime_mastery(){
	$("<div id='ultime_mastery' class='p7 vert g pfun bulle_daide'></div>").appendTo("body").hide();
	$("#ultime_mastery").html("maîtrise ULTIME du défi "+challengeNotion+" !!!")
	$("#ultime_mastery").css("position", "absolute").css("width", "50%").css("padding", "30px 10px 30px 10px").css("top", "20%").css("left", "25%");
	if(playerVolumeSoundEffects == 1){
		$("#sound_challenge_ultime_mastery").trigger("play");
	}
	$("#ultime_mastery").show("scale", {easing: "swing", percent: 100}, 1000, function () {
		$("#ultime_mastery").effect("pulsate", {easing: "swing"}, 1000, function () {
			setTimeout(function(){
				$("#ultime_mastery").hide("scale", {easing: "swing", percent: 0}, 1000, function(){
					displayEndFreePeriod(limitReached, true);
				});
			}, 4000);
		});
	});
}

function display_impressions(){
	//display_clip($("#impressions"));
}

//Allow to choose another challenge
var hauteur_elem_base;
var chosenElement;
var title, description, strength, weakness;
var element_choisi = false;

function choix_element(){
		if(!$(".choix_element").length)	{
			$('<div class="choix_element"></div>').appendTo("body");
			$(".choix_element").css("z-index", "99");
			$('<span class="consigne mb4 p3 g">Quelle magie veux-tu pratiquer ?</span>').appendTo(".choix_element");
			$('<span><img class="element_principal" src="/webroot/img/elements/feu.png" /></span>').appendTo(".choix_element");
			$('<span><img class="element_principal" src="/webroot/img/elements/eau.png" /></span>').appendTo(".choix_element");
			$('<span><img class="element_principal" src="/webroot/img/elements/vent.png" /></span>').appendTo(".choix_element");
			$('<span><img class="element_principal" src="/webroot/img/elements/terre.png" /></span>').appendTo(".choix_element");
			$('<span id="descr_elem" class="ib mh4 mb4 l90 justif"></span>').appendTo(".choix_element");
			hauteur_elem_base = $(".element_principal:eq(0)").css("height");
		}
		$(".element_principal:eq(0)").on("mouseover", function(){ //Clic sur le feu
			title = "<span class='g ib l100 rouge align_centre mb2 p2'>Le Feu - Nombres et Calculs</span>";
			description = "<span class='ib l100 mb4'>Une magie brutale et instantanée. Pour s'entraîner à la magie du Feu, il n'y a pas de meilleur moyen que de <span class='g'>manipuler des nombres</span>.<br>C'est ta puissance de calcul en combat qui te permettra de carboniser tes ennemis.</span>";
			strength = "<span class='ib l100'>Fort contre : <span class='g vert'>La Terre</span></span>";
			weakness = "<span class='ib l100'>Faible contre : <span class='g bleu'>L\'Eau</span></span>";
		});

		$(".element_principal:eq(1)").on("mouseover", function(){ //Clic sur l'eau
			title = "<span class='g ib l100 bleu align_centre mb2 p2'>L'Eau - Gestion de données et Fonctions</span>";
			description = "<span class='ib l100 mb4'>Telle une marée montante, la puissance de la magie de l'Eau augmente progressivement pour engloutir ses ennemis.<br>Pour manier cette magie, il te faudra t'entraîner à <span class='g'>analyser rapidement les données</span> et tendances en combat afin de les utiliser à ton avantage.</span>";
			strength = "<span class='ib l100'>Fort contre : <span class='g rouge'>Le Feu</span></span>";
			weakness = "<span class='ib l100'>Faible contre : <span class='g jaune'>Le Vent</span></span>";
		});

		$(".element_principal:eq(2)").on("mouseover", function(){ //Clic sur le vent
			title = "<span class='g ib l100 jaune align_centre mb2 p2'>Le Vent - Espace et Géométrie</span>";
			description = "<span class='ib l100 mb4'>La magie du Vent est à l'image d'une violente bourrasque: aussi impressionante que rapide et élégante. Ses adeptes la comparent à une danse.<br>Pour utiliser tout son potentiel, il te faudra devenir expert"+e+" en <span class='g'>maîtrise de la géométrie et de l'espace</span>.</span>";
			strength = "<span class='ib l100'>Fort contre : <span class='g bleu'>L'Eau</span></span>";
			weakness = "<span class='ib l100'>Faible contre : <span class='g vert'>La Terre</span></span>";
		});

		$(".element_principal:eq(3)").on("mouseover", function(){ //Clic sur la terre
			title = "<span class='g ib l100 vert align_centre mb2 p2'>La Terre - Grandeurs et Mesures</span>";
			description = "<span class='ib l100 mb4'>La magie de la Terre est profonde et solide. Elle puise toute sa force des entrailles de ce monde et c'est un lien que tu devras entretenir.<br>Ton entraînement consistera à mieux <span class='g'>comprendre les forces et grandeurs</span> impliquées pour développer cette connexion.</span>";
			strength = "<span class='ib l100'>Fort contre : <span class='g jaune'>Le Vent</span></span>";
			weakness = "<span class='ib l100'>Faible contre : <span class='g rouge'>Le Feu</span></span>";
		});

		$(".element_principal").on("mouseover", function(){
			$("#user_info").hide();
			$('#descr_elem').html(title+description+strength+weakness);
			chosenElement = $(this).attr("src").replace(/^.{1,}\/(.{3,5})\.png/, '$1');
			$(".element_principal").removeAttr("style");
			$(this).css("height", hauteur_elem_base + 10).css("border", "2px #e0c399 solid").css("background-color", "#ffe4bd").css("-moz-border-radius", "10px").css("-webkit-border-radius", "10px").css("border-radius", "10px");
			element_choisi = true;
			$(".valider_choix_elem").show();
			$("#valider_icone").show();
		});

		$(".element_principal").on("click", function(){
			if(element_choisi){
				assignChallenge(chosenElement);
			}
		});
}

function popUpDisplay(){
	if(playerAssignedChallenges > 0){
		$(location).attr('href',"/app/controllers/accueil_defi.php");
	} else if(playerUnassignedChallenges > 0){
		choix_element();
	} else if(levelBeforeChallenge == 5) {
		$(location).attr('href',"/app/controllers/profil.php?id="+playerId+"&tab=challenges");
	} else {
		$(location).attr('href',"/app/controllers/grimoire.php");
	}
}

function assignChallenge(elmnt){
	$.ajax({url: '/app/controllers/ajax.php',
	   type: 'POST',
	   data: 'assignChallenge='+elmnt,
	   dataType: 'html',
	   success: function (result, status) {
		 $(location).attr('href',"/app/controllers/accueil_defi.php");
	   },
	   error: function (result, status, error) {
		 $("<div title='Désolé...'>Erreur du serveur : peux-tu réessayer ?</div>").dialog();
	   },
	   complete: function (result, status) {
	   }
	});
}

$("#bulle_bas").css("cursor", "pointer").on("click", function () {
     popUpDisplay();
});

$("#lancer_defi_bas").css("cursor", "pointer").on("click", function () {
     popUpDisplay();
});


$(window).load(function(){

if(playerTuto == "fini" && (playerAssignedChallenges + playerUnassignedChallenges > 0) ){
	$("#bulle_bas").hide();
	$("#lancer_defi_bas").hide();
} else if($("#lancer_defi_bas").length) {
	$("#lancer_defi_bas").hide();
}

if(playerTuto != "fini"){
	$("#bulle_bas").unbind("click").on("click", function(event){
		event.preventDefault();
	});
}

//Diagram initialization
var series = [];
series.push({
	name: "Niveaux",
	data: [1, 2, 3, 4, 5],
	draggableX: false,
	draggableY: false,
	dragMinY: 0,
	minPointLength: 1,
	type: "column",
	showInLegend: false
});
var legendDiag = ["Maîtrise 1", "Maîtrise 2", "Maîtrise 3", "Maîtrise 4", "Maîtrise 5"];
initiate_bars_diagram("Progression", legendDiag, series);

$("#level_up").hide();
$("#annonce_fin_defi").hide();
var timer = 0;
color_bars(levelBeforeChallenge);
position_images(levelBeforeChallenge);

if ($("#spotTraining").length) {
	$("#spotTraining").hide();
	$("#spotTraining").css("top", $("#lancer_defi_bas").offset().top - $("#spotTraining").height()).css("left", $("#lancer_defi_bas").offset().left + 0.5*$("#lancer_defi_bas").width() - 0.5*$("#spotTraining").width());
	$("#spotTraining").css("z-index", "101").css("cursor", "pointer");
	$("#spotTraining").show("pulsate", {easing: "swing"}, 1000, function () {
	});
}

if(levelUp == "yes") //If the player has won a level with the exercise
{
	$("#level_up").show();
	if(playerVolumeSoundEffects == 1){
		$("#son_level_up").trigger("play");
	}
	$("#level_up").switchClass("level_up_cache", "level_up", 500);
	setTimeout(function(){
		$("#level_up").switchClass("level_up", "level_up_cache", 500);
	}, 2000);
	setTimeout(function(){
		$("#level_up").hide();
	}, 2500);
	timer = 3000; //On fait commencer l'anim suivante après celle là
}

if(levelAfterChallenge > levelBeforeChallenge) //If the player has unlocked a new level
{
	setTimeout(function(){
		$("#annonce_fin_defi").show();
		if(playerVolumeSoundEffects == 1 && congratulations == 1){
			$("#son_deblocage").trigger("play");
		} else if(playerVolumeSoundEffects == 1 && congratulations == 0){
			$("#son_oups").trigger("play");
		}
		$("#annonce_fin_defi").switchClass("annonce_fin_defi_cachee", "annonce_fin_defi", 500);
		createCloseImg($("#annonce_fin_defi"), true);
		$("#annonce_fin_defi").on("click", function(){
			$("#annonce_fin_defi").switchClass("annonce_fin_defi", "annonce_fin_defi_cachee", 500);
			setTimeout(function(){
				$("#annonce_fin_defi").hide();
				if(playerVolumeSoundEffects == 1){
					$("#son_fin_defi").trigger("play");
				}
				change_level(levelBeforeChallenge, levelAfterChallenge)
			}, 500);
		});
	}, timer);
}
else
{
	setTimeout(function(){
		if(playerVolumeSoundEffects == 1){
			$("#son_fin_defi").trigger("play");
		}
		displayEndFreePeriod(limitReached, true);
	}, timer);
}

if($("#reminderEndFreePeriod").length && typeof(mixpanel) != "undefined"){
	mixpanel.track("EmailParentSentAuto2");
}

});
