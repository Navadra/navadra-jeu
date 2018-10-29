// JavaScript Document
// When this number is reached : combo is full and we add critical spell ! Percentage
const COMBO_MAX_AMOUNT = 1;
// How many turns in a round
const TURNS_IN_A_ROUND = 3;
// We star from TURNS_IN_A_ROUND to 0 (at 0 means 1 monster attacks and a new round starts
var remaining_turn = TURNS_IN_A_ROUND;
// Time in seconds to let the player choose a spell
var COUNTDOWN_CHOOSE_SPELL = 20;

// ***************************
// ****** GLOBAL VARIABLES
var COMBAT_DEBUG = true;
//Variables globales du joueur
var id_joueur;
var pseudo_joueur;
var img_joueur;
var niveau_joueur;
var element_joueur;
var profil_elem_joueur = "";
var elem1_nom = "";
var elem2_nom = "";
var elem3_nom = "";
var elem4_nom = "";
var plot_j = "";
var pm_joueur = 0;
var pm_joueur_debut_tour = 0;
var endu_joueur = 0;
var player_combo = 0;
var plot_combo = "";
var dernier_joueur;

// Propre au combat
var endu_joueur_max;
var pourcent_vie_joueur;
var pourcent_vie_monstre;
var degats_joueur = 0;
var attaque_esquive = false;

var potential_challenges = [];

// All spells choosed by the player from his deck
var spells_available = [];
// Usable spells for the round
var spells_usable = [];
var spells_usable_nums = [];
// Spells used during the round
var spells_used = [];
// Once the player chooses a spell, this is the spell_number
var selected_spell_num_for_turn = -1;
var spellLaunched = false; //Avoid multiple spell request

var round = 1;
var vitesse_anim = 650;

//Compteur pour déterminer la position d'un sort dans les sorts du joueur
var num_anim = 1;

//Variables globales du monstre
var elem_monstre_nom = "";
var pm_monstre = 0;
var pm_monstre_debut_tour = 0;
var endu_monstre;
var endu_monstre_max;
var plot_m = "";
var niveau_monstre;
var coef_atq_monster;

//Autres variables globales
var plot_chrono;
var issue_combat;
var nb_chasseurs;
var nb_chasseurs_recommandes;
var nb_chasseurs_recommandes_txt;
var bonus_elem_joueur_actif;
var pause = false;
var duration = "";
var difficulty = "";

//Déroulement du combat (passé ou pésent)
var deroulement = [];
var deroulement_array = [];
var fight_flow_array = [];

var caracs_joueur_suivant;
var id_combat;
var nb_ko = 0;
//Evite de lancer 2 fois l'animation du déroulement
var doublon;
// countdown timer interval object
var countdown;
// monster countdown timer interval object
var countdown_monster;

// Countdown timer in seconds
var count_from = COUNTDOWN_CHOOSE_SPELL;

//Fonctions et variable de gestion des calculs
var resultat;
var chrono;
var codeChallenge; //Type : "element_name_x_x
var masteryChallenge; //Get the current mastery of the challenge
// Full time used to make a diff with the remaining time -> caclutate time bonus for berserk load !
var duree_chrono;
var chrono_interval;
var reponse_temp;

var bonus_chrono = 0;

// Affichage
var pos_fond;
var largeur_fond;
var pos_monstre;
var largeur_monstre;
var decalage;

var top_j;
var left_j;
var haut_j;
var larg_j;
var top_m;
var left_m;
var haut_m;
var larg_m;

// ***** NOUVELLES VARIABLES
var used_heal = false;
var spells_all_ok = true;
var spells_pm_percent = 0;

// ***************************
// ****** FUNCTIONS GLOBALES

function add_base_spell() {
  var base_spell = get_base_spell();
  var new_spell = '' +
      '<div class="cases_calculs_combat" id="spell_base" >' +
      '  <img id="spell_' + base_spell.num + '" data-num="' + base_spell.num + '"' +
      '       class="icones_sorts_combat" src="/webroot/img/spells/' + base_spell.icone + '"/>' +
      '  <span id="spell_info_' + base_spell.num + '" class="cache spell_choosed_info"' +
      '        data-num="' + base_spell.num + '" data-nom="' + base_spell.nom + '" data-niveau="' + base_spell.niveau + '"' +
      '        data-element="' + base_spell.element + '" data-categorie="' + base_spell.categorie + '"' +
      '        data-icone="/webroot/img/spells/' + base_spell.icone + '"/>' +
      '  <span id="descriptif_' + base_spell.num + '" class="cache" data-num="' + base_spell.num + '" ></span>' +
      '</div>';

  // Adding critical spell to usable spell list
  spells_available.push(base_spell);
  $("#fond_sorts").append(new_spell);
  $('#spell_base').effect("pulsate", {easing: "swing"}, vitesse_anim * 2);
}

function affinite(index_serie) {
  //if (COMBAT_DEBUG) console.log('FCT --> affinite');
  var elmnt = '';
  switch (index_serie) {
    case 0:
      elmnt = elem1_nom;
      break;
    case 1:
      elmnt = elem2_nom;
      break;
    case 2:
      elmnt = elem3_nom;
      break;
    case 3:
      elmnt = elem4_nom;
      break;
  }
  switch (elmnt) {
    case "feu":
      return ["le Feu", rouge];
    case "eau":
      return ["l'Eau", bleu];
    case "vent":
      return ["le Vent", jaune];
    case "terre":
    default:
      return ["la Terre", vert];
  }
}

function affinite_monstre(index_serie) {
  //if (COMBAT_DEBUG) console.log('FCT --> affinite_monstre');
  if (index_serie == 0) {
    var elmnt = elem_monstre_nom;
  }
  else if (index_serie == 1) {
    var elmnt = elem_monstre_nom;
  }
  else if (index_serie == 2) {
    var elmnt = elem_monstre_nom;
  }
  else if (index_serie == 3) {
    var elmnt = elem_monstre_nom;
  }
  switch (elmnt) {
    case "feu":
      return ["le Feu", rouge];
    case "eau":
      return ["l'Eau", bleu];
    case "vent":
      return ["le Vent", jaune];
    case "terre":
    default:
      return ["la Terre", vert];
  }
}

function bonus_chrono_pm_joueur(correct_answer) {
  //if (COMBAT_DEBUG) console.log('FCT --> bonus_chrono_pm_joueur');
  if (correct_answer && playerTuto != "combattre_2") {
		playAudio("son_bonne_rep");

    $("#bonus_chrono").html('Bonus rapidité :<br>+' + bonus_chrono);
    // *****************
    // Fill combo gauge
    var critical_num = get_critical_for_spell(selected_spell_num_for_turn);
    // Add combo only when spell is not a base one
    if (critical_num > 0) {
      var percent_bonus = chrono / duree_chrono / 2; // /2 = 50% de moins que le temps restant
      var player_combo_bar = $("#player_combo_bar");

      if (player_combo == -1) {
        // If player combo is -1 : we just used it : do nothing (means do not add
        //   combo bonus when answering critical spell question)
        player_combo = 0;
        player_combo_bar.effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
          plot_combo.series[0].data = [[[player_combo]]];
          plot_combo.redraw();
        });
      }
      else if (player_combo < COMBO_MAX_AMOUNT) {
        // KLOUG : critical deactivation
        player_combo = 0;
        /*
        player_combo = Math.min((percent_bonus + player_combo), COMBO_MAX_AMOUNT);
        //if (COMBAT_DEBUG) console.log('FCT --> player_combo = ' + player_combo);
        // Update combo plot
        player_combo_bar.effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
          plot_combo.series[0].data = [[[player_combo]]];
          plot_combo.redraw();
        });
        // If combo is full
        if (player_combo == COMBO_MAX_AMOUNT) {

          // Add critical spell to existing spells
          add_criticalspell( critical_num );
        }
        */
      }
      // *****************
    }
  }
  else if (correct_answer && playerTuto == "combattre_2") {
		playAudio("son_bonne_rep");
    $("#bonus_chrono").html('Invocation réussie !');
  }
  else {
    spells_all_ok = false;
		playAudio("son_calcul_rate");
		$("#bonus_chrono").html('Oups !!');
  }

  // Show bonus rapidité only if not critical
  if (is_critical(selected_spell_num_for_turn) == false) {
    $("#bonus_chrono").show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
      $("#bonus_chrono").hide();
    });
  }
}

function add_criticalspell( critical_num ) {

  // get critical of last used spell
  var critical_spell_info = $('#info_critical_' + critical_num);
  // Add it to spells
  var crit_spell = {
    num: critical_num,
    nom: critical_spell_info.attr('data-nom'),
    niveau: critical_spell_info.attr('data-niveau'),
    element: critical_spell_info.attr('data-element'),
    categorie: critical_spell_info.attr('data-categorie'),
    icone: critical_spell_info.attr('data-icone')
  };

  // Adding critical spell to usable spell list
  spells_available.push(crit_spell);

  var new_spell = '' +
      '<div class="cases_calculs_combat" id="spell_critical" >' +
      '  <img id="spell_' + crit_spell.num + '" data-num="' + crit_spell.num + '"' +
      '       class="icones_sorts_combat" src="' + crit_spell.icone + '" />' +
      '  <span id="spell_info_' + crit_spell.num + '" class="cache spell_choosed_info"' +
      '        data-num="' + crit_spell.num + '" data-nom="' + crit_spell.nom + '" data-niveau="' + crit_spell.niveau + '"' +
      '        data-element="' + crit_spell.element + '" data-categorie="' + crit_spell.categorie + '"' +
      '        data-icone="' + crit_spell.icone + '"/>' +
      '  <span id="descriptif_' + crit_spell.num + '" class="cache" data-num="' + crit_spell.num + '" ></span>' +
      '</div>';

  // add critical to final destination but hidden !
  // Get absolute position of the final destination
  $("#fond_sorts").append(new_spell);
  var target_offset = $("#spell_critical").offset();
  $(".fond_combat").append('<span id="temp_critical" class="descriptif_sort g p2">' + crit_spell.nom + ' débloqué !</span>');
  $("#temp_critical")
      .css("position", "absolute").css("width", "15%")
      .css("top", target_offset.top - 75).css("left", target_offset.left - 50);
  $("#temp_critical").css("text-align", "center");
  $('#temp_critical').effect("pulsate", {easing: "swing"}, vitesse_anim * 2, function () {
    $('#spell_critical').effect("pulsate", {easing: "swing"}, vitesse_anim * 4, function () {
      $("#temp_critical").remove();
    });
  });

}


function get_spell_description( spell ) {
  var spell_num = spell.num;
  var curr_level = spell.niveau;
  var curr_name = spell.nom;

  var params = get_effect_level_param(spell_num, curr_level);
  var desc = theoretical_effet(spell_num, params);
  return '<div class="p1 g '+spell_color(spell_num)+'">' + curr_name + ' - niv. ' + curr_level + '</div>' +
      '<span>' + desc + '</span>';
}

function reinitialiser() {
  //if (COMBAT_DEBUG) console.log('FCT --> reinitialiser');
  attaque_esquive = false;
  $("#pm_monstre").html(pm_monstre).removeClass("rouge");
  //Fait réapparaitre le joueur
  $("#img_joueur").show();
  //Remove protection pictures
  // Mur de feu : 5
  $("#5").hide("scale", {easing: "swing", percent: 0}, vitesse_anim);
  // Protection aquatique : 13
  $("#13").hide("scale", {easing: "swing", percent: 0}, vitesse_anim);
  // Vengeance fulgurante : 17
  $("#17").hide("scale", {easing: "swing", percent: 0}, vitesse_anim);
  // Tourmente céleste : 21
  $("#21").hide("scale", {easing: "swing", percent: 0}, vitesse_anim);
   // armure d'écorce : 23
  $("#23").hide("scale", {easing: "swing", percent: 0}, vitesse_anim);
   // Drain de puissance : 28
  $("#28").hide("scale", {easing: "swing", percent: 0}, vitesse_anim);
  $("#info_absorb").hide("pulsate", {easing: "swing"}, vitesse_anim);
  // RESET SPECIAL ACTIONS (skip turns, absorb, dodge, send back)
  reset_states();
}

function recuperer_joueur_suivant() {
  //if (COMBAT_DEBUG) console.log('FCT --> recuperer_joueur_suivant');
  $.ajax({
    url: '/app/controllers/ajax.php',
    type: 'POST',
    data: 'combat_en_cours=' + id_combat,
    dataType: 'json',
    success: function (resultat, statut) {
      caracs_joueur_suivant = resultat; //On récupère le joueur suivant
    },
    error: function (resultat, statut, erreur) {
      //Erreur est une chaine de caractère à afficher au joueur
    },
    complete: function (resultat, statut) {
      changement_joueur();
    }
  });
}

function recuperer_joueur_suivant_combat_fini() {
  //if (COMBAT_DEBUG) console.log('FCT --> recuperer_joueur_suivant_combat_fini');
  nb_ko++;
  $.ajax({
    url: '/app/controllers/ajax.php',
    type: 'POST',
    data: 'combat_fini=' + id_combat + '&nb_ko=' + nb_ko,
    dataType: 'json',
    success: function (resultat, statut) {
      caracs_joueur_suivant = resultat; //On récupère le joueur suivant
    },
    error: function (resultat, statut, erreur) {
      //Erreur est une chaine de caractère à afficher au joueur
    },
    complete: function (resultat, statut) {
      changement_joueur_combat_fini();
    }
  });
}

//Lorsqu'un joueur est ko et que le combat n'est pas fini
function changement_joueur() {
  //if (COMBAT_DEBUG) console.log('FCT --> changement_joueur');
  //On fait passer sa tête en haut avec les autres icones
  is_chasseur_ko(img_joueur, pseudo_joueur);

  //Récupération des caracs du joueur qui est sur le point d'être affiché
  profil_elem_joueur = caracs_joueur_suivant.profil_elem.split(",");
  elem1_nom = profil_elem_joueur[0];
  elem2_nom = profil_elem_joueur[2];
  elem3_nom = profil_elem_joueur[4];
  elem4_nom = profil_elem_joueur[6];
  var couleur1 = couleur_barre(elem1_nom);
  var couleur2 = couleur_barre(elem2_nom);
  var couleur3 = couleur_barre(elem3_nom);
  var couleur4 = couleur_barre(elem4_nom);
  var elem1_val = parseInt(profil_elem_joueur[1]);
  var elem2_val = parseInt(profil_elem_joueur[3]);
  var elem3_val = parseInt(profil_elem_joueur[5]);
  var elem4_val = parseInt(profil_elem_joueur[7]);

  pseudo_joueur = caracs_joueur_suivant.pseudo;
  niveau_joueur = caracs_joueur_suivant.niveau;
  element_joueur = caracs_joueur_suivant.element;
  img_joueur = caracs_joueur_suivant.img_joueur;
  img_tete_joueur = caracs_joueur_suivant.img_tete_joueur;
  pm_joueur = parseInt(caracs_joueur_suivant.pm_joueur);
  endu_joueur = parseInt(caracs_joueur_suivant.endu_joueur);
  pm_joueur_debut_tour = pm_joueur;
  change_pm_joueur();

  //Changement du pseudo et niveau du joueur actif
  $(".fond_niveau_combattants:eq(0)").attr("src", "/webroot/img/monstres/niv_monstre_" + element_joueur + ".png");
  $(".niveau_combattants:eq(0)").html(niveau_joueur);
  $(".noms_combattants:eq(0)").removeClass("monstre_feu monstre_eau monstre_vent monstre_terre").addClass("monstre_" + element_joueur).html(pseudo_joueur);
  $("#pm_joueur").html(pm_joueur);
  $("#pm_monstre").html(pm_monstre);

  //Changement du profil elem et de la barre d'endu du joueur actif
  initialiser_barre_endu_joueur(endu_joueur);
  $("#pv_joueur_nbre").html(endu_joueur);
  initialisation_player_combo(0);
  //initialisation_profil_elem("joueur", elem1_val, elem2_val, elem3_val, elem4_val, couleur1, couleur2, couleur3, couleur4);

  //Ouverture du message indiquant que c'est au suivant de jouer
  $("#issue_combat").addClass("jaune bordures_jaune");
  $("#issue_combat img:eq(0)").attr("src", caracs_joueur_suivant.img_tete_joueur);
  $("#issue_combat span:eq(1) span:eq(0)").switchClass("l100", "l70").html(pseudo_joueur + " va venir à ta rescousse pour continuer le combat.");
  $("#issue_combat span:eq(1) span:eq(1)").html("");
  $("#issue_combat span:eq(1) span:eq(2)").html("");
	createCloseImg($("#issue_combat"));
  $("#img_joueur").hide("slide", {easing: "swing"}, vitesse_anim, function () {
    $("#img_joueur").attr("src", caracs_joueur_suivant.img_joueur).load(function () {
      $("#img_joueur").show("slide", {easing: "swing"}, vitesse_anim, function () {
        $("#issue_combat").show("scale", {easing: "swing", percent: 100}, 1000, function () {
		  $("#son_musique_combat").trigger("pause");
		  if(playerVolumeSoundEffects == 1){
          $("#son_changement_joueur").trigger("play");
		  }
          $("#issue_combat").effect("pulsate", {easing: "swing"}, 1000);
        });
      });
    });
  });
}

//Lorsqu'un joueur est ko et que le combat n'est pas fini
function changement_joueur_combat_fini() {
  //if (COMBAT_DEBUG) console.log('FCT --> changement_joueur_combat_fini');
  //On fait passer sa tête en haut avec les autres icones
  is_chasseur_ko(img_joueur, pseudo_joueur);

  //Récupération des caracs du joueur qui est sur le point d'être affiché
  profil_elem_joueur = caracs_joueur_suivant.profil_elem.split(",");
  elem1_nom = profil_elem_joueur[0];
  elem2_nom = profil_elem_joueur[2];
  elem3_nom = profil_elem_joueur[4];
  elem4_nom = profil_elem_joueur[6];
  var couleur1 = couleur_barre(elem1_nom);
  var couleur2 = couleur_barre(elem2_nom);
  var couleur3 = couleur_barre(elem3_nom);
  var couleur4 = couleur_barre(elem4_nom);
  var elem1_val = parseInt(profil_elem_joueur[1]);
  var elem2_val = parseInt(profil_elem_joueur[3]);
  var elem3_val = parseInt(profil_elem_joueur[5]);
  var elem4_val = parseInt(profil_elem_joueur[7]);

  pseudo_joueur = caracs_joueur_suivant.pseudo;
  niveau_joueur = caracs_joueur_suivant.niveau;
  element_joueur = caracs_joueur_suivant.element;
  img_joueur = caracs_joueur_suivant.img_joueur;
  img_tete_joueur = caracs_joueur_suivant.img_tete_joueur;
  pm_joueur = parseInt(caracs_joueur_suivant.pm_joueur);
  pm_joueur_debut_tour = pm_joueur;
  endu_joueur = parseInt(caracs_joueur_suivant.endu_joueur);
  pm_monstre = parseInt(caracs_joueur_suivant.pm_monstre);
  pm_monstre_debut_tour = pm_monstre;
  change_pm_joueur();
  change_pm_monstre();

  doublon = false;

  //Changement du pseudo et niveau du joueur actif
  $(".fond_niveau_combattants:eq(0)").attr("src", "/webroot/img/monstres/niv_monstre_" + element_joueur + ".png");
  $(".niveau_combattants:eq(0)").html(niveau_joueur);
  $(".noms_combattants:eq(0)").removeClass("monstre_feu monstre_eau monstre_vent monstre_terre").addClass("monstre_" + element_joueur).html(pseudo_joueur);
  $("#pm_joueur").html(pm_joueur);
  $("#pm_monstre").html(pm_monstre);

  //Changement du profil elem et de la barre d'endu du joueur actif
  initialiser_barre_endu_joueur(endu_joueur);
  $("#pv_joueur_nbre").html(endu_joueur);
  initialisation_player_combo(0);

  //Changement de l'image du joueur
  $("#img_joueur").hide("slide", {easing: "swing"}, vitesse_anim, function () {
    $("#img_joueur").attr("src", caracs_joueur_suivant.img_joueur).load(function () {
      $("#img_joueur").show("slide", {easing: "swing"}, vitesse_anim, function () {
        if (doublon == false) {
          doublon = true;
        }
      });
    });
  });
}

function initialisation_player_combo(valeur) {
  //if (COMBAT_DEBUG) console.log('FCT --> initialisation_player_combo');
  var tmp = $("#player_combo_bar");

  plot_combo = $.jqplot('player_combo_bar', [[[parseInt(valeur), 1]]], {
    stackSeries: true,
    captureRightClick: false,
    seriesDefaults: {
      renderer: $.jqplot.BarRenderer,
      shadowAngle: 0,
      rendererOptions: {
        barDirection: 'horizontal',
        highlightMouseDown: true,
        highlightMouseOver: true,
        barWidth: 100,
        shadowOffset: 0
      },
      pointLabels: {show: false, formatString: '%d'}
    },
    legend: {
      show: false
    },
    grid: {
      drawGridLines: false,
      gridLineColor: '#FFF',
      background: '#D6C8A7',
      borderWidth: 0,
      shadow: false
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
      min: 0,
      max: COMBO_MAX_AMOUNT
    },
    axes: {
      xaxis: {
        show: false,
        showTicks: false,
        showTickMarks: false
      },
      yaxis: {
        show: false,
        showTicks: false,
        showTickMarks: false
      }
    },
    animate: false,
    animateReplot: false,
    seriesColors: ["#6A3500"]
  });
}

function initialiser_barre_endu_joueur(valeur) {
  //if (COMBAT_DEBUG) console.log('FCT --> initialiser_barre_endu_joueur');
  plot_j = $.jqplot('barre_endu_joueur', [[[parseInt(valeur), 1]]], {
    stackSeries: true,
    captureRightClick: false,
    seriesDefaults: {
      renderer: $.jqplot.BarRenderer,
      shadowAngle: 0,
      rendererOptions: {
        barDirection: 'horizontal',
        highlightMouseDown: true,
        highlightMouseOver: true,
        barWidth: 100,
        shadowOffset: 0
      },
      pointLabels: {show: false, formatString: '%d'}
    },
    legend: {
      show: false
    },
    grid: {
      drawGridLines: false,
      gridLineColor: '#FFF',
      background: '#D6C8A7',
      borderWidth: 0,
      shadow: false
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
      min: 0,
      max: valeur
    },
    axes: {
      xaxis: {
        show: false,
        showTicks: false,
        showTickMarks: false
      },
      yaxis: {
        show: false,
        showTicks: false,
        showTickMarks: false
      }
    },
    animate: false,
    animateReplot: false,
    seriesColors: ["#6A3500"]
  });
}

function initialiser_barre_endu_monstre(valeur, valeur_max) {
  //if (COMBAT_DEBUG) console.log('FCT --> initialiser_barre_endu_monstre');
  plot_m = $.jqplot('barre_endu_monstre', [[[parseInt(valeur), 1]]], {
    stackSeries: true,
    captureRightClick: false,
    seriesDefaults: {
      renderer: $.jqplot.BarRenderer,
      shadowAngle: 0,
      rendererOptions: {
        barDirection: 'horizontal',
        highlightMouseDown: true,
        highlightMouseOver: true,
        barWidth: 100,
        shadowOffset: 0,
      },
      pointLabels: {show: false, formatString: '%d'}
    },
    legend: {
      show: false
    },
    grid: {
      drawGridLines: false,
      gridLineColor: '#FFF',
      background: '#D6C8A7',
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
      min: 0,
      max: valeur_max,
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
      },
    },
    animate: false,
    animateReplot: !$.jqplot.use_excanvas,
    seriesColors: ["#6A3500"]
  });
}

function initialiser_combo_gauge(valeur, valeur_max) {
  //if (COMBAT_DEBUG) console.log('FCT --> initialiser_barre_endu_monstre');
  plot_m = $.jqplot('barre_endu_monstre', [[[parseInt(valeur), 1]]], {
    stackSeries: true,
    captureRightClick: false,
    seriesDefaults: {
      renderer: $.jqplot.BarRenderer,
      shadowAngle: 0,
      rendererOptions: {
        barDirection: 'horizontal',
        highlightMouseDown: true,
        highlightMouseOver: true,
        barWidth: 100,
        shadowOffset: 0,
      },
      pointLabels: {show: false, formatString: '%d'}
    },
    legend: {
      show: false
    },
    grid: {
      drawGridLines: false,
      gridLineColor: '#FFF',
      background: '#D6C8A7',
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
      min: 0,
      max: valeur_max,
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
      },
    },
    animate: false,
    animateReplot: !$.jqplot.use_excanvas,
    seriesColors: ["#6A3500"]
  });
}

function get_new_exercice() {
  //if (COMBAT_DEBUG) console.log('FCT --> start_excercice');
  $(".icones_sorts_combat").removeAttr("style");
  $("#combat_borders").show();

  // Determining exercise depending on element and
  var spell = $("#spell_info_" + selected_spell_num_for_turn);
  var spell_element = spell.attr("data-element");
  // FIXME change spells element from french to english
  if ('feu' === spell_element) spell_element = 'fire';
  if ('eau' === spell_element) spell_element = 'water';
  if ('vent' === spell_element) spell_element = 'wind';
  if ('terre' === spell_element) spell_element = 'earth';
  var challengeRequest = challenge_choice(potential_challenges, spell_element, timeSlot);
  codeChallenge = challengeRequest[0]; //Type : "element_name_x_x
  masteryChallenge = challengeRequest[1];
  if (COMBAT_DEBUG) console.log("ID CHALLENGE = " + codeChallenge);
  get_challenge_json(codeChallenge); // !! Callback to start_exercise(id_challenge);
}

function start_exercise(id_challenge) {

  $("#chrono_combat").show();

  // 'challenge' comes from challenge.js it is the json exercise file
  if (typeof challenge == 'undefined' || typeof challenge.timer == 'undefined') {
    if (COMBAT_DEBUG) console.log("le fichier de l'exercice (" + id_challenge + ") n'existe pas : ca va planter !");
  }
  if(historic_timing[codeChallenge] != undefined && playerId!=48) //If there is already some answering time data on this exercise (or if it is the demo)
  {
		var userFactor = 4;
		var baseFactor = 1;
		duree_chrono = (historic_timing[codeChallenge]*userFactor + challenge.timer*baseFactor)/(userFactor+baseFactor);
  }
  else
  {
		duree_chrono = challenge.timer;
  }
  chrono = duree_chrono;

  initialization();
	spellLaunched = false;

  // Let's init the excercice's chrono
  plot_chrono = $.jqplot('chrono_combat', [[[chrono, 1]]], {
    stackSeries: true,
    captureRightClick: false,
    seriesDefaults: {
      renderer: $.jqplot.BarRenderer,
      shadowAngle: 0,
      rendererOptions: {
        barDirection: 'horizontal',
        highlightMouseDown: true,
        highlightMouseOver: true,
        barWidth: 100,
        shadowOffset: 0
      },
      pointLabels: {show: false, formatString: '%d'}
    },
    legend: {
      show: false
    },
    grid: {
      drawGridLines: false,
      gridLineColor: '#FCD9D8',
      background: '#FCD9D8',
      borderWidth: 0,
      shadow: false
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
      min: 0,
      max: chrono
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
        showTickMarks: false
      }
    },
    animate: false,
    animateReplot: !$.jqplot.use_excanvas,
    seriesColors: ["#ac0000"]
  });

  chrono_interval = setInterval(function () {
	if(pause == false){
		chrono -= 100;
		plot_chrono.series[0].data = [[[chrono]]];
		plot_chrono.redraw();
		if (chrono <= 0) {
		  $("#combat_borders").hide();
		  // Chrono end : no answer : only if answer was not submitted meanwhile
		  if (answer_submitted == false) {
			arreter_chrono();
		  }
		}
	}

  }, 100);
}

function arreter_chrono() {
  //if (COMBAT_DEBUG) console.log('FCT --> arreter_chrono');
  clearInterval(chrono_interval);
  $(".icones_sorts_combat").unbind("click");

  $("#chrono_combat").hide("clip", {easing: "swing"}, 200, function () {
    // Chrono ends : no answer, so false
    $("#bonus_chrono").show("scale", {easing: "swing", percent: 100}, vitesse_anim*2, function () {
      $("#bonus_chrono").html('Temps écoulé !!');
      $("#bonus_chrono").hide();
    });
    animation_player_action(false);
  });
}

function commencer_combat() {
  $.ajax({
    url: '/app/controllers/ajax.php',
    type :'POST',
    data: 'commencer_combat=' + id_combat,
    dataType: 'json',
    success: function(resultat, statut){
    },
    error: function(resultat, statut, erreur){
      //Erreur est une chaine de caractère à afficher au joueur
    },
    complete: function(resultat, statut){
      // Nothing to do, consider fight started
      round_start();
    }
  });
}

/**
 * victory = 1  : victoire
 * victory = 0  : défaite
 * victory = -1 : not ended
 * @param callback
 * @param victory
 */
function enregistrer_deroulement(callback, issue) {
  // END FIGHT
  if (COMBAT_DEBUG) console.log('FCT --> enregistrer_deroulement');
  if (COMBAT_DEBUG) console.log('fight_flow=' + JSON.stringify(fight_flow_array));
  if (COMBAT_DEBUG) console.log('---> endu_monstre      = ' + endu_monstre);
  if (COMBAT_DEBUG) console.log('---> used_heal         = ' + used_heal);
  if (COMBAT_DEBUG) console.log('---> percent_life      = ' + pourcent_vie_joueur);
  if (COMBAT_DEBUG) console.log('---> spells_all_ok     = ' + spells_all_ok);
  if (COMBAT_DEBUG) console.log('---> spells_pm_percent = ' + spells_pm_percent);
  $.ajax({
    url: '/app/controllers/ajax.php',
    type: 'POST',
    data: 'deroulement_combat=' + JSON.stringify(deroulement_array)
        + '&endu_monstre=' + endu_monstre
        + '&id=' + id_combat
        + '&fight_flow=' + JSON.stringify(fight_flow_array)
        + '&used_heal=' + used_heal
        + '&percent_life=' + pourcent_vie_joueur
        + '&spells_all_ok=' + spells_all_ok
        + '&spells_pm_percent=' + spells_pm_percent
        + '&end=' + issue,
    success: function (resultat, statut) {
      callback();
    },
    error: function (resultat, statut, erreur) {
      if (COMBAT_DEBUG) console.log('Enregistrement échoué status : ' + statut);
      if (COMBAT_DEBUG) console.log('Enregistrement échoué erreur : ' + erreur);
      enregistrer_deroulement(callback, issue);
    },
    complete: function (resultat, statut) {
    },
	timeout: 5000
  });
}

function animation_monster_action() {
  $("#countdown_monster").addClass("cache");

  //if (COMBAT_DEBUG) console.log('FCT --> animation_monster_action');
  // Final turn of current round : monster attacks
  // So we prepare the next round

  if (state_skipturn != true) {
    // Monster Attack
    var impact_monster = false;
    var impact_player = true;
    var degats_monstre = Math.round( entier_aleatoire( 0.8 * pm_monstre, 1.2 * pm_monstre ) * coef_atq_monster );

    var fight_flow = {};
    fight_flow.joueur_id = id_joueur;
    fight_flow.combat_id = id_combat;
    fight_flow.turn_player = 0;
    fight_flow.round = round;
    fight_flow.spell_num = -1;


    if (degats_monstre > 0) {
      fight_flow.success = 1;

      var absorb = 0;
      var log_fight = '';
      if (state_dodge == true) {
        impact_player = false;
        fight_flow.dodge = 1;
        log_fight += "Tu esquives l'attaque du monstre.<br>";
      }

      if (impact_player && state_absorb.active == true) {
        // STATE : ABSORB % ATTACK
        // Cannot absorb more than 100 %
        state_absorb.percent = Math.min(100, state_absorb.percent );
        absorb = Math.round(degats_monstre * state_absorb.percent / 100);
        if (COMBAT_DEBUG) console.log("Absorbing " + state_absorb.percent + "% of monster's attack : " + degats_monstre + " = " + absorb + " pv");
        degats_monstre -= absorb;
        log_fight += "Tu absorbes " + state_absorb.percent + "% de l'attaque du monstre.<br>";
        fight_flow.absorb = absorb;
      }

      if (impact_player) {
        fight_flow.hit = degats_monstre;
        endu_joueur -= degats_monstre;
        log_fight += "L'attaque du monstre t'inflige " + degats_monstre + " dégâts.<br>";
      }

      if (state_sendback.active == true) {
        // STATE : SEND BACK % ATTACK
        log_fight += "Tu renvois au monstre " + state_sendback.percent + "% de son attaque.<br>";
        var impact = Math.round(degats_monstre * state_sendback.percent / 100);
        if (COMBAT_DEBUG) console.log("Sending back " + state_sendback.percent + "% of monster's attack : " + degats_monstre + " = " + impact + " pv");
        endu_monstre -= impact;
        fight_flow.sendback = impact;
        impact_monster = true;
      }
      log_fight = log_fight.substr(0, log_fight.length - 4); //Remove the last <br>
      $("#info_combat").html("<span class='l100 ib'>" + log_fight + "</span>");
      fight_flow_array.push( fight_flow );
      if (state_dodge) monstre_aucune_atq();
      else monstre_atq( impact_player, impact_monster);
    }
    else {
      fight_flow.success = 0;
      fight_flow_array.push( fight_flow );
      $("#info_combat").html("<span class='l100 ib'>Le monstre ne t'inflige aucun dégâts ce tour-ci.</span>");
      monstre_aucune_atq();
    }
  }
  else {
    // STATE : message monster has to skip turn !
    $("#info_combat").html("<span class='l100 ib'>Le monstre passe son tour.</span>");
    monstre_aucune_atq();
  }
  //var timer = vitesse_anim * 3 + 50;
  //animation_timeout_timer(timer);
}
function animation_timeout_timer(timer, after_monster_turn) {

  // Here we are just after the monster's attack
  if (after_monster_turn) {
    remaining_turn = TURNS_IN_A_ROUND;
    round++;
    // on new round reset : spells_used = []
    spells_used = [];

    // CHECKING OBSOLETE CONDITIONS
    // PM for players
    pm_joueur = get_pm_player_adjusted(round, pm_joueur);
    // PM for monster
    pm_monstre = get_pm_monster_adjusted(round, pm_monstre);

    reinitialiser();
  }
  setTimeout(function () {
    //if (COMBAT_DEBUG) console.log('--> animation_timeout_timer');
    var continuer_combat = (endu_joueur > 0) && (endu_monstre > 0);
    if (continuer_combat) {
      //Si le tour est fini mais pas le combat, on passe au tour suivant
      pourcent_vie_joueur = Math.round(100 * endu_joueur / endu_joueur_max);
      pourcent_vie_monstre = Math.round(100 * endu_monstre / endu_monstre_max);
      // Now we start the round (either new or same) -> depends on remaining turn
      round_start();
    }
    else if (endu_monstre <= 0) {
      deroulement_array.push("victoire, , , , , ");
      enregistrer_deroulement(victoire, "victoire"); //Callback sur animation de victoire
    }
    else if (endu_joueur <= 0 && dernier_joueur == "non") {
      deroulement_array.push("changer_joueur, , , , , ");
      enregistrer_deroulement(changer_joueur, ""); //Callback sur animation de changement de joueur
    }
    else if (endu_joueur <= 0 && dernier_joueur == "oui") {
      deroulement_array.push("defaite, , , , , ");
      enregistrer_deroulement(defaite, "defaite"); //Callback sur animation de défaite
    }
  }, timer);
}
function send_spell_actions(fight_flow) {
  if (COMBAT_DEBUG) console.log('--> send_spell_actions');

  var impact_pv_monstre = false;
  var impact_pv_player = false;

  // Coef form element for player
  var coef = get_elem_coef(fight_flow.spell_element, elem_monstre_nom);

  var effect = get_effect(fight_flow.spell_num, fight_flow.spell_level, round, pm_joueur, pm_monstre, endu_joueur, true, coef, endu_joueur_max);
  increment_spell_reussite(fight_flow.spell_num);

  fight_flow.spell_reussite = get_spell_reussite(fight_flow.spell_num);


  // ONLY IF here (no elses) because effects are sometimes cumulatives
  if ( typeof effect.hit !== 'undefined' && effect.hit != 0 ) {
    var hit_t = Math.round(effect.hit);
    if (COMBAT_DEBUG) console.log('HIT MONSTER : ' + hit_t);
    endu_monstre -= hit_t;
    impact_pv_monstre = true;
    fight_flow.hit = hit_t;
    spells_pm_percent = Math.max(spells_pm_percent, Math.round(hit_t / pm_joueur) * 100);
    if (COMBAT_DEBUG) console.log('hit PM% : ' + spells_pm_percent);
  }
  if ( typeof effect.heal != 'undefined' && effect.heal != 0 ) {
    var heal_t = Math.round(effect.heal);
    if (COMBAT_DEBUG) console.log('HEAL PLAYER : ' + heal_t);
    endu_joueur = Math.min( endu_joueur + heal_t, endu_joueur_max);
    impact_pv_player = true;
    fight_flow.heal = heal_t;
  }

  if ( typeof effect.pm_player != 'undefined' && effect.pm_player != 0 ) {
    if (COMBAT_DEBUG) console.log('PM for Player : ' + effect.pm_player);
    pm_joueur += effect.pm_player;
	  change_pm_joueur(true);
    fight_flow.pm_player = effect.pm_player;
  }
  if ( typeof effect.pm_monster != 'undefined' && effect.pm_monster != 0 ) {
    if (COMBAT_DEBUG) console.log('PM- for monster : ' + effect.pm_monster);
    pm_monstre -= effect.pm_monster;
	  change_pm_monstre(true);
    fight_flow.pm_monster = effect.pm_monster;
  }

  if ( typeof effect.skip_turn != 'undefined') {
    if (COMBAT_DEBUG) console.log("Will skip monster's turn is active : " + effect.skip_turn)
    state_skipturn = effect.skip_turn;
    if (state_skipturn) {
      affiche_skipturn_monstre();
      fight_flow.skipturn = 1;
    }
  }
  if ( typeof effect.dodge != 'undefined') {
    if (COMBAT_DEBUG) console.log("Will dodge monster's attack is active : " + effect.dodge)
    state_dodge = effect.dodge;
    affiche_dodge_joueur();
    fight_flow.dodge = 1;
  }
  if ( typeof effect.send_back != 'undefined') {
    var sendback = effect.send_back;
    if (state_sendback.active == true) {
      sendback += state_sendback.percent;
      fight_flow.sendback = state_sendback.percent;
    }
    state_sendback.active = true;
    state_sendback.percent = sendback;
    if (COMBAT_DEBUG) console.log("Will send back " + sendback + "% of monster's attack");
    affiche_sendback_joueur();
  }
  if ( typeof effect.absorb != 'undefined') {
    var absorb = effect.absorb;
    if (state_absorb.active == true) {
      absorb += state_absorb.percent;
      fight_flow.absorb = state_absorb.percent;
    }
    state_absorb.active = true;
    // Cannot absorb more than 100 %
    absorb = Math.min(100, absorb );
    state_absorb.percent = absorb;
    if (COMBAT_DEBUG) console.log("Will absorb " + absorb + "% of monster's attack")
    affiche_absorb_joueur();
  }

  switch (fight_flow.spell_num) {
	case 0 : // Base attack
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    // *************************************** FIRE
    case 1 : // Boule de feu
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 2: // Brulures
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 3: // Sacrifice
      buff_joueur($("#" + fight_flow.spell_num));
      break;
    case 4: // Feu du désespoir
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 5: // Mur de feu
      protec_joueur($("#" + fight_flow.spell_num), true);
      break;
    case 6: // Pacte de flammes
      buff_joueur($("#" + fight_flow.spell_num));
      break;
    case 7: // Frappe incandescente
      sort_lance($("#" + fight_flow.spell_num), false);
      break;

    // *************************************** WATER
    case 8: // Brouillard aveuglant
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 9: // Puissance des flots
      buff_joueur($("#" + fight_flow.spell_num));
      break;
    case 10: // Lame de fond
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 11: // Déferlante
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 12: // Gel
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 13: // Protection aquatique
      protec_joueur($("#" + fight_flow.spell_num), false);
      break;
    case 14: // Marée haute
      buff_joueur($("#" + fight_flow.spell_num));
      break;

    // *************************************** WIND
    case 15: // Mistral
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 16: // Bourrasque
        if (state_dodge == true) {
          // TODO ?? esquive($("#" + fight_flow.spell_num));
          buff_joueur($("#" + fight_flow.spell_num));
        }
        else {
          // TODO add nothing animation ??
        }
      break;
    case 17: // Vengeance fulgurante
      protec_joueur($("#" + fight_flow.spell_num), true);
      break;
    case 18: // Rafale
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 19: // oeil du cyclone
      soin_joueur($("#" + fight_flow.spell_num));
      break;
    case 20: // Tornade : lancé uniquement si réussi
        if (effect.pm_monster > 0) sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 21: // Tourmente céleste
      protec_joueur($("#" + fight_flow.spell_num), true);
      break;

    // *************************************** EARTH
    case 22: // Enracinement
      soin_joueur($("#" + fight_flow.spell_num));
      break;
    case 23: // Armure d'écorce
      protec_joueur($("#" + fight_flow.spell_num), false);
      break;
    case 24: // Montée de sève
      buff_joueur($("#" + fight_flow.spell_num));
      break;
    case 25: // Jet de poison
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 26: // Etau végétal
      sort_lance($("#" + fight_flow.spell_num), false);
      break;
    case 27: // Régénération : lancé uniquement si réussi
      if (effect.heal > 0) soin_joueur($("#" + fight_flow.spell_num));
      break;
    case 28: // Drain de puissance
      protec_joueur($("#" + fight_flow.spell_num), false);
      break;

  }

  if (impact_pv_monstre) change_pv(endu_monstre, vitesse_anim, plot_m, $("#barre_endu_monstre"), $("#pv_monstre_nbre"));
  if (impact_pv_player) change_pv(endu_joueur, vitesse_anim, plot_j, $("#barre_endu_joueur"), $("#pv_joueur_nbre"));


  $("#info_combat").html("<span class='l100 ib'>" + fight_flow.spell_name + " " + effect.desc + ".</span>");

  var icone = $("#icone_" + fight_flow.spell_num);
  var niveau_sort = $("#niveau_" + fight_flow.spell_num);
  niveau_sort.html(fight_flow.spell_level);
  icone.attr("src", "/webroot/img/spells/" + fight_flow.spell_element + "_"+ fight_flow.spell_num + ".png");
  if(playerVolumeSoundEffects == 1){
    $("#son_" + fight_flow.spell_num).trigger("play");
  }
  apparition_sort(fight_flow.spell_num);
}
function animation_player_action(correct_answer) {
  //if (COMBAT_DEBUG) console.log('FCT --> animation_player_action');
  //SORT LANCES SUR LE MONSTRE
  var spell = $("#spell_info_" + selected_spell_num_for_turn);
  var spell_name = spell.attr("data-nom");
  var spell_level = spell.attr("data-niveau");
  var spell_element = spell.attr("data-element");

  // If spell is critical remove it !
  if (is_critical(selected_spell_num_for_turn) ) {
    $('#spell_critical').hide().effect("pulsate", {easing: "swing"}, vitesse_anim);
    $('#spell_critical').remove();
  }

  var fight_flow = {};
  fight_flow.joueur_id = id_joueur;
  fight_flow.combat_id = id_combat;
  fight_flow.turn_player = 1;
  fight_flow.round = round;
  fight_flow.spell_num = selected_spell_num_for_turn;
  fight_flow.spell_name = spell_name;
  fight_flow.spell_level = spell_level;
  fight_flow.spell_element = spell_element;

  // is it a heal spell ? For Achievements purpose
  used_heal = is_heal(selected_spell_num_for_turn);

  // Player Attack
  if (correct_answer) {
    fight_flow.success = 1;
    send_spell_actions(fight_flow);

    //TRAITEMENT EN CAS DE SORT LANCE
    //Si la réponse au sort est bonne
    //Si l'attaque du monstre a été réduite à 0
    if (attaque_esquive) {
      var timer = vitesse_anim * 2 / 3;
    }
    //Si l'attaque du monstre n'a pas déjà été esquivée et que le sort a été invoqué
    else if (get_spell_reussite(selected_spell_num_for_turn) > 1) {
      info(vitesse_anim);
      var timer = vitesse_anim * 3 + 50;
      if (pm_monstre == 0) {
        attaque_esquive = true;
      }
    }
    //Si l'attaque du monstre n'a pas déjà été esquivée et que le sort n'a pas été invoqué (%age de chance)
    else {
      info(vitesse_anim);
      var timer = vitesse_anim * 3 + 50;
    }
  }
  else {
    if (COMBAT_DEBUG) console.log('--> mauvaise réponse');
    spells_all_ok = false;
    fight_flow.success = 0;
    //Si la réponse au sort est mauvaise
    var timer = vitesse_anim * 2 / 3;
    reset_spell_reussite(selected_spell_num_for_turn);
  }

  // Adding déroulement :)
  fight_flow_array.push(fight_flow);

  animation_timeout_timer(timer, false);
}

// **************** -----------> FUNCTION ANIMATION
//Fonction pour démarrer les animations
function joueur_atq() {
  var pos_monstre = coordonnees_milieu_monstre2($("#graphisme_atq_joueur_" + elem1_nom));
  var effet = {top: pos_monstre[0], left: pos_monstre[1]};

  droite_joueur($("#graphisme_atq_joueur_" + elem1_nom));
  info(vitesse_anim);
  $("#graphisme_atq_joueur_" + elem1_nom).show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
	if(playerVolumeSoundEffects == 1){
    $("#son_attaque").trigger("play");
	}
    $("#graphisme_atq_joueur_" + elem1_nom).animate(effet, vitesse_anim, "easeInExpo", function () {
      $("#graphisme_atq_joueur_" + elem1_nom).hide();
      $("#img_monstre").effect("shake", {easing: "swing"}, vitesse_anim);
    });
  });
}

function monstre_atq(impact_player, impact_monster) {
  var pos_joueur = coordonnees_milieu_joueur2($("#graphisme_atq_monstre"));
  var effet = {top: pos_joueur[0], left: pos_joueur[1]};

  info(vitesse_anim);
  gauche_monstre($("#graphisme_atq_monstre"));
  $("#graphisme_atq_monstre").show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
		if(playerVolumeSoundEffects == 1){
	    $("#son_attaque").trigger("play");
		}
    $("#graphisme_atq_monstre").animate(effet, vitesse_anim, "easeInExpo", function () {
			if (impact_monster) change_pv(endu_monstre, vitesse_anim, plot_m, $("#barre_endu_monstre"), $("#pv_monstre_nbre"));
	    if (impact_player) change_pv(endu_joueur, vitesse_anim, plot_j, $("#barre_endu_joueur"), $("#pv_joueur_nbre"));
      //reinitialiser(); //Enleve les différents effets
      $("#graphisme_atq_monstre").hide();
      $("#img_joueur").effect("shake", {easing: "swing"}, vitesse_anim);
      var timer = vitesse_anim * 3 + 50;
      animation_timeout_timer(timer, true);
    });
  });
}
function monstre_aucune_atq() { //Utilisé quand le monstre a une PM égale à 0 ce tour là
  var pos_joueur = coordonnees_milieu($("#graphisme_atq_monstre"));
  var effet = {top: pos_joueur[0], left: pos_joueur[1]};

  info(vitesse_anim);
  gauche_monstre($("#graphisme_atq_monstre"));
  $("#graphisme_atq_monstre").show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
	if(playerVolumeSoundEffects == 1){
    $("#son_attaque_ratee").trigger("play");
	}
    $("#graphisme_atq_monstre").animate(effet, vitesse_anim, "easeOutExpo", function () {
      $("#graphisme_atq_monstre").hide("scale", {easing: "swing", percent: 0}, vitesse_anim, function () {
        //reinitialiser(); //Enleve les différents effets
        var timer = vitesse_anim * 3 + 50;
        animation_timeout_timer(timer, true);
      });
    });
  });
}

function changer_joueur() { //Utilisé pour passer au joueur suivant quand le premier joueur est K.O.
  if (issue_combat == "victoire" || issue_combat == "defaite") //Si le combat est déjà fini
  {
    recuperer_joueur_suivant_combat_fini(); //Callback pour effectuer graphiquement le changement de joueur
  }
  else {
    recordExercises();
	recuperer_joueur_suivant(); //Callback pour effectuer graphiquement le changement de joueur
  }
}
function victoire() { //Fait apparaitre le panneau Victoire avec le gain de prestige
  recordExercises();
  hide_monstre();
  $("#issue_combat").addClass("vert bordures_vert");
  $("#issue_combat img:eq(0)").attr("src", "/webroot/img/icones/victoire.png");
  $("#issue_combat span:eq(1) span:eq(0)").html("VICTOIRE !");
  $("#issue_combat span:eq(1) span:eq(1)").html("+" + $("#gain_prestige").html());
  if(playerTuto == "combattre_2"){
  	$("#issue_combat span:eq(1) span:eq(1)").hide();
		$("#issue_combat span:eq(1) span:eq(2)").hide();
  }
  $(message_encouragement("victoire")).appendTo($("#issue_combat span:eq(1)"));
	createCloseImg($("#issue_combat"));
  if ($("#son_musique_combat").length) {
    $("#son_musique_combat").trigger("pause");
	if(playerVolumeSoundEffects == 1){
    $("#son_victoire").trigger("play");
  }
  }
  $("#issue_combat").show("scale", {easing: "swing", percent: 100}, 1000, function () {
	position_tuto_arrow();
    $("#issue_combat").effect("pulsate", {easing: "swing"}, 1000, function() {
			if(playerTuto == "fini"){
				display_impressions();
			}
		});
  });
}
function defaite() { //Fait apparaitre Teish qui chasse le monstre puis la perte de prestige
  recordExercises();
  $("#teish").show("pulsate", {easing: "swing"}, 1000, function () {
    $("#bulle_teish").show("blind", {easing: "swing"}, vitesse_anim);
    var pos_monstre = coordonnees_milieu_monstre2($("#graphisme_atq_teish"));
    var effet = {top: pos_monstre[0], left: pos_monstre[1]};
    droite_teish($("#graphisme_atq_teish"));
    position_tuto_arrow();
    $("#graphisme_atq_teish").show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
      $("#graphisme_atq_teish").animate(effet, vitesse_anim, "easeInExpo", function () {
        $("#graphisme_atq_teish").hide();
				hide_monstre();
        $("#issue_combat").addClass("rouge bordures_rouge");
        $("#issue_combat img:eq(0)").attr("src", "/webroot/img/icones/defaite.png");
        $("#issue_combat span:eq(1) span:eq(0)").html("DEFAITE !");
				$("#issue_combat span:eq(1) span:eq(1)").html($("#perte_prestige").html());
				if(playerTuto == "combattre_2")	{
					$("#issue_combat span:eq(1) span:eq(1)").hide();
					$("#issue_combat span:eq(1) span:eq(2)").hide();
				}
		    $(message_encouragement("defaite")).appendTo($("#issue_combat span:eq(1)"));
				createCloseImg($("#issue_combat"));
		        $("#teish").hide();
		        $("#bulle_teish").hide();
		        if ($("#son_musique_combat").length) {
		          $("#son_musique_combat").trigger("pause");
		        }
				if(playerVolumeSoundEffects == 1){
		        $("#son_defaite").trigger("play");
				}
		    $("#issue_combat").show("scale", {easing: "swing", percent: 100}, 1000, function () {
		      position_tuto_arrow();
		      $("#issue_combat").effect("pulsate", {easing: "swing"}, 1000, function(){
					 if(playerTuto == "fini"){
						display_impressions();
					 }
				  });
		    });
      });
    });
  });
}
// **************** <----------- FUNCTION ANIMATION

function message_encouragement(fin_combat) {
  //if (COMBAT_DEBUG) console.log('FCT --> message_encouragement');
  pourcent_vie_joueur = Math.round(100 * endu_joueur / endu_joueur_max);
  if (fin_combat == "victoire") {
    if (nb_chasseurs + 1 < nb_chasseurs_recommandes) {
      return '<span class="ib l100 g mh2">INCROYABLE !</span><span class="ib l100">Normalement, il fallait ' + nb_chasseurs_recommandes_txt + ' chasseurs pour en venir à bout !</span>';
    }
    else if (coef_atq_monster == 1.15) //Si l'élément du monstre était la seule faiblesse du joueur
    {
      if (nb_chasseurs == 1) {
        return '<span class="ib l100 g mh2">INCROYABLE !</span><span class="ib l100">L\'élément de ce monstre était pourtant précisémment ta faiblesse !</span>';
      }
      else {
        return '<span class="ib l100 g mh2">BRAVO !</span><span class="ib l100">L\'élément de ce monstre était pourtant précisémment ta faiblesse, tu as bien fait de t\'allier !</span>';
      }
    }
    else if (coef_atq_monster > 1) //Si l'élément du monstre était un handicap (même léger)
    {
      if (nb_chasseurs == 1) {
        return '<span class="ib l100 g mh2">FELICITATIONS !</span><span class="ib l100">Tu as gagné alors que l\'élément de ce monstre était une de tes faiblesses !</span>';
      }
      else {
        return '<span class="ib l100 g mh2">BRAVO !</span><span class="ib l100">Tu as gagné alors que l\'élément de ce monstre était une de tes faiblesses, tu as bien fait de t\'allier !</span>';
      }
    }
    else if (pourcent_vie_joueur <= 20 && (dernier_joueur == "oui" || nb_ko == nb_chasseurs - 1)) {
      return '<span class="ib l100 g mh2">Quel combat ÉPIQUE !</span><span class="ib l100">C\'est pas passé loin hein ?</span>';
    }
    else {
      if (nb_chasseurs == 1) {
        return '<span class="ib l100 g mh2">Et un monstre en moins, un !</span>';
      }
      else {
        return '<span class="ib l100 g mh2">Un beau travail d\'équipe !</span>';
      }
    }
  }
  else if (fin_combat == "defaite") {
    if (nb_chasseurs + 1 < nb_chasseurs_recommandes) {
      return '<span class="ib l100 g mh2">Bien combattu quand même !</span><span class="ib l100">Il fallait ' + nb_chasseurs_recommandes_txt + ' chasseurs pour en venir à bout. Mais tu as quand même eu raison de tenter, on ne sait jamais !</span>';
    }
    else if (coef_atq_monster == 1.15) //Si l'élément du monstre était la seule faiblesse du joueur
    {
      if (nb_chasseurs == 1) {
        return '<span class="ib l100 g mh2">Bien combattu quand même !</span><span class="ib l100">L\'élément de ce monstre était précisémment ta faiblesse. Mais tu as quand même eu raison de tenter, on ne sait jamais !</span>';
      }
      else {
        return '<span class="ib l100 g mh2">Bien combattu quand même !</span><span class="ib l100">L\'élément de ce monstre était précisémment ta faiblesse, même en équipe ça reste diffcile de gagner.</span>';
      }
    }
    else if (coef_atq_monster > 1) //Si l'élément du monstre était un handicap (même léger)
    {
      if (nb_chasseurs == 1) {
        return '<span class="ib l100 g mh2">Bien combattu quand même !</span><span class="ib l100">L\'élément de ce monstre était une de tes faiblesses. Mais tu as quand même eu raison de tenter, on ne sait jamais !</span>';
      }
      else {
        return '<span class="ib l100 g mh2">Bien combattu quand même !</span><span class="ib l100">L\'élément de ce monstre était une de tes faiblesses, même en équipe ça reste diffcile de gagner.</span>';
      }
    }
    else if (pourcent_vie_monstre <= 20) {
      if (nb_chasseurs == 1) {
        return '<span class="ib l100 g mh2">Tu l\'avais presque !</span><span class="ib l100">La prochaine fois, c\'est toi qui l\'auras !</span>';
      }
      else {
        return '<span class="ib l100 g mh2">Vous l\'aviez presque !</span><span class="ib l100">La prochaine fois, c\'est vous qui l\'aurez !</span>';
      }
    }
    else {
      return '<span class="ib l100 g mh2">Bien combattu quand même !</span><span class="ib l100">Ça arrive à tout le monde de perdre, l\'important c\'est de persévérer !</span>';
    }
  }
}

//Permet de faire apparaitre l'icone du sort
function apparition_sort(spell_num) {
  //if (COMBAT_DEBUG) console.log('FCT --> apparition_sort');
  $("#icone_" + spell_num).show("scale", {percent: 100}, vitesse_anim / 2, function () {
    $("#niveau_" + spell_num).css("top", $("#icone_" + spell_num).offset().top - 10).css("left", $("#icone_" + spell_num).offset().left - 10);
    $("#niveau_" + spell_num).show();
    setTimeout(function () {
      $("#niveau_" + spell_num).hide();
      $("#icone_" + spell_num).hide("scale", {easing: "swing", percent: 0}, vitesse_anim / 2);
    }, vitesse_anim * 2);
  });
}

function centrer_joueur(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> centrer_joueur');
  var top = top_j + 0.5 * haut_j - 0.5 * objet.height();
  var left = left_j + 0.5 * larg_j - 0.5 * objet.width();
  positionner(objet, top, left);
}

function droite_joueur(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> droite_joueur');
  var top = top_j + 0.5 * haut_j - 0.5 * objet.height();
  var left = left_j + larg_j;
  positionner(objet, top, left);
}

function droite_teish(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> droite_teish');
  var top = $("#teish").offset().top + 0.5 * $("#teish").height() - 0.5 * objet.height();
  var left = $("#teish").offset().left + $("#teish").width();
  positionner(objet, top, left);
}

function haut_joueur(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> haut_joueur');
  var top = top_j - objet.height();
  var left = left_j + 0.5 * larg_j - 0.5 * objet.width();
  positionner(objet, top, left);
}

function centrer_monstre(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> centrer_monstre');
  var top = top_m + 0.5 * haut_m - 0.5 * objet.height();
  var left = left_m + 0.5 * larg_m - 0.5 * objet.width();
  positionner(objet, top, left);
}

function gauche_monstre(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> gauche_monstre');
  var top = top_m + 0.5 * haut_m - 0.5 * objet.height();
  var left = left_m - objet.width();
  positionner(objet, top, left);
}

function coordonnees_milieu_monstre(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> coordonnees_milieu_monstre');
  var top = top_m + 0.5 * haut_m - 0.5 * objet.height();
  var left = left_m + 0.5 * larg_m - 0.5 * objet.width();
  return new Array(top, left);
}

function coordonnees_milieu_monstre2(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> coordonnees_milieu_monstre2');
  var top = top_m + 0.5 * haut_m - 0.5 * objet.height();
  var left = left_m + 0.5 * larg_m - objet.width();
  return new Array(top, left);
}

function coordonnees_milieu_joueur2(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> coordonnees_milieu_joueur2');
  var top = top_j + 0.5 * haut_j - 0.5 * objet.height();
  var left = left_j + 0.5 * larg_j;
  return new Array(top, left);
}

function coordonnees_milieu(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> coordonnees_milieu');
  var top = top_m + 0.5 * haut_m - 0.5 * objet.height();
  var left = 0.5 * (left_m + left_j + larg_j) - 0.5 * objet.width();
  return new Array(top, left);
}

// *************************************
// ******* SPELLS

function dot(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> dot');
  centrer_monstre(objet);
  objet.show("puff", {easing: "swing"}, vitesse_anim, function () {
    objet.effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
      objet.hide("puff", {easing: "swing"}, vitesse_anim);
    });
  });
}

function mur_depines(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> mur_depines');
  centrer_monstre(objet);
  objet.show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
    objet.effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
      objet.hide("scale", {easing: "swing", percent: 0}, vitesse_anim);
    });
  });
}

function esquive(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> esquive');
  droite_joueur(objet);
  objet.show("puff", {easing: "swing"}, vitesse_anim, function () {
    objet.effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
      objet.hide("puff", {easing: "swing"}, vitesse_anim);
      $("#img_joueur").hide("slide", {easing: "swing"}, vitesse_anim);
    });
  });
}

function debuff(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> debuff');
  centrer_monstre(objet);
  objet.show("puff", {easing: "swing"}, vitesse_anim, function () {
    objet.effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
      objet.hide("puff", {easing: "swing"}, vitesse_anim);
    });
  });
}

function buff_joueur(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> buff_joueur');
  centrer_joueur(objet);
  objet.show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
    objet.effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
      objet.hide("scale", {easing: "swing", percent: 0}, vitesse_anim);
    });
  });
}

function soin_joueur(objet) {
  //if (COMBAT_DEBUG) console.log('FCT --> soin_joueur');
  centrer_joueur(objet);
  objet.show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
    objet.effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
      objet.hide("scale", {easing: "swing", percent: 0}, vitesse_anim);
    });
  });
}

function protec_joueur(objet, side) {
  //if (COMBAT_DEBUG) console.log('FCT --> protec_joueur');
  if (side == true) droite_joueur(objet);
  else centrer_joueur(objet);
  objet.show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
    //$("#info_absorb").html("Absorbe : " + montant_absorbe + " dégâts");
    //$("#info_absorb").show("pulsate", {easing: "swing"}, vitesse_anim);
    objet.effect("pulsate", {easing: "swing"}, vitesse_anim);
  });
}

function sort_lance(objet, impact_joueur) {
  //if (COMBAT_DEBUG) console.log('FCT --> sort_lance');
  droite_joueur(objet);
  var pos_monstre = coordonnees_milieu_monstre(objet);
  var effet = {top: pos_monstre[0], left: pos_monstre[1]};

  objet.show("scale", {easing: "swing", percent: 100}, vitesse_anim, function () {
    objet.animate(effet, vitesse_anim, "easeInExpo", function () {
      objet.hide();
      $("#img_monstre").effect("shake", {easing: "swing"}, vitesse_anim);
    });
  });
}

function change_pm_joueur(anim) {

  var new_img = '/webroot/img/icones/state_pm.png';
  if (pm_joueur < pm_joueur_debut_tour) {
    new_img = '/webroot/img/icones/state_debuff.png';
  }
  else if (pm_joueur > pm_joueur_debut_tour) {
    new_img = '/webroot/img/icones/state_buff.png';
  }
  $("#icon_pm_joueur").attr("src", new_img);

  if (anim == true) {
    $("#pm_joueur").effect("pulsate", {easing: "swing"}, vitesse_anim, function(){
      $("#pm_joueur").html(pm_joueur);
    });
  }
}

function change_pm_monstre(anim) {
  var new_img = '/webroot/img/icones/state_pm.png';
  if (pm_monstre < pm_monstre_debut_tour) {
    new_img = '/webroot/img/icones/state_debuff.png';
  }
  else if (pm_monstre > pm_monstre_debut_tour) {
    new_img = '/webroot/img/icones/state_buff.png';
  }
  $("#icon_pm_monstre").attr("src", new_img);

  if (anim == true) {
    $("#pm_monstre").effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
      $("#pm_monstre").html(pm_monstre);
    });
  }
}

function affiche_dodge_joueur() {
  $("#icon_dodge_joueur").show();
  $("#dodge_joueur").show();
}

function affiche_skipturn_monstre() {
  $("#icon_skipturn_monstre").show();
  $("#skipturn_monstre").show();
}

function affiche_sendback_joueur() {
  $("#icon_sendback_joueur").show();
  $("#sendback_joueur").show();

  $("#sendback_joueur").effect("pulsate", {easing: "swing"}, vitesse_anim, function(){
    $("#sendback_joueur").addClass("vert").html(state_sendback.percent + '%');
  });
}

function affiche_absorb_joueur() {
  $("#icon_absorb_joueur").show();
  $("#absorb_joueur").show();

  $("#absorb_joueur").effect("pulsate", {easing: "swing"}, vitesse_anim, function(){
    $("#absorb_joueur").addClass("vert").html(state_absorb.percent + '%');
  });
}

function hide_monstre() {
	$("#img_monstre").hide("pulsate", {easing: "swing"}, vitesse_anim);
}

// *************************************
// ******* ACTION UTILISATEURS

function check_end_fight() {
  return issue_combat == "victoire" || issue_combat == "defaite";
}

// *********************************
// *********************************
// ******* GRAPHICAL INTERFACE ACTIONS
function hide_stuff() {

  $(".sort_actif_icone").hide().removeClass("cache");
  $(".sort_actif_niveau").hide().removeClass("cache");
  $("#graphisme_atq_joueur_feu").hide().removeClass("cache");
  $("#graphisme_atq_joueur_eau").hide().removeClass("cache");
  $("#graphisme_atq_joueur_vent").hide().removeClass("cache");
  $("#graphisme_atq_joueur_terre").hide().removeClass("cache");
  $("#graphisme_atq_monstre").hide().removeClass("cache");
  $("#graphisme_atq_teish").hide().removeClass("cache");
  $("#issue_combat").hide();
  $("#teish").hide().removeClass("cache");
  $("#bulle_teish").hide().removeClass("cache");
  $("#info_combat").hide().removeClass("cache");
  $("#info_absorb").hide();
}

function hide_spells() {
  $("#0").hide().removeClass("cache");
  $("#1").hide().removeClass("cache");
  $("#2").hide().removeClass("cache");
  $("#3").hide().removeClass("cache");
  $("#4").hide().removeClass("cache");
  $("#5").hide().removeClass("cache");
  $("#6").hide().removeClass("cache");
  $("#7").hide().removeClass("cache");

  $("#8").hide().removeClass("cache");
  $("#9").hide().removeClass("cache");
  $("#10").hide().removeClass("cache");
  $("#11").hide().removeClass("cache");
  $("#12").hide().removeClass("cache");
  $("#13").hide().removeClass("cache");
  $("#14").hide().removeClass("cache");

  $("#15").hide().removeClass("cache");
  $("#16").hide().removeClass("cache");
  $("#17").hide().removeClass("cache");
  $("#18").hide().removeClass("cache");
  $("#19").hide().removeClass("cache");
  $("#20").hide().removeClass("cache");
  $("#21").hide().removeClass("cache");

  $("#22").hide().removeClass("cache");
  $("#23").hide().removeClass("cache");
  $("#24").hide().removeClass("cache");
  $("#25").hide().removeClass("cache");
  $("#26").hide().removeClass("cache");
  $("#27").hide().removeClass("cache");
  $("#28").hide().removeClass("cache");
}

function visual_element_positionning() {

  $('<div id="pv_joueur_nbre" class="p1 g texte_centre bulle_daide">' + endu_joueur + '</div>').appendTo('body');
  var pos_y_barre_endu_joueur = $("#barre_endu_joueur").offset().top;
  var pos_x_barre_endu_joueur = $("#barre_endu_joueur").offset().left;
  var largeur_barre_endu_joueur = $("#barre_endu_joueur").width();
  var hauteur_barre_endu_joueur = $("#barre_endu_joueur").height();
  $("#pv_joueur_nbre").css("position", "absolute").css("top", pos_y_barre_endu_joueur + 0.5*hauteur_barre_endu_joueur - 0.5*$("#pv_joueur_nbre").height() );
  $("#pv_joueur_nbre").css("left", pos_x_barre_endu_joueur + largeur_barre_endu_joueur);

  $('<div id="pv_monstre_nbre" class="p1 g texte_centre bulle_daide">' + endu_monstre + '</div>').appendTo('body');
  var pos_y_barre_endu_monstre = $("#barre_endu_monstre").offset().top;
  var pos_x_barre_endu_monstre = $("#barre_endu_monstre").offset().left;
   var largeur_barre_endu_monstre = $("#barre_endu_monstre").width();
  var hauteur_barre_endu_monstre = $("#barre_endu_monstre").height();
  $("#pv_monstre_nbre").css("position", "absolute").css("top", pos_y_barre_endu_monstre + 0.5*hauteur_barre_endu_monstre - 0.5*$("#pv_monstre_nbre").height() );
  $("#pv_monstre_nbre").css("left", pos_x_barre_endu_monstre - 1.1*$("#pv_monstre_nbre").width() );

  $('<div id="bonus_chrono" class="p6 vert g texte_centre bulle_daide">Bonus rapidité :<br>+' + bonus_chrono + '</div>').appendTo('body');
  $("#bonus_chrono").css("position", "absolute").css("top", "15%").css("left", "35%").css("width", "30%").hide();

  //On fait en sorte que le monstre ne dépasse pas du cadre
  pos_fond = $(".fond_combat").offset().left;
  largeur_fond = $(".fond_combat").width();
  pos_monstre = $("#img_monstre").offset().left;
  largeur_monstre = $("#img_monstre").width();
  decalage = pos_monstre + largeur_monstre - pos_fond - largeur_fond + 5;
  if (decalage > 0) {
    $("#img_monstre").css("position", "relative").css("left", -decalage);
  }
  top_j = $("#img_joueur").offset().top;
  left_j = $("#img_joueur").offset().left;
  haut_j = $("#img_joueur").height();
  larg_j = $("#img_joueur").width();
  top_m = $("#img_monstre").offset().top;
  left_m = $("#img_monstre").offset().left;
  haut_m = $("#img_monstre").height();
  larg_m = $("#img_monstre").width();

  //We add images of other hunters
  if($(".tetes_chasseurs").length)
	{
		$(".tetes_chasseurs").tooltip({
			show: {
				effect: "slideDown",
				delay: 250
			}
		});
		$(".tetes_chasseurs").each(function(){
			var idTemp = $(this).attr("id");
			superpose_portrait("#"+idTemp, parseInt($("#"+idTemp+"_level").html()), $("#"+idTemp+"_element").html(), $("#"+idTemp+"_portrait").html());
			var red_cross = $(this).parent().children(".icone_chasseur_ko");
			red_cross.css("left", 0);
		});
	}

  //For Bug Feedback
   $("textarea[name=descriptif_bug]").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});

	$("textarea").on("focus", function(){
		resize_textarea($(this));
	});

	$("textarea[name=descriptif_bug]").on("keyup",function(){
		descriptif_long_valide($(this));
	});

	$("#fermer_feedback").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/refuser_selec.png");
	});

	$("#fermer_feedback").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/refuser.png");
	});

	$("#fermer_feedback").on("click", function(){
		hide_clip($("#feedback"));
		pause = false;
		if(playerVolumeMusic == 1){
		$("#son_musique_combat").trigger("play");
		}
	});

	$(".valider_feedback").on("click", function(event){
		event.preventDefault();
		if( descriptif_long_valide($("textarea[name=descriptif_bug]")) )
		{
			report_bug(codeChallenge);
		}
	});

	$('<div id="suggestion" class="bulle_daide_cliquable">Reporter un bug</div>').appendTo("#combat_borders");
	$("#suggestion").css("position", "absolute").css("right", "3%").css("top", "3%").css("width","8%");

	$("#suggestion").on("click", function(){
		display_clip($("#feedback"));
		pause = true;
		$("#son_musique_combat").trigger("pause");
	});

	$("#feedback").hide();

	if($(".validate_impressions").length){
		$("#impressions").css("position", "absolute").css("width", "50%").css("top", "20%").css("left", "25%").css("z-index","50").hide();
		$("#length").buttonset();
		$("#difficulty").buttonset();
		$("#error_impressions").dialog({
			autoOpen: false,
		});
		$(".validate_impressions").on("mousedown", function(){
			if($("#short_length").is(':checked')){
				duration = $("#short_length").val();
			}
			else if($("#good_length").is(':checked')){
				duration = $("#good_length").val();
			}
			else if($("#long_length").is(':checked')){
				duration = $("#long_length").val();
			}
			if($("#easy_difficulty").is(':checked')){
				difficulty = $("#easy_difficulty").val();
			}
			else if($("#good_difficulty").is(':checked')){
				difficulty = $("#good_difficulty").val();
			}
			else if($("#hard_difficulty").is(':checked')){
				difficulty = $("#hard_difficulty").val();
			}
			if(duration != "" && difficulty != ""){
				send_impressions();
			} else {
				$("#error_impressions").dialog("open");
			}
		});
	}

	//Pausing and resuming challenge
	$('<img id="pause_challenge" title="Pause" src="/webroot/img/icones/pause.png"/>').appendTo("#combat_borders");
	$("#pause_challenge").css("position", "absolute").css("right", "9%").css("top", "11%").css("width","4%").css("cursor", "pointer");
	$('<img id="exit" title="Abandonner le combat" src="/webroot/img/icones/exit.png"/>').appendTo("#combat_borders");
	$("#exit").css("position", "absolute").css("right", "3%").css("top", "11%").css("width","4%").css("cursor", "pointer");

	$("#pause_challenge").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	  });

	$("#exit").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
  });

  	$("#pause_challenge").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/pause_hover.png");
	});

	$("#pause_challenge").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/pause.png");
	});

	$("#pause_challenge").on("click", function(){
		pause = true;
		$("#son_musique_combat").trigger("pause");
		$("#screen_challenge").show();
	});

	$("#exit").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/exit_hover.png");
	});

	$("#exit").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/exit.png");
	});

	$("#exit").on("click", function(){
		pause = true;
		$("#exit_confirm").dialog("open");
	});

	$("#exit_confirm").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		dialogClass: "no-close",
		buttons: {
			"Abandonner" : function(){
				$(this).dialog("close");
				abortFight();
			},
			"Continuer": function(){
				pause = false;
				$(this).dialog("close");
			}
		}
	});

	function abortFight(){
		$(location).attr('href',"/index.php");
	}

  	$("#resume_challenge").css("cursor", "pointer");

  	$("#resume_challenge").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/resume_hover.png");
	});

	$("#resume_challenge").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/resume.png");
	});

	$("#resume_challenge").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});

	$("#son_musique_combat").trigger("pause");
  	pause = true;
	$("#resume_challenge").hide().removeClass("cache");
	$("#resume_challenge").show("slide", {easing:"swing"}, 500);
	$("#resume_challenge").on("click", function(){
		if(playerTuto == "fini" && playerVolumeMusic == 1){
		$("#son_musique_combat").trigger("play");
		}
		$("#resume_challenge").attr("title", "Allez, on y retourne !");
		$("#resume_challenge").unbind("click");
		pause = false;
		$("#screen_challenge").hide("clip",{easing:"swing"}, 500, function(){
			$("#loading_msg").hide();
			$("#resume_challenge").on("click", function(){
				pause = false;
				if(playerVolumeMusic == 1){
				$("#son_musique_combat").trigger("play");
				}
				$("#screen_challenge").hide();
			});
		});
	});
}

// ****** FONCTION PRINCIPALES

function spell_selected() {
  if (COMBAT_DEBUG) console.log('FCT --> spell_selected : ' + selected_spell_num_for_turn);
  //selected_spell_num_for_turn = spell_num;
  var spell_element = $("spell_info_" + selected_spell_num_for_turn);

  // If spell is critical
  if (is_critical(selected_spell_num_for_turn) ) {
    // 1- remove it and reset combo gauge
    // '-1' means we just used the combo spell : so don't add time bonus to combo when answering critical spell
    player_combo = -1;
    // Update combo plot
    $("#player_combo_bar").effect("pulsate", {easing: "swing"}, vitesse_anim, function () {
      plot_combo.series[0].data = [[[player_combo]]];
      plot_combo.redraw();
    });
    // 2- remove critical spell from available ones
    for (var i = 0; i < spells_available.length; i++) {
      var curr_spell = spells_available[i];
      if (curr_spell.num == selected_spell_num_for_turn) {
        spells_available.splice(i, 1);
        // quit loop
        break;
      }
    }
  }

  // Forbid click upon spells button
  $(".icones_sorts_combat").unbind("click");
  // Focus on current executed spell
  $(".icones_sorts_combat").removeAttr("style");
  spell_element.css("border", "4px solid #FC3").css("margin-top", "0px").css("margin-bottom", "0px");
  // Add spell to used spells for this turn
  spells_used.push(selected_spell_num_for_turn);
}
function spells_activation() {
  if (COMBAT_DEBUG) console.log('MAIN --> spells_activation');
  selected_spell_num_for_turn = -1;

  var spell_elements_sum_coef = 0;

  // grey out every spell and remove click action : *nb.png -> *.png
  for (var h = 0; h < spells_available.length; h++) {
    var spell_1 = spells_available[h];
    var curr_spell_1 = $("#spell_" + spell_1.num);
    var attr_img = curr_spell_1.attr("src");
    // Only grey out the image if it is not already in '_nb.png'
    if (attr_img.indexOf('_nb.png') == -1) {
      var new_img = attr_img.replace(/\.png/, '_nb.png');
      curr_spell_1.attr("src", new_img);
    }
    curr_spell_1.unbind("click");
    // Activate description for this turn
    var descriptif = $('#descriptif_' + spell_1.num).html( get_spell_description(spell_1) );
    // FIXME : mouse pointer change is not working !
    curr_spell_1.css('cursor', 'default');

    // Add spell element to count
    spell_elements_sum_coef += get_elem_coef(elem_monstre_nom, spell_1.element);
  }
  // Summ of all element coef divided by number of spells
  coef_atq_monster = spell_elements_sum_coef / spells_available.length;
  if (COMBAT_DEBUG) console.log("COEF MONSTER = " + coef_atq_monster);

  spells_usable = [];
  spells_usable_nums = [];
  // On fait : spells_usable =  spells available - unusable spells - used spells
  for (var i = 0; i < spells_available.length; i++) {
    var curr_spell = spells_available[i];
    var remove = false;
    // Check spell usability !
    if (!is_spell_usable(parseInt(curr_spell.num), parseInt(curr_spell.niveau), endu_joueur, endu_joueur_max, pourcent_vie_joueur, endu_monstre)) {
      remove = true;
    }
    // If spell is disabled (sacrifice, or Puissance des flots)
    if (is_spell_disabled(round, curr_spell.num)) {
      remove = true;
    }
    for (var l = 0; l < spells_used.length; l++) {
      if (curr_spell.num == spells_used[l]) {
        remove = true;
        break;
      }
    }
    if (!remove) {
      spells_usable.push(curr_spell);
      spells_usable_nums.push(parseInt(curr_spell.num));
    }
  }
  //if (COMBAT_DEBUG) console.log('ACTION --> spells_usable : ' + spells_usable_nums);

  // Activate spells_usable icons
  for (var k = 0; k < spells_usable.length; k++) {
    var spell_2 = spells_usable[k];
    var curr_spell_2 = $("#spell_" + spell_2.num);

    // ungray the image : *nb.png -> *.png
    var new_img_2 = curr_spell_2.attr("src").replace(/_nb\.png/, ".png");
    curr_spell_2.attr("src", new_img_2);
    // FIXME : pointer change is not working !
    curr_spell_2.css('cursor', 'pointer');

    // IMPORTANT : Activate click on spells to allow player to choose on for the turn
    curr_spell_2.on("click", function () {
			if(!spellLaunched){
				spellLaunched = true;
	      //if (COMBAT_DEBUG) console.log('ACTION --> click on spell: ' + $(this).attr('data-num'));
	      // Keep in memory the selected spell number for this turn
	      selected_spell_num_for_turn = parseInt($(this).attr("data-num"));
	      // Say countdown is over : 0 = shows 'starting fight message'
	      count_from = 0;
				if($("#fleche_tuto").length){
					$("#fleche_tuto").hide();
				}
			}
    });
  }

  // Reactivate descriptions
  $(".icones_sorts_combat").unbind("mouseover mouseout");
  $(".icones_sorts_combat").on("mouseover", function () {
    var id = "descriptif_" + $(this).attr("data-num");
    $(this).css("cursor", "pointer");
    var position = $(this).offset();

    var curr_spell_num = $("#" + id).attr('data-num');
    var descriptif = $("#" + id).html();
    $("#descriptif").html(descriptif);
    $("#descriptif").css("position", "absolute").css("top", position.top - 100).css("width", "25%").css("left", position.left + 0.5 * $(this).width() - 0.5 * $("#descriptif").width());
    $("#descriptif").css("text-align", "center");
    afficher($("#descriptif"), 250);
  });

  $(".icones_sorts_combat").on("mouseout", function () {
    $("#descriptif").hide();
  });


}

function desactiver_tooltip() {
  //if (COMBAT_DEBUG) console.log('FCT --> desactiver_tooltip');
  $(".icone_endu").unbind("mouseover mouseout");
  $('#barre_endu_monstre').unbind("jqplotDataHighlight jqplotDataUnhighlight");
  $('#barre_endu_joueur').unbind("jqplotDataHighlight jqplotDataUnhighlight");
  $('#player_combo_bar').unbind("jqplotDataHighlight jqplotDataUnhighlight");
  $("#legende").hide();
  //Must keep spell descriptions $(".icones_sorts_combat").unbind("mouseover mouseout");
  $("#descriptif").hide();
}

function fight_start() {
  //if (COMBAT_DEBUG) console.log('MAIN --> fight_start');
  // When fight_starts, we stop the countdown timer
  clearInterval(countdown);
  countdown = null;

  // Hiding countdown
  $("#countdown").addClass("cache");
  // Deactivate tooltips to ease UX ?? usefull ?
  desactiver_tooltip();

  // Chrono end no spell selection
  // - choose first aspell from list
  if (selected_spell_num_for_turn <= 0) {
    selected_spell_num_for_turn = parseInt(spells_usable_nums[0]);
  }

  spell_selected();

  get_new_exercice();

}

function countdown_choose_spell() {
  //if (COMBAT_DEBUG) console.log('MAIN --> countdown_choose_spell');

  // Reinit counter in seconds for this round
  count_from = COUNTDOWN_CHOOSE_SPELL;

  // Showing countdown
  $("#countdown").removeClass("cache");
  // reinit style
  var coutdown_fight = $(".countdown_fight");
  coutdown_fight.css('font-size', '60px');
  coutdown_fight.css('color', 'white');

  // When countdown ends, if player choosed no spell we choose the first one for him
  countdown = setInterval(function () {
    //if (COMBAT_DEBUG) console.log(count_from);
    // coutdown_fight is span showing counter
    var coutdown_fight = $(".countdown_fight");
    // Check if replay : if .countdown_fight doesn't exist
    if (coutdown_fight.length == 0) {
      // Here we are in replay mode
      //if (COMBAT_DEBUG) console.log('REPLAY');
      fight_start();
    }
    else {
      if (count_from > 0) {
        coutdown_fight.fadeOut('fast', function () {
          coutdown_fight.text(count_from);
          coutdown_fight.fadeIn();
          count_from--;
        });
      }
      else if (count_from == 0) {
        coutdown_fight.css('font-size', '26px');
        coutdown_fight.css('color', 'yellow');
        coutdown_fight.text("Invocation d'un sort");
        count_from--;
      }
      else {
        coutdown_fight.fadeOut();
        fight_start();
      }
    }
  }, 1000);

}

function position_tuto_arrow() {
	if ($("#tuto_suivant").length) 	{
		var pos_x = $("#issue_combat").offset().left;
		var pos_y = $("#issue_combat").offset().top;
		var larg = $("#issue_combat").width();
		var haut = $("#issue_combat").height();
		var haut_fleche = $("#fleche_tuto").height();
		$("#fleche_tuto").attr("src", "/webroot/img/icones/fleche2.png").load(function(){
			$("#fleche_tuto").css("position", "absolute").css("top", pos_y + 0.5*(haut - haut_fleche)).css("left", pos_x + larg);
			$("#fleche_tuto").effect("pulsate", {easing: "swing"}, 1000);
		});
	}
}

// INITIALISATIONS
function initialisation_joueur() {
  //if (COMBAT_DEBUG) console.log('INIT --> initialisation_joueur');

  id_joueur = parseInt($("#id_chasseur").html());
  pseudo_joueur = $("#pseudo_chasseur").html();
  niveau_joueur = parseInt($(".niveau_combattants:eq(0)").html());
  img_joueur = $('#img_joueur').attr("src");
  dernier_joueur = $("#dernier_joueur").html();
  nb_chasseurs = parseInt($("#nb_chasseurs").html());
  nb_chasseurs_recommandes = parseFloat($("#nb_chasseurs_recommandes").html());
  nb_chasseurs_recommandes_txt = nb_chasseurs_recommandes;
  switch (nb_chasseurs_recommandes) {
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
  bonus_elem_joueur_actif = parseInt($("#bonus_elem_joueur_actif").html());

  //Récupération du profil élémentaire du joueur qui est sur le point d'être affiché
  profil_elem_joueur = $("#profil_" + id_joueur).text().split(",");
  elem1_nom = profil_elem_joueur[0];
  elem2_nom = profil_elem_joueur[2];
  elem3_nom = profil_elem_joueur[4];
  elem4_nom = profil_elem_joueur[6];
  var couleur1 = couleur_barre(elem1_nom);
  var couleur2 = couleur_barre(elem2_nom);
  var couleur3 = couleur_barre(elem3_nom);
  var couleur4 = couleur_barre(elem4_nom);
  var elem1_val = parseInt(profil_elem_joueur[1]);
  var elem2_val = parseInt(profil_elem_joueur[3]);
  var elem3_val = parseInt(profil_elem_joueur[5]);
  var elem4_val = parseInt(profil_elem_joueur[7]);

  pm_joueur = parseInt($("#pm_joueur").text());
  endu_joueur = parseInt($("#endu_joueur").text());
  player_combo = parseInt($("#player_combo").text());
  initialiser_barre_endu_joueur(endu_joueur);
  initialisation_player_combo(0);

  //initialisation_profil_elem("joueur", elem1_val, elem2_val, elem3_val, elem4_val, couleur1, couleur2, couleur3, couleur4);
}
function initialisation_monstre() {
  //if (COMBAT_DEBUG) console.log('INIT --> initialisation_monstre');

  endu_monstre = parseInt($("#endu_monstre").text());
  endu_monstre_max = parseInt($("#endu_monstre_max").text());
  niveau_monstre = parseInt($("#niveau_monstre").html());
  pm_monstre = parseInt($("#pm_monstre").text());

  var profil_elem_monstre = $("#prof_monstre").text().split(",");
  elem_monstre_nom = profil_elem_monstre[0];
  //var couleur_monstre = couleur_barre(elem_monstre_nom);
  //var elem_monstre = parseInt(profil_elem_monstre[1]);
  //initialisation_profil_elem("monstre", elem_monstre, 0, 0, 0, couleur_monstre, "#FFF", "#FFF", "#FFF");
  initialiser_barre_endu_monstre(endu_monstre, endu_monstre_max)
}
function initialisation_combat() {
  //if (COMBAT_DEBUG) console.log('INIT --> initialisation_combat');
  id_combat = parseInt($("#id_combat").html());
  issue_combat = $("#issue_combat_txt").text();

  // Init spells_available array from hidden div
  $(".spell_choosed_info").each(function () {
    var spell_info = $(this);
    spells_available.push({
      num: spell_info.attr("data-num"),
      nom: spell_info.attr("data-nom"),
      niveau: spell_info.attr("data-niveau"),
      element: spell_info.attr("data-element"),
      categorie: spell_info.attr("data-categorie"),
      icone: spell_info.attr("data-icone")
    });
  });

  //Fonctions et variables pour gérer l'aspect "combat"
  pm_monstre_debut_tour = pm_monstre;
  pm_joueur_debut_tour = pm_joueur;

  endu_joueur_max = endu_joueur;
  pourcent_vie_joueur = Math.round(100 * endu_joueur / endu_joueur_max);
  pourcent_vie_monstre = Math.round(100 * endu_monstre / endu_monstre_max);
}
function initialisation_vue() {
  //if (COMBAT_DEBUG) console.log('INIT --> initialisation_vue');

  $('.fond_combat').css("background-image", "url(/webroot/img/combat_" + elem_monstre_nom + ".jpg)");

  //Récupère le déroulement du combat passé
  //Si le combat a déjà commencé ou est terminé on reprend le déroulement existant
  if ($('#deroulement').html() != "") {
    deroulement_array = $("#deroulement").html().split(";");
    if (deroulement_array[deroulement_array.length - 1] == "") {
      //On supprime le dernier élément s'il est vide
      deroulement_array.splice(deroulement_array.length - 1, 1);
    }
  }

  $(".tetes_chasseurs").tooltip({
    show: {
      effect: "slideDown",
      delay: 250
    }
  });

  //Gestion du descriptif qui s'affiche sur les différentes images de sorts et de PM et Endu
  $('<span id="descriptif" class="descriptif_sort p0"></span>').appendTo("body");
  $("#descriptif").hide();

  $('#barre_endu_joueur').bind('jqplotDataHighlight',
      function (ev, seriesIndex, pointIndex, data) {
        $(this).css("cursor", "pointer");
        var position = $(this).offset();
        var descriptif = $("#img_endu_joueur_descr").html();
        $("#descriptif").html(descriptif);
        $("#descriptif").css("position", "absolute").css("top", position.top + 80).css("width", "20%").css("left", position.left + 0.5 * $(this).width() - 0.5 * $("#descriptif").width());
        $("#descriptif").css("text-align", "center");
        afficher($("#descriptif"));
      }
  );

  $('#barre_endu_joueur').bind('jqplotDataUnhighlight',
      function (ev, seriesIndex, pointIndex, data) {
        $("#descriptif").hide();
      }
  );

  $('#player_combo_bar').bind('jqplotDataHighlight',
      function (ev, seriesIndex, pointIndex, data) {
        $(this).css("cursor", "pointer");
        var position = $(this).offset();
        var descriptif = $("#player_combo_img_descr").html();
        $("#descriptif").html(descriptif);
        $("#descriptif").css("position", "absolute").css("top", position.top + 80).css("width", "20%").css("left", position.left + 0.5 * $(this).width() - 0.5 * $("#descriptif").width());
        $("#descriptif").css("text-align", "center");
        afficher($("#descriptif"));
      }
  );

  $('#player_combo_bar').bind('jqplotDataUnhighlight',
      function (ev, seriesIndex, pointIndex, data) {
        $("#descriptif").hide();
      }
  );

  $('#barre_endu_monstre').bind('jqplotDataHighlight',
      function (ev, seriesIndex, pointIndex, data) {
        $(this).css("cursor", "pointer");
        var position = $(this).offset();
        var descriptif = $("#img_endu_monstre_descr").html();
        $("#descriptif").html(descriptif);
        $("#descriptif").css("position", "absolute").css("top", position.top + 80).css("width", "20%").css("left", position.left + 0.5 * $(this).width() - 0.5 * $("#descriptif").width());
        $("#descriptif").css("text-align", "center");
        afficher($("#descriptif"));
      }
  );

  $('#barre_endu_monstre').bind('jqplotDataUnhighlight',
      function (ev, seriesIndex, pointIndex, data) {
        $("#descriptif").hide();
      }
  );

  $('<span id="legende" class="legende_donnees_profil p0"></span>').appendTo("body");
  $("#legende").hide();

  $("#chrono_combat").hide();

  $("#issue_combat").tooltip({
    show: {
      effect: "slideDown",
      delay: 250
    }
  });

  // Hidden by default
  $("#combat_borders").hide();


  // Effects applied to the monster

  $("#icon_dodge_joueur").hide();
  $("#dodge_joueur").hide();
  $("#icon_sendback_joueur").hide();
  $("#sendback_joueur").hide();
  $("#icon_absorb_joueur").hide();
  $("#absorb_joueur").hide();
  $("#icon_skipturn_monstre").hide();
  $("#skipturn_monstre").hide();

  $("#desc_pm_monstre").hide();
  $("#desc_skipturn_monstre").hide();
  $("#icon_pm_monstre").on("mouseover", function () {
    $(this).css("cursor", "pointer");
    var position = $(this).offset();
    $("#desc_pm_monstre").css("position", "absolute").css("top", 70).css("left", 0).css("width", 200);
    $("#desc_pm_monstre").css("text-align", "center");
    afficher($("#desc_pm_monstre"), 250);
  });
  $("#icon_pm_monstre").on("mouseout", function () {
    $("#desc_pm_monstre").hide();
  });
  $("#icon_skipturn_monstre").on("mouseover", function () {
    $(this).css("cursor", "pointer");
    var position = $(this).offset();
    $("#desc_skipturn_monstre").css("position", "absolute").css("top", 70).css("left", 0).css("width", 200);
    $("#desc_skipturn_monstre").css("text-align", "center");
    afficher($("#desc_skipturn_monstre"), 250);
  });
  $("#icon_skipturn_monstre").on("mouseout", function () {
    $("#desc_skipturn_monstre").hide();
  });

  // Effects applied to the player
  $("#desc_pm_joueur").hide();
  $("#desc_dodge_joueur").hide();
  $("#desc_sendback_joueur").hide();
  $("#desc_absorb_joueur").hide();

  $("#icon_pm_joueur").on("mouseover", function () {
    $(this).css("cursor", "pointer");
    var position = $(this).offset();
    $("#desc_pm_joueur").css("position", "absolute").css("top", 70).css("left", 0).css("width", 200);
    $("#desc_pm_joueur").css("text-align", "center");
    afficher($("#desc_pm_joueur"), 250);
  });
  $("#icon_pm_joueur").on("mouseout", function () {
    $("#desc_pm_joueur").hide();
  });
  $("#icon_dodge_joueur").on("mouseover", function () {
    $(this).css("cursor", "pointer");
    var position = $(this).offset();
    $("#desc_dodge_joueur").css("position", "absolute").css("top", 70).css("left", 0).css("width", 200);
    $("#desc_dodge_joueur").css("text-align", "center");
    afficher($("#desc_dodge_joueur"), 250);
  });
  $("#icon_dodge_joueur").on("mouseout", function () {
    $("#desc_dodge_joueur").hide();
  });
  $("#icon_sendback_joueur").on("mouseover", function () {
    $(this).css("cursor", "pointer");
    var position = $(this).offset();
    $("#desc_sendback_joueur").css("position", "absolute").css("top", 70).css("left", 0).css("width", 200);
    $("#desc_sendback_joueur").css("text-align", "center");
    afficher($("#desc_sendback_joueur"), 250);
  });
  $("#icon_sendback_joueur").on("mouseout", function () {
    $("#desc_sendback_joueur").hide();
  });
  $("#icon_absorb_joueur").on("mouseover", function () {
    $(this).css("cursor", "pointer");
    var position = $(this).offset();
    $("#desc_absorb_joueur").css("position", "absolute").css("top", 70).css("left", 0).css("width", 200);
    $("#desc_absorb_joueur").css("text-align", "center");
    afficher($("#desc_absorb_joueur"), 250);
  });
  $("#icon_absorb_joueur").on("mouseout", function () {
    $("#desc_absorb_joueur").hide();
  });

  // If player has no spells add a default one :
  var count_spells = parseInt($("#player_count_spell").html());
  if (count_spells < 1) {
    add_base_spell();
  }

}
function initialisation_actions() {
  //if (COMBAT_DEBUG) console.log('INIT --> initialisation_actions');

  $(".icone_endu").on("mouseover", function () {
    var id = $(this).attr("id") + "_descr";
    $(this).css("cursor", "pointer");
    var position = $(this).offset();
    var descriptif = $("#" + id).html();
    $("#descriptif").html(descriptif);
    $("#descriptif").css("position", "absolute").css("top", position.top + 80).css("width", "20%").css("left", position.left + 0.5 * $(this).width() - 0.5 * $("#descriptif").width());
    $("#descriptif").css("text-align", "center");
    afficher($("#descriptif"));
  });

  $(".icone_endu").on("mouseout", function () {
    $("#descriptif").hide();
  });

  // Renvoi sur l'index quand l'utilisateur clique sur le message de fin
  $("#issue_combat").on("click", function () {
    $(this).hide("scale", {easing: "swing", percent: 0}, 1000, function () {
      $(location).attr('href', "/index.php?fight="+id_combat);
    });
  });

}

function end_round_go_next() {
   // MONSTER TURN HERE
  $("#countdown_monster").removeClass("cache");
  setTimeout(function(){
    animation_monster_action();
  }, 1000);
}

function round_start() {
  //if (COMBAT_DEBUG) console.log('MAIN --> round_start');
  if (COMBAT_DEBUG) console.log('\n----------------------------------------------------');
  if (COMBAT_DEBUG) console.log('---- ROUND ' + round + ' turn ' + (TURNS_IN_A_ROUND - remaining_turn));
  if (COMBAT_DEBUG) console.log('PM P= ' + pm_joueur + ' PM M=' + pm_monstre);
  if (COMBAT_DEBUG) console.log('PV P= ' + endu_joueur + '('+ pourcent_vie_joueur+'%) PV M=' + endu_monstre + '('+ pourcent_vie_monstre+'%)' );
  if (COMBAT_DEBUG) console.log('--');
  $("#info_combat").hide().removeClass("cache");

  // Check if fight is over
  if (!check_end_fight()) {

    // Prepare next turn... of current round
    if (remaining_turn > 0) {
      remaining_turn--;
      // this is the player's turn
	  if(playerTuto == "combattre_2")
	  {
      	  $("#countdown_title").html("Tour " + round);
	  }
	  else
	  {
		  $("#countdown_title").html("Tour " + round + "<br>Sort "+ (TURNS_IN_A_ROUND-remaining_turn) + "/" + TURNS_IN_A_ROUND);
	  }
      // Tuto stuff...
      if ($("#tuto_suivant").length) {
        $("#mise_en_evidence").hide();
      }
      // *** TURN ACTIONS

      // checking for usable spells (already used or condition based)
      // listens for the click on the spell icon,
      spells_activation();

      // if there are no available spells : Go next turn
      if (spells_usable.length > 0) {
        // Generate operations one for each usable spells
        // TODORemove : generer_nouvelles_operations();
        // Starting countdown for spell choose : MOST THING ARE INSIDE THIS FUNCTION
        countdown_choose_spell();
      }
      else {
        // Go next round :
        end_round_go_next();
      }
    } else {
      // this is the monster's turn
      end_round_go_next();
    }
  }
}

// Getting potential challenges for player
function fight_initialization() {
	if(playerTuto != "fini"){
		$("#tuteur_combat").hide();
		$("#bulle_combat").hide();
		$("#commandes_tuto_combat").hide();
		reposition_arrow_left($(".icones_sorts_combat:not(#spell_critical)"));
	}
  $.ajax({
    url: '/app/controllers/ajax.php',
    type: 'POST',
    data: 'get_challenges_tried=true',
    dataType: 'json',
    success: function (result, status) {
      if (COMBAT_DEBUG) console.log('\n\n success FROM get_challenges_tried : \n' + JSON.stringify(result));
      potential_challenges = result;
    },
    error: function (result, status, error) {
      //Erreur est une chaine de caractère à afficher au joueur
    },
    complete: function (result, status) {
      // Starting first round
      //if (window.location.href.indexOf('http://localhost') == -1 ) {
        // Say fight has started in database
        commencer_combat();
      //}
      //else {
      //  // No need to remove fight !
      //  round_start();
      //}
    }
  });
}

function display_impressions(){
	//display_clip($("#impressions"));
}

function send_impressions(){
	var total_duration = 0;
	$.each(exercises_record, function(index, exercise){
		total_duration += exercise.answerTime;
	});
	total_duration = total_duration/1000;
	$.ajax({
		url: '/app/controllers/ajax.php',
		type: 'POST',
		data: 'record_impression=fight&id_fight='+id_combat+'&difficulty='+difficulty+'&duration='+duration+'&total_duration='+total_duration,
		dataType: 'json',
		success: function (result, status) {
		},
		error: function (result, status, error) {
		  //Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function (result, status) {
		  hide_clip($("#impressions"));
		}
	});
}

// ****************************************
$(window).load(function(){

  //if (COMBAT_DEBUG) console.log('MAIN --> Starting');

  // *********** INITIALISATION DE LA VUE
  hide_stuff();
  hide_spells();

  // *********** INITIALISATION
  initialisation_joueur();
  initialisation_monstre();
  initialisation_combat();
  initialisation_vue();
  initialisation_actions();

  //Si pas de tuto on affiche direct le bouton sinon on l'affiche dans le tuto
  if (!$("#tuto_suivant").length) {
    fight_initialization();
  }
  else {
    $("#countdown").hide();
  }

  if ($("#son_musique_combat").length) {
    $("#son_musique_combat")[0].volume = 0.75;
	if(playerVolumeMusic == 1){
    $("#son_musique_combat").trigger("play");
	}
  }

  visual_element_positionning();

});


// ****************************************
function good_answer() {
  answered(true);
  record_exercise(1);
}

//Configure la div affichant la réponse (text, icone) en cas de mauvaise réponse
function wrong_answer(info) {
  answered(false);
  record_exercise(0);
}

//Permet de voir si le joueur a fini avant la fin du chrono et lui donner un bonus
function answered(correct_answer) {
  // In every case we :
  $("#combat_borders").hide();
  //On arrête le chrono si le joueur a tout juste et on lui met une animation
  bonus_chrono = Math.round(chrono / duree_chrono * 100);

  clearInterval(chrono_interval);
  $(".icones_sorts_combat").unbind("click");
  $("#chrono_combat").hide();

  bonus_chrono_pm_joueur(correct_answer); //Animation
  setTimeout( function () { animation_player_action(correct_answer); }, vitesse_anim * 2);
}

function record_exercise(success) {
	var elementTemp = codeChallenge.match(/(\w+)_\w+_\d+_\d+/)[1];
	var nameTemp = codeChallenge.match(/\w+_(\w+)_\d+_\d+/)[1];
	var levelTemp = codeChallenge.match(/\w+_\w+_(\d+)_\d+/)[1];
	var numberTemp = codeChallenge.match(/\w+_\w+_\d+_(\d+)/)[1];
	exercises_record.push({
		element: elementTemp,
		challengeName: nameTemp,
		level: levelTemp,
		number: numberTemp,
		mastery: masteryChallenge,
		initialTime: duree_chrono,
		answerTime: duree_chrono-chrono,
		success: success,
		situation: 2,
	    diagnosis: 0
	});
}


function recordExercises() {
	$.ajax({
		url: '/app/controllers/ajax_challenge.php',
		type: 'POST',
		data: 'exercises_record='+JSON.stringify(exercises_record),
		dataType: 'json',
		success: function (result, status) {
		},
		error: function (result, status, error) {
		  //Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function (result, status) {
		}
	  });
}
