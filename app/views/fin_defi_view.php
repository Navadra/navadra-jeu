<!-- Tuteur -->
<img alt="" id="tuteur_cote" src="<?= $mentor; ?>" />

<?php if($joueur->tuto() == "fini"){ ?>
<!-- Bulle du personnage-->
<div id="bulle_bas" class="bulle">
    <!-- le texte dans la bulle -->
    <span id="txt_bulle"><?= $msg_tuteur; ?></span>
</div>
<img id="lancer_defi_bas" src="/webroot/img/icones/play_inverse.png" />

<?php
	if ($joueur->nb_jours_fin_tuto() == 0 && $sum_challenges > 0){
		echo('<img id="spotTraining" class="img_120 absolu" src="/webroot/img/icones/fleche3.png" />');
	}
}
else { ?>

<!-- Bulle du personnage-->
<div id="bulle_bas" class="bulle">
    <!-- le texte dans la bulle -->
    <span id="txt_bulle"><?= $msg_tuteur; ?></span>
</div>

<div id="commandes_tuto_bas">
	<img id="tuto_precedent" src="/webroot/img/icones/chevron1.png" />
    <img id="tuto_suivant" src="/webroot/img/icones/chevron2.png" />
</div>
<?php } ?>


<!-- Fond -->
<div class="fond l70 mh1 h68">

    <div class="titre_petit">
        <span class="ib l5"><img class="l100" src="<?= $challenge->element_img(); ?>" /></span>
        <span class="ib md5 align_centre">
            <span class="ib l100"><?= $challenge->notion(); ?></span>
            <span class="ib l100 p1 i"><?= $challenge->getLevel_user($formerLevel,$challenge->getTries()); ?></span>
        </span>
    </div>

	<!-- Récompenses du joueur -->
	<div id="recompenses_defi" class="col80">
    	<?php if($ultimate_missed == 0) {?>
            <!-- Si score = 0 -->
            <?php if($_POST["score"] == 0) { ?>
            <div class="msg_fin_defi">
                <span><img class="icone_fin_defi" alt="" src="/webroot/img/icones/dommage.png" /></span>
                <span class="ib l90 g">C'est pas grave !</span>
                <span class="ib l90">C'est en pratiquant qu'on s'améliore,<br />ne te décourage pas !<span>
            <!-- Si score > 0 -->
            <?php } else { ?>
            <div class="msg_fin_defi">
                <span><img class="icone_fin_defi" alt="" src="/webroot/img/icones/bravo.png" /></span>
                <span class="ib l90 g">BRAVO !</span>
                <span class="ib l90">Tu as eu <?= $_POST["score"]; ?> bonnes réponses à ce défi !<span>
            <?php } ?>
            <div class="recompense_fin_defi">
                <span>Récompense :</span>
                <span class="g"><font color="#39e6f6">+<?= $regularXP; ?> XP</font></span>
                <?php
                if($regularPyrs["fire"]!=0){echo '<span class="coul_pyrs_feu">+'.$regularPyrs["fire"].' <img class="pyrs_fin_defi" src="/webroot/img/icones/pyrs_feu.png" /></span>';}
                if($regularPyrs["water"]!=0){echo '<span class="coul_pyrs_eau">+'.$regularPyrs["water"].' <img class="pyrs_fin_defi" src="/webroot/img/icones/pyrs_eau.png" /></span>';}
                if($regularPyrs["wind"]!=0){echo '<span class="coul_pyrs_vent">+'.$regularPyrs["wind"].' <img class="pyrs_fin_defi" src="/webroot/img/icones/pyrs_vent.png" /></span>';}
                if($regularPyrs["earth"]!=0){echo '<span class="coul_pyrs_terre">+'.$regularPyrs["earth"].' <img class="pyrs_fin_defi" src="/webroot/img/icones/pyrs_terre.png" /></span>';}
                ?>
            </div>
        </div>
        <?php } elseif($ultimate_missed == 1) { //No reward if ultimate missed ?>
        <div class="msg_fin_defi">
            <span><img class="icone_fin_defi" alt="" src="/webroot/img/icones/dommage.png" /></span>
            <span class="ib l90 g">C'est pas grave !</span>
            <span class="ib l90">Tu peux retenter chaque Défi Ultime une fois par jour.<br />Réessaie demain !<span>
        </div>
        <?php } ?>

        <div id="progress_graph_borders_after">
            <div id="progress_graph">
                <!-- Created in JS -->
            </div>
            <!-- Si nouveau palier atteint  => no more XP and Pyrs, just achievments !
            < ?php if($newLevel > $formerLevel) { ?>
            <div class="recompense_fin_defi">
               <span>Bonus niveau débloqué :</span>
               <span class="g"><font color="#39e6f6">+< ?= $bonusXP; ?> XP</font></span>
                    < ?php
                    if($bonusPyrs["fire"]!=0){echo '<span class="coul_pyrs_feu">+'.$bonusPyrs["fire"].' <img class="pyrs_fin_defi" src="/webroot/img/icones/pyrs_feu.png" /></span>';}
                    if($bonusPyrs["water"]!=0){echo '<span class="coul_pyrs_eau">+'.$bonusPyrs["water"].' <img class="pyrs_fin_defi" src="/webroot/img/icones/pyrs_eau.png" /></span>';}
                    if($bonusPyrs["wind"]!=0){echo '<span class="coul_pyrs_vent">+'.$bonusPyrs["wind"].' <img class="pyrs_fin_defi" src="/webroot/img/icones/pyrs_vent.png" /></span>';}
                    if($bonusPyrs["earth"]!=0){echo '<span class="coul_pyrs_terre">+'.$bonusPyrs["earth"].' <img class="pyrs_fin_defi" src="/webroot/img/icones/pyrs_terre.png" /></span>';}
                    ?>
            </div>
            < ?php } ?>
            -->
        </div>
	</div>

<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
<!-- Fin du fond -->
</div>

<?php
if($endFreePeriod == 1) {
	echo ('<div id="displayEndFreePeriod" class="cache">1</div>');
} elseif(isset($reminderEndFreePeriod)) {
	echo ('<div id="reminderEndFreePeriod" class="cache">1</div>');
} ?>


<!-- Div qui s'active en cas de level up -->
<div id="level_up" class="level_up_cache">
    <span class="ib l100">Félicitations, tu es passé</span>
    <span class="ib l100 g">Niveau <?= $joueur->niveau(); ?> !</span>
</div>

<!-- Div qui s'active en cas de débloquage de défis -->
<div id="annonce_fin_defi" class="annonce_fin_defi_cachee">
<?= $achievementMsg; ?>
</div>

<audio preload="auto" id="son_level_up">
	 <source src = "/webroot/sons/level_up.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/level_up.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_fin_defi">
	 <source src = "/webroot/sons/fin_defi.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/fin_defi.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_deblocage">
	 <source src = "/webroot/sons/deblocage.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/deblocage.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_oups">
	 <source src = "/webroot/sons/oups.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/oups.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="sound_challenge_ultime_mastery">
	 <source src = "/webroot/sons/challenge_ultime_mastery.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/challenge_ultime_mastery.mp3" type="audio/mp3" />
</audio>

<!-- Player head for progress graph -->
<img id="playerAvatar" class="l6" src="<?= $joueur->full_portrait(); ?>" />

<!-- Div cachées utilisées par JS-->
<div id="challengeNotion" class="cache"><?= $challenge->notion(); ?></div>
<div id="challengeElement" class="cache"><?= $challenge->getElement(); ?></div>
<div id="levelBeforeChallenge" class="cache"><?= $formerLevel; ?></div>
<div id="levelAfterChallenge" class="cache"><?= $newLevel; ?></div>
<div id="congratulations" class="cache"><?= $congratulations; ?></div>
<div id="challengeTries" class="cache"><?= $challenge->getTries(); ?></div>
<div id="levelUp" class="cache"><?= $levelUp; ?></div>
<div id="newLevel" class="cache"><?= $joueur->niveau(); ?></div>
<div id="etape_tuto" class="cache"><?= $joueur->tuto(); ?></div>
<div id="nom_tuteur" class="cache"><?= $joueur->tuteur(); ?></div>
<div id="sexe_joueur" class="cache"><?= $joueur->sexe(); ?></div>
<div id="pseudo" class="cache"><?= $joueur->pseudo(); ?></div>
<?php if (isset($new_mentor )) { ?>
  <div id="new_mentor" class="cache"><?= $new_mentor; ?></div>
<?php } ?>
<script src="/webroot/js/utils/anim.js?nvd_r=xxx"></script>
<script src="/webroot/js/fin_defi.js?nvd_r=xxx"></script>
