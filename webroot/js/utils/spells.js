var spell_launched = false;

// ************************************
var state_absorb = { active: false, percent:0 };
var state_sendback = { active: false, percent:0 };
var state_dodge = false;
var state_skipturn = false;
/*
 An object containing all spells success
 Usage : state_spell_reussite['success_<spell_num>'] = <number of successive success>.
 Ex :
 state_spell_reussite['success_8'] = 3
 state_spell_reussite['success_13'] = 1
 state_spell_reussite['success_24'] = 2
 state_spell_reussite['success_26'] = 2
 */
var state_spell_reussite = {};

/*
 An object containing spell numbers to disable for each turn.
 Usage : state_spell_disable['spell_<spell_num>'] = <round number>.
 Ex :
 state_spell_disable['spell_8'] = 3
 */
var state_spell_disable = {};

/*
 An object containing a number of player's PM to add or remove per each turn.
 Usage : state_round_pm_player.round_<round_num> = <number of pm to add or remove>.
 Ex :
 state_round_pm_player['round_3'] = 42
 state_round_pm_player['round_7'] = -52
 state_round_pm_player['round_8'] = 104
 */
var state_round_pm_player = {};
/*
 An object containing a number of monster's PM to add or remove per each turn.
 Usage : state_round_pm_monster.round_<round_num> = <number of pm to add or remove>.
 Ex :
 state_round_pm_monster['round_3'] = 42
 state_round_pm_monster['round_7'] = -52
 state_round_pm_monster['round_8'] = 104
 */
var state_round_pm_monster = {};

function get_spell_reussite(spell_num) {
  var reussite   = state_spell_reussite['success_' + spell_num];
  if (typeof reussite == 'undefined') reussite = 1;
  //console.log('--> get_spell_reussite(' + spell_num + ') : ' + reussite);
  return reussite;
}
function increment_spell_reussite(spell_num) {
  var reussite = get_spell_reussite(spell_num);
  if (typeof reussite == 'undefined') reussite = 0;
  var max = get_max_reussite_for_spell(spell_num);
  reussite += 1;
  if (reussite >= max ) reussite = max;
  //console.log('--> increment_spell_reussite(' + spell_num + ') -> ' + reussite);
  state_spell_reussite['success_' + spell_num] = reussite;
}
function reset_spell_reussite(spell_num) {
  //console.log('--> reset_spell_reussite(' + spell_num + ') -> 1');
  state_spell_reussite['success_' + spell_num] = 1;
}

/**
 * Removes the spell until a given round
 * @param end_round
 * @param spell number
 */
function remove_spell_until(end_round, spell) {
  if (spell_launched) {
    //console.log('--> remove_spell_until(round: ' + end_round + ', pm: ' + pm + ')');
    var disable_until_round = state_spell_disable['spell_' + spell];
    if (typeof disable_until_round == 'undefined') {
      // No previous end round
      disable_until_round = parseInt(end_round);
    }
    else {
      // there is an existing round : keep the furthest one !
      disable_until_round = Math.max( disable_until_round, parseInt(end_round) );
    }
    console.log('Disable spell ' + spell + ' until round ' + disable_until_round);
    state_spell_disable['spell_' + spell] = disable_until_round;
  }
}
function is_spell_disabled(current_round, spell) {
  if (spell_launched) {
    //console.log('--> remove_spell_until(round: ' + end_round + ', pm: ' + pm + ')');
    var disable_until_round = state_spell_disable['spell_' + spell];
    if (typeof disable_until_round !== 'undefined') {
      // Is spell is still disabled for current round
      return current_round <= disable_until_round;
    }
    return false;
  }
}


// RESET SPECIAL ACTIONS (skip turns, absorb, dodge, send back)
function reset_states() {
  state_absorb = { active: false, percent:0 };
  state_sendback = { active: false, percent:0 };
  state_dodge = false;
  state_skipturn = false;

  $("#icon_dodge_joueur").hide();
  $("#dodge_joueur").hide();
  $("#icon_sendback_joueur").hide();
  $("#sendback_joueur").hide();
  $("#icon_absorb_joueur").hide();
  $("#absorb_joueur").hide();
  $("#icon_skipturn_monstre").hide();
  $("#skipturn_monstre").hide();

  change_pm_joueur();
  change_pm_monstre();

}

// PM players are added by spells : so we remove them when adjustment
function get_pm_player_adjusted(round, pm) {
  //console.log('--> get_pm_player_adjusted(round: ' + round + ', pm: ' + pm + ')');
  var amount = state_round_pm_player['round_' + round];
  if (typeof amount != 'undefined') {
    console.log('Removing ' + amount + ' PM for player');
    // Removing obsolete PMs
    pm -= amount;
	$("#pm_joueur").removeClass("vert").html(pm);
  }
  return pm;
}
// PM monsters are reduced by spells : so we add them when adjustment
function get_pm_monster_adjusted(round, pm) {
  //console.log('--> get_pm_monster_adjusted(round: ' + round + ', pm: ' + pm + ')');
  var amount = state_round_pm_monster['round_' + round];
  if (typeof amount != 'undefined') {
    console.log('Adding ' + amount + ' PM for monster');
    // Adding back PMs
    pm += amount;
	$("#pm_monstre").removeClass("rouge").html(pm);
  }
  return pm;
}

/**
 * Keeps the added PM to remove them back at the beginning of the specified turn
 * @param end_round
 * @param pm_m
 */
function add_pm_player_until(end_round, pm) {
  if (spell_launched) {
    //console.log('--> add_pm_player_until(round: ' + end_round + ', pm: ' + pm + ')');
    var amount = state_round_pm_player['round_' + end_round];
    if (typeof amount == 'undefined') {
        // No previous PM to remove at the given round
      amount = pm;
    }
    else {
      // Adding to existing pm to remove at the given round
      amount += parseInt(pm);
    }
    console.log('Total player pm to remove at round ' + end_round + ' is ' + amount);
    state_round_pm_player['round_' + end_round] = parseInt(amount);
  }
}

/**
 * Keeps the removed PM to add them back at the beginning of the specified turn
 * @param end_round
 * @param pm
 */
function remove_pm_monster_until(end_round, pm) {
  if (spell_launched) {
    //console.log('--> remove_pm_monster_until(round: ' + end_round + ', pm: ' + pm + ')');
    var amount = state_round_pm_monster['round_' + end_round];
    if (typeof amount == 'undefined') {
      // No previous PM to remove at the given round
      amount = pm;
    }
    else {
      // Adding to existing pm to remove at the given round
      amount += parseInt(pm);
    }
    console.log('Total monster pm to remove at round ' + end_round + ' is ' + amount);
    state_round_pm_monster['round_' + end_round] = parseInt(amount);
  }
}


function get_theoretical_spell_description( spell_num, spell_level, spell_name ) {
  if (spell_level < 0) spell_level = 0;
	if(spell_level > 0){
		var params = get_effect_level_param(spell_num, spell_level);
	  var desc = theoretical_effet(spell_num, params);
	  return '<div class="p1 g '+spell_color(spell_num)+'">' + spell_name + ' - niv. ' + spell_level + '</div>' + '<div class="p0 mh1">' + desc + '</div>';
	} else {
		return '<div class="p1 g '+spell_color(spell_num)+'">' + spell_name + '</div>' + '<div class="p0 i mh1">Tu ne connais pas encore ce sort</div>';
	}
}

function spell_color(spell_num)
{
	if(parseInt(spell_num) >=1 && parseInt(spell_num) <= 7)
	{
		return "rouge"
	}
	else if(parseInt(spell_num) >=8 && parseInt(spell_num) <= 14)
	{
		return "bleu"
	}
	else if(parseInt(spell_num) >=15 && parseInt(spell_num) <= 21)
	{
		return "jaune"
	}
	else if(parseInt(spell_num) >=22 && parseInt(spell_num) <= 28)
	{
		return "vert"
	}
}


const bonus = 1.15;
const malus = 0.85;

var elem_coef = {};
elem_coef.feu = {};
elem_coef.feu.terre = bonus;
elem_coef.feu.eau = malus;
elem_coef.feu.feu = 1;
elem_coef.feu.vent = 1;

elem_coef.eau = {};
elem_coef.eau.terre = 1;
elem_coef.eau.eau = 1;
elem_coef.eau.feu = bonus;
elem_coef.eau.vent = malus;

elem_coef.terre = {};
elem_coef.terre.terre = 1;
elem_coef.terre.eau = 1;
elem_coef.terre.feu = malus;
elem_coef.terre.vent = bonus;

elem_coef.vent = {};
elem_coef.vent.terre = malus;
elem_coef.vent.eau = bonus;
elem_coef.vent.feu = 1;
elem_coef.vent.vent = 1;


function get_elem_coef(elem_1, elem_2) {
  if (elem_1 === "base" || elem_2 == "base") {
    return 1;
  }
  return elem_coef[elem_1][elem_2];
}
// ************************************
function advanced_theoretical_effect(spell_num, params) {
  switch( parseInt(spell_num) ) {
    case 0: return "Un sortilège primitif pour te défendre avec la magie de Navadra.";
    // FIRE
    case 1: return "Inflige des dégâts avec " + params.x + "% de chance de coup critique (+" + params.y + "% de dégâts)";
    case 2: return "Inflige entre " + params.xx[0] + "% et " + params.xx[(params.xx.length - 1)] + "% de dégâts (dépend de la reussite)";
    case 3: return "Retire " + params.x + "% des PV restants mais +" + params.y + "% PM durant jusqu'à la fin du combat";
    case 4: return "Inflige " + params.x + "% de dégâts si PV < " + params.y + "%";
    case 5: return "Absorbe " + params.x + "% des dégâts de l'attaque adverse";
    case 6: return "Augmente la PM de " + params.x + "% ce tour si PV joueur > PV monstre";
    case 7: return "Inflige " + params.x + "% de dégâts";
    // WATER
	  case 8: return "Chaque réussite retire définitivement " + params.x + "% de PM à l'adversaire";
    case 9: return "Augmente la PM de " + params.x + "% pendant " + params.y + " tours";
    case 10: return "Inflige entre " + params.xx[0] + "% et " + params.xx[(params.xx.length - 1)] + "% de dégâts (dépend de la reussite)";
    case 11: return "Inflige des dégâts avec " + params.x + "% de chance de coup critique (+" + params.y + "% de dégâts)";
    case 12: return params.x + "% de chances de faire passer le tour de l'adversaire";
    case 13: return "Absorbe entre " + params.xx[0] + "% et " + params.xx[(params.xx.length - 1)] + "% de l'attaque de l'adversaire (dépend de la reussite)";
    case 14: return "Augmente ta PM (PM = PM x " + params.x + ") jusqu'à la fin du combat";
    // WIND
    case 15: return "Inflige des dégâts avec " + params.x + "% de chance de coup critique (+" + params.y + "% de dégâts)";
    case 16: return params.x + "% de chance d'esquiver l'attaque adverse au prochain tour - si succès : +50% PM";
    case 17: return "Renvoie " + params.x + "% des dégâts de l'attaque adverse ";
    case 18: return "Inflige entre " + params.xx[0] + "% et " + params.xx[(params.xx.length - 1)] + "% de dégâts (dépend de la reussite)";
    case 19: return "Regagne " + params.x + "% de PV si PV joueur < PV monstre";
    case 20: return params.x + "% de chances de réduire de 50% l'attaque du monstre au prochain tour";
    case 21: return "Renvoie " + params.x + "% de l'attaque de l'adversaire";
    // EARTH
    case 22: return "Gagne " + params.x + "% de PV tant que tes PV sont inférieur à ceux du monstre";
    case 23: return "Absorbe " + params.x + "% des dégâts de l'attaque adverse";
    case 24: return "Gagne " + params.x + "% de puissance magique";
    case 25: return "Inflige des dégâts avec " + params.x + "% de chance de coup critique (+" + params.y + "% de dégâts)";
    case 26: return "Inflige entre " + params.xx[0] + "% et " + params.xx[(params.xx.length - 1)] + "% de dégâts (dépend de la reussite)";
    case 27: return "Te permet de regagner " + params.y + "% de PV";
    case 28: return "Absorbe " + params.x + "% des dégâts de l'attaque adverse et augmente de " + params.y + "% l'attaque jusqu'au prochain tour";
  }
}

// ************************************
function basic_theoretical_effet(spell_num, params) {

  switch( parseInt(spell_num) ) {
    case 0: return "Un sortilège primitif pour te défendre avec la magie de Navadra.";
    // FIRE
    case 1: return "Attaque de base du Feu.";
    case 2: return "Un sort avec des dégâts bonus.<br>Réussis le plusieurs fois d'affilée pour augmenter ses dégâts !";
    case 3: return "Sacrifie de la vie pour avoir un bonus d'attaque au prochain tour.";
    case 4: return "Un sort puissant si ta vie est basse.";
    case 5: return "Absorbe une partie de l'attaque adverse.";
    case 6: return "Bonus à ton attaque si ton adversaire a moins de vie que toi.";
    case 7: return "Inflige de GROS dégâts.";
    // WATER
    case 8: return "Diminue la puissance de l'adversaire définitivement.";
    case 9: return "Te donne un bonus d'attaque pendant " + params.y + " tour(s).";
    case 10: return "Un sort avec des dégâts bonus.<br>Réussis le plusieurs fois d'affilée pour augmenter ses dégâts !";
    case 11: return "Attaque de base de l'Eau.";
    case 12: return "Tente de geler l'adversaire pour lui faire passer son tour.";
    case 13: return "Absorbe une partie de l'attaque adverse.";
    case 14: return "Augmente ton attaque jusqu'à la fin du combat.";
    // WIND
    case 15: return "Attaque de base du Vent.";
    case 16: return "Tente d'esquiver le coup adverse et te donne un bonus à l'attaque en cas de succès.";
    case 17: return "Renvoie une partie des dégâts reçus à l'adversaire.";
    case 18: return "Un sort avec des dégâts bonus.<br>Réussis le plusieurs fois d'affilée pour augmenter ses dégâts !";
    case 19: return "Te redonne de la vie si ton adversaire en a plus que toi.";
    case 20: return "Tente de réduire de moitié l'attaque de l'adversaire au prochain tour.";
    case 21: return "Renvoie à ton adversaire le double des dégâts de sa prochaine attaque.";
    // EARTH
    case 22: return "Regagne de la vie si ton adversaire en a plus que toi.";
    case 23: return "Absorbe une partie de l'attaque adverse.";
    case 24: return "Augmente ta puissance d'attaque.";
    case 25: return "Attaque de base de la Terre.";
    case 26: return "Un sort avec des dégâts bonus.<br>Réussis le plusieurs fois d'affilée pour augmenter ses dégâts !";
    case 27: return "Te permet de regagner de la vie.";
    case 28: return "Absorbe une grande partie de la prochaine attaque adverse et augmente ta puissance magique jusqu'au prochain tour.";
  }
}

// ************************************
function theoretical_effet(spell_num, params) {
	if(playerAdvanced_description == 1){
		return advanced_theoretical_effect(spell_num, params);
	} else {
		return basic_theoretical_effet(spell_num, params);
	}
}

function descriptif_effet(spell_num, params) {

  switch( parseInt(spell_num) ) {
    case 0: return "Un sort primitif pour te défendre avec la magie de Navadra.";
    // FIRE
    case 1: return "Inflige " + params.hit + " de dégâts avec " + params.x + "% de chance de coup critique (+" + params.y + "% de dégâts)";
    case 2: return "Inflige " + params.hit + " de dégâts (dépend de la reussite)";
    case 3: return "Retire " + Math.abs(params.heal) + " des PV restants mais +" + params.pm_player + " PM durant jusqu'au tour prochain (inclus)";
    case 4: return "Inflige " + params.hit + " de dégâts si PV < " + params.y + "%";
    case 5: return "Absorbe " + params.absorb + "% des dégâts de l'attaque adverse";
    case 6: return "Augmente la PM de " + params.pm_player + " ce tour tant que PV joueur > PV monstre";
    case 7: return "Inflige " + params.hit + " de dégâts";
    // WATER
    case 8: return "Retire définitivement " + params.pm_monster + " PM à l'adversaire";
    case 9: return "Augmente la PM de " + params.pm_player + " pendant " + params.y + " tours";
    case 10: return "Inflige " + params.hit + " de dégâts";
    case 11: return "Inflige " + params.hit + " de dégâts avec " + params.x + "% de chance de coup critique (+" + params.y + "% de dégâts)";
    case 12: return params.x + "% de chances de faire passer le tour de l'adversaire";
    case 13: return "Absorbe " + params.absorb + " de l'attaque de l'adversaire";
    case 14: return "Augmente ta PM (PM = PM x " + params.x + ") jusqu'à la fin du combat";
    // WIND
    case 15: return "Inflige " + params.hit + " de dégâts avec " + params.x + "% de chance de coup critique (+" + params.y + "% de dégâts)";
    case 16: return params.x + "% de chance d'esquiver l'attaque adverse au prochain tour - si succès : +50% PM";
    case 17: return "Renvoie " + params.x + "% des dégâts de l'attaque adverse ";
    case 18: return "Inflige " + params.hit + " de dégâts ";
    case 19: return "Regagne " + params.x + "% de PV si PV joueur < PV monstre";
    case 20: return params.x + "% de chances de réduire de 50% l'attaque du monstre au prochain tour";
    case 21: return "Renvoie " + params.x + "% de l'attaque de l'adversaire";
    // EARTH
    case 22: return "Gagne " + params.x + "% de PV tant que tes PV sont inférieur à ceux du monstre";
    case 23: return "Absorbe " + params.absorb + "% des dégâts de l'attaque adverse";
    case 24: return "Augmente ton attaque de " + params.x + "%";
    case 25: return "Inflige " + params.hit + " de dégâts avec " + params.x + "% de chance de coup critique (+" + params.y + "% de dégâts)";
    case 26: return "Inflige " + params.hit + " de dégâts";
    case 27: return params.x + "% de chances (selon niveau du joueur) de regagner " + Math.abs(params.heal) + " PV";
    case 28: return "Absorbe " + params.absorb + "% des dégâts de l'attaque adverse et augmente de " + params.y + "% l'attaque jusqu'au prochain tour";
  }
}

function is_spell_usable(spell_num, spell_level, pv_joueur, pv_joueur_max, prctv_joueur, pv_monstre) {
  //console.log('FCT --> is_spell_usable');
  switch (spell_num) {
    case 4: //Feu du désespoir
      var param = get_effect_level_param(spell_num, spell_level);
      return prctv_joueur < param.y;
    case 6: //Pacte de flamme
      return pv_joueur >= pv_monstre;
    case 19: // Oeil du cyclone : sort de soin (seulement quand la vie est < au max
      return pv_joueur < pv_monstre && pv_joueur < pv_joueur_max;
    case 22: // Enracinement : sort de soin
      return pv_joueur < pv_monstre && pv_joueur < pv_joueur_max;
    case 27: // Enracinement : sort de soin
      return pv_joueur < pv_monstre && pv_joueur < pv_joueur_max;
  }
  return true;
}

// Number of max allowed reussite for each spell
function get_max_reussite_for_spell(spell_num) {
  spell_num = parseInt(spell_num);

  switch (spell_num) {

    case 2 :
    case 26 :
    case 10 :
    case 13 :
    case 18 :
      return 3;

    default :
      return 1;
  }
}

function get_base_spell() {
  return {
    num: 0,
    nom: "Attaque primitive",
    niveau: 1,
    element: 'base',
    categorie: 'offensif',
    icone: 'base_0.png'
  };
}
// Get critical spell number for a given spell num
function get_critical_for_spell(spell_num) {
  spell_num = parseInt(spell_num);
  if (spell_num < 1 ) return 0;
  else if (spell_num < 7) return 7;
  else if (spell_num < 14) return 14;
  else if (spell_num < 21) return 21;
  else return 28;
}

// Get critical spell number for a given element
function get_critical_for_element(element) {
  if ('feu' === element ) return 7;
  else if ('eau' === element ) return 14;
  else if ('vent' === element ) return 21;
  else return 28;
}

// Is spell num critical ?
function is_critical(spell_num) {
  spell_num = parseInt(spell_num);
  switch (spell_num) {
    case 7 :
    case 14 :
    case 21 :
    case 28 :
      return true;

    default :
      return false;
  }
}

// Is spell a heal one ?
function is_heal(spell_num) {
  spell_num = parseInt(spell_num);
  switch (spell_num) {
    case 19 :
    case 22 :
    case 27 :
      return true;

    default :
      return false;
  }
}

function get_effect_level_param(spell_num, spell_level) {
  spell_num = parseInt(spell_num);
  spell_level = parseInt(spell_level);

  switch (spell_num) {

    // Base attack
    case 0: return { "x":60 }; // 60% of the PM

    // *************************************** FIRE
    case 1 : // Boule de feu
      switch (spell_level) {
        case 1: return { "x":25, "y":10 };
        case 2: return { "x":25, "y":12 };
        case 3: return { "x":30, "y":14 };
        case 4: return { "x":30, "y":17 };
        case 5: return { "x":35, "y":19 };
        case 6: return { "x":35, "y":21 };
        case 7: return { "x":40, "y":23 };
        case 8: return { "x":40, "y":26 };
        case 9: return { "x":45, "y":28 };
        case 10:return { "x":50, "y":30 };
      }

    case 2: // Brulures
      switch (spell_level) {
        case 1: return { "xx":[100,125,150] };
        case 2: return { "xx":[102,128,153] };
        case 3: return { "xx":[104,130,156] };
        case 4: return { "xx":[106,133,159] };
        case 5: return { "xx":[108,135,162] };
        case 6: return { "xx":[110,138,166] };
        case 7: return { "xx":[113,141,169] };
        case 8: return { "xx":[115,144,172] };
        case 9: return { "xx":[117,146,176] };
        case 10:return { "xx":[120,149,179] };
      }

    case 3: // Sacrifice
      switch (spell_level) {
        case 1: return { "x":3 , "y":10 };
        case 2: return { "x":3, "y":11 };
        case 3: return { "x":3, "y":12 };
        case 4: return { "x":4, "y":13 };
        case 5: return { "x":4, "y":14 };
        case 6: return { "x":4, "y":15 };
        case 7: return { "x":5, "y":16 };
        case 8: return { "x":5, "y":17 };
        case 9: return { "x":5, "y":18 };
        case 10:return { "x":6, "y":19};
      }

    case 4: // Feu du désespoir
      switch (spell_level) {
        case 1: return { "x":110, "y":50 };
        case 2: return { "x":112, "y":50 };
        case 3: return { "x":114, "y":50 };
        case 4: return { "x":117, "y":50 };
        case 5: return { "x":119, "y":50 };
        case 6: return { "x":121, "y":33 };
        case 7: return { "x":124, "y":33 };
        case 8: return { "x":126, "y":33 };
        case 9: return { "x":129, "y":33 };
        case 10:return { "x":131, "y":33 };
      }

    case 5: // Mur de feu
      switch (spell_level) {
        case 1: return { "x":45 };
        case 2: return { "x":46 };
        case 3: return { "x":47 };
        case 4: return { "x":48 };
        case 5: return { "x":49 };
        case 6: return { "x":50 };
        case 7: return { "x":51 };
        case 8: return { "x":52 };
        case 9: return { "x":53 };
        case 10:return { "x":54 };
      }

    case 6: // Pacte de flammes
      switch (spell_level) {
        case 1: return { "x":33  };
        case 2: return { "x":34  };
        case 3: return { "x":35  };
        case 4: return { "x":36 };
        case 5: return { "x":38 };
        case 6: return { "x":39 };
        case 7: return { "x":40 };
        case 8: return { "x":41 };
        case 9: return { "x":43 };
        case 10:return { "x":45 };
      }

    case 7: // Frappe incandescente
      switch (spell_level) {
        case 1: return { "x":150 };
        case 2: return { "x":153 };
        case 3: return { "x":156 };
        case 4: return { "x":159 };
        case 5: return { "x":162 };
        case 6: return { "x":166 };
        case 7: return { "x":169 };
        case 8: return { "x":172 };
        case 9: return { "x":176 };
        case 10:return { "x":179 };
      }


    // *************************************** WATER
	   case 8: // Brouillard aveuglant
      switch (spell_level) {
        case 1: return { "x":5.0 };
        case 2: return { "x":5.1 };
        case 3: return { "x":5.2 };
        case 4: return { "x":5.3 };
        case 5: return { "x":5.4 };
        case 6: return { "x":5.5 };
        case 7: return { "x":5.6 };
        case 8: return { "x":5.7 };
        case 9: return { "x":5.8 };
        case 10:return { "x":5.9 };
      }

    case 9: // Puissance des flots
      switch (spell_level) {
        case 1: return { "x":50, "y":1 };
        case 2: return { "x":51, "y":1 };
        case 3: return { "x":53, "y":1 };
        case 4: return { "x":54, "y":2 };
        case 5: return { "x":55, "y":2 };
        case 6: return { "x":57, "y":2 };
        case 7: return { "x":58, "y":2 };
        case 8: return { "x":59, "y":2 };
        case 9: return { "x":61, "y":3 };
        case 10:return { "x":62, "y":3 };
      }


    case 10: // Lame de fond
      switch (spell_level) {
        case 1: return { "xx":[75, 90, 150]  };
        case 2: return { "xx":[77, 92, 158]  };
        case 3: return { "xx":[78, 94, 165]  };
        case 4: return { "xx":[80, 96, 174]  };
        case 5: return { "xx":[81, 97, 182]  };
        case 6: return { "xx":[83, 99, 191]  };
        case 7: return { "xx":[84, 101, 201]  };
        case 8: return { "xx":[86, 103, 211] };
        case 9: return { "xx":[88, 105, 222] };
        case 10:return { "xx":[90, 108, 233] };
      }

    case 11: // Déferlante
      switch (spell_level) {
        case 1: return { "x":25, "y":10 };
        case 2: return { "x":25, "y":12 };
        case 3: return { "x":30, "y":14 };
        case 4: return { "x":30, "y":17 };
        case 5: return { "x":35, "y":19 };
        case 6: return { "x":35, "y":21 };
        case 7: return { "x":40, "y":23 };
        case 8: return { "x":40, "y":26 };
        case 9: return { "x":45, "y":28 };
        case 10:return { "x":50, "y":30 };
      }

    case 12: // Gel
      switch (spell_level) {
        case 1: return { "x":33 };
        case 2: return { "x":35 };
        case 3: return { "x":38 };
        case 4: return { "x":41 };
        case 5: return { "x":44 };
        case 6: return { "x":47 };
        case 7: return { "x":51 };
        case 8: return { "x":55 };
        case 9: return { "x":59 };
        case 10:return { "x":63 };
      }

    case 13: // Protection aquatique
      switch (spell_level) {
        case 1: return { "xx":[25, 50, 75] };
        case 2: return { "xx":[26, 52, 77] };
        case 3: return { "xx":[26, 53, 79] };
        case 4: return { "xx":[27, 55, 81] };
        case 5: return { "xx":[27, 56, 84] };
        case 6: return { "xx":[28, 58, 86] };
        case 7: return { "xx":[28, 60, 89] };
        case 8: return { "xx":[29, 61, 91] };
        case 9: return { "xx":[29, 63, 94] };
        case 10:return { "xx":[30, 65, 96] };
      }

    case 14: // Marée haute
      switch (spell_level) {
        case 1: return { "x":1.25};
        case 2: return { "x":1.3};
        case 3: return { "x":1.5};
        case 4: return { "x":1.6  };
        case 5: return { "x":1.65 };
        case 6: return { "x":1.75 };
        case 7: return { "x":1.8  };
        case 8: return { "x":1.9  };
        case 9: return { "x":1.95 };
        case 10:return { "x":2   };
      }

    // *************************************** WIND

    case 15: // Mistral
      switch (spell_level) {
        case 1: return { "x":25, "y":10 };
        case 2: return { "x":25, "y":12 };
        case 3: return { "x":30, "y":14 };
        case 4: return { "x":30, "y":17 };
        case 5: return { "x":35, "y":19 };
        case 6: return { "x":35, "y":21 };
        case 7: return { "x":40, "y":23 };
        case 8: return { "x":40, "y":26 };
        case 9: return { "x":45, "y":28 };
        case 10:return { "x":50, "y":30 };
      }

    case 16: // Bourrasque
      switch (spell_level) {
        case 1: return { "x":50 };
        case 2: return { "x":53 };
        case 3: return { "x":55 };
        case 4: return { "x":58 };
        case 5: return { "x":60 };
        case 6: return { "x":63 };
        case 7: return { "x":65 };
        case 8: return { "x":68 };
        case 9: return { "x":70 };
        case 10:return { "x":75 };
      }

    case 17: // Vengeance fulgurante
      switch (spell_level) {
        case 1: return { "x":33 };
        case 2: return { "x":34 };
        case 3: return { "x":35 };
        case 4: return { "x":36 };
        case 5: return { "x":38 };
        case 6: return { "x":39 };
        case 7: return { "x":40 };
        case 8: return { "x":41 };
        case 9: return { "x":43 };
        case 10:return { "x":44 };
      }

    case 18: // Rafale
      switch (spell_level) {
        case 1: return { "xx":[80, 110, 125]  };
        case 2: return { "xx":[82, 112, 128]  };
        case 3: return { "xx":[83, 114, 130]  };
        case 4: return { "xx":[85, 117, 133]  };
        case 5: return { "xx":[87, 119, 135]  };
        case 6: return { "xx":[88, 121, 138]  };
        case 7: return { "xx":[90, 124, 141]  };
        case 8: return { "xx":[92, 126, 144]  };
        case 9: return { "xx":[94, 129, 146]  };
        case 10:return { "xx":[96, 131, 149] };
      }

    case 19: // oeil du cyclone
      switch (spell_level) {
        case 1: return { "x":33 };
        case 2: return { "x":34 };
        case 3: return { "x":35 };
        case 4: return { "x":36 };
        case 5: return { "x":38 };
        case 6: return { "x":39 };
        case 7: return { "x":40 };
        case 8: return { "x":41 };
        case 9: return { "x":43 };
        case 10:return { "x":44 };
      }

    case 20: // Tornade
      switch (spell_level) {
        case 1: return { "x":50  };
        case 2: return { "x":51 };
        case 3: return { "x":52 };
        case 4: return { "x":53 };
        case 5: return { "x":54 };
        case 6: return { "x":55 };
        case 7: return { "x":56 };
        case 8: return { "x":57 };
        case 9: return { "x":59 };
        case 10:return { "x":60 };
      }

    case 21: // Tourmente céleste
      switch (spell_level) {
        case 1: return { "x":125  };
        case 2: return { "x":128 };
        case 3: return { "x":130 };
        case 4: return { "x":133 };
        case 5: return { "x":135 };
        case 6: return { "x":138 };
        case 7: return { "x":141 };
        case 8: return { "x":144 };
        case 9: return { "x":146 };
        case 10:return { "x":149 };
      }

    // *************************************** EARTH

    case 22: // Enracinement
      switch (spell_level) {
        case 1: return { "x":15 };
        case 2: return { "x":16 };
        case 3: return { "x":17 };
        case 4: return { "x":19 };
        case 5: return { "x":20 };
        case 6: return { "x":22 };
        case 7: return { "x":23 };
        case 8: return { "x":25 };
        case 9: return { "x":27};
        case 10:return { "x":29 };
      }

    case 23: // Armure d'écorce
      switch (spell_level) {
        case 1: return { "x":50 };
        case 2: return { "x":52 };
        case 3: return { "x":53 };
        case 4: return { "x":55 };
        case 5: return { "x":57 };
        case 6: return { "x":59 };
        case 7: return { "x":61 };
        case 8: return { "x":63 };
        case 9: return { "x":65 };
        case 10:return { "x":67 };
      }

	   case 24: // Montée de sève
      switch (spell_level) {
        case 1: return { "x":5.0 };
        case 2: return { "x":5.5 };
        case 3: return { "x":6.0 };
        case 4: return { "x":6.5 };
        case 5: return { "x":7.0 };
        case 6: return { "x":7.5 };
        case 7: return { "x":8.0 };
        case 8: return { "x":8.5 };
        case 9: return { "x":9.0 };
        case 10:return { "x":9.5 };
      }

    case 25: // Jet de poison
      switch (spell_level) {
        case 1: return { "x":25, "y":10 };
        case 2: return { "x":25, "y":12 };
        case 3: return { "x":30, "y":14 };
        case 4: return { "x":30, "y":17 };
        case 5: return { "x":35, "y":19 };
        case 6: return { "x":35, "y":21 };
        case 7: return { "x":40, "y":23 };
        case 8: return { "x":40, "y":26 };
        case 9: return { "x":45, "y":28 };
        case 10:return { "x":50, "y":30 };
      }

    case 26: // Etau végétal
      switch (spell_level) {
        case 1: return { "xx":[90, 110, 130] };
        case 2: return { "xx":[92, 112, 133] };
        case 3: return { "xx":[94, 114, 135] };
        case 4: return { "xx":[96, 117, 138] };
        case 5: return { "xx":[97, 119, 141] };
        case 6: return { "xx":[99, 121, 144] };
        case 7: return { "xx":[101, 124, 146] };
        case 8: return { "xx":[103, 126, 149] };
        case 9: return { "xx":[105, 129, 152] };
        case 10:return { "xx":[108, 131, 155] };
      }

    case 27: // Régénération
      switch (spell_level) {
        case 1: return { "x":100, "y":12 };
        case 2: return { "x":100, "y":13 };
        case 3: return { "x":100, "y":14 };
        case 4: return { "x":100, "y":15 };
        case 5: return { "x":100, "y":16 };
        case 6: return { "x":100, "y":17 };
        case 7: return { "x":100, "y":18 };
        case 8: return { "x":100, "y":19 };
        case 9: return { "x":100, "y":20 };
        case 10:return { "x":100, "y":21 };
      }

    case 28: // Drain de puissance
      switch (spell_level) {
        case 1: return { "x":33,  "y":33 };
        case 2: return { "x":36, "y":35 };
        case 3: return { "x":38, "y":36 };
        case 4: return { "x":42, "y":38};
        case 5: return { "x":45, "y":40 };
        case 6: return { "x":49, "y":42 };
        case 7: return { "x":52, "y":44 };
        case 8: return { "x":57, "y":46};
        case 9: return { "x":61, "y":48 };
        case 10:return { "x":66, "y":50 };
      }

  }

}

/**
 *
 * @param spell_num
 * @param spell_level
 * @param round
 * @param pm_player
 * @param pm_monster
 * @param pv_player
 * @param go true if the spell has to be launched, false otherwise
 * @returns {*}
 */
function get_effect(spell_num, spell_level, round, pm_player, pm_monster, pv_player, go, coef, endu_joueur_max) {
  //var curr_spell = spells[spell.num];

  spell_launched = false;
  if (typeof go != 'undefined' && go == true) {
    spell_launched = true;
  }
  // *** INITTIAL DATA
  spell_num = parseInt(spell_num);
  spell_level = parseInt(spell_level);
  round = parseInt(round);
  pm_player = parseInt(pm_player);
  pm_monster = parseInt(pm_monster);
  pv_player = parseInt(pv_player);
  var reussite = get_spell_reussite(spell_num);
  if (spell_launched && reussite > 1 ) {
    console.log('Spell called is : ' + spell_num + ', reussite is : ' + reussite);
  }

  var effect_params = get_effect_level_param(spell_num, spell_level);

  switch (spell_num) {

    case 0 : // Boule de feu
      return spell_base_0(pm_player, effect_params);

    // *************************************** FIRE
    case 1 : // Boule de feu
      return spell_fire_1(pm_player, effect_params, coef);

    case 2: // Brulures
      return spell_fire_2(pm_player, reussite, effect_params, coef);

    case 3: // Sacrifice
        return spell_fire_3(pm_player, pv_player, round, effect_params);

    case 4: // Feu du désespoir
        return spell_fire_4(pm_player, effect_params, coef);

    case 5: // Mur de feu
        return spell_fire_5(effect_params);

    case 6: // Pacte de flammes
      return spell_fire_6(round, pm_player, effect_params);

    case 7: // Frappe incandescente
      return spell_fire_7(pm_player, effect_params, coef);

    // *************************************** WATER
    case 8: // Brouillard aveuglant
      return spell_water_8(pm_monster, effect_params);

    case 9: // Puissance des flots
      return spell_water_9(round, pm_player, effect_params);

    case 10: // Lame de fond
      return spell_water_10(pm_player, reussite, effect_params, coef);

    case 11: // Déferlante
      return spell_water_11(pm_player, effect_params, coef);

    case 12: // Gel
      return spell_water_12(effect_params);

    case 13: // Protection aquatique
      return spell_water_13(reussite, effect_params);

    case 14: // Marée haute
      return spell_water_14(pm_player, effect_params);

    // *************************************** WIND

    case 15: // Mistral
      return spell_wind_15(pm_player, effect_params, coef);

    case 16: // Bourrasque
      return spell_wind_16(round, pm_player, effect_params);

    case 17: // Vengeance fulgurante
      return spell_wind_17(effect_params);

    case 18: // Rafale
      return spell_wind_18(pm_player, reussite, effect_params, coef);

    case 19: // oeil du cyclone
      return spell_wind_19(endu_joueur_max, effect_params);

    case 20: // Tornade
      return spell_wind_20(pm_monster, round, effect_params);

    case 21: // Tourmente céleste
      return spell_wind_21(effect_params);

    // *************************************** EARTH

    case 22: // Enracinement
      return spell_earth_22(endu_joueur_max, effect_params);

    case 23: // Armure d'écorce
      return spell_earth_23(effect_params);

    case 24: // Montée de sève
      return spell_earth_24(pm_player, effect_params);

    case 25: // Jet de poison
      return spell_earth_25(pm_player, effect_params, coef);

    case 26: // Etau végétal
      return spell_earth_26(pm_player, reussite, effect_params, coef);

    case 27: // Régénération
      return spell_earth_27(endu_joueur_max, effect_params);

    case 28: // Drain de puissance
      return spell_earth_28(round, pm_player, effect_params);

  }
}


function spell_base_0(hit, param) {
  hit = Math.round( hit * param.x / 100 );
  if(hit < 2){
	  var s ="";
  } else {
	  var s = "s";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return {"desc": desc, "hit":hit, "x":param.x };
}

// *************************************** FIRE

function spell_fire_1(hit, param, coef) {
  if (is_within_threshold_percent(param.x)) {
    hit = Math.round( ( hit + (hit * param.y / 100)) * coef);
  }
  if(hit < 2){
	  var s ="";
  } else {
	  var s = "s";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return {"desc": desc, "hit":hit, "x":param.x, "y":param.y};
}
function spell_fire_2(hit, reussite, param, coef) {
  hit = Math.round(hit * param.xx[reussite-1] *0.01 * coef); // -1 cause it's a table index
  if(hit < 2){
	  var s ="";
  } else {
	  var s = "s";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return { "desc": desc, "hit":hit, "reussite":true };
}
function spell_fire_3(hit, pv_player, round, param) {
  // '-' because we remove player life
  var heal = -(Math.round(pv_player * param.x / 100));
  // number of pm to add
  var pm_p = Math.ceil(hit * param.y / 100);
  // Has to be removed in 2 turns -> +2
  add_pm_player_until(round + 2, pm_p);
  // This spell is disabled for 2 turns
  remove_spell_until(round + 2, 3);
  if(Math.abs(heal) < 2){
	  var s ="";
  } else {
	  var s = "s";
  }
  var desc = "t'inflige " + Math.abs(heal) + " dégât"+s+" et te fait gagner " + pm_p + " de PM durant 1 round";
  return { "desc": desc, "heal":heal, "pm_player": pm_p};
}
function spell_fire_4(hit, param, coef) {
  hit = Math.round(hit * param.x / 100 * coef);
  if(hit < 2){
	  var s ="";
  } else {
	  var s = "s";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return { "desc": desc, "hit":hit, "y": param.y};
}
function spell_fire_5(param) {
  var desc = "absorbera " + param.x + "% des dégâts de l'attaque du monstre";
  return { "desc": desc, "absorb":param.x };
}
function spell_fire_6(round, pm_player, param) {
  // number of pm to add
  var pm_p = Math.ceil(pm_player * param.x / 100);
  // Has to be removed for the next turn -> +1
  add_pm_player_until(round + 1, pm_p);
  var desc = "augmente ta PM de " + pm_p + " durant ce round";
  return { "desc": desc, "pm_player":pm_p, "x":param.x };
}
function spell_fire_7(hit, param, coef) {
  hit = Math.round( hit * param.x / 100 * coef );
  var s = "s";
  if(hit < 2){
    s ="";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return { "desc": desc, "hit":hit };
}

// *************************************** WATER

function spell_water_8(pm_monster, param) {
  // pm_monster to remove
  var pm_m = Math.ceil(pm_monster * param.x * 0.01 );
  // Has to stay until end fight
  remove_pm_monster_until(42000, pm_m);
  var desc = "réduit la PM du monstre de " + pm_m;
  return { "desc": desc, "pm_monster":pm_m, "x":param.x  };
}
function spell_water_9(round, pm_player, param) {
  // pm_monster to remove
  var pm_p = Math.round(pm_player * (param.x / 100));
  var end_round = round + param.y;
  // Has to be removed at end_round + 1
  add_pm_player_until(end_round + 4, pm_p);
  var desc = "augmente ta PM de " + pm_p + " jusqu'au round " + end_round;
  return { "desc": desc, "pm_player":pm_p, "x":param.x, "y":param.y };
}
function spell_water_10(hit, reussite, param, coef) {
  // reussite est l'index
  hit = Math.round(hit * param.xx[reussite - 1 ] * 0.01 * coef); // -1 cause it's a table index
  var s = "s";
  if(hit < 2){
    s ="";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return { "desc": desc, "hit":hit };
}
function spell_water_11(hit, param, coef) {
  if (is_within_threshold_percent(param.x)) {
    hit = Math.round( (hit + (hit * param.y / 100)) * coef);
  }
  var s = "s";
  if(hit < 2){
    s ="";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return { "desc": desc, "hit":hit, "x":param.x, "y":param.y };
}
function spell_water_12(param) {
  var skip = is_within_threshold_percent(param.x);
  var desc = "n'a pas fonctionné";
  if (skip) desc = "fait sauter son tour au monstre";
  return { "desc": desc, "skip_turn":skip, "x":param.x };
}
function spell_water_13(reussite, param) {
  var absorb_percent = param.xx[reussite - 1]; // -1 cause it's a table index
  // This spell is disabled for 2 turns
  remove_spell_until(round + 2, 13);
  var desc = "absorbera " + absorb_percent + "% des dégâts de l'attaque du monstre";
  return { "desc": desc, "absorb":absorb_percent};
}
function spell_water_14(pm_player, param) {
  // '- pm_player' because 'pm_p' is the value to add to the existing pm_player
  // We don't call add_pm_player_until because this spell is stackable so no need to remove pm later on
  var pm_p = Math.ceil(pm_player * param.x) - pm_player;
  var desc = "augmente ta PM de " + pm_p + " jusqu'à la fin du combat";
  // No combat should last 42000 rounds ! Otherwise : could be a bug
  add_pm_player_until(42000, pm_p);
  return { "desc": desc, "pm_player":pm_p, "x":param.x  };
}

// *************************************** WIND

function spell_wind_15(hit, param, coef) {
  if (is_within_threshold_percent(param.x)) {
    hit = Math.round( (hit + (hit * param.y / 100)) * coef);
  }
  var s = "s";
  if(hit < 2){
	  s ="";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return { "desc": desc, "hit":hit, "x":param.x, "y":param.y };
}
function spell_wind_16(round, pm_player, param) {
  var dodge = is_within_threshold_percent(param.x);
  var desc = "est sans effet, tu n'esquiveras pas l'attaque du monstre";
  var pm_p = 0;
  if (dodge) {
    pm_p = Math.ceil(pm_player * 50 / 100);
    desc = "te permet d'esquiver la prochaine attaque, et te fait gagner 50% de PM ce tour";
    // Has to be removed for the next turn -> +1
    add_pm_player_until(round + 1, pm_p);
  }
  // This spell is disabled for 2 turns
  remove_spell_until(round + 2, 16);
  return { "desc": desc, "dodge":dodge, "pm_player":pm_p, "x":param.x };
}
function spell_wind_17(param) {
  var desc = "retournera contre le monstre " + param.x + "% de son attaque";
  return { "desc": desc, "send_back":param.x, "x":param.x };
}
function spell_wind_18(hit, reussite, param, coef) {
  // reussite est l'index
  hit = Math.round(hit * param.xx[reussite - 1] * 0.01 * coef); // -1 cause it's a table index
  var s = "s";
  if(hit < 2){
	  s ="";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return { "desc": desc, "hit":hit };
}
function spell_wind_19(pv_player, param) {
  var heal = Math.round(pv_player * param.x / 100);
  var s = "s";
  if(heal < 2){
	  s ="";
  }
  var desc = "te fait récupérer " + heal + " point"+s+" de vie";
  return { "desc": desc,  "heal":heal, "x":param.x };
}
function spell_wind_20(pm_monster, round, param) {
  var pm_m = 0;
  var desc = "rate et ne réduit pas la puissance magique du monstre";
  if (is_within_threshold_percent(param.x)) {
      // pm_monster to remove '-'
    pm_m = Math.ceil( pm_monster * 50 / 100);
    // Has to be added back to PM for the next turn -> +1
    remove_pm_monster_until(round + 1, pm_m);
    desc = "réduit la PM du monstre de moitié";
  }
  return { "desc": desc,  "pm_monster":pm_m, "x":param.x };
}
function spell_wind_21(param) {
  var desc = "retournera contre le monstre " + param.x + "% de son attaque";
  return { "desc": desc, "send_back":param.x, "x":param.x };
}

// *************************************** EARTH

function spell_earth_22(pv_player, param) {
  var heal = Math.ceil(pv_player * param.x / 100);
  var s = "s";
  if(heal < 2){
	  s ="";
  }
  var desc = "te fait récupérer " + heal + " point"+s+" de vie";
  return { "desc": desc,  "heal":heal, "x":param.x };
}
function spell_earth_23(param) {
  var desc = "absorbera " + param.x + "% des dégâts de l'attaque du monstre";
  return { "desc": desc, "absorb":param.x };
}
function spell_earth_24(pm_player, param) {
  // number of pm to add
  var pm_p = Math.ceil(pm_player * param.x * 0.01);
  var desc = "augmente ta PM de " + pm_p + " durant ce tour";
  // Has to be removed until end of fight
  add_pm_player_until(420, pm_p);
  return { "desc": desc, "pm_player":pm_p, "x":param.x  };
}
function spell_earth_25(hit, param, coef) {
  if (is_within_threshold_percent(param.x)) {
    hit = Math.round((hit + (hit * param.y / 100)) * coef);
  }
  if(hit < 2){
	  var s ="";
  } else {
	  var s = "s";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return { "desc": desc, "hit":hit, "x":param.x, "y":param.y };
}
function spell_earth_26(hit, reussite, param, coef) {
  if (reussite > 4) reussite = 4;
  // reussite est l'index
  hit = Math.round(hit * param.xx[reussite - 1] * 0.01 * coef); // -1 cause it's a table index
  if(hit < 2){
	  var s ="";
  } else {
	  var s = "s";
  }
  var desc = "inflige " + hit + " dégât"+s+" au monstre";
  return { "desc": desc, "hit":hit, "x":param.xx[reussite - 1]};
}
function spell_earth_27(pv_player, param) {
  var heal = 0;
  var desc = "n'a aucun effet... cette fois-ci.";
  if (is_within_threshold_percent(param.x)) {
    heal = Math.ceil(pv_player * param.y / 100);
	if(heal < 2){
		  var s ="";
	  } else {
		  var s = "s";
	  }
    desc = "te fait regagner " + heal + " point"+s+" de vie";
  }
  return { "desc": desc, "heal":heal, "x":param.x, "y":param.y };
}
function spell_earth_28(round, pm_player, param) {
  var pm_p = Math.ceil(pm_player * param.y / 100);
  // Has to be removed for the next/next turn -> +2
  add_pm_player_until(round + 2, pm_p);
  var desc = "absorbera " + param.x + "% de l'attaque adverse et augmente de " + param.y + "% ta PM";
  return { "desc": desc, "pm_player":pm_p, "absorb":param.x, "y":param.y };
}
