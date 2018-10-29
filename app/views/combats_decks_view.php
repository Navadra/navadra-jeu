<!-- Tuteur -->
<img alt="" id="tuteur_cote" src="<?= $joueur->portrait_tuteur(); ?>"/>

<!-- Bulle du personnage-->
<div id="bulle_bas" class="bulle">
  <!-- le texte dans la bulle -->
  <span id="txt_bulle">C'est le moment de réfléchir aux sorts que tu vas utiliser pendant le combat !</span>
</div>
<?php if ($joueur->tuto() != "fini") { ?>
  <div id="commandes_tuto_bas">
    <img id="tuto_precedent" src="/webroot/img/icones/chevron1.png"/>
    <img id="tuto_suivant" src="/webroot/img/icones/chevron2.png"/>
  </div>
<?php } ?>

<!-- Fond -->
<div class="fond l70 mh1 h68">

  <div class="titre">Choix des sorts</div>

  <div class="ib mg15 l60 align_haut">

    <div id="elements_spells_selection" class="ib l80 mh2">
      <img class="md2" id="feu" src="/webroot/img/elements/feu.png"/>
      <img class="md2" id="eau" src="/webroot/img/elements/eau.png"/>
      <img class="md2" id="vent" src="/webroot/img/elements/vent.png"/>
      <img class="md2" id="terre" src="/webroot/img/elements/terre.png"/>
    </div>

    <ul id="sorts_feu" class="ib centre l100 mh1 draggable_list">
      <?php
      $lenght = count($spells_by_elem["feu"]);
      if ($lenght < 1) { ?>
        <li class="sous_titre">Tu ne connais aucun sort de feu</li>
      <?php } else {
        foreach ($spells_by_elem["feu"] as $sort) { ?>
          <li id="spell_<?= $sort->num(); ?>" class="original_spells draggable_spells" data-spell="<?= $sort->num(); ?>">
            <img class="icones_sorts_deck" src="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.png"
                 onclick="show_description('#spell_desc', '<?= addslashes($sort->nom()); ?>', '<?= $sort->niveau(); ?>', '<?= $sort->num(); ?>', '<?= $joueur->pm(); ?>', '<?= $monstre->pm(); ?>', '<?= $joueur->endu(); ?>');"/>
          </li>
          <?php
        }
      } ?>
    </ul>

    <ul id="sorts_eau" class="ib centre l100 mh1 draggable_list">
      <?php
      $lenght = count($spells_by_elem["eau"]);
      if ($lenght < 1) { ?>
        <li class="sous_titre">Tu ne connais aucun sort d'eau</li>
      <?php } else {
        foreach ($spells_by_elem["eau"] as $sort) { ?>
          <li id="spell_<?= $sort->num(); ?>" class="original_spells draggable_spells" data-spell="<?= $sort->num(); ?>">
            <img class="icones_sorts_deck" src="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.png"
                 onclick="show_description('#spell_desc', '<?= addslashes($sort->nom()); ?>', '<?= $sort->niveau(); ?>', '<?= $sort->num(); ?>', '<?= $joueur->pm(); ?>', '<?= $monstre->pm(); ?>', '<?= $joueur->endu(); ?>');"/>
          </li>
          <?php
        }
      } ?>
    </ul>

    <ul id="sorts_vent" class="ib centre l100 mh1 draggable_list">
      <?php
      $lenght = count($spells_by_elem["vent"]);
      if ($lenght < 1) { ?>
        <li class="sous_titre">Tu ne connais aucun sort de vent</li>
      <?php } else {
        foreach ($spells_by_elem["vent"] as $sort) { ?>
          <li id="spell_<?= $sort->num(); ?>" class="original_spells draggable_spells" data-spell="<?= $sort->num(); ?>">
            <img class="icones_sorts_deck" src="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.png"
                 onclick="show_description('#spell_desc', '<?= addslashes($sort->nom()); ?>', '<?= $sort->niveau(); ?>', '<?= $sort->num(); ?>', '<?= $joueur->pm(); ?>', '<?= $monstre->pm(); ?>', '<?= $joueur->endu(); ?>');"/>
          </li>
          <?php
        }
      } ?>
    </ul>

    <ul id="sorts_terre" class="ib centre l100 mh1 draggable_list">
      <?php
      $lenght = count($spells_by_elem["terre"]);
      if ($lenght < 1) { ?>
        <li class="sous_titre">Tu ne connais aucun sort de terre</li>
      <?php } else {
        foreach ($spells_by_elem["terre"] as $sort) { ?>
          <li id="spell_<?= $sort->num(); ?>" class="original_spells draggable_spells" data-spell="<?= $sort->num(); ?>"
              onclick="show_description('#spell_desc', '<?= addslashes($sort->nom()); ?>', '<?= $sort->niveau(); ?>', '<?= $sort->num(); ?>', '<?= $joueur->pm(); ?>', '<?= $monstre->pm(); ?>', '<?= $joueur->endu(); ?>');">
            <img class="icones_sorts_deck" src="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.png"/>
          </li>
          <?php
        }
      } ?>
    </ul>

    <!-- Descriptif des sorts -->
    <div id="spell_desc" class="ib centre l100 spells_description">
    </div>

    <!-- Sorts choisit du joueur -->
    <div id="sortable_spells_parent" class="ib centre l80 mh1">
      <ul id="sortable_spells">
        <?php
				if($joueur->tuto() == "fini"){
	        foreach ($selected_spells as $spell) { ?>
	          <li id="selected_spell_<?= $spell->num(); ?>" data-spell="<?= $spell->num(); ?>">
	            <img class="icones_sorts_deck" src="/webroot/img/spells/<?= $spell->element1(); ?>_<?= $spell->num(); ?>.png"
	                 onclick="show_description('#spell_desc', '<?= addslashes($spell->nom()); ?>', '<?= $spell->niveau(); ?>', '<?= $spell->num(); ?>', '<?= $joueur->pm(); ?>', '<?= $monstre->pm(); ?>', '<?= $joueur->endu(); ?>');">
	          </li>
	        <?php }
				} ?>
      </ul>
      <span class="sous_titre">Déplace jusqu'à 5 sorts dans cette zone (<span
            id="spell_count"><?= count($selected_spells); ?></span>/5)</span>
    </div>

  </div>

  <!-- MONSTRE -->
  <div class="ib l15">
    <span class="div_centrale"><img id="monstre" class="l100" src="<?= $monstre->img(); ?>"/></span>
    <span class="info_monstre_flow mb2">
    	<span class="ib nom_monstre monstre_<?= $monstre->element(); ?>"><?= $monstre->nom(); ?></span>
    	<img class="fond_niveau_monstre" src="/webroot/img/monstres/niv_monstre_<?= $monstre->element(); ?>.png"/>
    	<span class="ib niveau_monstre"><?= $monstre->niveau(); ?></span>
    </span>
  </div>

  <span id="spells_auto_choose">
      <a class="gris" href="#">
        <div class="bouton form_gauche">Choix<br>automatique</div>
      </a>
      <a href="#"><img class="icone_form_gauche" src="/webroot/img/icones/btn_reinitialiser.png"></a>
  </span>

  <span id="spells_start_fight" onclick="load_fight(<?= $combat->id(); ?>);">
    <span class="blanc attaquer"><div class="bouton form_droite2">Attaquer</div></span>
    <span class="attaquer" ><img id="jouer_icone" class="icone_form_droite2" src="/webroot/img/icones/play.png"></span>
  </span>


  <div class="cache" id="initial_selected_spells"
       data-spells="<?php foreach ($selected_spells as $spell) { ?><?= $spell->num(); ?>,<?php } ?>"></div>
  <div class="cache" id="id_monstre"><?= $monstre->id(); ?></div>
  <div id="id_joueur" class="cache"><?= $joueur->id(); ?></div>
  <div id="elem_joueur" class="cache"><?= $joueur->element(); ?></div>

  <!-- Fin zone blanche -->
	<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
</div>

<table border="0" id="tableau_profil" class="cache">
  <tr>
    <th scope="col l30">Élément</th>
    <th scope="col l30">Dégâts infligés</th>
    <th scope="col l30">Dégâts reçus</th>
  </tr>
  <tr>
    <td><img class="img_30" src="/webroot/img/elements/<?= $monstre->element(); ?>.png"/></td>
    <td class="g"><?= $joueur->surligner_bonus_elem($joueur->facteur_elem_atq($monstre->element()), "atq"); ?></td>
    <td class="g"><?= $joueur->surligner_bonus_elem($joueur->facteur_elem_def($monstre->element()), "def"); ?></td>
  </tr>
</table>

<!-- Div cachés pour JS -->
<div class="cache" id="id_joueur"><?= $joueur->id(); ?></div>
<div class="cache" id="id_combat"><?= $combat->id(); ?></div>
<?php if ($joueur->tuto() != "fini") { ?>
  <div id="etape_tuto" class="cache"><?= $joueur->tuto(); ?></div>
  <div id="nom_tuteur" class="cache"><?= $joueur->tuteur(); ?></div>
  <div id="sexe_joueur" class="cache"><?= $joueur->sexe(); ?></div>
  <div id="pseudo" class="cache"><?= $joueur->pseudo(); ?></div>
  <div id="id_monstre" class="cache"><?= $monstre->id(); ?></div>
<?php } ?>

<script src="/webroot/js/utils/anim.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/math.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/spells.js?nvd_r=xxx"></script>
<script src="/webroot/js/combats_decks.js?nvd_r=xxx"></script>
