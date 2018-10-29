
var is_chasseur_ko = function (img_joueur, pseudo_joueur) {
  console.log('FCT --> is_chasseur_ko');
  //S'il y a déjà au moins un chasseur KO
  var is_chasseur_ko_elem = $('.icone_chasseur_ko');
  if (is_chasseur_ko_elem.length) {
    //On repère le dernier joueur KO
    var dernier_joueur_ko = is_chasseur_ko_elem.index(is_chasseur_ko_elem.last());
    //On remplace la tête du prochain joueur non KO par celle du joueur actif
    var tetes_chasseurs = $('.tetes_chasseurs:eq(' + dernier_joueur_ko + 1 + ')');
	tetes_chasseurs.attr("src", playerFullPortrait);
	tetes_chasseurs.attr("title", playerPseudo + " - Niv." + playerNiveau);
	tetes_chasseurs.parent().append('<img class="icone_chasseur_ko" src="/webroot/img/icones/faux.png"/>');
    tetes_chasseurs.parent().children('.pseudo_chasseur').html(playerPseudo);
  }
  else {
    var tetes_chasseurs = $('.tetes_chasseurs:eq(0)');
	tetes_chasseurs.attr("src", playerFullPortrait);
	tetes_chasseurs.attr("title", playerPseudo + " - Niv." + playerNiveau);
	tetes_chasseurs.parent().append('<img class="icone_chasseur_ko" src="/webroot/img/icones/faux.png"/>');
    tetes_chasseurs.parent().children('.pseudo_chasseur').html(playerPseudo);
  }
};

function change_pv(endu, anim_speed, plot, div_endu, div_pv) {
	if(div_pv.attr("id") == "pv_monstre_nbre"){
		var delayTemp = 2*anim_speed;
	} else {
		var delayTemp = 0;
	}
	setTimeout(function(){
		endu = Math.max(parseInt(endu), 0);
	  div_endu.effect("pulsate", {easing: "swing"}, anim_speed, function () {
	    plot.series[0].data = [[[parseInt(endu)]]];
	    plot.redraw();
	  });
	  div_pv.effect("pulsate", {easing: "swing"}, anim_speed, function () {
	    div_pv.html(endu)
	  });
	}, delayTemp)
}

function couleur_barre(elmt) {
  switch (elmt) {
    case "feu":
      return rouge_pastel;
    case "eau":
      return bleu_pastel;
    case "vent":
      return jaune_pastel;
    case "terre":
      return vert_pastel;
    default:
      return gris;
  }
}

function position_tuto_arrow() {
  var div_fleche_tuto = $('#fleche_tuto');
  var div_tuto_suivant = $('#tuto_suivant');
  //Si dans le tuto, on remet la flèche à sa place
  if (div_fleche_tuto.length) {
    var pos_x = div_tuto_suivant.offset().left;
    var pos_y = div_tuto_suivant.offset().top;
    var larg = div_tuto_suivant.width();
    var haut = div_tuto_suivant.height();
    var haut_fleche = div_fleche_tuto.height();
    div_fleche_tuto.css('position', 'absolute').css('top', pos_y + 0.5 * (haut - haut_fleche)).css('left', pos_x + larg);
    $('#bulle_cote').show();
  }
}

function chasser_monstre() {
  //console.log('FCT --> chasser_monstre');
  $("#img_monstre").css("visibility", "hidden");
}

function info() {
  //console.log('FCT --> info');
  $("#info_combat").show();
  setTimeout(function () {
    $("#info_combat").hide();
  }, vitesse_anim * 3);
}
