function afficher(objet, speed) {
  if (!speed) speed = 500;
  objet.show("clip", speed);
}

var challenge = function (id_challenge) {
  //CHALLENGES
  //Comportement des lignes qui se mettent en surbrillance et en gros quand on passe le curseur dessus
  var div_corps_scroll = $('.corps_scroll div');
  div_corps_scroll.on("mouseover", function () {
    mettre_en_evidence_ligne_scroll($(this));
  });
  console.log('CHALLENGE');
  div_corps_scroll.on("mouseout", function () {
    enlever_evidence_ligne_scroll($(this));
  });

  var div_challenges_en_cours = $('#challenges_en_cours');
  div_challenges_en_cours.hide();
  //Lorsque le joueur affiche les défis en attente
  $("#nb_challenges").on("click", function () {
    div_challenges_en_cours.hide();
    div_challenges_en_cours.show("clip", {easing: "swing"}, 500);
  });

  var div_masquer_challenges = $('#div_masquer_challenges');

  div_masquer_challenges.on("mouseover", function () {
    $(this).css("cursor", "pointer");
    var hauteur = $(this).height();
    $(this).css("height", hauteur + 10);
  });

  div_masquer_challenges.on("mouseout", function () {
    var hauteur = $(this).height();
    $(this).css("height", hauteur - 10);
  });

  div_masquer_challenges.on("click", function () {
    var hauteur = $(this).height();
    $(this).css("height", hauteur - 10);
    $("#challenges_en_cours").hide("clip", {easing: "swing"}, 500);
  });

  //Lorsque le joueur clique sur un des challenges
  $(".challenge").on("click", function () {
    id_challenge = parseInt($(this).children("span:eq(2)").attr("id"));
    $(location).attr('href', '/app/controllers/accueil_defi.php?id=' + id_challenge);
  });
};

function changer_descriptif_force_faiblesse(id_target, element_a_afficher) {
  var target = '#' + id_target;
  $(target).empty();
  switch(element_a_afficher) {
    case "feu" :
      $('<span class="ib l70 p3 g align_haut ph2">Fort contre :</span>').appendTo(target);
      $('<span class="ib l25 align_centre"><img class="img_40" src="/webroot/img/elements/terre.png"/></span>').appendTo(target);
      $('<span class="ib l70 p3 g align_haut ph2">Faible contre :</span>').appendTo(target);
      $('<span class="ib l25 align_centre"><img class="img_40" src="/webroot/img/elements/eau.png"/></span>').appendTo(target);
      break;
    case "eau" :
      $('<span class="ib l70 p3 g align_haut ph2">Fort contre :</span>').appendTo(target);
      $('<span class="ib l25 align_centre"><img class="img_40" src="/webroot/img/elements/feu.png"/></span>').appendTo(target);
      $('<span class="ib l70 p3 g align_haut ph2">Faible contre :</span>').appendTo(target);
      $('<span class="ib l25 align_centre"><img class="img_40" src="/webroot/img/elements/vent.png"/></span>').appendTo(target);
      break;
    case "vent" :
      $('<span class="ib l70 p3 g align_haut ph2">Fort contre :</span>').appendTo(target);
      $('<span class="ib l25 align_centre"><img class="img_40" src="/webroot/img/elements/eau.png"/></span>').appendTo(target);
      $('<span class="ib l70 p3 g align_haut ph2">Faible contre :</span>').appendTo(target);
      $('<span class="ib l25 align_centre"><img class="img_40" src="/webroot/img/elements/terre.png"/></span>').appendTo(target);
      break;
    case "terre" :
      $('<span class="ib l70 p3 g align_haut ph2">Fort contre :</span>').appendTo(target);
      $('<span class="ib l25 align_centre"><img class="img_40" src="/webroot/img/elements/vent.png"/></span>').appendTo(target);
      $('<span class="ib l70 p3 g align_haut ph2">Faible contre :</span>').appendTo(target);
      $('<span class="ib l25 align_centre"><img class="img_40" src="/webroot/img/elements/feu.png"/></span>').appendTo(target);
      break;
  }
  $(target).hide();
  afficher($(target));
}

function positionner(objet, top, left) {
  objet.css("top", top).css("left", left);
}

function center (objet) {
  positionner(
      objet,
      Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()),
      Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft())
  );
}

function playAudio(id){
	if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
		//do nothing
	} else {
		$('#' + id)[0].play();
	}
}
