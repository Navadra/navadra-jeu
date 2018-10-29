// JavaScript Document

function select_elem_spells(element_a_afficher) {
  //console.log('select_elem_spells');
  var haut_base_elem = $('#elements_spells_selection').find('img').height();
  //On réinitialise les icones éléments et on masque les sorts
  $('#elements_spells_selection').find("img").removeAttr("style");
  $("#sorts_feu").hide();
  $("#sorts_eau").hide();
  $("#sorts_vent").hide();
  $("#sorts_terre").hide();

  //On masque le descriptif sorts
  $("#spell_desc").html('');

  ////On affiche les sorts de l'élément en question et on le met en forme
  //element_a_afficher = $(this).attr("id");
  $("#sorts_" + element_a_afficher).show();
  $(this).css("background-color", "#FFFFC6").css("height", haut_base_elem - 4).css("border", "2px #e0c399 solid");
  $(this).css("-moz-border-radius", "10px").css("-webkit-border-radius", "10px").css("border-radius", "10px");
  $(this).css("padding", "5px");

  //On affiche les forces/faiblesses de l'élément
  //console.log('select_elem_spells changer_descriptif_force_faiblesse');
  changer_descriptif_force_faiblesse('descriptif_forces_faiblesses', element_a_afficher);

  deactivate_selected_spells();
}

var spell_count = 0;
// Remove pre-selected spells from grimoire's list
function deactivate_selected_spells() {
  //console.log('deactivate_selected_spells');
  // First activate all spells then
  $('.original_spells').removeClass('already_selected');
  $('.original_spells').addClass('draggable_spells');
  //$(".original_spells").attr("class", "original_spells draggable_spells");

  // Read selected spells to get spell's num in order to deactivate them
  $('#sortable_spells li').each(function (i) {
    var spell_num = $(this).attr('data-spell');
    //console.log("deactivate_selected_spells spell num : " + spell_num);
    if (spell_num) {
      $('#spell_' + spell_num).removeClass('draggable_spells');
      $('#spell_' + spell_num).addClass('already_selected');
    }
  });
  count_selected_spell();

}

function remove_selected_spells(remove) {
  //console.log('remove_selected_spells');
  // Reading selected spells to get spell's num in order to deactivate them
  $('#sortable_spells li').each(function (i) {
    var spell_num = $(this).attr('data-spell');
    $('#spell_' + spell_num).removeClass('already_selected');
    $('#spell_' + spell_num).addClass('draggable_spells');
    $(this).remove();
  });
  count_selected_spell();
}

function deactivate_selected_spell(spell_num) {
  //console.log('deactivate_selected_spell');
  // remove spell from selected list
  $('#selected_spell_' + spell_num).remove();
  // Reactivate spell from spell list
  $('#spell_' + spell_num).removeClass('already_selected');
  $('#spell_' + spell_num).addClass('draggable_spells');
  count_selected_spell();
	if($("#fleche_tuto").length){
		$("#fleche_tuto").hide();
	}
}

function add_selected_spell(ui_item) {
  //console.log('add_selected_spell');
  var cancel = ui_item.attr('data-cancel') === 'true';
  if (cancel) {
    //console.log('add_selected_spell Cancelling drop');
    ui_item.remove();
  }
  else {
    var spell_num = ui_item.attr('data-spell');
    var spell_ico = ui_item.find('img').attr('src');
    var spell_id = 'spell_' + spell_num;
    //console.log('add_selected_spell : ' + spell_num);

    // Deactivate draggable spell from spell list
    $('#' + spell_id).removeClass('draggable_spells');
    $('#' + spell_id).addClass('already_selected');

    ui_item.attr('id', 'selected_spell_' + spell_num);
    ui_item.html('' +
        '   <img class="icones_sorts_deck" src="' + spell_ico + '"/>');
  }
  count_selected_spell();
  deactivate_selected_spells();
  //tooltip_icone_sort_remove();
}
function count_selected_spell() {
  //console.log('count_selected_spell');
  var sortable_spells = $('#sortable_spells');
  // Removing already_selected that stays stuck inside drop area
  sortable_spells.find('li.already_selected').each(function (index) {
    $(this).remove();
  });
  spell_count = Math.min(sortable_spells.find('li').length, 5);
  $('#spell_count').html(spell_count);

  // Active le bouton Commencer le combat dès qu'au moins 1 sort à été choisit
  if (spell_count > 0 ) {
    $("#spells_start_fight").show().removeClass("cache");
		if(playerTuto == "combats_decks_10"){
				$("#fleche_tuto").show();
				if(etape==1){
					etape++;
					changer_txt_bulle(msg_tuteur());
					block_instructions = false;
				}
		}
  }
  else {
    $("#spells_start_fight").hide().addClass("cache");
  }
}

function show_description(id_div, spell_name, spell_level, spell_num, pm_player, pm_monster, pv_player) {
  var descriptif = get_theoretical_spell_description(spell_num, spell_level, spell_name);
  $(id_div).html('' +
      '<span class="spells_desc p1 l80">' + descriptif +
      '</span>'
  ).show();
}


// Go to the fight
function load_fight(combat_id) {
  // Get all selected spells
  var selected_spells = [];
  $('#sortable_spells li').each(function (i) {
    var spell_num = $(this).attr('data-spell');
    selected_spells.push( spell_num);
  });
  // go to the fight !
  window.location='/app/controllers/combattre.php?id=' + combat_id + '&s=' + selected_spells.join(",");
}

function tooltip_icone_sort_remove() {
	//Info-bulle title
	$(".icones_sorts_remove").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});
}

$(function () {

  //Variables
  var id_monstre = parseInt($("#id_monstre").html());
  var id_joueur = parseInt($("#id_joueur").html());
  var id_combat = parseInt($("#id_combat").html());
  var gain_prestige = "";
  var perte_prestige = "";

  //Gestion de l'affichage du profil élémentaire
  $("#tableau_profil").hide();
  $("#tableau_profil").removeClass('cache');
  var position = $("#monstre").offset();
  $("#tableau_profil").css("top", position.top + 200).css("left", position.left + 0.5 * $("#monstre").width() - 0.5 * $("#tableau_profil").width());
  //Affichage du bonus/malus du joueur en survolant le monstre
  $("#monstre").on("mouseover", function () {
    $(this).css("cursor", "pointer");
    afficher($("#tableau_profil"));
  });

  $("#monstre").on("mouseout", function () {
    $("#tableau_profil").hide();
  });


  var position = $('#monstre').offset();
  $('#tableau_profil').css("top", position.top + 200).css("left", position.left + 0.5 * $('#monstre').width() - 0.5 * $('#tableau_profil').width());

  //Affichage du bonus/malus du joueur en survolant le monstre
  $('#monstre').on("mouseover", function () {
    $(this).css("cursor", "pointer");
    afficher($("#tableau_profil"));
  });

  $('#monstre').on("mouseout", function () {
    $("#tableau_profil").hide();
  });

  $("#sortable_spells").sortable({
    revert: true,
    cursor: 'pointer',
    placeholder: "highlight",
    start: function (event, ui) {
      //console.log('sortable start');
      var spell_num = ui.item.attr('data-spell');

      // Check if source is not from selected_list (otherwise we can keep it for sort purpose)
      if (ui.item.attr('id') != 'selected_spell_' + spell_num) {
        // Check if area has already 5 spells only if source is not from selected_list
        var cancel = false;
        if (spell_count >= 5) {
          //console.log('Already 5 spells, cannot add more!');
          cancel = true;
        }
        // Check if spell is a draggable_spells
        else if (!ui.item.hasClass('draggable_spells')) {
          //console.log('Is not draggable_spells : cannot drop!');
          cancel = true;
        }
        // Check if spell is not already in the list (should not happen)
        else if ($('#selected_spell_' + spell_num).length > 0) {
          //console.log('Already in the list : cannot drop!');
          cancel = true;
        }
        if (cancel) {
          ui.item.attr('data-cancel', 'true');
          ui.item.remove();
          //$(this).sortable("cancel");
        }
      }
    },
    stop: function (event, ui) {
      //console.log('sortable stop');
      if (sortableIn == 1) {
        add_selected_spell(ui.item);
      }
      deactivate_selected_spells();
    },
    update: function (event, ui) {
      //console.log('sortable update');
    },
    over: function(e, ui) { sortableIn = 1; },
    out: function(e, ui) { sortableIn = 0; },
    beforeStop: function (event, ui) {
      //console.log('sortable beforeStop');
      if (sortableIn == 0) {
        ui.item.remove();
        deactivate_selected_spells();
      }
    }

  });


  $("#sortable_spells").disableSelection();
  $('.draggable_spells').draggable({
    helper: 'clone',
    connectToSortable: '#sortable_spells'
  });

  //tooltip_icone_sort_remove();


  // Bouton choix automatique des sorts choisis automatiquement en fonction de l'élément du monstre à combattre
  $("#spells_auto_choose").on("click", function () {
    // Remove all selected spells
    remove_selected_spells();
    // Loading inside initial_selected_spells
    var spells = $('#initial_selected_spells').attr('data-spells');
    var spell_list = spells.split(',');
    for (var i = 0; i < spell_list.length; i++) {
      var spell_num = spell_list[i];
      if (spell_num.length > 0) {
        // Get spells from elements lists
        var img_html = $('#spell_' + spell_num).html();

        // Deactivate draggable spell from spell list
        $('#spell_' + spell_num).removeClass('draggable_spells');
        $('#spell_' + spell_num).addClass('already_selected');

        $('#sortable_spells').append('' +
            '<li id="selected_spell_' + spell_num + '" class="draggable_spells" data-spell="' + spell_num + '" >' + img_html + '</li>');

        count_selected_spell();
      }
    }
	  tooltip_icone_sort_remove();
		if(playerTuto != "fini"){
			$("#fleche_tuto").show();
			if(etape==1){
				etape++;
				changer_txt_bulle(msg_tuteur());
				block_instructions = false;
			}
		}
  });

  $('#elements_spells_selection').find('img').on("click", function () {
    // Getting 'feu' or 'terre' or ...
    select_elem_spells( $(this).attr('id') );
  });
  $('<span id="descriptif_forces_faiblesses" class="bulle_daide align_centre"></span>').appendTo("body");
  $("#descriptif_forces_faiblesses").css("z-index", "2").css("position", "absolute").css("top", "2%").css("left", "15%").css("width", "20%").hide();

  select_elem_spells($('#elem_joueur').html());



});

$(window).load(function () {
  //Détection de Safari
  if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
    var decalage = parseInt($('.info_monstre_flow').css("left"));
    $('.info_monstre_flow').css("left", -1.8 * decalage);
  }

});
