<!-- Tuteur -->
<img alt="" id="tuteur_cote" src="<?= $mentor; ?>" />

<!-- Bulle du personnage-->
<div id="bulle_bas" class="bulle">
    <!-- le texte dans la bulle -->
    <span id="txt_bulle"><?= $msg_tuteur; ?></span>
</div>

<?php if($joueur->tuto() != "fini"){ ?>
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
        <span class="ib l100 p1 i"><?= $challenge->getLevel_user($challenge->getCurrent_level(),$challenge->getTries()); ?></span>
    </span>
</div>

<!-- Element du défi,  nom du chapitre et accomplissements du joueur sur ce défi -->
<div id="accomplissement_joueur" class="mg20 mh2 l60 align_centre">
	<?php if($challenge->getTries() == 0){ ?>
    <span class="ib l100 mh2"><span class='g'>C'est la première fois que tu rencontres ce défi.</span><br>La difficulté des questions va s'adapter à ta maîtrise de cette notion et on pourra ensuite focaliser ton entraînement sur les points où tu bloques.</span>
    <?php } else { ?>
    <span class="ib l100 mh2 p3">Défi effectué <span class='g'><?= $challenge->getTries(); ?> fois</span></span>
    <?php } ?>
</div>

<div id="progress_graph_borders_before">
	<div id="progress_graph">
    	<!-- Created in JS -->
    </div>
</div>


<!-- Boutons entraînement et Jouer -->
<a class="blanc entrainement" href="/app/controllers/entrainement.php"><div class="bouton form_gauche">Entrainement</div></a>
<a class="entrainement" href="/app/controllers/entrainement.php"><img class="icone_form_gauche" src="/webroot/img/icones/play.png"></a>

<a class="blanc jouer" href="/app/controllers/new_defi.php"><div class="bouton form_droite2">Jouer</div></a>
<a class="jouer" href="/app/controllers/new_defi.php"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>

<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
<!-- Fin du fond -->
</div>

<!-- Player head for progress graph -->
<img id="playerAvatar" class="l6" src="<?= $joueur->full_portrait(); ?>" />

<!-- Div cachées utilisées par JS-->
<div id="etape_tuto" class="cache"><?= $joueur->tuto(); ?></div>
<div id="nom_tuteur" class="cache"><?= $joueur->tuteur(); ?></div>
<div id="sexe_joueur" class="cache"><?= $joueur->sexe(); ?></div>
<div id="pseudo" class="cache"><?= $joueur->pseudo(); ?></div>
<div id="id_joueur" class="cache"><?= $joueur->id(); ?></div>
<div id="challengeLevel" class="cache"><?= $challenge->getCurrent_level(); ?></div>
<div id="challengeTries" class="cache"><?= $challenge->getTries(); ?></div>
<div id="new_mentor" class="cache"><?= $new_mentor; ?></div>
<div id="previous_training" class="cache"><?= $_SESSION["training"]; ?></div>


<script src="/webroot/js/utils/anim.js?nvd_r=xxx"></script>
<script src="/webroot/js/accueil_defi.js?nvd_r=xxx"></script>
