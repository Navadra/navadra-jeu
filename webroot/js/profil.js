$("#tabs_profile").tabs();
var landing_tab = $("#landing_tab").html();
var level_player_profile = parseInt($("#level_player_profile").html());
var prestige_player_profile = parseInt($("#prestige_player_profile").html());
var id_joueur_profil = parseInt($("#id_joueur_profil").html());
var exercises = [];
var triesGetExercises = 0;

function visual_positionning(){
	var totalImages = 0;
	var imgLoaded = 0;
	//Positionne les icones d'envoi de message et d'ajout aux contacts si elles existent
	if($("#envoi_msg").length){
		$("#envoi_msg").hide();
		$("#envoi_msg").removeClass("cache");
		var pos = $(".fond_sans_bordures").offset();
		var hauteur_fond = $(".fond_sans_bordures").height();
		var largeur_fond = $(".fond_sans_bordures").width();
		var hauteur_img = $("#envoi_msg").height();
		var largeur_img = $("#envoi_msg").width();
		$("#envoi_msg").css("top", pos.top + hauteur_fond - hauteur_img + 15).css("left", pos.left + largeur_fond - largeur_img);
		$("#envoi_msg").show();

		$("#envoi_msg").on("mouseover",function(){
			$(this).attr("src", "/webroot/img/icones/message_joueur_selec.png");
			$(this).css("height", $(this).height() + 10).css("cursor", "pointer");
		});

		$("#envoi_msg").on("mouseout",function(){
			$(this).attr("src", "/webroot/img/icones/message_joueur.png");
			$(this).css("height", $(this).height() - 10).css("cursor", "");
		});

		$("#envoi_msg").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
		});
	}

	if($("#ajouter_contact").length){
		$("#ajouter_contact").hide();
		$("#ajouter_contact").removeClass("cache");
		var pos = $("#envoi_msg").offset();
		var largeur_img = $("#ajouter_contact").width();
		$("#ajouter_contact").css("top", pos.top).css("left", pos.left - largeur_img);
		$("#ajouter_contact").show();

		$("#ajouter_contact").on("mouseover",function(){
			$(this).attr("src", "/webroot/img/icones/ajouter_contact_selec.png");
			$(this).css("height", $(this).height() + 10).css("cursor", "pointer");
		});

		$("#ajouter_contact").on("mouseout",function(){
			$(this).attr("src", "/webroot/img/icones/ajouter_contact.png");
			$(this).css("height", $(this).height() - 10).css("cursor", "");
		});

		$("#ajouter_contact").on("mouseup",function(){
			//Ajoute ce personnage aux contacts
			var id_contact = $("#id_joueur_profil").html();
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
					$("#ajouter_contact").hide("blind", 500);
				}
			});
		});

		$("#ajouter_contact").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
		});
	}

	//Positionning Level and Prestige for profile player
	var avatar_top = $("#fond_avatar").offset().top;
	var avatar_left = $("#fond_avatar").offset().left;
	var avatar_width = $("#fond_avatar").width();
	var avatar_height = $("#fond_avatar").height();
	totalImages ++;
	$('<img id="fond_niveau_joueur_profil" class="img_40" alt="" src="/webroot/img/icones/avatar_niveau.png">').appendTo("body").load(function(){
		$("#fond_niveau_joueur_profil").css("position", "absolute").css("top", $(".fond_sans_bordures").offset().top + $(".fond_sans_bordures").outerHeight() - 1.2*$("#fond_niveau_joueur_profil").height()).css("left", avatar_left + 0.18*avatar_width);
		$('<div id="niveau_joueur_profil" class="ib g">'+level_player_profile+'</div>').appendTo("body");
	$("#niveau_joueur_profil").css("position", "absolute").css("top", $(".fond_sans_bordures").offset().top + $(".fond_sans_bordures").outerHeight() - 1.2*$("#fond_niveau_joueur_profil").height() + 0.18*$("#fond_niveau_joueur_profil").height()).css("left", avatar_left + 0.18*avatar_width).css("width", $("#fond_niveau_joueur_profil").width());
		imgLoaded++;
		change_tab();
	});

	$('<div id="prestige_joueur_profil" class="ib g align_centre fond_noir blanc">'+prestige_player_profile+'</div>').appendTo("body");
	$("#prestige_joueur_profil").css("position", "absolute").css("top", $(".fond_sans_bordures").offset().top + $(".fond_sans_bordures").outerHeight() - 1.6*$("#prestige_joueur_profil").height()).css("left", avatar_left + 0.60*avatar_width);

	totalImages ++;
	$('<img id="icone_prestige_joueur_profil" class="img_30" alt="" src="/webroot/img/icones/prestige.png">').appendTo("body").load(function(){
		$("#icone_prestige_joueur_profil").css("position", "absolute").css("top", $("#prestige_joueur_profil").offset().top - $("#icone_prestige_joueur_profil").height()).css("left", avatar_left + 0.60*avatar_width);
		$("#prestige_joueur_profil").css("-moz-border-radius", "10px").css("-webkit-border-radius", "10px").css("border-radius", "10px").css("width", $("#icone_prestige_joueur_profil").width());
		imgLoaded++;
		change_tab();
	});

	$("#elem_descr").hide().removeClass("cache");
	$("#elem_descr").css("position", "absolute").css("top", 1.2*avatar_top).css("left", avatar_left);
	//Display table profile when hovering avatar
	$("#element_player_profile").on("mouseover", function(){
		display_clip($("#elem_descr"));
	});

	$("#element_player_profile").on("mouseout", function(){
		$("#elem_descr").hide();
	});

	//Positionning monsters' level
	$(".fond_niveau_monstre").each(function(index, element){
		$(this).css("left", 0.5*parseInt($(this).css("left"))).css("top", $(this).parent().height() - 0.7*$(this).height());
		var monster_lvl = $(this).parent().children(".niveau_monstre");
		monster_lvl.css("left", 0.5*parseInt(monster_lvl.css("left"))).css("top", $(this).parent().height() - 0.7*$(this).height());
		var issue_icon = $(this).parent().children(".issue_icon");
		issue_icon.css("left", $(this).parent().width() - issue_icon.width()).css("top", 0);
	});

	function afficher(objet)
	{
		objet.show("clip", 500);
	}

	//TROPHEES
	if($("#trophees").length)
	{
		var titre_joueur = $(".titre_gauche:eq(0)");
		$("#trophees").css("position", "absolute").css("top", titre_joueur.offset().top - 0.5*($("#trophees").height() - titre_joueur.height())).css("right", "16%");
		if($("#coupe_or").length)
		{
			$("#coupe_or").css("cursor", "pointer");
			totalImages ++;
			$('<img class="absolu" id="fond_coupe_or" alt="" src="/webroot/img/icones/avatar_niveau.png">').appendTo("body").load(function(){
				$("#fond_coupe_or").css("height", 1.5*$("#nb_or").height());
				$("#nb_or").css("top", $("#coupe_or").offset().top + 0.95*$("#coupe_or").height()).css("left", $("#coupe_or").offset().left + 0.5*$("#coupe_or").width() - 0.5*$("#nb_or").width()).css("z-index", "3");
				$("#fond_coupe_or").css("top", $("#coupe_or").offset().top + 0.95*$("#coupe_or").height()-0.4*($("#fond_coupe_or").height() - $("#nb_or").height())).css("left", $("#coupe_or").offset().left + 0.5*$("#coupe_or").width() - 0.5*$("#fond_coupe_or").width())
				$("#descriptif_or").css("top", $("#coupe_or").offset().top + 1.3*$("#coupe_or").height()).css("left", $("#coupe_or").offset().left + 0.5*$("#coupe_or").width() - 0.5*$("#descriptif_or").width()).css("z-index", "4").hide();

				$("#coupe_or").on("mouseover", function(){
					afficher($("#descriptif_or"));
				});
				$("#coupe_or").on("mouseout", function(){
					$("#descriptif_or").hide();
				});
				imgLoaded++;
				change_tab();
			});
		}

		if($("#coupe_argent").length)
		{
			$("#coupe_argent").css("cursor", "pointer");
			totalImages ++;
			$('<img class="absolu" id="fond_coupe_argent" alt="" src="/webroot/img/icones/avatar_niveau.png">').appendTo("body").load(function(){
				$("#fond_coupe_argent").css("height", 1.5*$("#nb_argent").height());
				$("#nb_argent").css("top", $("#coupe_argent").offset().top + 0.95*$("#coupe_argent").height()).css("left", $("#coupe_argent").offset().left + 0.5*$("#coupe_argent").width() - 0.5*$("#nb_argent").width()).css("z-index", "3");
				$("#fond_coupe_argent").css("top", $("#coupe_argent").offset().top + 0.95*$("#coupe_argent").height()-0.4*($("#fond_coupe_argent").height() - $("#nb_argent").height())).css("left", $("#coupe_argent").offset().left + 0.5*$("#coupe_argent").width() - 0.5*$("#fond_coupe_argent").width())
				$("#descriptif_argent").css("top", $("#coupe_argent").offset().top + 1.3*$("#coupe_argent").height()).css("left", $("#coupe_argent").offset().left + 0.5*$("#coupe_argent").width() - 0.5*$("#descriptif_argent").width()).css("z-index", "4").hide();

				$("#coupe_argent").on("mouseover", function(){
					afficher($("#descriptif_argent"));
				});
				$("#coupe_argent").on("mouseout", function(){
					$("#descriptif_argent").hide();
				});
				imgLoaded++;
				change_tab();
			});
		}

		if($("#coupe_bronze").length)
		{
			$("#coupe_bronze").css("cursor", "pointer");
			totalImages ++;
			$('<img class="absolu" id="fond_coupe_bronze" alt="" src="/webroot/img/icones/avatar_niveau.png">').appendTo("body").load(function(){
				$("#fond_coupe_bronze").css("height", 1.5*$("#nb_bronze").height());
				$("#nb_bronze").css("top", $("#coupe_bronze").offset().top + 0.95*$("#coupe_bronze").height()).css("left", $("#coupe_bronze").offset().left + 0.5*$("#coupe_bronze").width() - 0.5*$("#nb_bronze").width()).css("z-index", "3");
				$("#fond_coupe_bronze").css("top", $("#coupe_bronze").offset().top + 0.95*$("#coupe_bronze").height()-0.4*($("#fond_coupe_bronze").height() - $("#nb_bronze").height())).css("left", $("#coupe_bronze").offset().left + 0.5*$("#coupe_bronze").width() - 0.5*$("#fond_coupe_bronze").width())
				$("#descriptif_bronze").css("top", $("#coupe_bronze").offset().top + 1.3*$("#coupe_bronze").height()).css("left", $("#coupe_bronze").offset().left + 0.5*$("#coupe_bronze").width() - 0.5*$("#descriptif_bronze").width()).css("z-index", "4").hide();

				$("#coupe_bronze").on("mouseover", function(){
					afficher($("#descriptif_bronze"));
				});
				$("#coupe_bronze").on("mouseout", function(){
					$("#descriptif_bronze").hide();
				});
				imgLoaded++;
				change_tab();
			});
		}

		if($("#meilleur_classement").length) {
			$("#meilleur_classement").css("cursor", "pointer");
			$("#meilleur_classement").css("top", $("#fond_meilleur_classement").offset().top + 0.5*($("#fond_meilleur_classement").height() - $("#meilleur_classement").height()))
			$("#meilleur_classement").css("left", $("#fond_meilleur_classement").offset().left + 0.5*($("#fond_meilleur_classement").width() - $("#meilleur_classement").width()))
			$("#descriptif_meilleur_classement").css("top", $("#fond_meilleur_classement").offset().top + 1.3*$("#fond_meilleur_classement").height()).css("left", $("#fond_meilleur_classement").offset().left + 0.5*$("#fond_meilleur_classement").width() - 0.5*$("#descriptif_meilleur_classement").width()).css("z-index", "4").hide();

			$("#meilleur_classement").on("mouseover", function(){
				afficher($("#descriptif_meilleur_classement"));
			});
			$("#meilleur_classement").on("mouseout", function(){
				$("#descriptif_meilleur_classement").hide();
			});
		}
	}

	function change_tab(){
		if(landing_tab == "challenges" && imgLoaded==totalImages){
			$("#tabs_profile").tabs("option", "active", 1);
			hide_profile_icones();
		} else if(landing_tab == "sponsor" && imgLoaded==totalImages){
			$("#tabs_profile").tabs("option", "active", 3);
			hide_profile_icones();
		} else if(landing_tab == "admin" && imgLoaded==totalImages){
			$("#tabs_profile").tabs("option", "active", 4);
			hide_profile_icones();
		} else if(landing_tab == "teacher" && imgLoaded==totalImages){
				if($("#admin").length){
					$("#tabs_profile").tabs("option", "active", 5);
				} else {
					$("#tabs_profile").tabs("option", "active", 4);
				}
			hide_profile_icones();
		}
	}

	if($(".challenge_notions").length){
		$(".challenge_notions").css("text-align", "left");
	}

	if($("#teacher").length){
		$("#teacher .actionGraph").css("width", "100%");
	}
}

function show_profile_icones(){
	$("#ajouter_contact").show();
	$("#envoi_msg").show();
	$("#fond_niveau_joueur_profil").show();
	$("#niveau_joueur_profil").show();
	$("#prestige_joueur_profil").show();
	$("#icone_prestige_joueur_profil").show();
	if($("#trophees").length){
		$("#trophees").show();
		if($("#coupe_or").length){
			$("#coupe_or").show();
			$("#fond_coupe_or").show();
			$("#nb_or").show();
		}
		if($("#coupe_argent").length){
			$("#coupe_argent").show();
			$("#fond_coupe_argent").show();
			$("#nb_argent").show();
		}
		if($("#coupe_bronze").length){
			$("#coupe_bronze").show();
			$("#fond_coupe_bronze").show();
			$("#nb_bronze").show();
		}
		if($("#meilleur_classement").length){
			$("#meilleur_classement").show();
			$("#fond_meilleur_classement").show();
		}
	}
}

function hide_profile_icones(){
	$("#ajouter_contact").hide();
	$("#envoi_msg").hide();
	$("#fond_niveau_joueur_profil").hide();
	$("#niveau_joueur_profil").hide();
	$("#prestige_joueur_profil").hide();
	$("#icone_prestige_joueur_profil").hide();
	$("#elem_descr").hide();
	if($("#trophees").length){
		$("#trophees").hide();
		if($("#coupe_or").length){
			$("#coupe_or").hide();
			$("#fond_coupe_or").hide();
			$("#nb_or").hide();
		}
		if($("#coupe_argent").length){
			$("#coupe_argent").hide();
			$("#fond_coupe_argent").hide();
			$("#nb_argent").hide();
		}
		if($("#coupe_bronze").length){
			$("#coupe_bronze").hide();
			$("#fond_coupe_bronze").hide();
			$("#nb_bronze").hide();
		}
		if($("#meilleur_classement").length){
			$("#meilleur_classement").hide();
			$("#fond_meilleur_classement").hide();
		}
	}
}

var maxInvitations;
var categoryInvitation = "";
var descriptionInvitation = "";
var newCode;


$(function(){

$("#tabs_profile").tabs({
  activate: function( event, ui ) {
	  if(ui.newPanel.selector == "#general"){
		  show_profile_icones();
	  } else {
		  hide_profile_icones();
	  }
  }
});

$("#error_training").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Retour": function(){
			$(this).dialog("close");
		}
	}
});

var id_challenge;
$(".link_training").on("click", function(event){
	event.preventDefault();
	if($(this).attr("href") == "#"){ //Never encountered the challenge before : training not possible
		$("#error_training").dialog("open");
	} else if($(this).children().children(".l10").children("img").attr("src") == "/webroot/img/icones/play.png"){ //Possibility to do the ultimate challenge
		getPlayerExercises($(this).attr("name"), $(this).attr("notion"), $(this).attr("element"), true);
		id_challenge = parseInt($(this).attr("id"));
	} else { //Only possibility is to train
		getPlayerExercises($(this).attr("name"), $(this).attr("notion"), $(this).attr("element"), false);
		id_challenge = parseInt($(this).attr("id"));
	}
});

$("#confirm_mastery").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Lancer le défi" : function(){
			$(this).dialog("close");
			$(location).attr('href',"/app/controllers/new_defi.php?id="+id_challenge);
		},
		"S'entrainer d'abord": function(){
			$(this).dialog("close");
			$(location).attr('href',"/app/controllers/entrainement.php?id="+id_challenge);
		},
		"Retour": function(){
			$(this).dialog("close");
		}
	}
});

$("#confirm_training").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"S'entrainer": function(){
			$(this).dialog("close");
			$(location).attr('href',"/app/controllers/entrainement.php?id="+id_challenge);
		},
		"Retour": function(){
			$(this).dialog("close");
		}
	}
});

function afficher(objet)
{
	objet.show("clip", 500);
}

function disparaitre(objet)
{
	objet.hide("clip", 500);
}


//CHALLENGES PROGRESSION
show_challenges("none");

function show_challenges(elem){
	$(".fire_challenges").hide();
	$(".water_challenges").hide();
	$(".wind_challenges").hide();
	$(".earth_challenges").hide();
	if(elem == "fire" || elem == "water" || elem == "wind" || elem == "earth"){
		$("#userHint").hide();
		$("."+elem+"_challenges").show();
	}
}

$("#elements_challenges .l20").on("mouseover", function(){
	$(this).css("cursor", "pointer");
	$(this).css("background-color", "#FFFFC6");
	$(this).css("box-sizing", "border-box");
	$(this).css("-moz-box-sizing", "border-box");
	$(this).css("-webkit-box-sizing", "border-box");
	$(this).css("box-shadow", "0px 0px 0px 2px #e0c399 inset");
	$(this).css("-moz-border-radius", "10px");
	$(this).css("-webkit-border-radius", "10px");
	$(this).css("border-radius", "10px");
});

$("#elements_challenges .l20").on("mouseout", function(){
	$(this).attr("style", "");
});

$("#elements_challenges .l20").on("click", function(){
	var idTemp = $(this).attr("id");
	var elem = idTemp.match(/(\w+)_\w+/)[1];
	show_challenges(elem);
});

$("#challenge_progress_graph").hide();

//Get all player exercises
function getPlayerExercises(nameChallenge, notionChallenge, elementChallenge, ultimate){
	if(!$("#challenge_progress_graph").is(':visible')){
		$("#challenge_progress_graph").show("clip", {easing: "swing"}, 500);
	}
	if(!$("#loading_graph").length){
		$("<img id='loading_graph' class='img_100' src='/webroot/img/icones/loading.gif'/>").appendTo("#container_progress_graph");
	}
	if(exercises.length == 0 && triesGetExercises<3){
		triesGetExercises ++;
		$.ajax({
		   url: '/app/controllers/ajax_challenge.php',
		   type: 'POST',
		   data: 'historicExercises=get',
		   dataType: 'json',
		   success: function (result, status) {
			 exercises = result;
			 initiate_progress_graph(nameChallenge, notionChallenge, elementChallenge);
			 options_progress_graph(ultimate);
		   },
		   error: function (result, status, error) {
			 getPlayerExercises(nameChallenge, notionChallenge, elementChallenge, ultimate);
		   },
		});
	} else if(exercises.length == 0 && triesGetExercises==3){
		$("#container_progress_graph").empty();
		$("<div class='p6 g mb4 pfun'>"+notionChallenge+"</div>").appendTo("#container_progress_graph");
		$("<img class='img_100 mb4' src='/webroot/img/icones/dommage.png'/>").appendTo("#container_progress_graph");
		$("<div class='p3 rouge g'>Désolé, ta connexion Internet ne permet pas de récupérer tes progrès.</div>").appendTo("#container_progress_graph");
		options_progress_graph(ultimate);
	} else if(exercises.length > 0){
		 initiate_progress_graph(nameChallenge, notionChallenge, elementChallenge);
		 options_progress_graph(ultimate);
	}
}

function options_progress_graph(ultimate){
	$("#options_progress_graph").empty();
	$("<div id='training' class='actionGraph p2'>S'entrainer</div>").appendTo("#options_progress_graph");
	$("#training").on("click", function(){
		$("#confirm_training").dialog("open");
	});
	if(ultimate){
		$("<div id='ultimate' class='actionGraph p2'>Défi Ultime</div>").appendTo("#options_progress_graph");
		$("#ultimate").on("click", function(){
			 $("#confirm_mastery").dialog("open");
		});
	}
	$("<img id='hide_progress_graph' class='absolu img_50' title='Retour' src='/webroot/img/icones/btn_retour.png'/>").appendTo("#options_progress_graph").load(function(){
		$("#hide_progress_graph").tooltip({
			show: {
				effect: "slideDown",
				delay: 250
			}
		});
		$("#hide_progress_graph").css("left", 10).css("bottom", 0).css("cursor", "pointer");
		$("#hide_progress_graph").on("click", function(){
			$("#challenge_progress_graph").hide();
		});
	});
}

function formatDates(dateTemp){
	return "Le "+ dateTemp.substring(8,10) + "/" + dateTemp.substring(5,7) + "/" + dateTemp.substring(0,4);
}

function initiate_progress_graph(nameChallenge, notionChallenge, elementChallenge){
	$("#container_progress_graph").empty();
	initiate_styles_diagram();
	if(elementChallenge == "fire"){
		var colorTemp = "#ba0019";
	} else if(elementChallenge == "water"){
		var colorTemp = "#059cd3";
	} else if(elementChallenge == "wind"){
		var colorTemp = "#d0a00a";
	} else if(elementChallenge == "earth"){
		var colorTemp = "#1c9500";
	}
	Highcharts.theme.colors = [colorTemp];
	Highcharts.theme.title.style.color = [colorTemp];
	Highcharts.setOptions(Highcharts.theme);
	var scores = [];
	var dates = [];
	var abscisses = [];
	var diagnosis;
	for(var i = 0; i<exercises.length ; i++){
		var ex = exercises[i];
		if(ex.challenge == nameChallenge){
			if(ex.diagnosis==1){
				scores.push({
               		marker: {
                    	symbol: 'url(/webroot/img/icones/speedometer_small.png)'
					},
					y: ex.score
                });
			} else {
				scores.push(math.min(6,ex.score));
			}
			diagnosis = ex.diagnosis;
			dates.push(formatDates(ex.date));
			abscisses.push(scores.length);
		}
	}
	$('#container_progress_graph').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: notionChallenge
        },
		/*
        subtitle: {
            text: 'Source: WorldClimate.com'
        }, */
        xAxis: {
			title: {
                text: 'Nombre de questions'
            },
            categories: abscisses
        },
        yAxis: {
			min: 0,
			max: 6,
            title: {
                text: 'Maîtrise atteinte'
            },
            labels: {
                formatter: function () {
					if(this.value <= 6){
                    	return this.value;
					} else {
						return "";
					}
                }
            },
			plotBands: [{
                from: 0,
                to: 1,
                color: 'rgba(240, 217, 67, 0.1)',
                label: {
                    text: '',
                    style: {
                        color: '#F0D943',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
                }
			}, {
                from: 1,
                to: 2.5,
                color: 'rgba(240, 217, 67, 0.1)',
                label: {
                    text: 'Appropriation',
                    style: {
                        color: '#F0D943',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
                }
            }, {
                from: 2.5,
                to: 4.5,
                color: 'rgba(250, 169, 77, 0.1)',
                label: {
                    text: 'Approfondissement',
                    style: {
                        color: '#FAA94D',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
                }
			}, {
                from: 4.5,
                to: 5,
                color: 'rgba(232, 70, 70, 0.1)',
                label: {
                    text: 'Maîtrise',
                    style: {
                        color: '#E84646',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
				}
            }, {
                from: 5,
                to: 5.5,
                color: 'rgba(232, 70, 70, 0.1)',
                label: {
                    text: '',
                    style: {
                        color: '#E84646',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
				}
             }, {
				from: 5.5,
                to: 6.5,
                color: 'rgba(191, 48, 48, 0.1)',
                label: {
                    text: 'Maîtrise Absolue',
                    style: {
                        color: '#BF3030',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
				}
            }]
        },
        tooltip: {
            crosshairs: true,
			shared: true,
			formatter: function () {
				if(scores[this.x-1].marker != undefined){
					var scoreTemp = scores[this.x-1].y;
				} else {
					var scoreTemp = scores[this.x-1];
				}
				if(math.round(scoreTemp) <= scoreTemp){
					var successTemp = "Bonne réponse";
				} else if(math.round(scoreTemp) > scoreTemp){
					var successTemp = "Erreur";
				}
				return  dates[this.x-1]+"<br>Maîtrise <b>"+math.round(scoreTemp)+"</b><br>"+successTemp;
			}
        },
        plotOptions: {
            spline: {
                marker: {
                    radius: 4,
                    lineColor: '#666666',
                    lineWidth: 1
                }
            }
        },
        series: [{
            name: 'Score au défi '+notionChallenge,
            marker: {
                symbol: 'square'
            },
            data: scores
        }],
		credits: {
			enabled: false
		},
		legend: {
		  enabled: false
		}
    });
	$("<div class='mb4'><img class='ib img_20' src='/webroot/img/icones/speedometer.png'/><span class='ib i'> : Calibrage initial du niveau des exercices</span></div>").appendTo("#container_progress_graph");
}

function initiate_styles_diagram(){
	if(Highcharts.theme == undefined){
		// Load the fonts
		Highcharts.createElement('link', {
		   href: 'https://fonts.googleapis.com/css?family=Dosis:400,600',
		   rel: 'stylesheet',
		   type: 'text/css'
		}, null, document.getElementsByTagName('head')[0]);

		Highcharts.theme = {
		   colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF"],
		   chart: {
			  backgroundColor: null,
			  style: {
				 fontFamily: "Dosis, sans-serif"
			  }
		   },
		   title: {
			  style: {
				 fontSize: '20px',
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
			  gridLineWidth: 0,
			  gridLineColor: "#797979",
			  tickColor: "#797979",
			  labels: {
				 style: {
					fontSize: '12px'
				 }
			  },
			  title: {
				 style: {
					fontSize: '14px',
				 	fontWeight: 'bold'
				 }
			  }
		   },
		   yAxis: {
			  minorTickInterval: 'auto',
			  gridLineWidth: 1,
			  gridLineColor: "#797979",
			  minorGridLineColor: "#797979",
			  minorGridLineWidth: 0,
			  title: {
				 style: {
					textTransform: 'uppercase',
					fontSize: '16px',
				 	fontWeight: 'bold'
				 }
			  },
			  labels: {
				 style: {
					fontSize: '16px',
					fontWeight: 'bold'
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
}

//Highlight when mouseover
$(".corps_scroll div").on("mouseover",function(){
	mettre_en_evidence_ligne_scroll($(this));
});

$(".corps_scroll div").on("mouseout",function(){
	enlever_evidence_ligne_scroll($(this));
});

$(".trophyIcon").css("cursor", "pointer");

$(".trophyIcon").on("click", function(){
	$("#tabs_profile").tabs("option", "active", 2);
})


$(".titles").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

if($("#invitees").length){
	$("#invitees").css("top", "5%");
}

if($("#inviteFriend").length){
	$("#inviteWindow").hide();
	$("#loadingInvitation").hide();
	$("#closeInviteWindow").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/refuser_selec.png")
	});
	$("#closeInviteWindow").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/refuser.png")
	});
	$("#closeInviteWindow").on("click", function(){
		hide_clip($("#inviteWindow"));
	});

	$("#inviteFriend").on("click", function(){
		display_clip($("#inviteWindow"));
	});

	$("input[name=emailFriend]").on("keyup",function(){
		email_valide($(this));
	});

	function email_valide(objet){
		valeur_champ = objet.val();
		var ok = valeur_champ.match(/^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4}$/);
		if(ok == null ) {
			erreur_saisie(objet);
			return false;
		}	else {
			enlever_erreur(objet);
			return true;
		}
	}

	$(".sendInvitationBtn").on("click", function(){
		if(email_valide($("input[name=emailFriend]"))){
			$("#loadingInvitation").show();
			$(".sendInvitationBtn").hide();
			$("#confirmationInvite").hide();
			$.ajax({
				 url: '/app/controllers/ajax.php',
				 type: 'POST',
				 data: 'inviteFriend='+$("input[name=emailFriend]").val(),
				 dataType: 'html',
				 success: function (result, status) {
					console.log(result);
					if(result == "tooMuchInvitations"){
						$("input[name=emailFriend]").val("");
						$("#confirmationInvite").switchClass("vert", "rouge").html("Tu n'as le droit qu'à 3 invitations par jour, réessaie demain !").show();
					} else {
						$("input[name=emailFriend]").val("");
						$("#confirmationInvite").switchClass("rouge", "vert").html("Email envoyé !").show();
					}
				 },
				 error: function (result, status, error) {
					$("#confirmationInvite").switchClass("vert", "rouge").html("Désolé, problème de serveur... Réessaie plus tard !").show();
				 },
				 complete: function (result, status, error) {
					 $("#loadingInvitation").hide();
		 			$(".sendInvitationBtn").show();
				 }
			});
		}
	});

}

if($("#discoverMonster").length){
	$("#confirmationMonster").hide();
	$("#discoverMonster").on("click", function(){
		$("#discoverMonster").hide();
		$.ajax({
			 url: '/app/controllers/ajax.php',
			 type: 'POST',
			 data: 'discoverMonster=ok',
			 dataType: 'json',
			 success: function (monster, status) {
				console.log(monster);
				$(".playerIcons").hide("pulsate", {easing: "swing"}, 1500, function(){
					$("#treasureMapProfile").effect("pulsate", {easing: "swing"}, 500, function(){
						$("#treasureMapProfile").attr("src", monster.image);
						if(playerVolumeSoundEffects == 1){
							$("#sound_challenge_ultime_mastery").trigger("play");
						}
						$("#treasureMapProfile").effect("pulsate", {easing: "swing"}, 300, function(){
							$("#confirmationMonster").removeClass("rouge").html("Oh, un monstre <span class='g'>légendaire</span> : <span class='g "+monster.color+"'>" + monster.name + " niveau "+ monster.level + " </span>!<br>Il va te falloir de l'aide pour en venir à bout...");
							$("#confirmationMonster").show("pulsate", {easing: "swing"}, 1500);
							$("#invitees_sentence").html("Tu as découvert un monstre légendaire !")
							$("#treasureMapProfile").css("cursor", "pointer");
							$("#treasureMapProfile").on("click", function(){
								$(location).attr('href',"/app/controllers/prepa_combats.php?idm="+monster.id);
							})
						})
					});
				});
			 },
			 error: function (result, status, error) {
				$("#confirmationMonster").addClass("rouge").html("Désolé, problème de serveur... Réessaie plus tard !").show();
				$("#discoverMonster").show();
			 },
			 complete: function (result, status, error) {
			 }
		});
	});
}

if($("#generateCode").length){

	$("#categoryInvitation").buttonset();
	$("input[name=categoryInvitation]").on("click",function(){
		categoryInvitation = $(this).val();
		if(categoryInvitation == "Classe d'un prof"){
			$("#maxInvitations").val(35);
		} else if(categoryInvitation == "Facebook" || categoryInvitation == "Twitter"){
			$("#maxInvitations").val(10);
		} else if(categoryInvitation == "Salon"){
			$("#maxInvitations").val(300);
		} else {
			$("#maxInvitations").val(1);
		}
	});

	$("#generateCode").on("click", function(){
		if($("#maxInvitations").val().match(/^\d+$/) && parseInt($("#maxInvitations").val()) >= 1 && validDescription() && categoryInvitation != ""){
			maxInvitations = parseInt($("#maxInvitations").val());
			descriptionInvitation = $("#descriptionInvitation").val();
			$("#generateCode").html('<img class="img_50" src="/webroot/img/icones/loading.gif"/>');
			$.ajax({
			   url: '/app/controllers/ajax.php',
			   type: 'POST',
			   data: 'maxInvitations='+maxInvitations+'&descriptionInvitation='+descriptionInvitation+'&categoryInvitation='+categoryInvitation,
			   dataType: 'html',
			   success: function (result, status) {
				 	newCode = result;
					$("#newCode").html(newCode);
					$("#invitees_sentence").switchClass("jaune", "vert").html("Et voilà un nouveau code tout chaud pour " + maxInvitations + " aventurier(s) !")
			   },
			   error: function (result, status, error) {
				  alert('Problème avec le serveur... sob...')
			   },
				 complete: function (result, status, error) {
					 $("#descriptionInvitation").val("");
					 descriptionInvitation = "";
					 $("#generateCode").html("Un autre !")
				 }
			});
		} else {
			alert ("T'es sérieux là ? Tu voudrais que je rajoute du Form Control pour les admins en plus ?");
		}
	});

	//Fonctions de contrôle du format des données
	function validDescription(){
		var ok = $("#descriptionInvitation").val().match(/^.{5,100}$/);
		if(ok == null){
			$("#descriptionInvitation").css("border", "2px solid #f00");
			return false;
		}	else{
			$("#descriptionInvitation").css("border", "2px solid #1c9500");
			return true;
		}
	}

	$("#descriptionInvitation").on("keyup",function(){
		validDescription();
	});


}

if($("#classroomCode").length){

	$("#loadingClassroom").hide();

	$("#classroomCode").on("keyup", function(){
		$(this).val($(this).val().toUpperCase());
	});

	$("#joinClassroom").on("click", function(){
		if($("#classroomCode").val() != ""){
			$("#loadingClassroom").show();
			$("#joinClassroom").hide();
			$.ajax({
				url: '/app/controllers/ajax_admin.php',
				type :'POST',
				data: 'classroomJoinCode='+$("#classroomCode").val(),
				dataType: 'html',
				success: function(result, statut){
					if(result == "DoesNotExist"){
						$("#joinClassroomMsg").switchClass("orange", "rouge").html("Désolé mais ce code n'existe pas !");
						$("#loadingClassroom").hide();
						$("#joinClassroom").show();
					} else if(result == "TooManySubscriptions"){
						$("#joinClassroomMsg").switchClass("orange", "rouge").html("Désolé mais cette classe est déjà complète !");
						$("#loadingClassroom").hide();
						$("#joinClassroom").show();
					} else {
						$("#classroom").empty().html(result);
						$(".corps_scroll div").unbind("mouseover", "mouseout");
						$(".corps_scroll div").on("mouseover",function(){
							mettre_en_evidence_ligne_scroll($(this));
						});
						$(".corps_scroll div").on("mouseout",function(){
							enlever_evidence_ligne_scroll($(this));
						});
						$(".team_portraits").tooltip({
							show: {
								effect: "slideDown",
								delay: 250
							}
						});
					}
				},
				error: function(result, statut, erreur){
					$("#joinClassroomMsg").switchClass("orange", "rouge").html("Erreur : la requête a échoué !");
					$("#loadingClassroom").hide();
					$("#joinClassroom").show();
					console.log(erreur);
				},
				complete: function(result, statut){
				}
			});
		}
	});

}

});

$(window).load(function(){

	//On fait en sorte que le fond de couleur ne dépasse pas du cadre
	var pos_bordure = $(".fond_sans_bordures").offset().top;
	var hauteur_bordure = $(".fond_sans_bordures").height();
	var pos_cadre = $("#fond_avatar").offset().top;
	var hauteur_cadre = $("#fond_avatar").height();
	if(pos_cadre + hauteur_cadre > pos_bordure + hauteur_bordure)	{
		$(".fond_sans_bordures").css("height", pos_cadre + 1.02*hauteur_cadre);
	}
	$("#info_input").hide();
	visual_positionning();

});
