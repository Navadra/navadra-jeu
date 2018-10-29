<div id="screen_challenge">
    <span class="ib l100 pfun align_centre mh4 mb2">
    	<span class="ib l100 p7 g"><?= $monstre->nom(); ?></span>
    	<span class="ib l100 p3 g">Niveau <?= $monstre->niveau(); ?></span>
    </span>
    <span class="ib l100 align_centre mb2"><img class="l20" src="<?= $monstre->img(); ?>" /></span>
    <span class="ib l100 align_centre p5 mb2" id="loading_msg"><?= $monstre->loading_msg(); ?></span>
    <span class="ib l100 align_centre"><img id="resume_challenge" title="C'est parti !" class="l8 cache" src="/webroot/img/icones/resume.png" /></span>
</div>
<div id="exit_confirm" title="Abandonner le combat en cours ?">Si tu quittes le combat en cours, il sera considéré comme perdu...</div>

<?php if ($joueur->tuto() != "fini") { ?>
  <!-- Tuteur -->
  <img alt="" id="tuteur_combat" src="<?= $joueur->portrait_tuteur(); ?>"/>

  <!-- Bulle du personnage-->
  <div id="bulle_combat" class="bulle">
    <!-- le texte dans la bulle -->
    <span id="txt_bulle"></span>
  </div>
  <div id="commandes_tuto_combat">
    <img id="tuto_precedent" src="/webroot/img/icones/chevron1.png"/>
    <img id="tuto_suivant" src="/webroot/img/icones/chevron2.png"/>
  </div>
<?php } ?>

<!-- Fond -->
<div class="fond_combat l100 h100">

  <!-- Chasseurs restants -->
  <div id="liste_autres_chasseurs">
    <?php
    $nb_chass = 0;
    foreach ($chasseurs as $chass) {
      $nb_chass++;
      if ($chass != $chasseur) { ?>
        <div class="autre_chasseur <?php if ($combat->nb_chasseurs() == 2) {echo 'mh2';} ?> ">
            <img class="tetes_chasseurs" title="<?= $chass->pseudo(); ?> - Niv.<?= $chass->niveau(); ?>" src="<?= $chass->full_portrait(); ?>" />
            <span class="pseudo_chasseur cache"><?= $chass->pseudo(); ?></span>
			<?php if ($nb_chass <= $combat->nb_chasseurs_ko()) { ?>
              <img class="icone_chasseur_ko" src="/webroot/img/icones/faux.png"/>
            <?php } ?>
        </div>
      <?php }
    } ?>
  </div>

  <!-- Nom, niveau et PM du joueur -->
  <span id="caracs_joueur_haut_gauche">
     <img class="fond_niveau_combattants" src="/webroot/img/monstres/niv_monstre_<?= $chasseur->element(); ?>.png"/>
     <span class="niveau_combattants"><?= $chasseur->niveau(); ?></span>
     <span class="noms_combattants monstre_<?= $chasseur->element(); ?>"><?= $chasseur->pseudo(); ?></span>

     <!-- Endu Joueur -->
     <span class="ib l100"><img class="icone_endu" id="img_endu_joueur" src="/webroot/img/icones/endurance.png"></span>
     <span id="barre_endu_joueur" class="ib"></span>
     <span id="endu_joueur" class="cache"><?= $endu_joueur; ?></span>
     <span id="img_endu_joueur_descr" class="cache"><?= $chasseur->descriptif_endu($joueur); ?></span>
     <!-- player Combo gauge -->
     <span class="ib l100"><img class="icone_endu" id="player_combo_img" src="/webroot/img/icones/puissance_magique.png"></span>
     <span id="player_combo_bar" class="ib"></span>
     <span id="player_combo" class="cache">0</span>
     <span id="player_combo_img_descr" class="cache">Lorsque cette barre est pleine, tu débloques un sort ultime !</span>
  </span>

  <span class="player_effects">
    <!-- PM Joueur -->
    <img id="icon_pm_joueur" class="img_30" src="/webroot/img/icones/state_pm.png">
    <span id="pm_joueur" class="p1 g bulle_daide"><?= $pm_joueur; ?></span>
    <span id="desc_pm_joueur" class="descriptif_sort p0">Ta puissance magique</span>
    <!-- SendBack Joueur -->
    <img id="icon_sendback_joueur" class="img_30" src="/webroot/img/icones/state_sendback.png">
    <span id="sendback_joueur" class="p1 g bulle_daide"></span>
    <span id="desc_sendback_joueur" class="descriptif_sort p0">Pourcentage de l'attaque du monstre qui lui est renvoyée</span>
    <!-- Absorb Joueur -->
    <img id="icon_absorb_joueur" class="img_30" src="/webroot/img/icones/state_absorb.png">
    <span id="absorb_joueur" class="p1 g bulle_daide"></span>
    <span id="desc_absorb_joueur" class="descriptif_sort p0">Pourcentage de l'attaque du monstre que tu va absorber</span>
    <!-- Dodge icon -->
    <img id="icon_dodge_joueur" class="img_30" src="/webroot/img/icones/state_dodge.png" >
    <span id="desc_dodge_joueur" class="descriptif_sort p0">Tu esquivera la prochaine attaque</span>
  </span>

  <!-- CASE JOUEUR -->
  <div id="case_joueur_combat" class="<?= $hauteur_joueur; ?>">
    <img id="img_joueur" class="h80" src="<?= $chasseur->avatar_entier(); ?>">
  </div>

  <!-- Nom, niveau et PM du monstre -->
  <span id="caracs_monstre_haut_droite">
     <img class="fond_niveau_combattants" src="/webroot/img/monstres/niv_monstre_<?= $monstre->element(); ?>.png"/>
     <span id="niveau_monstre" class="ib niveau_combattants"><?= $monstre->niveau(); ?></span>
     <span class="ib noms_combattants monstre_<?= $monstre->element(); ?>"><?= $monstre->nom(); ?></span>
    <!-- Endu Monstre -->
    <span id="barre_endu_monstre" class="ib"></span>
    <span><img class="icone_endu_monstre" id="img_endu_monstre" src="/webroot/img/icones/endurance.png"></span>
    <span id="endu_monstre" class="cache"><?= $combat->endu_monstre_restante(); ?></span>
    <span id="endu_monstre_max" class="cache"><?= $endu_monstre; ?></span>
    <span id="img_endu_monstre_descr" class="cache"><?= $monstre->descriptif_endu(); ?></span>
  </span>

  <span class="monster_effects">
    <!-- Skip turn Monstre -->
    <img id="icon_skipturn_monstre" class="img_30" src="/webroot/img/icones/state_skipturn.png" >
    <span id="desc_skipturn_monstre" class="descriptif_sort p0">Le monstre passera son prochain tour</span>
    <!-- PM Monstre -->
    <span id="pm_monstre" class="p1 g bulle_daide"><?= $pm_monstre; ?></span>
    <img id="icon_pm_monstre" class="img_30" src="/webroot/img/icones/state_pm.png">
    <span id="desc_pm_monstre" class="descriptif_sort p0">La puissance magique du monstre</span>
  </span>

  <!-- CASE MONSTRE -->
  <div id="case_monstre_combat" class="h60">
    <img id="img_monstre" class="h80" src="<?= $monstre->img(); ?>">
  </div>


  <!-- Info combat -->
  <div id="info_combat" class="g cache bulle_daide"></div>

  <!-- Info absorption -->
  <div id="info_absorb" class="g bleu bulle_daide"></div>

  <div class="extra_mghaut"></div>
  <div class="adapt_tablettes"></div>

  <!-- Div de victoire ou défaite -->
  <div id="issue_combat" class="align_centre align_milieu" title="Revenir à l'île">
    <span class="ib l15 md5 align_milieu"><img class="img_120" alt="" src="/webroot/img/icones/victoire.png"/></span>
	<span class="ib l75 align_haut">
    	<span class="ib l100 p4 g"></span>
        <span class="ib mh2 align_haut p4"></span>
        <span class="ib mh2"><img class="img_30" alt="" src="/webroot/img/icones/prestige.png"/></span>
    </span>
  </div>

  <!-- Sorts du joueur -->
  <div id="fond_sorts">
    <?php foreach ($sorts as $sort) { ?>
      <div class="cases_calculs_combat">
        <img id="spell_<?= $sort->num(); ?>" data-num="<?= $sort->num(); ?>"
             class="icones_sorts_combat" alt="" src="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.png"/>
        <span id="spell_info_<?= $sort->num(); ?>" class="cache spell_choosed_info"
              data-num="<?= $sort->num(); ?>" data-nom="<?= $sort->nom(); ?>" data-niveau="<?= $sort->niveau(); ?>"
              data-element="<?= $sort->element1(); ?>" data-categorie="<?= $sort->categorie(); ?>"
              data-icone="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.png"/>
        <span id="descriptif_<?= $sort->num(); ?>" class="cache"
              data-num="<?= $sort->num(); ?>" ></span>

      </div>
    <?php } ?>
  </div>


  <div id="combat_borders">
    <div id="challenge_content">
      <!-- JS inserts content inside this div -->
    </div>
  </div>

  <!-- Chrono de combat -->
  <div id="chrono_combat"></div>

  <!-- Fin du fond -->
</div>

<!-- Améliorations / Bug reporting -->
<div id="feedback" class="bordure_sans_pos">
	<img id="fermer_feedback" src="/webroot/img/icones/refuser.png">

    <!-- Soumettre bug -->
    <div id="soumettre_bug" class="corps_scroll2 align_centre scroll_grand">
		<span class="ib mg10 l90 mh1 mb1 align_gauche p3">Descriptif :</span>
        <span class="ib mg10 l80 md10 align_gauche h60"><textarea class="zone_texte h100" autocomplete="off" name="descriptif_bug" title="Entre 20 et 2 000 caractères"></textarea></span>
        <span class="cache"><input type="hidden" name="page_courante" value="<?= $adresse; ?>" /></span>
        <span id="confirm_bug" class="ib mg5 l90 md5 vert g mh1"></span>
    </div>

    <!-- Boutons pour valider le formulaire -->
    <div id="valider_feedback">
    	<a class="blanc valider_feedback" href="#"><div class="bouton form_droite2">Envoyer</div></a>
		<a href="#" class="valider_feedback"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>
    </div>

</div>

<!-- Countdown Popup -->
<div id="countdown" class="countdown bouton">
  <span id="countdown_title"></span>
  <br/>
  <br/>
  <span>Choisis un sort dans la liste</span>
  <br/>
  <br/>
  <span class="countdown_fight"></span>
</div>
  <!-- Countdown Popup Monster -->
<div id="countdown_monster" class="countdown bouton cache">
  <br/>
  <span>Tour du monstre</span>
  <br/>
</div>

<!-- Teish qui apparait -->
<img alt="" id="teish" class="cache" src="/webroot/img/personnages/teish.png"/>

<!-- Bulle de Teish -->
<div id="bulle_teish" class="cache bulle">
  <!-- le texte qui apparaitra dans la bulle -->
    <span id="txt_bulle_teish">
    Va-t'en !
    </span>
</div>

<?php if($joueur->tuto() == "fini") { ?>
<!-- Impressions challenge -->
<!--div id="impressions" class="bordure_sans_pos">

	<div class="mh2 g p1 ib l100 align_centre mb4">On a besoin de ton avis pour améliorer Navadra !</div>
    <div class="label">
      <label>Tu as trouvé la durée du combat :</label>
    </div>
    <div id="length" class="input">
      <input type="radio" name="length" id="short_length" value="too short"><label for="short_length">Trop courte</label>
      <input type="radio" name="length" id="good_length" value="good"><label for="good_length">Comme il faut</label>
      <input type="radio" name="length" id="long_length" value="too long"><label for="long_length">Trop longue</label>
    </div>

    <div class="label mh2 mb8">
      <label>Tu as trouvé la difficulté du combat :</label>
    </div>
    <div id="difficulty" class="input mh2 mb8">
      <input type="radio" name="difficulty" id="easy_difficulty" value="too easy"><label for="easy_difficulty">Trop facile</label>
      <input type="radio" name="difficulty" id="good_difficulty" value="good"><label for="good_difficulty">Comme il faut</label>
      <input type="radio" name="difficulty" id="hard_difficulty" value="too hard"><label for="hard_difficulty">Trop dure</label>
    </div>

    <div id="validate_impressions">
    	<a class="blanc validate_impressions" href="#"><div class="bouton form_droite2">Envoyer</div></a>
		<a href="#" class="validate_impressions"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>
    </div>

</div>

<div id="error_impressions" title="Pas si vite !">Peux-tu répondre à nos 2 questions s'il te plaît ?<br />C'est très important pour nous permettre d'améliorer Navadra.</div-->
<?php } ?>

<!-- Graphisme des attaques -->
<img class="cache absolu img_100 graphismes_sort_petit" id="graphisme_atq_joueur_<?= $chasseur->element(); ?>"
     src="/webroot/img/combat/atq_<?= $chasseur->element(); ?>_joueur.png"/>
<img class="cache absolu img_100 graphismes_sort_petit" id="graphisme_atq_monstre"
     src="/webroot/img/combat/atq_<?= $monstre->element(); ?>_monstre.png"/>
<img class="cache absolu img_100 graphismes_sort_petit" id="graphisme_atq_teish"
     src="/webroot/img/combat/atq_teish.png"/>

<!-- Icones et graphisme attaque sans sort -->
  <img class="cache sorts_combat sort_actif_icone" id="icone_0"
       src="/webroot/img/spells/base_0.png"/>
  <img class="cache absolu img_100 graphismes_sort_petit" id="0"
       src="/webroot/img/combat/atq_<?= $chasseur->element(); ?>_joueur.png"/>

<!-- Icones et graphisme des sorts -->
<?php foreach ($sorts as $sort) { ?>
  <span class="sort_actif_niveau niveau_sort <?= $sort->couleur(); ?> bordures_<?= $sort->couleur(); ?>"
        id="niveau_<?= $sort->num(); ?>" class="cache niveau_sort p3"></span>
  <img class="cache sorts_combat sort_actif_icone" id="icone_<?= $sort->num(); ?>"
       src="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.png"/>
  <img class="cache absolu img_200 graphismes_sort_grd" id="<?= $sort->num(); ?>"
       src="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>_graphisme.png"/>
<?php } ?>

<!-- Icones et graphisme des sorts critiques -->
<?php
if (isset( $critical_spells )) {
  foreach ($critical_spells as $critical) { ?>
  <span class="sort_actif_niveau niveau_sort <?= $critical->couleur(); ?> bordures_<?= $critical->couleur(); ?>"
        id="niveau_<?= $critical->num(); ?>" class="cache niveau_sort p3"></span>
  <img class="cache sorts_combat sort_actif_icone" id="icone_<?= $critical->num(); ?>"
       src="/webroot/img/spells/<?= $critical->element1(); ?>_<?= $critical->num(); ?>.png"/>
  <img class="cache absolu img_200 graphismes_sort_grd" id="<?= $critical->num(); ?>"
       src="/webroot/img/spells/<?= $critical->element1(); ?>_<?= $critical->num(); ?>_graphisme.png"/>
<?php
  }
} ?>

<!-- Div utilisées par JS -->
<div class="cache" id="profil_<?= $chasseur->id(); ?>"><?= $profil_elem_joueur; ?></div>
<div class="cache" id="prof_monstre"><?= $profil_elem_monstre; ?></div>
<div class="cache" id="id_chasseur"><?= $chasseur->id(); ?></div>
<div class="cache" id="id_combat"><?= $combat->id(); ?></div>
<div class="cache" id="pseudo_chasseur"><?= $chasseur->pseudo(); ?></div>
<div class="cache" id="niveau_chasseur"><?= $chasseur->niveau(); ?></div>
<div class="cache" id="deroulement"><?= $combat->deroulement(); ?></div>
<div class="cache" id="issue_combat"><?= $combat->issue(); ?></div>
<div class="cache" id="dernier_joueur"><?= $dernier_joueur; ?></div>
<?php if($joueur->tuto() == "combattre_2")
{
	echo('<div class="cache" id="gain_prestige">0</div>');
	echo('<div class="cache" id="perte_prestige">0</div>');
}
else
{
	echo('<div class="cache" id="gain_prestige">'.$monstre->gain_prestige($combat->nb_chasseurs(), $timeSlot).'</div>');
	echo('<div class="cache" id="perte_prestige">'.$monstre->perte_prestige($combat->nb_chasseurs(), $timeSlot).'</div>');
} ?>
<div class="cache" id="nb_chasseurs"><?= $combat->nb_chasseurs(); ?></div>
<div class="cache" id="nb_chasseurs_recommandes"><?= $monstre->nb_chasseurs(); ?></div>
<div class="cache" id="bonus_elem_joueur_actif"><?= $joueur->facteur_elem_atq($monstre->element()) ?></div>
<div class="cache" id="player_count_spell"><?= count($sorts) ?></div>

<?php foreach ($critical_spells as $critical) { ?>
  <div class="cache" id="info_critical_<?= $critical->num(); ?>"
       data-num="<?= $critical->num(); ?>" data-nom="<?= $critical->nom(); ?>"
       data-element="<?= $critical->element1(); ?>" data-niveau="<?= ceil($joueur->niveau() / 5); ?>"
       data-categorie="<?= $critical->categorie(); ?>"
       data-icone="/webroot/img/spells/<?= $critical->element1(); ?>_<?= $critical->num(); ?>.png" />
<?php } ?>


<?php if ($joueur->tuto() != "fini") { ?>
  <!-- Div cachées pour JS -->
  <div id="etape_tuto" class="cache"><?= $joueur->tuto(); ?></div>
  <div id="nom_tuteur" class="cache"><?= $joueur->tuteur(); ?></div>
  <div id="sexe_joueur" class="cache"><?= $joueur->sexe(); ?></div>
  <div id="pseudo" class="cache"><?= $joueur->pseudo(); ?></div>
  <div id="id_monstre" class="cache"><?= $monstre->id(); ?></div>
<?php } ?>

<!-- Sons généraux combats -->
<audio preload="auto" id="son_bonne_rep">
  <source src="/webroot/sons/bonne_rep.ogg" type="audio/ogg"/>
  <source src="/webroot/sons/bonne_rep.mp3" type="audio/mp3"/>
</audio>

<audio preload="auto" id="son_victoire" class="sons">
  <source src="/webroot/sons/victoire.ogg" type="audio/ogg"/>
  <source src="/webroot/sons/victoire.mp3" type="audio/mp3"/>
</audio>

<audio preload="auto" id="son_defaite" class="sons">
  <source src="/webroot/sons/defaite.ogg" type="audio/ogg"/>
  <source src="/webroot/sons/defaite.mp3" type="audio/mp3"/>
</audio>

<audio preload="auto" id="son_attaque" class="sons">
  <source src="/webroot/sons/attaque.ogg" type="audio/ogg"/>
  <source src="/webroot/sons/attaque.mp3" type="audio/mp3"/>
</audio>

<audio preload="auto" id="son_attaque_ratee" class="sons">
  <source src="/webroot/sons/attaque_ratee.ogg" type="audio/ogg"/>
  <source src="/webroot/sons/attaque_ratee.mp3" type="audio/mp3"/>
</audio>

<audio preload="auto" id="son_calcul_rate" class="sons">
  <source src="/webroot/sons/calcul_rate.ogg" type="audio/ogg"/>
  <source src="/webroot/sons/calcul_rate.mp3" type="audio/mp3"/>
</audio>

<audio preload="auto" id="son_changement_joueur" class="sons">
  <source src="/webroot/sons/changement_joueur.ogg" type="audio/ogg"/>
  <source src="/webroot/sons/changement_joueur.mp3" type="audio/mp3"/>
</audio>

<audio preload="auto" id="son_musique_combat" class="sons" controls loop>
  <source src="/webroot/sons/musique_combat.ogg" type="audio/ogg"/>
  <source src="/webroot/sons/musique_combat.mp3" type="audio/mp3"/>
</audio>

<!-- Sons sorts -->
<audio preload="auto" id="son_0" class="sons">
  <source src="/webroot/sons/attaque.ogg" type="audio/ogg"/>
  <source src="/webroot/sons/attaque.mp3" type="audio/mp3"/>
</audio>

<?php foreach ($sorts as $sort) { ?>
  <audio preload="auto" id="son_<?= $sort->num(); ?>" class="sons">
    <source src="/webroot/sons/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.ogg" type="audio/ogg"/>
    <source src="/webroot/sons/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.mp3" type="audio/mp3"/>
  </audio>
<?php } ?>

<?php foreach ($critical_spells as $critical) { ?>
  <audio preload="auto" id="son_<?= $critical->num(); ?>" class="sons">
    <source src="/webroot/sons/spells/<?= $critical->element1(); ?>_<?= $critical->num(); ?>.ogg" type="audio/ogg"/>
    <source src="/webroot/sons/spells/<?= $critical->element1(); ?>_<?= $critical->num(); ?>.mp3" type="audio/mp3"/>
  </audio>
<?php } ?>

<script src="/webroot/js/utils/anim.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/math.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/challenge_choice.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/spells.js?nvd_r=xxx"></script>
<script src="/webroot/js/challenge.js?nvd_r=xxx"></script>
<script src="/webroot/js/combattre_tools.js?nvd_r=xxx"></script>
<script src="/webroot/js/combattre.js?nvd_r=xxx"></script>
<?php if($joueur->tuto() != "fini") { ?>
    <audio preload="auto" id="son_tuto">
         <source src = "/webroot/sons/tuto.ogg" type="audio/ogg" />
         <source src = "/webroot/sons/tuto.mp3" type="audio/mp3" />
    </audio>

    <script src="/webroot/js/tutoriel.js?nvd_r=xxx"></script>
<?php } ?>
