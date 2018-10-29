<!-- Fond -->
<div class="fond_combat l100 h100">

<!-- Chasseurs restants -->
<div id="liste_autres_chasseurs">
	<?php
$nb_chass = 0;
    foreach ($chasseurs as $chass) {
	$nb_chass ++;
	if($nb_chass != 1) //Le premier chasseur est affiché en face du monstre
	{?>
    <div class="autre_chasseur <?php if($combat->nb_chasseurs()==2){echo "mh2";}?> ">
        <img class="tetes_chasseurs" title="<?= $chass->pseudo(); ?>" src="<?= $chass->avatar_tete(); ?>" />
        <span class="pseudo_chasseur cache"><?= $chass->pseudo(); ?></span>
    </div>
	<?php }
}?>
</div>

<!-- Nom, niveau et PM du joueur -->
<span id="caracs_joueur_haut_gauche">
    <img class="fond_niveau_combattants" src="/webroot/img/monstres/niv_monstre_<?= $chasseur->element(); ?>.png" />
    <span class="niveau_combattants"><?= $chasseur->niveau(); ?></span>
    <span class="noms_combattants monstre_<?= $chasseur->element(); ?>"><?= $chasseur->pseudo(); ?></span>

    <!-- Endu Joueur -->
     <span class="ib l100"><img class="icone_endu" id="img_endu_joueur" src="/webroot/img/icones/endurance.png"></span>
    <span id="barre_endu_joueur" class="ib"></span>
    <span id="endu_joueur" class="cache"><?= $chasseur->endu(); ?></span>
    <span id="img_endu_joueur_descr" class="cache"><?= $chasseur->descriptif_endu($joueur); ?></span>
     <!-- player Combo gauge -->
     <span class="ib l100"><img class="icone_endu" id="player_combo_img" src="/webroot/img/icones/puissance_magique.png"></span>
     <span id="player_combo_bar" class="ib"></span>
     <span id="player_combo" class="cache">0</span>
     <span id="player_combo_img_descr" class="cache">Lorsque cette barre est pleine, un coup special apparait !</span>
</span>
<!-- PM Joueur -->
<span id="pm_joueur" class="p1 g bulle_daide"><?= $chasseur->pm(); ?></span>
<img id="icon_pm_joueur" class="img_30" src="/webroot/img/icones/puissance_magique.png">

  <!-- CASE JOUEUR -->
  <div id="case_joueur_combat" class="<?= $hauteur_joueur; ?>">
    <img id="img_joueur" class="h80" src="<?= $chasseur->avatar_entier(); ?>">
</div>

<!-- Nom, niveau et PM du monstre -->
<span id="caracs_monstre_haut_droite">
    <img class="fond_niveau_combattants" src="/webroot/img/monstres/niv_monstre_<?= $monstre->element(); ?>.png" />
    <span id="niveau_monstre" class="ib niveau_combattants"><?= $monstre->niveau(); ?></span>
    <span class="ib noms_combattants monstre_<?= $monstre->element(); ?>"><?= $monstre->nom(); ?></span>
     <!-- Endu Monstre -->
    <span id="barre_endu_monstre" class="ib"></span>
    <span><img class="icone_endu_monstre" id="img_endu_monstre" src="/webroot/img/icones/endurance.png"></span>
    <span id="endu_monstre" class="cache"><?= $monstre->endu(); ?></span>
    <!-- ?? Use this one ? span id="endu_monstre" class="cache">< ?= $combat->endu_monstre_restante(); ? ></span-->
    <span id="endu_monstre_max" class="cache"><?= $monstre->endu(); ?></span>
    <span id="img_endu_monstre_descr" class="cache"><?= $monstre->descriptif_endu(); ?></span>
</span>

<!-- PM Monstre -->
<span id="pm_monstre" class="p1 g bulle_daide"><?= $monstre->pm(); ?></span>
<img id="icon_pm_monstre" class="img_30" src="/webroot/img/icones/puissance_magique.png">

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

<!-- Div de victoire -->
<div id="issue_combat" class="cache align_centre" title="Revenir à l'île">
	<span class="ib l15 md5"><img class="img_120" alt="" src="/webroot/img/icones/victoire.png" /></span>
	<span class="ib l75 align_haut">
    	<span class="ib l100 p4 g">VICTOIRE !</span>
        <span class="ib mh10 pb4 align_haut p4">+ 100</span>
        <span class="ib mh10"><img class="img_30" alt="" src="/webroot/img/icones/prestige.png" /></span>
    </span>
</div>

<!-- Sorts du joueur -->
<div id="fond_sorts">
	<!-- Emplacement laissé vide -->
</div>

<!-- Clavier pour saisir les résultats -->
<div id="clavier_combat">
	<span class="signe_combat">+</span>
	<span class="chiffre_clavier chiffre_trio">1</span>
    <span class="chiffre_clavier chiffre_trio">2</span>
    <span class="chiffre_clavier chiffre_trio">3</span>
    <span class="chiffre_clavier chiffre_trio">4</span>
    <span class="chiffre_clavier chiffre_trio">5</span>
    <span class="chiffre_clavier chiffre_trio">6</span>
    <span class="chiffre_clavier chiffre_trio">7</span>
    <span class="chiffre_clavier chiffre_trio">8</span>
    <span class="chiffre_clavier chiffre_trio">9</span>
    <span class="chiffre_clavier chiffre_seul">0</span>
    <img id="reinitialiser_clavier" src="/webroot/img/icones/btn_reinitialiser.png" />
</div>


<!-- Fin du fond -->
</div>

<!-- Bouton pour revoir le combat -->
<div id="start" class="bouton std cache">Revoir le combat</div>

<!-- Teish qui apparait -->
<img alt="" id="teish" class="cache" src="/webroot/img/personnages/teish.png" />

<!-- Bulle de Teish -->
<div id="bulle_teish" class="cache bulle">
<!-- le texte qui apparaitra dans la bulle -->
    <span id="txt_bulle_teish">
    Va-t'en !
    </span>
</div>

<!-- Graphisme des attaques -->
<img class="cache absolu img_100 graphismes_sort_petit" id="graphisme_atq_joueur_feu" src="/webroot/img/combat/atq_feu_joueur.png"/>
<img class="cache absolu img_100 graphismes_sort_petit" id="graphisme_atq_joueur_eau" src="/webroot/img/combat/atq_eau_joueur.png"/>
<img class="cache absolu img_100 graphismes_sort_petit" id="graphisme_atq_joueur_vent" src="/webroot/img/combat/atq_vent_joueur.png"/>
<img class="cache absolu img_100 graphismes_sort_petit" id="graphisme_atq_joueur_terre" src="/webroot/img/combat/atq_terre_joueur.png"/>
<img class="cache absolu img_100 graphismes_sort_petit" id="graphisme_atq_monstre" src="/webroot/img/combat/atq_<?= $monstre->element(); ?>_monstre.png"/>
<img class="cache absolu img_100 graphismes_sort_petit" id="graphisme_atq_teish" src="/webroot/img/combat/atq_teish.png"/>

<!-- Icones et graphisme des sorts -->
<?php
foreach($sorts as $sort)
{ ?>
	<span class="sort_actif_niveau niveau_sort <?= $sort->couleur(); ?> bordures_<?= $sort->couleur(); ?>" id="niveau_<?= $sort->num(); ?>" class="cache niveau_sort p3"></span>
    <img class="cache sorts_combat sort_actif_icone" id="icone_<?= $sort->num(); ?>" src="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.png"/>
    <img class="cache absolu img_200 graphismes_sort_grd" id="<?= $sort->num(); ?>" src="/webroot/img/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>_graphisme.png"/>
<?php } ?>

<!-- Div utilisées par JS -->
<div class="cache" id="profil_<?= $chasseur->id(); ?>"><?= $profil_elem_joueur; ?></div>
<div class="cache" id="pm_joueur"><?= $pm_joueur ?></div>
<div class="cache" id="pm_monstre"><?= $pm_monstre ?></div>
<div class="cache" id="prof_monstre"><?= $profil_elem_monstre; ?></div>
<div class="cache" id="id_chasseur"><?= $chasseur->id(); ?></div>
<div class="cache" id="id_combat"><?= $combat->id(); ?></div>
<div class="cache" id="pseudo_chasseur"><?= $chasseur->pseudo(); ?></div>
<div class="cache" id="niveau_chasseur"><?= $chasseur->niveau(); ?></div>
<div class="cache" id="deroulement"><?= $combat->deroulement(); ?></div>
<div class="cache" id="issue_combat_txt"><?= $combat->issue(); ?></div>
<div class="cache" id="dernier_joueur"></div>
<div class="cache" id="gain_prestige"><?= $monstre->gain_prestige($combat->nb_chasseurs(), $timeSlot); ?></div>
<div class="cache" id="perte_prestige"><?= $monstre->perte_prestige($combat->nb_chasseurs(), $timeSlot); ?></div>
<div class="cache" id="nb_chasseurs"><?= $combat->nb_chasseurs(); ?></div>
<div class="cache" id="nb_chasseurs_recommandes"><?= $monstre->nb_chasseurs(); ?></div>
<div class="cache" id="bonus_elem_joueur_actif"><?= $joueur->facteur_elem_atq($monstre->element()) ?></div>

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
	 <source src = "/webroot/sons/bonne_rep.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/bonne_rep.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_victoire" class="sons">
	 <source src = "/webroot/sons/victoire.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/victoire.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_defaite" class="sons">
	 <source src = "/webroot/sons/defaite.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/defaite.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_attaque" class="sons">
	 <source src = "/webroot/sons/attaque.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/attaque.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_attaque_ratee" class="sons">
	 <source src = "/webroot/sons/attaque_ratee.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/attaque_ratee.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_calcul_rate" class="sons">
	 <source src = "/webroot/sons/calcul_rate.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/calcul_rate.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_changement_joueur" class="sons">
	 <source src = "/webroot/sons/changement_joueur.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/changement_joueur.mp3" type="audio/mp3" />
</audio>

<!-- Sons sorts -->
<?php foreach ($sorts as $sort) { ?>
<audio preload="auto" id="son_<?= $sort->num(); ?>" class="sons">
	 <source src = "/webroot/sons/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/spells/<?= $sort->element1(); ?>_<?= $sort->num(); ?>.mp3" type="audio/mp3" />
</audio>
<?php } ?>

<script src="/webroot/js/utils/anim.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/math.js?nvd_r=xxx"></script>
<script src="/webroot/js/combattre_tools.js?nvd_r=xxx"></script>
<!--
<script src="/webroot/js/combattre.js?nvd_r=xxx"></script>
-->
